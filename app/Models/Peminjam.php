<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjam extends Model
{
    use HasFactory;

    protected $table = 'peminjams';
    
    protected $fillable = [
        'kode_peminjam', 
        'nama_peminjam',
        'email', 
        'no_telepon',
        'alamat', 
        'jenis_peminjam',
        'no_identitas',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->kode_peminjam)) {
                $model->kode_peminjam = 'PMJ-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
            
            if (empty($model->status)) {
                $model->status = 'aktif';
            }
        });
    }

    // Relationships
    public function peminjaman()
{
    return $this->hasManyThrough(
        Peminjaman::class,
        DetailPeminjaman::class,
        'barang_id',   // foreign key di detail_peminjaman
        'id',          // primary key di peminjaman
        'id',          // local key di barang
        'peminjaman_id'// foreign key di detail_peminjaman
    );
}

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_peminjam', $jenis);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nama_peminjam', 'like', '%' . $term . '%')
              ->orWhere('email', 'like', '%' . $term . '%')
              ->orWhere('no_telepon', 'like', '%' . $term . '%')
              ->orWhere('kode_peminjam', 'like', '%' . $term . '%');
        });
    }

    // Accessors
    public function getTotalPeminjamanAttribute()
    {
        return $this->peminjaman()->count();
    }

    public function getPeminjamanAktifAttribute()
    {
        return $this->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->count();
    }

    public function getPeminjamanSelesaiAttribute()
    {
        return $this->peminjaman()->where('status', 'dikembalikan')->count();
    }

    public function getTotalDendaAttribute()
    {
        return $this->peminjaman()->sum('total_denda');
    }

    public function getJenisBadgeAttribute()
    {
        $badges = [
            'individu' => 'bg-primary',
            'organisasi' => 'bg-success',
            'perusahaan' => 'bg-info'
        ];

        return $badges[$this->jenis_peminjam] ?? 'bg-secondary';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status === 'aktif' ? 'bg-success' : 'bg-secondary';
    }

    public function getFormattedPhoneAttribute()
    {
        // Format phone number (simple formatting)
        $phone = preg_replace('/[^0-9]/', '', $this->no_telepon);
        if (strlen($phone) >= 10) {
            return preg_replace('/(\d{4})(\d{4})(\d+)/', '$1-$2-$3', $phone);
        }
        return $this->no_telepon;
    }

    // Methods
    public function hasPeminjamanAktif()
    {
        return $this->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->exists();
    }

    public function getLastPeminjaman()
    {
        return $this->peminjaman()->latest()->first();
    }
}