<?php
// app/Http/Controllers/DashboardController.php (Enhanced)

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Base statistics available to all users
        $totalBarang = Barang::count();
        $totalPeminjam = Peminjam::count();
        $totalPeminjamanAktif = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count();
        
        // Role-based data
        $dashboardData = [
            'totalBarang' => $totalBarang,
            'totalPeminjam' => $totalPeminjam,
            'totalPeminjamanAktif' => $totalPeminjamanAktif,
        ];

        if ($user->isAdmin()) {
            $dashboardData = array_merge($dashboardData, $this->getAdminData());
    } elseif ($user->isOperator()) {
            $dashboardData = array_merge($dashboardData, $this->getOperatorData());
        } else {
            $dashboardData = array_merge($dashboardData, $this->getUserData($user));
        }

        return view('dashboard.index', $dashboardData);
    }

    private function getAdminData()
    {
        // Financial data - Admin only
        $totalPendapatanBulanIni = TransaksiKeuangan::where('jenis_transaksi', 'masuk')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('jumlah');

        $totalPengeluaranBulanIni = TransaksiKeuangan::where('jenis_transaksi', 'keluar')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('jumlah');

        // User statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();

        // Revenue trend (last 6 months)
        $pendapatanBulanan = $this->getMonthlyRevenue();

        // Most popular items
        $barangPopuler = $this->getMostPopularItems();

        return [
            'totalPendapatanBulanIni' => $totalPendapatanBulanIni,
            'totalPengeluaranBulanIni' => $totalPengeluaranBulanIni,
            'netProfitBulanIni' => $totalPendapatanBulanIni - $totalPengeluaranBulanIni,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'pendapatanBulanan' => $pendapatanBulanan,
            'barangPopuler' => $barangPopuler,
            'peminjamanTerlambat' => $this->getOverdueRentals(),
            'stokMenipis' => $this->getLowStockItems(),
            'transaksiTerbaru' => $this->getRecentTransactions(),
        ];
    }

    private function getOperatorData()
    {
        return [
            'peminjamanHariIni' => Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count(),
            'pengembalianHariIni' => Peminjaman::whereDate('tanggal_kembali_aktual', Carbon::today())->count(),
            'peminjamanTerlambat' => $this->getOverdueRentals(),
            'stokMenipis' => $this->getLowStockItems(),
            'barangPopuler' => $this->getMostPopularItems(5),
            'jadwalKembali' => $this->getUpcomingReturns(),
        ];
    }

    private function getUserData($user)
    {
        $userPeminjaman = Peminjaman::where('user_id', $user->id)->get();
        
        return [
            'peminjamanAktif' => $userPeminjaman->whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'totalPeminjaman' => $userPeminjaman->count(),
            'peminjamanSelesai' => $userPeminjaman->where('status', 'dikembalikan')->count(),
            'peminjamanTerlambat' => $userPeminjaman->where('status', 'terlambat')->count(),
            'riwayatPeminjaman' => $userPeminjaman->sortByDesc('created_at')->take(5),
            'barangFavorit' => $this->getUserFavoriteItems($user->id),
        ];
    }

    private function getMonthlyRevenue()
    {
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
        return $pendapatanBulanan;
    }

    private function getMostPopularItems($limit = 10)
{
    try {
        return DB::table('detail_peminjamans')
            ->join('barangs', 'detail_peminjamans.barang_id', '=', 'barangs.id')
            ->select('barangs.nama_barang', DB::raw('SUM(detail_peminjamans.jumlah) as total_dipinjam'))
            ->groupBy('barangs.id', 'barangs.nama_barang')
            ->orderBy('total_dipinjam', 'desc')
            ->limit($limit)
            ->get();
    } catch (\Exception $e) {
        return collect();
    }
}

private function getUserFavoriteItems($userId)
{
    try {
        return DB::table('detail_peminjamans')
            ->join('peminjamans', 'detail_peminjamans.peminjaman_id', '=', 'peminjamans.id')
            ->join('barangs', 'detail_peminjamans.barang_id', '=', 'barangs.id')
            ->where('peminjamans.user_id', $userId)
            ->select('barangs.nama_barang', DB::raw('COUNT(*) as frequency'))
            ->groupBy('barangs.id', 'barangs.nama_barang')
            ->orderBy('frequency', 'desc')
            ->limit(5)
            ->get();
    } catch (\Exception $e) {
        return collect();
    }
}

    private function getOverdueRentals()
    {
        try {
            return Peminjaman::with('peminjam')
                ->where('status', 'dipinjam')
                ->where('tanggal_kembali_rencana', '<', Carbon::now())
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getLowStockItems()
    {
        return Barang::where('stok_tersedia', '<=', 3)
            ->where('status', 'aktif')
            ->with('kategori')
            ->orderBy('stok_tersedia')
            ->limit(10)
            ->get();
    }

    private function getRecentTransactions()
    {
        return TransaksiKeuangan::with('peminjaman.peminjam')
            ->latest('tanggal_transaksi')
            ->limit(5)
            ->get();
    }

    private function getUpcomingReturns()
    {
        return Peminjaman::with(['peminjam', 'detailPeminjaman.barang'])
            ->where('status', 'dipinjam')
            ->whereBetween('tanggal_kembali_rencana', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('tanggal_kembali_rencana')
            ->limit(10)
            ->get();
    }
}