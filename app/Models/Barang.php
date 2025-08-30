<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'kategori_id',
        'stok_total',
        'stok_tersedia', 
        'harga_sewa_per_hari',
        'denda_per_hari',
        'kondisi',
        'status',
        'deskripsi',
        'gambar',
    ];

    protected $casts = [
        'harga_sewa_per_hari' => 'decimal:2',
        'denda_per_hari' => 'decimal:2',
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'barang_id');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeTersedia($query)
    {
        return $query->where('stok_tersedia', '>', 0);
    }

    // Accessors
    public function getIsAvailableAttribute()
    {
        return $this->stok_tersedia > 0 && $this->status === 'aktif';
    }

    public function getTotalDipinjamAttribute()
    {
        return $this->detailPeminjaman()->sum('jumlah');
    }
}