<?php
namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Barang;
use Carbon\Carbon;

class NotificationService
{
    public static function getSystemNotifications()
    {
        $notifications = collect();

        // Peminjaman yang akan jatuh tempo (H-1)
        $besok = Carbon::tomorrow();
        $jatuhTempo = Peminjaman::with('peminjam')
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', $besok)
            ->get();

        foreach ($jatuhTempo as $pinjam) {
            $notifications->push([
                'type' => 'warning',
                'icon' => 'clock',
                'title' => 'Peminjaman Jatuh Tempo',
                'message' => "Peminjaman {$pinjam->kode_peminjaman} oleh {$pinjam->peminjam->nama_peminjam} akan jatuh tempo besok",
                'url' => route('peminjaman.show', $pinjam->id),
                'time' => 'Besok',
                'created_at' => now(),
            ]);
        }

        // Peminjaman terlambat
        $terlambat = Peminjaman::with('peminjam')
            ->where('status', 'terlambat')
            ->get();

        foreach ($terlambat as $pinjam) {
            $hariTerlambat = Carbon::now()->diffInDays($pinjam->tanggal_kembali_rencana);
            $notifications->push([
                'type' => 'danger',
                'icon' => 'alert-triangle',
                'title' => 'Peminjaman Terlambat',
                'message' => "Peminjaman {$pinjam->kode_peminjaman} oleh {$pinjam->peminjam->nama_peminjam} terlambat {$hariTerlambat} hari",
                'url' => route('peminjaman.pengembalian', $pinjam->id),
                'time' => "{$hariTerlambat} hari yang lalu",
                'created_at' => $pinjam->tanggal_kembali_rencana,
            ]);
        }

        // Stok barang menipis (<=3)
        $stokMenipis = Barang::with('kategori')
            ->where('stok_tersedia', '<=', 3)
            ->where('stok_tersedia', '>', 0)
            ->where('status', 'aktif')
            ->get();

        foreach ($stokMenipis as $barang) {
            $notifications->push([
                'type' => 'info',
                'icon' => 'package',
                'title' => 'Stok Menipis',
                'message' => "Stok {$barang->nama_barang} tinggal {$barang->stok_tersedia} unit",
                'url' => route('barang.show', $barang->id),
                'time' => 'Sekarang',
                'created_at' => now(),
            ]);
        }

        // Barang habis
        $stokHabis = Barang::with('kategori')
            ->where('stok_tersedia', 0)
            ->where('status', 'aktif')
            ->get();

        foreach ($stokHabis as $barang) {
            $notifications->push([
                'type' => 'danger',
                'icon' => 'x-circle',
                'title' => 'Stok Habis',
                'message' => "Stok {$barang->nama_barang} sudah habis",
                'url' => route('barang.show', $barang->id),
                'time' => 'Sekarang',
                'created_at' => now(),
            ]);
        }

        return $notifications->sortByDesc('created_at');
    }

    public static function getNotificationCount()
    {
        return self::getSystemNotifications()->count();
    }
}
