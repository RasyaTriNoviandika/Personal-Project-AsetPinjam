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
                        'ID'     => $u->id,
                        'Nama'   => $u->name,
                        'Email'  => $u->email,
                        'Dibuat' => $u->created_at ? $u->created_at->format('d-m-Y') : '-',
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
                        'Stok Tersedia'  => $b->stok_tersedia,
                        'Total Dipinjam' => $b->total_dipinjam,
                        'Pendapatan'     => $b->pendapatan,
                    ];
                })->toArray(),
                'Barang'
            ),

            // ================== PEMINJAMAN ==================
            new SheetArrayExport(
                $this->data['peminjaman']->map(function ($p) {
                    return [
                        'ID'              => $p->id,
                        'Peminjam'        => $p->peminjam->nama_peminjam ?? '-', // ✅ pakai relasi peminjam
                        'Tanggal Pinjam'  => $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d-m-Y') : '-',
                        'Tanggal Kembali' => $p->tanggal_kembali_formatted, // ✅ accessor di model
                        'Total Bayar'     => $p->total_bayar ?? 0,
                        'Total Denda'     => $p->total_denda ?? 0,
                        'Status'          => ucfirst($p->status),
                    ];
                })->toArray(),
                'Peminjaman'
            ),

            // ================== TRANSAKSI ==================
            new SheetArrayExport(
                $this->data['transaksi']->map(function ($t) {
                    return [
                        'ID'         => $t->id,
                        'Tanggal'    => $t->tanggal_transaksi ? $t->tanggal_transaksi->format('d-m-Y') : '-',
                        'Jenis'      => ucfirst($t->jenis_transaksi),
                        'Jumlah'     => $t->jumlah,
                        'Keterangan' => $t->keterangan ?: ($t->peminjaman ? "Transaksi dari Peminjaman #{$t->peminjaman->id}" : '-'),
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
