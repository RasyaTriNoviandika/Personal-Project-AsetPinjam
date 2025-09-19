<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::withCount('barang')->latest()->paginate(10);
        return view('kategori-barang.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori-barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jumlah_barang' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'harga_sewa' => 'required|numeric|min:0',
        ]);

        KategoriBarang::create($request->all());

        // pakai session flash untuk SweetAlert
        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori barang berhasil ditambahkan');
    }

    public function show(KategoriBarang $kategori_barang)
    {
        $kategori_barang->load('barang');
        return view('kategori-barang.show', ['kategori' => $kategori_barang]);
    }

    public function edit(KategoriBarang $kategori_barang)
    {
        $kategori_barang->load('barang');
        return view('kategori-barang.edit', ['kategori' => $kategori_barang]);
    }

    public function update(Request $request, KategoriBarang $kategori_barang)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_barangs,nama_kategori,' . $kategori_barang->id,
            'jumlah_barang' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'harga_sewa' => 'required|numeric|min:0',
        ]);

        $kategori_barang->update($request->all());
        
        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori barang berhasil diperbarui');
    }

    public function destroy(KategoriBarang $kategori_barang)
    {
        if ($kategori_barang->barang()->count() > 0) {
            return redirect()->route('kategori-barang.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh barang');
        }

        $kategori_barang->delete();

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori barang berhasil dihapus');
    }
}
