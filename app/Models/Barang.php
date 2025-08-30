<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang'; // pastikan tabelnya benar

    protected $fillable = [
        'nama_barang',
        'kategori_id',
        'stok_total',
        'stok_tersedia',
        'harga_sewa',
        'denda_per_hari',
        'kondisi',
        'gambar',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'barang_id');
    }
}
