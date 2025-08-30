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
            'deskripsi' => 'nullable|string'
        ]);

        KategoriBarang::create($request->all());

        Alert::success('Berhasil', 'Kategori barang berhasil ditambahkan');
        return redirect()->route('kategori-barang.index');
    }

    public function show(KategoriBarang $kategoriBarang)
    {
        $kategoriBarang->load('barang');
        return view('kategori-barang.show', compact('kategoriBarang'));
    }

    public function edit(KategoriBarang $kategoriBarang)
    {
        return view('kategori-barang.edit', compact('kategoriBarang'));
    }

    public function update(Request $request, KategoriBarang $kategoriBarang)
{
    $request->validate([
        'nama_kategori' => 'required|string|max:255|unique:kategori_barang,nama_kategori,' . $kategoriBarang->id,
        'jumlah_barang' => 'required|integer|min:0', // <-- tambahkan validasi jumlah_barang
        'deskripsi' => 'nullable|string',
    ]);

    $kategoriBarang->update([
        'nama_kategori' => $request->nama_kategori,
        'jumlah_barang' => $request->jumlah_barang, // <-- pastikan ikut diupdate
        'deskripsi'     => $request->deskripsi,
    ]);

    Alert::success('Berhasil', 'Kategori barang berhasil diperbarui');
    return redirect()->route('kategori-barang.index');
}


    public function destroy(KategoriBarang $kategoriBarang)
    {
        if ($kategoriBarang->barang()->count() > 0) {
            Alert::error('Gagal', 'Kategori tidak dapat dihapus karena masih digunakan oleh barang');
            return redirect()->route('kategori-barang.index');
        }

        $kategoriBarang->delete();

        Alert::success('Berhasil', 'Kategori barang berhasil dihapus');
        return redirect()->route('kategori-barang.index');
    }
}