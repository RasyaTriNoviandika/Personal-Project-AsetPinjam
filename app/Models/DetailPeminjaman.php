<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';
    
    protected $fillable = [
        'peminjaman_id', 
        'barang_id', 
        'jumlah',
        'harga_sewa_per_hari', 
        'subtotal_sewa',  // sesuaikan dengan controller (bukan 'subtotal')
        'kondisi_pinjam', 
        'kondisi_kembali', 
        'catatan'
    ];

    protected $casts = [
        'harga_sewa_per_hari' => 'decimal:2',
        'subtotal_sewa' => 'decimal:2',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
