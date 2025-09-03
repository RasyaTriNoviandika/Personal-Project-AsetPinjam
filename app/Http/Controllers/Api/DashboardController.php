<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ApiResponse;

    public function statistics()
    {
        try {
            $stats = [
                'total_barang' => Barang::count(),
                'total_peminjam' => Peminjam::count(),
                'total_peminjaman_aktif' => Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
                'total_pendapatan_bulan_ini' => TransaksiKeuangan::where('jenis_transaksi', 'masuk')
                    ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                    ->whereYear('tanggal_transaksi', Carbon::now()->year)
                    ->sum('jumlah'),
                'barang_stok_menipis' => Barang::where('stok_tersedia', '<=', 3)
                    ->where('status', 'aktif')
                    ->count(),
                'peminjaman_terlambat' => Peminjaman::where('status', 'terlambat')->count()
            ];

            return $this->successResponse($stats, 'Statistik dashboard berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mengambil statistik: ' . $e->getMessage(), 500);
        }
    }

    public function chartData()
    {
        try {
            // Pendapatan per bulan (6 bulan terakhir)
            $pendapatanBulanan = [];
            for ($i = 5; $i >= 0; $i--) {
                $bulan = Carbon::now()->subMonths($i);
                $pendapatan = TransaksiKeuangan::where('jenis_transaksi', 'masuk')
                    ->whereMonth('tanggal_transaksi', $bulan->month)
                    ->whereYear('tanggal_transaksi', $bulan->year)
                    ->sum('jumlah');
                
                $pendapatanBulanan[] = [
                    'bulan' => $bulan->format('M Y'),
                    'pendapatan' => $pendapatan
                ];
            }

            // Barang paling populer
            $barangPopuler = DB::table('detail_peminjaman')
                ->join('barang', 'detail_peminjaman.barang_id', '=', 'barang.id')
                ->select('barang.nama_barang', DB::raw('SUM(detail_peminjaman.jumlah) as total_dipinjam'))
                ->groupBy('barang.id', 'barang.nama_barang')
                ->orderBy('total_dipinjam', 'desc')
                ->limit(5)
                ->get();

            return $this->successResponse([
                'pendapatan_bulanan' => $pendapatanBulanan,
                'barang_populer' => $barangPopuler
            ], 'Data chart berhasil diambil');

        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mengambil data chart: ' . $e->getMessage(), 500);
        }
    }
}