<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjamans';
    
    protected $fillable = [
        'peminjaman_id', 
        'barang_id', 
        'jumlah',
        'harga_sewa_per_hari', 
        'subtotal_sewa',
        'kondisi_pinjam', 
        'kondisi_kembali', 
        'catatan'
    ];

    protected $casts = [
        'harga_sewa_per_hari' => 'decimal:2',
        'subtotal_sewa' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    // Relationships
  public function detailPeminjaman()
{
    return $this->hasManyThrough(
        DetailPeminjaman::class,
        Peminjaman::class,
        'peminjam_id',   // Foreign key di tabel peminjaman
        'peminjaman_id', // Foreign key di tabel detail_peminjaman
        'id',            // Local key di tabel peminjam
        'id'             // Local key di tabel peminjaman
    );
}

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Accessors
    public function getKondisiPinjamBadgeAttribute()
    {
        $badges = [
            'baik' => 'bg-success',
            'rusak_ringan' => 'bg-warning',
            'rusak_berat' => 'bg-danger'
        ];

        return $badges[$this->kondisi_pinjam] ?? 'bg-secondary';
    }

    public function getKondisiKembaliBadgeAttribute()
    {
        $badges = [
            'baik' => 'bg-success',
            'rusak_ringan' => 'bg-warning',
            'rusak_berat' => 'bg-danger',
            'hilang' => 'bg-dark'
        ];

        return $badges[$this->kondisi_kembali] ?? 'bg-secondary';
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal_sewa, 0, ',', '.');
    }

    public function getFormattedHargaSewaAttribute()
    {
        return 'Rp ' . number_format($this->harga_sewa_per_hari, 0, ',', '.');
    }

    // Methods
    public function hitungSubtotal($durasi = null)
    {
        if (!$durasi && $this->peminjaman) {
            $durasi = $this->peminjaman->durasi_peminjaman;
        }
        
        return $this->harga_sewa_per_hari * $this->jumlah * ($durasi ?? 1);
    }

    // Boot method to auto-calculate subtotal
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->subtotal_sewa && $model->peminjaman) {
                $model->subtotal_sewa = $model->hitungSubtotal();
            }
        });
        
        static::updating(function ($model) {
            if ($model->isDirty(['harga_sewa_per_hari', 'jumlah']) && $model->peminjaman) {
                $model->subtotal_sewa = $model->hitungSubtotal();
            }
        });
    }
}