<?php
// app/Models/Peminjam.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjam extends Model
{
    use HasFactory;

    protected $table = 'peminjam';
    protected $fillable = [
        'kode_peminjam', 'nama_lengkap', 'email', 'no_telepon',
        'alamat', 'jenis_identitas', 'nomor_identitas',
        'foto_identitas', 'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->kode_peminjam)) {
                $model->kode_peminjam = 'PMJ-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}