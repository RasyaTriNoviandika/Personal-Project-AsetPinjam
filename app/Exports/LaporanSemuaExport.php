<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanSemuaExport implements WithMultipleSheets
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Sheet User
        $users = User::withCount(['peminjaman as total_peminjaman'])
            ->withSum(['peminjaman as total_pendapatan'], 'total_biaya_sewa')
            ->get()
            ->map(function($u) {
                return [
                    'ID' => $u->id,
                    'Nama'  => $u->name,
                    'Email' => $u->email,
                    'Role'  => ucfirst($u->role),
                    'Status' => $u->status == 'aktif' ? 'Aktif' : 'Tidak Aktif',
                    'Total Peminjaman' => $u->total_peminjaman ?? 0,
                    'Total Pendapatan' => $u->total_pendapatan ?? 0,
                ];
            })->toArray();
        $sheets[] = new SheetArrayExport($users, 'Users');

        // Sheet Barang
        $barang = Barang::with('kategori')
            ->withCount(['detailPeminjaman as total_dipinjam'])
            ->withSum(['detailPeminjaman as pendapatan'], 'total_biaya')
            ->get()
            ->map(function($b) {
                return [
                    'ID' => $b->id,
                    'Nama Barang' => $b->nama_barang,
                    'Kategori'    => $b->kategori->nama_kategori ?? '-',
                    'Stok Total' => $b->stok_total,
                    'Stok Tersedia' => $b->stok_tersedia,
                    'Harga Sewa/Hari' => $b->harga_sewa_per_hari,
                    'Total Dipinjam' => $b->total_dipinjam ?? 0,
                    'Total Pendapatan' => $b->pendapatan ?? 0,
                ];
            })->toArray();
        $sheets[] = new SheetArrayExport($barang, 'Barang');

        // Sheet Kategori
        $kategori = KategoriBarang::withCount(['barang as total_barang'])
            ->get()
            ->map(function($k) {
                return [
                    'ID' => $k->id,
                    'Nama Kategori' => $k->nama_kategori,
                    'Total Barang' => $k->total_barang ?? 0,
                    'Keterangan' => $k->keterangan ?? '-',
                ];
            })->toArray();
        $sheets[] = new SheetArrayExport($kategori, 'Kategori');

        // Sheet Peminjaman - dengan filter
        $peminjamanQuery = Peminjaman::with(['peminjam', 'detailPeminjaman.barang']);
        
        if (!empty($this->filters['tanggal_mulai'])) {
            $peminjamanQuery->whereDate('tanggal_pinjam', '>=', $this->filters['tanggal_mulai']);
        }
        
        if (!empty($this->filters['tanggal_selesai'])) {
            $peminjamanQuery->whereDate('tanggal_pinjam', '<=', $this->filters['tanggal_selesai']);
        }

        $peminjaman = $peminjamanQuery->get()->map(function($p) {
            return [
                'Kode Peminjaman' => $p->kode_peminjaman,
                'Peminjam' => $p->peminjam->nama_peminjam ?? '-',
                'Tanggal Pinjam' => $p->tanggal_pinjam->format('d/m/Y'),
                'Tanggal Kembali Rencana' => $p->tanggal_kembali_rencana->format('d/m/Y'),
                'Tanggal Kembali Aktual' => $p->tanggal_kembali_aktual ? $p->tanggal_kembali_aktual->format('d/m/Y') : '-',
                'Total Biaya Sewa' => $p->total_biaya_sewa,
                'Total Denda' => $p->total_denda,
                'Total Bayar' => $p->total_bayar,
                'Status' => ucfirst($p->status),
            ];
        })->toArray();
        $sheets[] = new SheetArrayExport($peminjaman, 'Peminjaman');

        // Sheet Keuangan - dengan filter
        $transaksiQuery = TransaksiKeuangan::with('peminjaman.peminjam');
        
        if (!empty($this->filters['tanggal_mulai'])) {
            $transaksiQuery->whereDate('tanggal_transaksi', '>=', $this->filters['tanggal_mulai']);
        }
        
        if (!empty($this->filters['tanggal_selesai'])) {
            $transaksiQuery->whereDate('tanggal_transaksi', '<=', $this->filters['tanggal_selesai']);
        }

        $transaksi = $transaksiQuery->get()->map(function($t) {
            return [
                'ID' => $t->id,
                'Tanggal' => $t->tanggal_transaksi->format('d/m/Y'),
                'Jenis Transaksi' => ucfirst($t->jenis_transaksi),
                'Jumlah' => $t->jumlah,
                'Keterangan' => $t->keterangan,
                'Peminjaman' => $t->peminjaman->kode_peminjaman ?? '-',
                'Peminjam' => $t->peminjaman->peminjam->nama_peminjam ?? '-',
            ];
        })->toArray();
        $sheets[] = new SheetArrayExport($transaksi, 'Keuangan');

        return $sheets;
    }
}