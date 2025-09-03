<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupFiles extends Command
{
    protected $signature = 'cleanup:files {--days=30}';
    protected $description = 'Clean up old uploaded files and reports';

    public function handle()
    {
        $days = $this->option('days');
        $this->info("Cleaning up files older than {$days} days...");

        $deleted = 0;

        // Cleanup temporary files
        $tempFiles = Storage::disk('local')->files('temp');
        foreach ($tempFiles as $file) {
            if (Storage::disk('local')->lastModified($file) < Carbon::now()->subDays($days)->timestamp) {
                Storage::disk('local')->delete($file);
                $deleted++;
            }
        }

        // Cleanup old reports
        $reportFiles = Storage::disk('local')->files('reports');
        foreach ($reportFiles as $file) {
            if (Storage::disk('local')->lastModified($file) < Carbon::now()->subDays($days * 2)->timestamp) {
                Storage::disk('local')->delete($file);
                $deleted++;
            }
        }

        $this->info("Deleted {$deleted} old files.");
        return 0;
    }
}