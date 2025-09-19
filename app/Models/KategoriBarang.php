<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;

    protected $table = 'kategori_barangs';
    
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'jumlah_barang',
        'status',
        'harga_sewa',
    ];

    protected $casts = [
        'jumlah_barang' => 'integer',
    ];

    // Relationships
    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    // Accessors
    public function getTotalBarangAttribute()
    {
        return $this->barang()->count();
    }

    public function getBarangTersediaAttribute()
    {
        return $this->barang()->where('status', 'aktif')->sum('stok_tersedia');
    }

    // Auto update jumlah_barang when barang is added/removed
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($model) {
            if (!$model->status) {
                $model->update(['status' => 'aktif']);
            }
        });
    }
}