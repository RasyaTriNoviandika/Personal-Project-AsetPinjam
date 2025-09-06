<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $rules = [
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok_total' => 'required|integer|min:1',
            'harga_sewa_per_hari' => 'required|numeric|min:0',
            'denda_per_hari' => 'required|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:aktif,non_aktif',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'nama_barang.required' => 'Nama barang harus diisi',
            'kategori_id.required' => 'Kategori harus dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'stok_total.required' => 'Stok total harus diisi',
            'stok_total.min' => 'Stok total minimal 1',
            'harga_sewa_per_hari.required' => 'Harga sewa per hari harus diisi',
            'harga_sewa_per_hari.min' => 'Harga sewa tidak boleh negatif',
            'denda_per_hari.required' => 'Denda per hari harus diisi',
            'denda_per_hari.min' => 'Denda tidak boleh negatif',
            'kondisi.required' => 'Kondisi barang harus dipilih',
            'status.required' => 'Status barang harus dipilih',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}