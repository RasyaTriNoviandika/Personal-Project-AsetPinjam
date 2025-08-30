<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiKeuangan;
use Illuminate\Support\Facades\Storage;
use Alert;

class TransaksiKeuanganController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiKeuangan::with('peminjaman.peminjam')
            ->latest('tanggal_transaksi')
            ->paginate(15);

        $totalMasuk = TransaksiKeuangan::where('jenis_transaksi', 'masuk')->sum('jumlah');
        $totalKeluar = TransaksiKeuangan::where('jenis_transaksi', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return view('transaksi-keuangan.index', compact('transaksi', 'totalMasuk', 'totalKeluar', 'saldo'));
    }

    public function create()
    {
        return view('transaksi-keuangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'kategori' => 'required|in:sewa,denda,pemeliharaan,pembelian,lainnya',
            'jumlah' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tanggal_transaksi' => 'required|date',
            'bukti_transaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('bukti_transaksi')) {
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('transaksi', 'public');
        }

        TransaksiKeuangan::create($data);

        Alert::success('Berhasil', 'Transaksi keuangan berhasil ditambahkan');
        return redirect()->route('transaksi-keuangan.index');
    }

    public function show(TransaksiKeuangan $transaksiKeuangan)
    {
        $transaksiKeuangan->load('peminjaman.peminjam');
        return view('transaksi-keuangan.show', compact('transaksiKeuangan'));
    }

    public function edit(TransaksiKeuangan $transaksiKeuangan)
    {
        // Hanya transaksi manual yang bisa diedit (tidak dari peminjaman)
        if ($transaksiKeuangan->peminjaman_id) {
            Alert::error('Gagal', 'Transaksi dari peminjaman tidak dapat diedit');
            return redirect()->route('transaksi-keuangan.index');
        }

        return view('transaksi-keuangan.edit', compact('transaksiKeuangan'));
    }

    public function update(Request $request, TransaksiKeuangan $transaksiKeuangan)
    {
        if ($transaksiKeuangan->peminjaman_id) {
            Alert::error('Gagal', 'Transaksi dari peminjaman tidak dapat diedit');
            return redirect()->route('transaksi-keuangan.index');
        }

        $request->validate([
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'kategori' => 'required|in:sewa,denda,pemeliharaan,pembelian,lainnya',
            'jumlah' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tanggal_transaksi' => 'required|date',
            'bukti_transaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('bukti_transaksi')) {
            if ($transaksiKeuangan->bukti_transaksi) {
                Storage::disk('public')->delete($transaksiKeuangan->bukti_transaksi);
            }
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('transaksi', 'public');
        }

        $transaksiKeuangan->update($data);

        Alert::success('Berhasil', 'Transaksi keuangan berhasil diperbarui');
        return redirect()->route('transaksi-keuangan.index');
    }

    public function destroy(TransaksiKeuangan $transaksiKeuangan)
    {
        if ($transaksiKeuangan->peminjaman_id) {
            Alert::error('Gagal', 'Transaksi dari peminjaman tidak dapat dihapus');
            return redirect()->route('transaksi-keuangan.index');
        }

        if ($transaksiKeuangan->bukti_transaksi) {
            Storage::disk('public')->delete($transaksiKeuangan->bukti_transaksi);
        }

        $transaksiKeuangan->delete();

        Alert::success('Berhasil', 'Transaksi keuangan berhasil dihapus');
        return redirect()->route('transaksi-keuangan.index');
    }
}