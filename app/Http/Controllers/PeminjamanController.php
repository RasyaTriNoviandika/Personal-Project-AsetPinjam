<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Peminjam;
use App\Models\Barang;
use App\Models\TransaksiKeuangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Alert;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['peminjam', 'detailPeminjaman.barang']);
        
        // Filter berdasarkan status
        if ($request->status && $request->status != 'semua') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->tanggal_mulai) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }
        
        if ($request->tanggal_selesai) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }

        $peminjaman = $query->latest()->paginate(15);

        // Update status peminjaman yang terlambat
        $this->updateStatusTerlambat();

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $peminjam = Peminjam::where('status', 'aktif')->get();
        $barang = Barang::where('status', 'aktif')
                       ->where('stok_tersedia', '>', 0)
                       ->with('kategori')
                       ->get();
        
        return view('peminjaman.create', compact('peminjam', 'barang'));
    }

    public function store(Request $request)
    {
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
        try {
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

            Alert::success('Berhasil', 'Data peminjaman berhasil ditambahkan');
            return redirect()->route('peminjaman.index');

        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['peminjam', 'detailPeminjaman.barang.kategori', 'user']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function pengembalian(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam' && $peminjaman->status !== 'terlambat') {
            Alert::error('Gagal', 'Peminjaman ini tidak dapat dikembalikan');
            return redirect()->route('peminjaman.index');
        }

        $peminjaman->load(['detailPeminjaman.barang']);
        return view('peminjaman.pengembalian', compact('peminjaman'));
    }

    public function prosesKembali(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'detail.*.kondisi_kembali' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'detail.*.catatan' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
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
                $detail->update([
                    'kondisi_kembali' => $detailData['kondisi_kembali'],
                    'catatan' => $detailData['catatan']
                ]);

                // Kembalikan stok jika barang tidak hilang
                if ($detailData['kondisi_kembali'] !== 'hilang') {
                    $detail->barang->increment('stok_tersedia', $detail->jumlah);
                } else {
                    // Kurangi stok total jika barang hilang
                    $detail->barang->decrement('stok_total', $detail->jumlah);
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

            Alert::success('Berhasil', 'Pengembalian barang berhasil diproses');
            return redirect()->route('peminjaman.index');

        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    private function updateStatusTerlambat()
    {
        Peminjaman::where('status', 'dipinjam')
                  ->where('tanggal_kembali_rencana', '<', now())
                  ->update(['status' => 'terlambat']);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam' || $peminjaman->status === 'terlambat') {
            Alert::error('Gagal', 'Peminjaman yang sedang berlangsung tidak dapat dihapus');
            return redirect()->route('peminjaman.index');
        }

        $peminjaman->delete();
        Alert::success('Berhasil', 'Data peminjaman berhasil dihapus');
        return redirect()->route('peminjaman.index');
    }
}
