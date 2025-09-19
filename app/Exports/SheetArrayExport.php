<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanExport implements WithMultipleSheets
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            // ================== USERS ==================
            new SheetArrayExport(
                $this->data['users']->map(function ($u) {
                    return [
                        'ID'    => $u->id,
                        'Nama'  => $u->name,
                        'Email' => $u->email,
                    ];
                })->toArray(),
                'Users'
            ),

            // ================== BARANG ==================
            new SheetArrayExport(
                $this->data['barang']->map(function ($b) {
                    return [
                        'ID'             => $b->id,
                        'Nama Barang'    => $b->nama_barang,
                        'Kategori'       => $b->kategori->nama_kategori ?? '-',
                        'Stok Tersedia'  => $b->stok_tersedia,   // ✅ pakai stok_tersedia
                        'Total Dipinjam' => $b->total_dipinjam ?? 0,
                        'Pendapatan'     => $b->pendapatan ?? 0,
                    ];
                })->toArray(),
                'Barang'
            ),

            // ================== PEMINJAMAN ==================
            new SheetArrayExport(
                $this->data['peminjaman']->map(function ($p) {
                    return [
                        'ID'              => $p->id,
                        'Peminjam'        => $p->peminjam->name ?? '-',
                        'Tanggal Pinjam'  => $p->tanggal_pinjam,
                        'Tanggal Kembali' => $p->tanggal_kembali ?? '-',
                        'Total Bayar'     => $p->total_bayar ?? 0,
                        'Total Denda'     => $p->total_denda ?? 0,
                        'Status'          => $p->status,
                    ];
                })->toArray(),
                'Peminjaman'
            ),

            // ================== TRANSAKSI ==================
            new SheetArrayExport(
                $this->data['transaksi']->map(function ($t) {
                    return [
                        'ID'        => $t->id,
                        'Tanggal'   => $t->tanggal,
                        'Jenis'     => $t->jenis_transaksi,
                        'Jumlah'    => $t->jumlah,
                        'Keterangan'=> $t->keterangan,
                    ];
                })->toArray(),
                'Transaksi'
            ),

            // ================== KATEGORI ==================
            new SheetArrayExport(
                $this->data['kategori']->map(function ($k) {
                    return [
                        'ID'            => $k->id,
                        'Nama Kategori' => $k->nama_kategori,
                        'Jumlah Barang' => $k->barang->count(),
                    ];
                })->toArray(),
                'Kategori'
            ),
        ];
    }
}
