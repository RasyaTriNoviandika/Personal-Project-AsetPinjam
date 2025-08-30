<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Dasar
        $totalBarang = Barang::count();
        $totalPeminjam = Peminjam::count();
        $totalPeminjamanAktif = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $totalPendapatanBulanIni = TransaksiKeuangan::where('jenis_transaksi', 'masuk')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('jumlah');

        // Barang Paling Sering Dipinjam (Top 5) - dengan pengecekan tabel exists
        $barangPopuler = collect();
        try {
            $barangPopuler = DB::table('detail_peminjaman')
                ->join('barang', 'detail_peminjaman.barang_id', '=', 'barang.id')
                ->select('barang.nama_barang', DB::raw('SUM(detail_peminjaman.jumlah) as total_dipinjam'))
                ->groupBy('barang.id', 'barang.nama_barang')
                ->orderBy('total_dipinjam', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Jika tabel detail_peminjaman belum ada
            $barangPopuler = collect();
        }

        // Pendapatan per Bulan (6 bulan terakhir)
        $pendapatanBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $pendapatan = 0;
            try {
                $pendapatan = TransaksiKeuangan::where('jenis_transaksi', 'masuk')
                    ->whereMonth('tanggal_transaksi', $bulan->month)
                    ->whereYear('tanggal_transaksi', $bulan->year)
                    ->sum('jumlah');
            } catch (\Exception $e) {
                $pendapatan = 0;
            }
            
            $pendapatanBulanan[] = [
                'bulan' => $bulan->format('M Y'),
                'pendapatan' => $pendapatan
            ];
        }

        // Peminjaman Terlambat - dengan pengecekan
        $peminjamanTerlambat = collect();
        try {
            $peminjamanTerlambat = Peminjaman::with('peminjam')
                ->where('status', 'dipinjam')
                ->where('tanggal_kembali_rencana', '<', Carbon::now())
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $peminjamanTerlambat = collect();
        }

        // Stok Barang Menipis
        $stokMenipis = Barang::where('stok_tersedia', '<=', 3)
            ->where('status', 'aktif')
            ->with('kategori')
            ->orderBy('stok_tersedia')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalBarang', 'totalPeminjam', 'totalPeminjamanAktif', 
            'totalPendapatanBulanIni', 'barangPopuler', 'pendapatanBulanan',
            'peminjamanTerlambat', 'stokMenipis'
        ));
    }
}

