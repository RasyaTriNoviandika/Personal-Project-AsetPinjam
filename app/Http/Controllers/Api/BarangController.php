<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $query = Barang::with('kategori');

            // Filter berdasarkan kategori
            if ($request->has('kategori_id') && $request->kategori_id) {
                $query->where('kategori_id', $request->kategori_id);
            }

            // Filter berdasarkan status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            } else {
                $query->where('status', 'aktif');
            }

            // Filter stok tersedia
            if ($request->has('stok_tersedia') && $request->stok_tersedia) {
                $query->where('stok_tersedia', '>', 0);
            }

            $barang = $query->get();

            return $this->successResponse($barang, 'Data barang berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mengambil data barang: ' . $e->getMessage(), 500);
        }
    }

    public function getByKategori(Request $request)
    {
        try {
            if (!$request->has('kategori_id')) {
                return $this->validationErrorResponse(['kategori_id' => 'Kategori ID wajib diisi']);
            }

            $barang = Barang::with('kategori')
                ->where('kategori_id', $request->kategori_id)
                ->where('status', 'aktif')
                ->where('stok_tersedia', '>', 0)
                ->get();

            return $this->successResponse($barang);
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mengambil data barang: ' . $e->getMessage(), 500);
        }
    }

    public function cekStok(Request $request)
    {
        try {
            $request->validate([
                'barang_id' => 'required|exists:barang,id',
                'jumlah_minta' => 'nullable|integer|min:1'
            ]);

            $barang = Barang::find($request->barang_id);
            
            if (!$barang) {
                return $this->notFoundResponse('Barang tidak ditemukan');
            }

            $data = [
                'barang_id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'stok_tersedia' => $barang->stok_tersedia,
                'harga_sewa' => $barang->harga_sewa_per_hari,
                'denda_per_hari' => $barang->denda_per_hari,
                'max_pinjam' => min($barang->stok_tersedia, $request->jumlah_minta ?? $barang->stok_tersedia),
                'is_available' => $barang->stok_tersedia > 0 && $barang->status === 'aktif'
            ];

            return $this->successResponse($data, 'Data stok barang');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal cek stok: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $barang = Barang::with(['kategori', 'detailPeminjaman.peminjaman'])->find($id);
            
            if (!$barang) {
                return $this->notFoundResponse('Barang tidak ditemukan');
            }

            return $this->successResponse($barang);
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mengambil detail barang: ' . $e->getMessage(), 500);
        }
    }
}