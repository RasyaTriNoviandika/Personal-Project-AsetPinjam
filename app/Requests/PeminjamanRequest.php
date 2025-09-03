<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeminjamanRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'peminjam_id' => 'required|exists:peminjam,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'barang' => 'required|array|min:1',
            'barang.*.barang_id' => 'required|exists:barang,id',
            'barang.*.jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'peminjam_id.required' => 'Peminjam harus dipilih',
            'peminjam_id.exists' => 'Peminjam tidak valid',
            'tanggal_pinjam.required' => 'Tanggal pinjam harus diisi',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini',
            'tanggal_kembali_rencana.required' => 'Tanggal kembali rencana harus diisi',
            'tanggal_kembali_rencana.after' => 'Tanggal kembali harus setelah tanggal pinjam',
            'barang.required' => 'Minimal harus memilih 1 barang',
            'barang.*.barang_id.required' => 'ID barang harus diisi',
            'barang.*.barang_id.exists' => 'Barang tidak valid',
            'barang.*.jumlah.required' => 'Jumlah barang harus diisi',
            'barang.*.jumlah.min' => 'Jumlah barang minimal 1',
        ];
    }
}
