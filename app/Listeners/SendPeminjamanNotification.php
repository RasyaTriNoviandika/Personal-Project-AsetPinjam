<?php
namespace App\Listeners;

use App\Events\PeminjamanCreated;
use App\Services\NotificationService;

class SendPeminjamanNotification
{
    public function __construct()
    {
        //
    }

    public function handle(PeminjamanCreated $event)
    {
        $peminjaman = $event->peminjaman;
        
        // Log activity
        \Log::info("New peminjaman created: {$peminjaman->kode_peminjaman}");
        
        // Send notification (implement your notification logic here)
        // Could be email, SMS, push notification, etc.
        
        // Example: Create system notification
        // Notification::create([
        //     'title' => 'Peminjaman Baru',
        //     'message' => "Peminjaman {$peminjaman->kode_peminjaman} telah dibuat",
        //     'type' => 'info',
        //     'url' => route('peminjaman.show', $peminjaman->id)
        // ]);
    }
}
