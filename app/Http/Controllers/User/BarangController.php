<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\KategoriBarang;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori']);
        
        // Filter berdasarkan ketersediaan stok
        if ($request->has('tersedia') && $request->tersedia == '1') {
            $query->where('stok_tersedia', '>', 0);
        }
        
        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('merek', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sort
        $sortBy = $request->get('sort', 'nama_barang');
        $sortOrder = $request->get('order', 'asc');
        
        if (in_array($sortBy, ['nama_barang', 'merek', 'stok_tersedia', 'harga_sewa_per_hari', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $barang = $query->paginate(12);
        $kategoris = KategoriBarang::orderBy('nama')->get();
        
        return view('user.barang.index', compact('barang', 'kategoris'));
    }
    
    public function show(Barang $barang)
    {
        $barang->load(['kategori']);
        
        // Barang terkait (kategori yang sama)
        $relatedBarang = Barang::where('kategori_id', $barang->kategori_id)
            ->where('id', '!=', $barang->id)
            ->where('stok_tersedia', '>', 0)
            ->limit(4)
            ->get();
            
        return view('user.barang.show', compact('barang', 'relatedBarang'));
    }
    
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }
        
        $barang = Barang::where('nama_barang', 'like', '%' . $query . '%')
            ->orWhere('merek', 'like', '%' . $query . '%')
            ->where('stok_tersedia', '>', 0)
            ->with(['kategori'])
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'merek' => $item->merek,
                    'kategori' => $item->kategori->nama ?? '',
                    'stok_tersedia' => $item->stok_tersedia,
                    'harga_sewa' => $item->harga_sewa_per_hari,
                    'gambar' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                ];
            });
            
        return response()->json($barang);
    }
}