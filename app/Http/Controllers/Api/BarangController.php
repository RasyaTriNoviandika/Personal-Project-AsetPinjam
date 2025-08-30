<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function getByKategori(Request $request)
    {
        $barang = Barang::with('kategori')
            ->where('kategori_id', $request->kategori_id)
            ->where('status', 'aktif')
            ->where('stok_tersedia', '>', 0)
            ->get();

        return response()->json($barang);
    }

    public function cekStok(Request $request)
    {
        $barang = Barang::find($request->barang_id);
        
        return response()->json([
            'stok_tersedia' => $barang->stok_tersedia,
            'harga_sewa' => $barang->harga_sewa_per_hari,
            'max_pinjam' => min($barang->stok_tersedia, $request->jumlah_minta ?? $barang->stok_tersedia)
        ]);
    }
}