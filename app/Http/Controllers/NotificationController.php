<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = $this->getNotifications();
        return response()->json($notifications);
    }

    private function getNotifications()
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
                'time' => $pinjam->tanggal_kembali_rencana->diffForHumans()
            ]);
        }

        // Peminjaman terlambat
        $terlambat = Peminjaman::with('peminjam')
            ->where('status', 'terlambat')
            ->get();

        foreach ($terlambat as $pinjam) {
            $notifications->push([
                'type' => 'danger',
                'icon' => 'alert-triangle',
                'title' => 'Peminjaman Terlambat',
                'message' => "Peminjaman {$pinjam->kode_peminjaman} oleh {$pinjam->peminjam->nama_peminjam} sudah terlambat",
                'url' => route('peminjaman.pengembalian', $pinjam->id),
                'time' => $pinjam->tanggal_kembali_rencana->diffForHumans()
            ]);
        }

        // Stok barang menipis
        $stokMenipis = Barang::with('kategori')
            ->where('stok_tersedia', '<=', 3)
            ->where('status', 'aktif')
            ->get();

        foreach ($stokMenipis as $barang) {
            $notifications->push([
                'type' => 'info',
                'icon' => 'package',
                'title' => 'Stok Menipis',
                'message' => "Stok {$barang->nama_barang} tinggal {$barang->stok_tersedia} unit",
                'url' => route('barang.show', $barang->id),
                'time' => 'Sekarang'
            ]);
        }

        return $notifications->sortBy('type');
    }
}
