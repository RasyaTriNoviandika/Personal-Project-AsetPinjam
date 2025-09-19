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
        'total_denda',
        'total_bayar',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali_rencana' => 'datetime',
        'tanggal_kembali_aktual' => 'datetime',
        'total_biaya_sewa' => 'decimal:2',
        'total_denda' => 'decimal:2',
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

    // Relationships
    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class,'peminjam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function transaksiKeuangan()
    {
        return $this->hasMany(TransaksiKeuangan::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['dipinjam', 'terlambat']);
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', 'terlambat');
    }

    public function scopeDikembalikan($query)
    {
        return $query->where('status', 'dikembalikan');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPeminjam($query, $peminjamId)
    {
        return $query->where('peminjam_id', $peminjamId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_pinjam', [$startDate, $endDate]);
    }

    // Accessors
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

    public function getDurasiPeminjamanAttribute()
    {
        return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali_rencana) + 1;
    }

    public function getIsTerlambatAttribute()
    {
        return $this->status === 'terlambat' || 
               ($this->status === 'dipinjam' && Carbon::now()->gt($this->tanggal_kembali_rencana));
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'dipinjam' => 'bg-primary',
            'dikembalikan' => 'bg-success', 
            'terlambat' => 'bg-danger',
            'batal' => 'bg-secondary'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getTotalItemAttribute()
    {
        return $this->detailPeminjaman->sum('jumlah');
    }

    // Methods
    public function hitungDenda()
    {
        if ($this->hari_terlambat > 0) {
            $totalDenda = 0;
            foreach ($this->detailPeminjaman as $detail) {
                $totalDenda += $detail->barang->denda_per_hari * $detail->jumlah * $this->hari_terlambat;
            }
            return $totalDenda;
        }
        return 0;
    }

    public function updateStatus()
    {
        if ($this->status === 'dipinjam' && Carbon::now()->gt($this->tanggal_kembali_rencana)) {
            $this->update(['status' => 'terlambat']);
        }
    }

    public function getTanggalKembaliFormattedAttribute()
{
    if ($this->tanggal_kembali_aktual) {
        return $this->tanggal_kembali_aktual->format('d-m-Y');
    }

    if ($this->tanggal_kembali_rencana) {
        return $this->tanggal_kembali_rencana->format('d-m-Y');
    }

    return '-';
}

}