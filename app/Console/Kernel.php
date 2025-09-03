<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\UpdateStatusPeminjaman::class,
        Commands\GenerateReport::class,
        Commands\CleanupFiles::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Update status peminjaman setiap hari jam 1 pagi
        $schedule->command('peminjaman:update-status')
                 ->dailyAt('01:00')
                 ->withoutOverlapping();

        // Generate laporan bulanan setiap tanggal 1 jam 2 pagi
        $schedule->command('report:generate peminjaman --month=' . date('m', strtotime('last month')) . ' --year=' . date('Y'))
                 ->monthlyOn(1, '02:00');

        $schedule->command('report:generate keuangan --month=' . date('m', strtotime('last month')) . ' --year=' . date('Y'))
                 ->monthlyOn(1, '02:30');

        // Cleanup files setiap minggu
        $schedule->command('cleanup:files --days=30')
                 ->weekly()
                 ->sundays()
                 ->at('03:00');

        // Backup database setiap hari jam 4 pagi (jika menggunakan backup package)
        // $schedule->command('backup:run')->dailyAt('04:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
