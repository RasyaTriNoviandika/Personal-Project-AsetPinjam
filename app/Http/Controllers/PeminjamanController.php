<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Peminjam;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Alert;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan daftar peminjaman terbaru
        $peminjaman = Peminjaman::with(['peminjam', 'barang'])->latest()->paginate(10);
        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $peminjam = Peminjam::all();
        $barang = Barang::all();
        return view('peminjaman.create', compact('peminjam', 'barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjam_id' => 'required|exists:peminjams,id',
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jumlah' => 'required|integer|min:1',
        ]);

        Peminjaman::create($request->all());

        Alert::success('Berhasil', 'Data peminjaman berhasil ditambahkan');
        return redirect()->route('peminjaman.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['peminjam', 'barang']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        $peminjam = Peminjam::all();
        $barang = Barang::all();
        return view('peminjaman.edit', compact('peminjaman', 'peminjam', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'peminjam_id' => 'required|exists:peminjam,id',
            'barang_id' => 'required|exists:barang,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jumlah' => 'required|integer|min:1',
        ]);

        $peminjaman->update($request->all());

        Alert::success('Berhasil', 'Data peminjaman berhasil diperbarui');
        return redirect()->route('peminjaman.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        Alert::success('Berhasil', 'Data peminjaman berhasil dihapus');
        return redirect()->route('peminjaman.index');
    }
}
