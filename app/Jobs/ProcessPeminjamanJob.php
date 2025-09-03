<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;

class ProcessPeminjamanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $peminjamanData;
    protected $userId;

    public function __construct($peminjamanData, $userId)
    {
        $this->peminjamanData = $peminjamanData;
        $this->userId = $userId;
    }

    public function handle()
    {
        try {
            // Create peminjaman record
            $peminjaman = Peminjaman::create([
                'peminjam_id' => $this->peminjamanData['peminjam_id'],
                'user_id' => $this->userId,
                'tanggal_pinjam' => $this->peminjamanData['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $this->peminjamanData['tanggal_kembali_rencana'],
                'total_biaya_sewa' => $this->peminjamanData['total_biaya_sewa'],
                'total_denda' => 0,
                'total_bayar' => $this->peminjamanData['total_biaya_sewa'],
                'status' => 'dipinjam',
                'catatan' => $this->peminjamanData['catatan'] ?? null
            ]);

            // Create transaction record
            TransaksiKeuangan::create([
                'peminjaman_id' => $peminjaman->id,
                'jenis_transaksi' => 'masuk',
                'kategori' => 'sewa',
                'jumlah' => $this->peminjamanData['total_biaya_sewa'],
                'deskripsi' => "Pembayaran sewa untuk peminjaman {$peminjaman->kode_peminjaman}",
                'tanggal_transaksi' => now()
            ]);

            \Log::info("Peminjaman {$peminjaman->kode_peminjaman} processed successfully");

        } catch (\Exception $e) {
            \Log::error("Failed to process peminjaman: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Exception $exception)
    {
        \Log::error("ProcessPeminjamanJob failed: " . $exception->getMessage());
    }
}
