<?php

namespace App\Http\Controllers;

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
        $peminjamans = Peminjaman::with(['peminjam', 'detailPeminjaman.barang'])
            ->latest()
            ->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    // =================== CREATE ===================
    public function create()
    {
        $peminjam = Peminjam::all();
        $barang = Barang::where('stok_tersedia', '>', 0)->get();

        return view('peminjaman.create', compact('peminjam', 'barang'));
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
            // Buat peminjaman
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

            // Simpan detail barang + hitung biaya
            foreach ($request->barang as $item) {
                $barang = Barang::findOrFail($item['barang_id']);

                $peminjaman->detailPeminjaman()->create([
                    'barang_id' => $barang->id,
                    'jumlah' => $item['jumlah']
                ]);

                // Hitung biaya sewa
                $biaya = $barang->harga_sewa_per_hari * $item['jumlah'] * $durasiHari;
                $totalBiaya += $biaya;

                // Kurangi stok
                $barang->decrement('stok_tersedia', $item['jumlah']);
            }

            // Update total biaya
            $peminjaman->update([
                'total_biaya_sewa' => $totalBiaya,
                'total_bayar' => $totalBiaya
            ]);

            Alert::success('Berhasil', 'Peminjaman berhasil ditambahkan');
            return redirect()->route('peminjaman.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // =================== SHOW ===================
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['peminjam', 'detailPeminjaman.barang']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    // =================== EDIT ===================
    public function edit(Peminjaman $peminjaman)
    {
        $peminjam = Peminjam::all();
        $barang = Barang::where('status', 'aktif')->get();

        return view('peminjaman.edit', compact('peminjaman', 'peminjam', 'barang'));
    }

    // =================== UPDATE ===================
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'catatan' => 'nullable|string'
        ]);

        $peminjaman->update([
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'catatan' => $request->catatan
        ]);

        Alert::success('Berhasil', 'Peminjaman berhasil diperbarui');
        return redirect()->route('peminjaman.index');
    }

    // =================== DESTROY ===================
    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        Alert::success('Berhasil', 'Peminjaman berhasil dihapus');
        return redirect()->route('peminjaman.index');
    }

    // =================== PENGEMBALIAN ===================
    public function pengembalian($id)
    {
        try {
            $peminjaman = Peminjaman::with('detailPeminjaman.barang')->findOrFail($id);

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
            ]);

            // Kembalikan stok barang
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $detail->barang->increment('stok_tersedia', $detail->jumlah);
            }

            Alert::success('Berhasil', 'Barang berhasil dikembalikan');
            return redirect()->route('peminjaman.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function prosesKembali(Request $request, Peminjaman $peminjaman)
{
    $request->validate([
        'tanggal_kembali_aktual' => 'required|date',
        'detail' => 'required|array',
    ]);

    try {
        // Update status & tanggal kembali aktual
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => $request->tanggal_kembali_aktual,
        ]);

        // Proses setiap detail barang
        foreach ($request->detail as $detailId => $data) {
            $detail = $peminjaman->detailPeminjaman()->find($detailId);

            if ($detail) {
                // Tambahkan stok kembali
                $detail->barang->increment('stok_tersedia', $detail->jumlah);

                // Simpan kondisi & catatan
                $detail->update([
                    'kondisi_kembali' => $data['kondisi_kembali'],
                    'catatan' => $data['catatan'] ?? null,
                ]);
            }
        }

        \RealRashid\SweetAlert\Facades\Alert::success('Berhasil', 'Barang berhasil dikembalikan.');
        return redirect()->route('peminjaman.index');

    } catch (\Exception $e) {
        \RealRashid\SweetAlert\Facades\Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }
}

}
