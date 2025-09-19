<?php

namespace App\Exports;

use App\Models\KategoriBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KategoriExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return KategoriBarang::withCount(['barang as total_barang'])
            ->with(['barang' => function($query) {
                $query->withCount('detailPeminjaman as total_dipinjam');
                $query->withSum('detailPeminjaman as total_pendapatan', 'total_biaya');
            }])
            ->get()
            ->map(function($item) {
                $item->total_dipinjam = $item->barang->sum('total_dipinjam');
                $item->total_pendapatan = $item->barang->sum('total_pendapatan');
                return $item;
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kategori',
            'Keterangan',
            'Total Barang',
            'Total Dipinjam',
            'Total Pendapatan',
            'Status',
        ];
    }

    public function map($kategori): array
    {
        return [
            $kategori->id,
            $kategori->nama_kategori,
            $kategori->keterangan ?? '-',
            $kategori->total_barang ?? 0,
            $kategori->total_dipinjam ?? 0,
            number_format($kategori->total_pendapatan ?? 0, 0, ',', '.'),
            ucfirst($kategori->status ?? 'aktif'),
        ];
    }
}