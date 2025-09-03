<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Models\TransaksiKeuangan;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $query = Peminjaman::with(['peminjam', 'detailPeminjaman.barang']);

            // Filter berdasarkan status
            if ($request->has('status') && $request->status !== 'semua') {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan peminjam
            if ($request->has('peminjam_id')) {
                $query->where('peminjam_id', $request->peminjam_id);
            }

            // Filter berdasarkan tanggal
            if ($request->has('tanggal_mulai')) {
                $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
            }

            if ($request->has('tanggal_selesai')) {
                $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $peminjaman = $query->latest()->paginate($perPage);

            return $this->successResponse($peminjaman, 'Data peminjaman berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mengambil data peminjaman: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'peminjam_id' => 'required|exists:peminjam,id',
                'tanggal_pinjam' => 'required|date|after_or_equal:today',
                'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
                'barang' => 'required|array|min:1',
                'barang.*.barang_id' => 'required|exists:barang,id',
                'barang.*.jumlah' => 'required|integer|min:1',
                'catatan' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            // Validasi stok tersedia
            foreach ($request->barang as $item) {
                $barang = Barang::find($item['barang_id']);
                if ($barang->stok_tersedia < $item['jumlah']) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Tersedia: {$barang->stok_tersedia}");
                }
            }

            // Hitung total biaya
            $totalBiayaSewa = 0;
            $durasi = Carbon::parse($request->tanggal_pinjam)
                           ->diffInDays(Carbon::parse($request->tanggal_kembali_rencana)) + 1;

            foreach ($request->barang as $item) {
                $barang = Barang::find($item['barang_id']);
                $subtotal = $barang->harga_sewa_per_hari * $item['jumlah'] * $durasi;
                $totalBiayaSewa += $subtotal;
            }

            // Buat peminjaman
            $peminjaman = Peminjaman::create([
                'peminjam_id' => $request->peminjam_id,
                'user_id' => auth()->id(),
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'total_biaya_sewa' => $totalBiayaSewa,
                'total_denda' => 0,
                'total_bayar' => $totalBiayaSewa,
                'status' => 'dipinjam',
                'catatan' => $request->catatan
            ]);

            // Buat detail peminjaman dan kurangi stok
            foreach ($request->barang as $item) {
                $barang = Barang::find($item['barang_id']);
                $subtotal = $barang->harga_sewa_per_hari * $item['jumlah'] * $durasi;

                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_sewa_per_hari' => $barang->harga_sewa_per_hari,
                    'subtotal_sewa' => $subtotal,
                    'kondisi_pinjam' => 'baik'
                ]);

                // Kurangi stok tersedia
                $barang->decrement('stok_tersedia', $item['jumlah']);
            }

            // Buat transaksi keuangan
            TransaksiKeuangan::create([
                'peminjaman_id' => $peminjaman->id,
                'jenis_transaksi' => 'masuk',
                'kategori' => 'sewa',
                'jumlah' => $totalBiayaSewa,
                'deskripsi' => "Pembayaran sewa untuk peminjaman {$peminjaman->kode_peminjaman}",
                'tanggal_transaksi' => now()
            ]);

            DB::commit();

            $peminjaman->load(['peminjam', 'detailPeminjaman.barang']);
            return $this->successResponse($peminjaman, 'Peminjaman berhasil dibuat', 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse('Gagal membuat peminjaman: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $peminjaman = Peminjaman::with(['peminjam', 'detailPeminjaman.barang.kategori', 'user'])
                                   ->find($id);
            
            if (!$peminjaman) {
                return $this->notFoundResponse('Peminjaman tidak ditemukan');
            }

            return $this->successResponse($peminjaman);
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mengambil detail peminjaman: ' . $e->getMessage(), 500);
        }
    }

    public function prosesKembali(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::find($id);
            
            if (!$peminjaman) {
                return $this->notFoundResponse('Peminjaman tidak ditemukan');
            }

            if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
                return $this->errorResponse('Peminjaman tidak dapat dikembalikan', 400);
            }

            $request->validate([
                'tanggal_kembali_aktual' => 'required|date',
                'detail' => 'required|array',
                'detail.*.kondisi_kembali' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
                'detail.*.catatan' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            $tanggalKembali = Carbon::parse($request->tanggal_kembali_aktual);
            $tanggalRencana = Carbon::parse($peminjaman->tanggal_kembali_rencana);
            
            // Hitung denda keterlambatan
            $hariTerlambat = $tanggalKembali->diffInDays($tanggalRencana, false);
            $totalDenda = 0;

            if ($hariTerlambat > 0) {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $dendaBarang = $detail->barang->denda_per_hari * $detail->jumlah * $hariTerlambat;
                    $totalDenda += $dendaBarang;
                }
            }

            // Update peminjaman
            $peminjaman->update([
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'total_denda' => $totalDenda,
                'total_bayar' => $peminjaman->total_biaya_sewa + $totalDenda,
                'status' => 'dikembalikan'
            ]);

            // Update detail dan kembalikan stok
            foreach ($request->detail as $detailId => $detailData) {
                $detail = DetailPeminjaman::find($detailId);
                if ($detail) {
                    $detail->update([
                        'kondisi_kembali' => $detailData['kondisi_kembali'],
                        'catatan' => $detailData['catatan'] ?? null
                    ]);

                    // Kembalikan stok jika barang tidak hilang
                    if ($detailData['kondisi_kembali'] !== 'hilang') {
                        $detail->barang->increment('stok_tersedia', $detail->jumlah);
                    } else {
                        // Kurangi stok total jika barang hilang
                        $detail->barang->decrement('stok_total', $detail->jumlah);
                    }
                }
            }

            // Buat transaksi denda jika ada
            if ($totalDenda > 0) {
                TransaksiKeuangan::create([
                    'peminjaman_id' => $peminjaman->id,
                    'jenis_transaksi' => 'masuk',
                    'kategori' => 'denda',
                    'jumlah' => $totalDenda,
                    'deskripsi' => "Denda keterlambatan {$hariTerlambat} hari untuk peminjaman {$peminjaman->kode_peminjaman}",
                    'tanggal_transaksi' => now()
                ]);
            }

            DB::commit();

            $peminjaman->load(['peminjam', 'detailPeminjaman.barang']);
            return $this->successResponse($peminjaman, 'Pengembalian berhasil diproses');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse('Gagal memproses pengembalian: ' . $e->getMessage(), 500);
        }
    }
}