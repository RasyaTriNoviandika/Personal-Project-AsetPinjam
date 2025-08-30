<?php
// app/Http/Controllers/LaporanController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use App\Models\Barang;
use Carbon\Carbon;
use PDF;
use Excel;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with('peminjam', 'detailPeminjaman.barang');
        
        if ($request->tanggal_mulai) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }
        
        if ($request->tanggal_selesai) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }
        
        if ($request->status && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();
        
        $totalPeminjaman = $peminjaman->count();
        $totalPendapatan = $peminjaman->sum('total_bayar');
        $totalDenda = $peminjaman->sum('total_denda');

        if ($request->export == 'pdf') {
            $pdf = PDF::loadView('laporan.peminjaman-pdf', compact('peminjaman', 'totalPeminjaman', 'totalPendapatan', 'totalDenda'));
            return $pdf->download('laporan-peminjaman.pdf');
        }

        return view('laporan.peminjaman', compact('peminjaman', 'totalPeminjaman', 'totalPendapatan', 'totalDenda'));
    }

    public function keuangan(Request $request)
    {
        $query = TransaksiKeuangan::with('peminjaman');
        
        if ($request->tanggal_mulai) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }
        
        if ($request->tanggal_selesai) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }
        
        if ($request->jenis && $request->jenis != 'semua') {
            $query->where('jenis_transaksi', $request->jenis);
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->get();
        
        $totalMasuk = $transaksi->where('jenis_transaksi', 'masuk')->sum('jumlah');
        $totalKeluar = $transaksi->where('jenis_transaksi', 'keluar')->sum('jumlah');
        $saldoBersih = $totalMasuk - $totalKeluar;

        if ($request->export == 'pdf') {
            $pdf = PDF::loadView('laporan.keuangan-pdf', compact('transaksi', 'totalMasuk', 'totalKeluar', 'saldoBersih'));
            return $pdf->download('laporan-keuangan.pdf');
        }

        return view('laporan.keuangan', compact('transaksi', 'totalMasuk', 'totalKeluar', 'saldoBersih'));
    }

    public function barang(Request $request)
    {
        $query = Barang::with(['kategori', 'detailPeminjaman']);
        
        if ($request->kategori_id && $request->kategori_id != 'semua') {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        if ($request->status && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        $barang = $query->get()->map(function($item) {
            $item->total_dipinjam = $item->detailPeminjaman->sum('jumlah');
            $item->pendapatan = $item->detailPeminjaman->sum('subtotal_sewa');
            return $item;
        });

        if ($request->export == 'pdf') {
            $pdf = PDF::loadView('laporan.barang-pdf', compact('barang'));
            return $pdf->download('laporan-barang.pdf');
        }

        return view('laporan.barang', compact('barang'));
    }
}