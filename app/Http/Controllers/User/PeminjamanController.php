<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Models\Peminjam;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // =================== INDEX ===================
    public function index()
    {
        $peminjamans = Peminjaman::where('user_id', auth()->id())
            ->with(['peminjam', 'detailPeminjaman.barang'])
            ->latest()
            ->paginate(10);

        return view('user.peminjaman.index', compact('peminjamans'));
    }

    // =================== CREATE ===================
    public function create()
    {
        $peminjam = Peminjam::where('user_id', auth()->id())->get();
        $barang = Barang::where('stok_tersedia', '>', 0)->get();

        return view('user.peminjaman.create', compact('peminjam', 'barang'));
    }

    // =================== STORE ===================
    public function store(Request $request)
    {
        $request->validate([
            'peminjam_id' => 'required|exists:peminjams,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'barang' => 'required|array|min:1',
            'barang.*.barang_id' => 'required|exists:barangs,id',
            'barang.*.jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string'
        ]);

        try {
            $peminjaman = Peminjaman::create([
                'peminjam_id' => $request->peminjam_id,
                'user_id' => auth()->id(),
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'status' => 'dipinjam',
                'catatan' => $request->catatan,
                'total_biaya_sewa' => 0,
                'total_bayar' => 0
            ]);

            $totalBiaya = 0;
            $durasiHari = Carbon::parse($request->tanggal_pinjam)
                ->diffInDays(Carbon::parse($request->tanggal_kembali_rencana)) + 1;

            foreach ($request->barang as $item) {
                $barang = Barang::findOrFail($item['barang_id']);

                $peminjaman->detailPeminjaman()->create([
                    'barang_id' => $barang->id,
                    'jumlah' => $item['jumlah']
                ]);

                $biaya = $barang->harga_sewa_per_hari * $item['jumlah'] * $durasiHari;
                $totalBiaya += $biaya;

                $barang->decrement('stok_tersedia', $item['jumlah']);
            }

            $peminjaman->update([
                'total_biaya_sewa' => $totalBiaya,
                'total_bayar' => $totalBiaya
            ]);

            Alert::success('Berhasil', 'Peminjaman berhasil ditambahkan');
            return redirect()->route('user.peminjaman.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // =================== SHOW ===================
    public function show(Peminjaman $peminjaman)
    {
        $this->authorizeUser($peminjaman);

        $peminjaman->load(['peminjam', 'detailPeminjaman.barang']);
        return view('user.peminjaman.show', compact('peminjaman'));
    }

    // =================== PENGEMBALIAN ===================
    public function prosesKembali(Request $request, Peminjaman $peminjaman)
    {
        $this->authorizeUser($peminjaman);

        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'detail' => 'required|array',
        ]);

        try {
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => $request->tanggal_kembali_aktual,
            ]);

            foreach ($request->detail as $detailId => $data) {
                $detail = $peminjaman->detailPeminjaman()->find($detailId);

                if ($detail) {
                    $detail->barang->increment('stok_tersedia', $detail->jumlah);

                    $detail->update([
                        'kondisi_kembali' => $data['kondisi_kembali'],
                        'catatan' => $data['catatan'] ?? null,
                    ]);
                }
            }

            Alert::success('Berhasil', 'Barang berhasil dikembalikan.');
            return redirect()->route('user.peminjaman.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    private function authorizeUser(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak');
        }
    }
}
