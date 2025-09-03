<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';
    
    protected $fillable = [
    'kode_peminjaman',
    'peminjam_id',
    'user_id',  
    'tanggal_pinjam',
    'tanggal_kembali_rencana',
    'tanggal_kembali_aktual',
    'total_biaya_sewa',
    'denda',
    'total_bayar',
    'status',
    'catatan'
];

protected $casts = [
    'tanggal_pinjam' => 'date',
    'tanggal_kembali_rencana' => 'date',
    'tanggal_kembali_aktual' => 'date',
    'total_biaya_sewa' => 'decimal:2',
    'denda' => 'decimal:2',
    'total_bayar' => 'decimal:2',
];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->kode_peminjaman)) {
                $model->kode_peminjaman = 'PJM-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function transaksiKeuangan()
    {
        return $this->hasMany(TransaksiKeuangan::class);
    }

    public function getHariTerlambatAttribute()
    {
        if ($this->status == 'dikembalikan' && $this->tanggal_kembali_aktual) {
            $terlambat = $this->tanggal_kembali_aktual->diffInDays($this->tanggal_kembali_rencana, false);
            return $terlambat > 0 ? $terlambat : 0;
        }
        
        if ($this->status == 'dipinjam' || $this->status == 'terlambat') {
            $terlambat = Carbon::now()->diffInDays($this->tanggal_kembali_rencana, false);
            return $terlambat > 0 ? $terlambat : 0;
        }
        
        return 0;
    }
}
