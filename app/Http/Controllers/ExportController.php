<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

// Models
use App\Models\User;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use App\Models\KategoriBarang;

class ExportController extends Controller
{
    /**
     * Export PDF Laporan Lengkap
     */
    public function exportPdf()
    {
        $users      = User::all();
        $barang     = Barang::with(['kategori', 'detailPeminjaman.peminjaman'])->get();
        $peminjaman = Peminjaman::with('peminjam')->get();
        $transaksi  = TransaksiKeuangan::with('peminjaman')->get();
        $kategori   = KategoriBarang::with('barang')->get();

        // Hitung rekap keuangan (case-insensitive)
        $totalMasuk  = (float) TransaksiKeuangan::whereRaw("LOWER(jenis_transaksi) = 'masuk'")->sum('jumlah');
        $totalKeluar = (float) TransaksiKeuangan::whereRaw("LOWER(jenis_transaksi) = 'keluar'")->sum('jumlah');
        $saldoAkhir  = $totalMasuk - $totalKeluar;

        // Pakai Blade PDF khusus
        $pdf = PDF::loadView('laporan.pdf.semua', compact(
            'users',
            'barang',
            'peminjaman',
            'transaksi',
            'kategori',
            'totalMasuk',
            'totalKeluar',
            'saldoAkhir'
        ));

        return $pdf->download('laporan_lengkap.pdf');
    }

    /**
     * Export Excel Laporan Lengkap
     */
    public function exportExcel()
    {
        $data = [
            'users'      => User::all(),
            'barang'     => Barang::with(['kategori', 'detailPeminjaman.peminjaman'])->get(),
            'peminjaman' => Peminjaman::with('peminjam')->get(),
            'transaksi'  => TransaksiKeuangan::with('peminjaman')->get(),
            'kategori'   => KategoriBarang::with('barang')->get(),
        ];

        return Excel::download(new LaporanExport($data), 'laporan_lengkap.xlsx');
    }
}
