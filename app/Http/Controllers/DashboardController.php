<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user || !$user->role) {
            abort(403, 'No role assigned to your account');
        }

        return match ($user->role) {
            'admin'    => $this->adminDashboard(),
            'operator' => $this->operatorDashboard(),
            'user'     => $this->userDashboard(),
            default    => abort(403, 'Invalid role assigned'),
        };
    }

    private function adminDashboard()
    {
        $data = [
            'totalBarang'            => Barang::count(),
            'totalPeminjam'          => Peminjam::count(),
            'totalUsers'             => User::count(),
            'totalPeminjamanAktif'   => Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'totalPendapatanBulanIni'=> $this->getPendapatanBulanIni(),
            'totalPengeluaranBulanIni'=> $this->getPengeluaranBulanIni(),
            'pendapatanBulanan'      => $this->getPendapatanBulanan(),
            'barangPopuler'          => $this->getBarangPopuler(10),
            'peminjamanTerlambat'    => $this->getPeminjamanTerlambat(),
            'stokMenipis'            => $this->getStokMenipis(),
            'transaksiTerakhir'      => $this->getTransaksiTerakhir(),
        ];

        return view('dashboard.admin', $data);
    }

    private function operatorDashboard()
    {
        $data = [
            'totalBarang'          => Barang::count(),
            'totalPeminjam'        => Peminjam::count(),
            'totalPeminjamanAktif' => Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'peminjamanHariIni'    => Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count(),
            'pengembalianHariIni'  => Peminjaman::whereDate('tanggal_kembali_aktual', Carbon::today())->count(),
            'barangPopuler'        => $this->getBarangPopuler(5),
            'peminjamanTerlambat'  => $this->getPeminjamanTerlambat(),
            'stokMenipis'          => $this->getStokMenipis(),
            'jadwalKembali'        => $this->getJadwalKembali(),
            'statistikBulanan'     => $this->getStatistikPeminjamanBulanan(),
        ];

        return view('dashboard.operator', $data);
    }

    private function userDashboard()
    {
        $user = auth()->user();

        // Ambil peminjaman berdasarkan user_id
        $userPeminjaman = Peminjaman::where('user_id', $user->id)->get();

        // Jika tidak ada, coba cari berdasarkan peminjam yang dibuat user
        if ($userPeminjaman->isEmpty()) {
            $peminjamIds = Peminjam::where('created_by', $user->id)->pluck('id');
            if ($peminjamIds->count() > 0) {
                $userPeminjaman = Peminjaman::whereIn('peminjam_id', $peminjamIds)->get();
            }
        }

        $data = [
            'peminjamanAktif'   => $userPeminjaman->whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'totalPeminjaman'   => $userPeminjaman->count(),
            'peminjamanSelesai' => $userPeminjaman->where('status', 'dikembalikan')->count(),
            'peminjamanTerlambat'=> $userPeminjaman->where('status', 'terlambat')->count(),
            'riwayatPeminjaman' => $userPeminjaman->sortByDesc('created_at')->take(5),
            'barangTersedia'    => Barang::where('status', 'aktif')->where('stok_tersedia', '>', 0)->count(),
            'kategoriTersedia'  => DB::table('barangs')
                ->join('kategori_barangs', 'barangs.kategori_id', '=', 'kategori_barangs.id')
                ->where('barangs.status', 'aktif')
                ->where('barangs.stok_tersedia', '>', 0)
                ->distinct('kategori_barangs.id')
                ->count(),
            'peminjamanMendatang' => $this->getUserUpcomingReturns($user->id),
        ];

        return view('dashboard.user', $data);
    }

    // ================= Helper Methods =================

    private function getPendapatanBulanIni()
    {
        return TransaksiKeuangan::where('jenis_transaksi', 'masuk')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('jumlah');
    }

    private function getPengeluaranBulanIni()
    {
        return TransaksiKeuangan::where('jenis_transaksi', 'keluar')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('jumlah');
    }

    private function getPendapatanBulanan()
    {
        $pendapatanBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $pendapatan = TransaksiKeuangan::where('jenis_transaksi', 'masuk')
                ->whereMonth('tanggal_transaksi', $bulan->month)
                ->whereYear('tanggal_transaksi', $bulan->year)
                ->sum('jumlah');

            $pendapatanBulanan[] = [
                'bulan'      => $bulan->format('M Y'),
                'pendapatan' => $pendapatan
            ];
        }
        return $pendapatanBulanan;
    }

    private function getStatistikPeminjamanBulanan()
    {
        $statistik = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $jumlah = Peminjaman::whereMonth('tanggal_pinjam', $bulan->month)
                ->whereYear('tanggal_pinjam', $bulan->year)
                ->count();

            $statistik[] = [
                'bulan' => $bulan->format('M Y'),
                'jumlah' => $jumlah
            ];
        }
        return $statistik;
    }

    private function getBarangPopuler($limit = 10)
    {
        if (!Schema::hasTable('detail_peminjamans')) {
            return collect();
        }

        return DB::table('detail_peminjamans')
            ->join('barangs', 'detail_peminjamans.barang_id', '=', 'barangs.id')
            ->select('barangs.nama_barang', DB::raw('SUM(detail_peminjamans.jumlah) as total_dipinjam'))
            ->groupBy('barangs.id', 'barangs.nama_barang')
            ->orderByDesc('total_dipinjam')
            ->limit($limit)
            ->get();
    }

    private function getPeminjamanTerlambat()
    {
        return Peminjaman::with('peminjam')
            ->where('status', 'terlambat')
            ->orWhere(function ($query) {
                $query->where('status', 'dipinjam')
                      ->where('tanggal_kembali_rencana', '<', Carbon::now());
            })
            ->latest()
            ->limit(10)
            ->get();
    }

    private function getStokMenipis()
    {
        return Barang::where('stok_tersedia', '<=', 3)
            ->where('stok_tersedia', '>', 0)
            ->where('status', 'aktif')
            ->with('kategori')
            ->orderBy('stok_tersedia')
            ->limit(10)
            ->get();
    }

    private function getJadwalKembali()
    {
        return Peminjaman::with(['peminjam', 'detailPeminjaman.barang'])
            ->where('status', 'dipinjam')
            ->whereBetween('tanggal_kembali_rencana', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('tanggal_kembali_rencana')
            ->limit(10)
            ->get();
    }

    private function getTransaksiTerakhir()
    {
        return TransaksiKeuangan::with('peminjaman.peminjam')
            ->latest('tanggal_transaksi')
            ->limit(5)
            ->get();
    }

    private function getUserUpcomingReturns($userId)
    {
        return Peminjaman::with(['peminjam', 'detailPeminjaman.barang'])
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereBetween('tanggal_kembali_rencana', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('tanggal_kembali_rencana')
            ->get();
    }
}