<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransaksiKeuangan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keuangans';
    
    protected $fillable = [
        'kode_transaksi', 
        'peminjaman_id', 
        'jenis_transaksi',
        'kategori', 
        'jumlah', 
        'deskripsi', 
        'tanggal_transaksi',
        'bukti_transaksi'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'jumlah' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->kode_transaksi)) {
                $jenis = $model->jenis_transaksi == 'masuk' ? 'IN' : 'OUT';
                $model->kode_transaksi = 'TRX-' . $jenis . '-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            if (!$model->tanggal_transaksi) {
                $model->tanggal_transaksi = Carbon::now();
            }
        });
    }

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // Scopes
    public function scopeMasuk($query)
    {
        return $query->where('jenis_transaksi', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis_transaksi', 'keluar');
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal_transaksi', Carbon::now()->month)
                    ->whereYear('tanggal_transaksi', Carbon::now()->year);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_transaksi', Carbon::today());
    }

    // Accessors
    public function getJenisBadgeAttribute()
    {
        return $this->jenis_transaksi === 'masuk' ? 'bg-success' : 'bg-danger';
    }

    public function getKategoriBadgeAttribute()
    {
        $badges = [
            'sewa' => 'bg-primary',
            'denda' => 'bg-warning',
            'pemeliharaan' => 'bg-info',
            'pembelian' => 'bg-secondary',
            'lainnya' => 'bg-dark'
        ];

        return $badges[$this->kategori] ?? 'bg-secondary';
    }

    public function getFormattedJumlahAttribute()
    {
        $sign = $this->jenis_transaksi === 'masuk' ? '+' : '-';
        return $sign . ' Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getFormattedJumlahSimpleAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getIsFromPeminjamanAttribute()
    {
        return !is_null($this->peminjaman_id);
    }

    public function getCanBeEditedAttribute()
    {
        // Only manual transactions (not from peminjaman) can be edited
        return is_null($this->peminjaman_id);
    }

    // Methods
    public static function getSaldoBerjalan()
    {
        $totalMasuk = static::masuk()->sum('jumlah');
        $totalKeluar = static::keluar()->sum('jumlah');
        return $totalMasuk - $totalKeluar;
    }

    public static function getPendapatanBulanIni()
    {
        return static::masuk()->thisMonth()->sum('jumlah');
    }

    public static function getPengeluaranBulanIni()
    {
        return static::keluar()->thisMonth()->sum('jumlah');
    }

    public function getBuktiTransaksiUrlAttribute()
    {
        if ($this->bukti_transaksi) {
            return asset('storage/' . $this->bukti_transaksi);
        }
        return null;
    }
}