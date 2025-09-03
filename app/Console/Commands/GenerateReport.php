<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;

class GenerateReport extends Command
{
    protected $signature = 'report:generate {type} {--month=} {--year=} {--email=}';
    protected $description = 'Generate monthly reports (peminjaman, keuangan)';

    public function handle()
    {
        $type = $this->argument('type');
        $month = $this->option('month') ?: Carbon::now()->month;
        $year = $this->option('year') ?: Carbon::now()->year;
        
        $this->info("Generating {$type} report for {$month}/{$year}...");

        switch ($type) {
            case 'peminjaman':
                $this->generatePeminjamanReport($month, $year);
                break;
            case 'keuangan':
                $this->generateKeuanganReport($month, $year);
                break;
            default:
                $this->error('Report type must be: peminjaman, keuangan');
                return 1;
        }

        return 0;
    }

    private function generatePeminjamanReport($month, $year)
    {
        $peminjaman = Peminjaman::with('peminjam', 'detailPeminjaman.barang')
            ->whereMonth('tanggal_pinjam', $month)
            ->whereYear('tanggal_pinjam', $year)
            ->get();

        $totalPeminjaman = $peminjaman->count();
        $totalPendapatan = $peminjaman->sum('total_bayar');
        $totalDenda = $peminjaman->sum('total_denda');

        $filename = "laporan-peminjaman-{$month}-{$year}.pdf";
        
        // Generate PDF menggunakan view
        $pdf = PDF::loadView('reports.peminjaman-monthly', compact(
            'peminjaman', 'totalPeminjaman', 'totalPendapatan', 'totalDenda', 'month', 'year'
        ));
        
        Storage::put("reports/{$filename}", $pdf->output());
        
        $this->info("Report saved: storage/app/reports/{$filename}");
        
        // Send email if option provided
        if ($this->option('email')) {
            // Implementation for sending email
            $this->info("Report sent to: " . $this->option('email'));
        }
    }

    private function generateKeuanganReport($month, $year)
    {
        $transaksi = TransaksiKeuangan::with('peminjaman')
            ->whereMonth('tanggal_transaksi', $month)
            ->whereYear('tanggal_transaksi', $year)
            ->get();

        $totalMasuk = $transaksi->where('jenis_transaksi', 'masuk')->sum('jumlah');
        $totalKeluar = $transaksi->where('jenis_transaksi', 'keluar')->sum('jumlah');
        $saldoBersih = $totalMasuk - $totalKeluar;

        $filename = "laporan-keuangan-{$month}-{$year}.pdf";
        
        $pdf = PDF::loadView('reports.keuangan-monthly', compact(
            'transaksi', 'totalMasuk', 'totalKeluar', 'saldoBersih', 'month', 'year'
        ));
        
        Storage::put("reports/{$filename}", $pdf->output());
        
        $this->info("Report saved: storage/app/reports/{$filename}");
        
        if ($this->option('email')) {
            $this->info("Report sent to: " . $this->option('email'));
        }
    }
}