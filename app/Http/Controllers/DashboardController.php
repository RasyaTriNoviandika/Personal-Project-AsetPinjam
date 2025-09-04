<?php
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

    // Base statistics
    $totalBarang = Barang::count();
    $totalPeminjam = Peminjam::count();
    $totalPeminjamanAktif = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count();

    $dashboardData = [
        'totalBarang' => $totalBarang,
        'totalPeminjam' => $totalPeminjam,
        'totalPeminjamanAktif' => $totalPeminjamanAktif,
    ];

    if ($user->isAdmin()) {
        $dashboardData = array_merge($dashboardData, $this->getAdminData());

        // ✅ Pendapatan bulan ini
        $dashboardData['totalPendapatanBulanIni'] = TransaksiKeuangan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('jumlah');

        // ✅ Barang populer
        $dashboardData['barangPopuler'] = Barang::withCount('detailPeminjaman')
    ->orderByDesc('detail_peminjaman_count')
    ->take(5)
    ->get()
    ->map(function ($barang) {
        $barang->total_dipinjam = $barang->detail_peminjaman_count;
        return $barang;
    });


        // ✅ Peminjaman terlambat
        $dashboardData['peminjamanTerlambat'] = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', Carbon::now())
            ->with('peminjam')
            ->get();

        // ✅ Stok menipis
        // $dashboardData['stokMenipis'] = Barang::whereColumn('stok_tersedia', '<=', 'stok_minimal')
        //     ->get();

        // ✅ Pendapatan 6 bulan terakhir (untuk chart)
        $dashboardData['pendapatanBulanan'] = TransaksiKeuangan::selectRaw('MONTH(created_at) as bulan, SUM(jumlah) as pendapatan')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->map(function ($item) {
                return [
                    'bulan' => Carbon::create()->month($item->bulan)->translatedFormat('F'),
                    'pendapatan' => $item->pendapatan,
                ];
            });
    } else {
        // Biar aman untuk role non-admin
        $dashboardData['barangPopuler'] = collect();
        $dashboardData['peminjamanTerlambat'] = collect();
        $dashboardData['stokMenipis'] = collect();
        $dashboardData['pendapatanBulanan'] = collect();
        $dashboardData['totalPendapatanBulanIni'] = 0;
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
            // Fallback if table doesn't exist yet
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