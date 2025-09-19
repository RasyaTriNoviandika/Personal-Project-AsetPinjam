<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->latest()->paginate(10);
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = KategoriBarang::all();
        $peminjaman = Peminjaman::with('peminjam')->latest()->get();
        return view('barang.create', compact('kategori', 'peminjaman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'         => 'required|string|max:255',
            'kategori_id'         => 'required|exists:kategori_barangs,id',
            'stok_total'          => 'required|integer|min:0',
            'harga_sewa_per_hari' => 'required|numeric|min:0',
            'denda_per_hari'      => 'required|numeric|min:0',
            'kondisi'             => 'required|in:baik,rusak_ringan,rusak_berat',
            'status'              => 'required|in:aktif,non_aktif',
            'gambar'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi'           => 'nullable|string',
        ]);

        $validated['stok_tersedia'] = $validated['stok_total'];

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        Barang::create($validated);

        Alert::success('Berhasil', 'Barang berhasil ditambahkan');
        return redirect()->route('barang.index');
    }

    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategori = KategoriBarang::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang'         => 'required|string|max:255',
            'kategori_id'         => 'required|exists:kategori_barangs,id',
            'stok_total'          => 'required|integer|min:0',
            'harga_sewa_per_hari' => 'required|numeric|min:0',
            'denda_per_hari'      => 'required|numeric|min:0',
            'kondisi'             => 'required|in:baik,rusak_ringan,rusak_berat',
            'status'              => 'required|in:aktif,non_aktif',
            'gambar'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi'           => 'nullable|string',
        ]);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        $barang->update($validated);

        Alert::success('Berhasil', 'Barang berhasil diperbarui');
        return redirect()->route('barang.index');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        Alert::success('Berhasil', 'Barang berhasil dihapus');
        return redirect()->route('barang.index');
    }
}
