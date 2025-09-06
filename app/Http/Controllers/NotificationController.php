<?php
// app/Http/Controllers/NotificationController.php (Enhanced)

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $this->getNotifications($user);
        
        if (request()->expectsJson()) {
            return response()->json($notifications);
        }
        
        return view('notifications.index', compact('notifications'));
    }

    public function getUnreadCount()
    {
        $user = auth()->user();
        $notifications = $this->getNotifications($user);
        
        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->take(5) // Only latest 5 for dropdown
        ]);
    }

    private function getNotifications($user)
    {
        $notifications = collect();

        // Changed from hasAnyRole(['admin', 'operator'])
        if ($user->isAdmin()) {
            $notifications = $notifications->merge($this->getAdminNotifications()); // Renamed method
        }
        
        if ($user->isUser()) {
            $notifications = $notifications->merge($this->getUserNotifications($user));
        }

        return $notifications->sortBy('priority')->values();
    }

    // Renamed from getAdminOperatorNotifications to getAdminNotifications
    private function getAdminNotifications()
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
                'id' => 'due_' . $pinjam->id,
                'type' => 'warning',
                'icon' => 'clock',
                'title' => 'Peminjaman Jatuh Tempo',
                'message' => "Peminjaman {$pinjam->kode_peminjaman} oleh {$pinjam->peminjam->nama_peminjam} akan jatuh tempo besok",
                'url' => route('peminjaman.show', $pinjam->id),
                'time' => $pinjam->tanggal_kembali_rencana->diffForHumans(),
                'priority' => 2
            ]);
        }

        // Peminjaman terlambat
        $terlambat = Peminjaman::with('peminjam')
            ->where('status', 'terlambat')
            ->get();

        foreach ($terlambat as $pinjam) {
            $notifications->push([
                'id' => 'overdue_' . $pinjam->id,
                'type' => 'danger',
                'icon' => 'alert-triangle',
                'title' => 'Peminjaman Terlambat',
                'message' => "Peminjaman {$pinjam->kode_peminjaman} oleh {$pinjam->peminjam->nama_peminjam} sudah terlambat",
                'url' => route('peminjaman.pengembalian', $pinjam->id),
                'time' => $pinjam->tanggal_kembali_rencana->diffForHumans(),
                'priority' => 1
            ]);
        }

        // Stok barang menipis
        $stokMenipis = Barang::with('kategori')
            ->where('stok_tersedia', '<=', 3)
            ->where('stok_tersedia', '>', 0)
            ->where('status', 'aktif')
            ->get();

        foreach ($stokMenipis as $barang) {
            $notifications->push([
                'id' => 'stock_' . $barang->id,
                'type' => 'info',
                'icon' => 'package',
                'title' => 'Stok Menipis',
                'message' => "Stok {$barang->nama_barang} tinggal {$barang->stok_tersedia} unit",
                'url' => route('barang.show', $barang->id),
                'time' => 'Sekarang',
                'priority' => 3
            ]);
        }

        // Barang habis
        $barangHabis = Barang::with('kategori')
            ->where('stok_tersedia', '=', 0)
            ->where('status', 'aktif')
            ->get();

        foreach ($barangHabis as $barang) {
            $notifications->push([
                'id' => 'out_of_stock_' . $barang->id,
                'type' => 'danger',
                'icon' => 'x-circle',
                'title' => 'Stok Habis',
                'message' => "Stok {$barang->nama_barang} sudah habis",
                'url' => route('barang.show', $barang->id),
                'time' => 'Sekarang',
                'priority' => 1
            ]);
        }

        return $notifications;
    }

    private function getUserNotifications($user)
    {
        $notifications = collect();

        // Peminjaman user yang akan jatuh tempo
        $userPeminjamanDue = Peminjaman::with('peminjam')
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->whereBetween('tanggal_kembali_rencana', [Carbon::today(), Carbon::today()->addDays(2)])
            ->get();

        foreach ($userPeminjamanDue as $pinjam) {
            $daysLeft = Carbon::today()->diffInDays(Carbon::parse($pinjam->tanggal_kembali_rencana));
            $notifications->push([
                'id' => 'user_due_' . $pinjam->id,
                'type' => $daysLeft <= 1 ? 'warning' : 'info',
                'icon' => 'clock',
                'title' => 'Peminjaman Anda Akan Jatuh Tempo',
                'message' => "Peminjaman {$pinjam->kode_peminjaman} akan jatuh tempo " . 
                    ($daysLeft == 0 ? 'hari ini' : "dalam {$daysLeft} hari"),
                'url' => route('peminjaman.show', $pinjam->id),
                'time' => $pinjam->tanggal_kembali_rencana->diffForHumans(),
                'priority' => $daysLeft <= 1 ? 2 : 3
            ]);
        }

        // Peminjaman user yang terlambat
        $userPeminjamanOverdue = Peminjaman::with('peminjam')
            ->where('user_id', $user->id)
            ->where('status', 'terlambat')
            ->get();

        foreach ($userPeminjamanOverdue as $pinjam) {
            $notifications->push([
                'id' => 'user_overdue_' . $pinjam->id,
                'type' => 'danger',
                'icon' => 'alert-triangle',
                'title' => 'Peminjaman Anda Terlambat',
                'message' => "Peminjaman {$pinjam->kode_peminjaman} sudah terlambat. Segera kembalikan untuk menghindari denda tambahan.",
                'url' => route('peminjaman.show', $pinjam->id),
                'time' => $pinjam->tanggal_kembali_rencana->diffForHumans(),
                'priority' => 1
            ]);
        }

        return $notifications;
    }

    public function markAsRead(Request $request)
    {
        // In a real implementation, you might store notification read status in database
        // For now, we'll just return success
        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        // In a real implementation, mark all notifications as read for the user
        return response()->json(['success' => true]);
    }
}