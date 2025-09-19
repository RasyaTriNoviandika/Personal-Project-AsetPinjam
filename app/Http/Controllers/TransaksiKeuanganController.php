<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;

class TransaksiKeuanganController extends Controller
{
    /**
     * Tampilkan daftar transaksi keuangan.
     */
    public function index(Request $request)
    {
        $query = TransaksiKeuangan::query();

        if ($request->filled('jenis')) {
            $query->where('jenis_transaksi', $request->jenis);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        $transaksi = $query->latest()->paginate(10);

        $totalMasuk  = TransaksiKeuangan::where('jenis_transaksi', 'masuk')->sum('jumlah');
        $totalKeluar = TransaksiKeuangan::where('jenis_transaksi', 'keluar')->sum('jumlah');
        $saldo       = $totalMasuk - $totalKeluar;

        $bulan = date('m');
        $tahun = date('Y');
        $transaksibulanIni = TransaksiKeuangan::whereMonth('tanggal_transaksi', $bulan)
                            ->whereYear('tanggal_transaksi', $tahun)
                            ->count();

        return view('transaksi-keuangan.index', compact(
            'transaksi', 'totalMasuk', 'totalKeluar', 'saldo', 'transaksibulanIni'
        ));
    }

    /**
     * Form tambah transaksi.
     */
    public function create()
    {
        return view('transaksi-keuangan.create');
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'tanggal_transaksi'  => 'required|date',
            'jenis_transaksi'    => 'required|in:masuk,keluar',
            'kategori_transaksi' => 'nullable|string|max:255',
            'jumlah'             => 'required|numeric',
            'metode_pembayaran'  => 'required|string|max:255',
            'status'             => 'required|in:berhasil,pending,gagal',
            'bukti_transaksi'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $kode_transaksi = 'TRX-' . strtoupper(substr($request->jenis_transaksi, 0, 2)) 
                        . '-' . now()->format('Ymd') . '-' . rand(1000, 9999);

        $data = $request->all();
        $data['kode_transaksi'] = $kode_transaksi;

        if ($request->hasFile('bukti_transaksi')) {
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti_transaksi', 'public');
        }

        TransaksiKeuangan::create($data);

        return redirect()->route('transaksi-keuangan.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Detail transaksi.
     */
    public function show(TransaksiKeuangan $transaksi_keuangan)
    {
        $transaksi = $transaksi_keuangan;
        return view('transaksi-keuangan.show', compact('transaksi'));
    }

    /**
     * Form edit transaksi.
     */
    public function edit(TransaksiKeuangan $transaksi_keuangan)
    {
        $transaksi = $transaksi_keuangan;
        return view('transaksi-keuangan.edit', compact('transaksi'));
    }

    /**
     * Update transaksi.
     */
    public function update(Request $request, TransaksiKeuangan $transaksi)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'tanggal_transaksi'  => 'required|date',
            'jenis_transaksi'    => 'required|in:masuk,keluar',
            'kategori_transaksi' => 'nullable|string|max:255',
            'jumlah'             => 'required|numeric',
            'metode_pembayaran'  => 'required|string|max:255',
            'status'             => 'required|in:berhasil,pending,gagal',
            'bukti_transaksi'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan'         => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('bukti_transaksi')) {
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti_transaksi', 'public');
        }

        $transaksi->update($data);

        return redirect()->route('transaksi-keuangan.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Hapus transaksi.
     */
    public function destroy(TransaksiKeuangan $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi-keuangan.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
