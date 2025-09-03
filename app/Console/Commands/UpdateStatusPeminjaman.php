<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;

class UpdateStatusPeminjaman extends Command
{
    protected $signature = 'peminjaman:update-status';
    protected $description = 'Update status peminjaman yang terlambat';

    public function handle()
    {
        $this->info('Memulai update status peminjaman...');

        // Update status peminjaman yang terlambat
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', Carbon::now())
            ->update(['status' => 'terlambat']);

        $this->info("Status {$terlambat} peminjaman telah diupdate menjadi terlambat.");

        // Log activity
        if ($terlambat > 0) {
            \Log::info("Updated {$terlambat} overdue rentals to 'terlambat' status");
        }

        return 0;
    }
}