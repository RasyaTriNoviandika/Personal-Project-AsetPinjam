<?php
// app/Models/TransaksiKeuangan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKeuangan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keuangans';
    protected $fillable = [
        'kode_transaksi', 'peminjaman_id', 'jenis_transaksi',
        'kategori', 'jumlah', 'deskripsi', 'tanggal_transaksi',
        'bukti_transaksi'
    ];

    protected $dates = ['tanggal_transaksi'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->kode_transaksi)) {
                $jenis = $model->jenis_transaksi == 'masuk' ? 'IN' : 'OUT';
                $model->kode_transaksi = 'TRX-' . $jenis . '-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function scopeMasuk($query)
    {
        return $query->where('jenis_transaksi', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis_transaksi', 'keluar');
    }
}