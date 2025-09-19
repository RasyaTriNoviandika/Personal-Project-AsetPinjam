<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use PDF;

class LaporanController extends Controller
{
    public function index()
    {
        $users       = User::all();
        $barang      = Barang::with('kategori')->get();
        $kategori    = KategoriBarang::with('barang')->get();
        $peminjaman  = Peminjaman::with('peminjam')->get();
        $transaksi   = TransaksiKeuangan::all();

        return view('laporan.index', compact(
            'users', 'barang', 'kategori', 'peminjaman', 'transaksi'
        ));
    }

        public function exportPDF()
{
    $users       = User::all();
    $barang      = Barang::with('kategori')->get();
    $peminjaman  = Peminjaman::with(['peminjam','detailPeminjaman.barang'])->get();
    $kategori    = KategoriBarang::withCount('barang')->get();
    $transaksi   = TransaksiKeuangan::all(); // ✅ jangan lupa

    // Hitung rekap keuangan
    $totalPemasukan   = $transaksi->where('jenis', 'masuk')->sum('jumlah');
    $totalPengeluaran = $transaksi->where('jenis', 'keluar')->sum('jumlah');
    $saldoAkhir       = $totalPemasukan - $totalPengeluaran;

    $pdf = PDF::loadView('laporan.export-pdf', compact(
        'users',
        'barang',
        'peminjaman',
        'kategori',
        'transaksi',
        'totalPemasukan',
        'totalPengeluaran',
        'saldoAkhir'
    ));

    return $pdf->download('laporan-lengkap.pdf');
}


    public function exportExcel()
    {
        $data = [
            'users'      => User::all(),
            'barang'     => Barang::with('kategori')->get(),
            'kategori'   => KategoriBarang::with('barang')->get(),
            'peminjaman' => Peminjaman::with('peminjam')->get(),
            'transaksi'  => TransaksiKeuangan::all(),
        ];

        return Excel::download(new LaporanExport($data), 'laporan-semua.xlsx');
    }
}
