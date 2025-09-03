<?php
// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class KategoriBarang extends Model
// {
//     protected $table = 'kategori';

//     protected $fillable = [
//         'nama_kategori',
//         'jumlah_barang',
//         'deskripsi',
//     ];

//     // ✅ aktifkan kembali relasi
//     public function barang()
//     {
//         return $this->hasMany(Barang::class, 'kategori_id');
//     }
// }
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barangs';
    protected $fillable = ['nama_kategori', 'deskripsi', 'jumlah_barang'];
}