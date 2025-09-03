<?php
namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarangExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Barang::with('kategori')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Kategori',
            'Stok Total',
            'Stok Tersedia',
            'Harga Sewa/Hari',
            'Denda/Hari',
            'Kondisi',
            'Status',
        ];
    }

    public function map($barang): array
    {
        return [
            $barang->nama_barang,
            $barang->kategori->nama_kategori ?? '-',
            $barang->stok_total,
            $barang->stok_tersedia,
            number_format($barang->harga_sewa_per_hari, 0, ',', '.'),
            number_format($barang->denda_per_hari, 0, ',', '.'),
            ucfirst($barang->kondisi),
            ucfirst($barang->status),
        ];
    }
}
