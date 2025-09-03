<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\KategoriBarang;
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
        return view('barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok_total' => 'required|integer|min:1',
            'harga_sewa_per_hari' => 'required|numeric|min:0',
            'denda_per_hari' => 'required|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:aktif,non_aktif',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['stok_tersedia'] = $request->stok_total;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        Barang::create($data);

        Alert::success('Berhasil', 'Data barang berhasil ditambahkan');
        return redirect()->route('barang.index');
    }

    public function show(Barang $barang)
    {
        $barang->load('kategori', 'detailPeminjaman.peminjaman.peminjam');
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategori = KategoriBarang::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok_total' => 'required|integer|min:1',
            'harga_sewa_per_hari' => 'required|numeric|min:0',
            'denda_per_hari' => 'required|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:aktif,non_aktif',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        // Update stok tersedia jika stok total berubah
        $selisihStok = $request->stok_total - $barang->stok_total;
        $data['stok_tersedia'] = max(0, $barang->stok_tersedia + $selisihStok);

        $barang->update($data);

        Alert::success('Berhasil', 'Data barang berhasil diperbarui');
        return redirect()->route('barang.index');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->detailPeminjaman()->exists()) {
            Alert::error('Gagal', 'Barang tidak dapat dihapus karena masih memiliki riwayat peminjaman');
            return redirect()->route('barang.index');
        }

        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        Alert::success('Berhasil', 'Data barang berhasil dihapus');
        return redirect()->route('barang.index');
    }
}