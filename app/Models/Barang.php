<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

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
        'peminjaman_id' // ✅ tambahan
    ];

    protected $casts = [
        'harga_sewa_per_hari' => 'decimal:2',
        'denda_per_hari' => 'decimal:2',
        'stok_total' => 'integer',
        'stok_tersedia' => 'integer',
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

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    // Accessors & Mutators
    public function getIsAvailableAttribute()
    {
        return $this->stok_tersedia > 0 && $this->status === 'aktif';
    }

    public function getTotalDipinjamAttribute()
    {
        return $this->detailPeminjaman()->sum('jumlah');
    }

    public function getStokDipinjamAttribute()
    {
        return $this->detailPeminjaman()
            ->whereHas('peminjaman', function($query) {
                $query->whereIn('status', ['dipinjam', 'terlambat']);
            })
            ->sum('jumlah');
    }

    public function getFormattedHargaSewaAttribute()
    {
        return 'Rp ' . number_format($this->harga_sewa_per_hari, 0, ',', '.');
    }

    public function getFormattedDendaAttribute()
    {
        return 'Rp ' . number_format($this->denda_per_hari, 0, ',', '.');
    }

// Barang.php

public function getPendapatanAttribute()
{
    // Hitung pendapatan dari semua detail peminjaman barang ini
    return $this->detailPeminjaman()->sum('subtotal') ?? 0;
}
}