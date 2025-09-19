<?php

namespace App\Exports;

use App\Models\TransaksiKeuangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiKeuanganExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = TransaksiKeuangan::with('peminjaman.peminjam');
        
        if (!empty($this->filters['jenis']) && $this->filters['jenis'] != 'semua') {
            $query->where('jenis_transaksi', $this->filters['jenis']);
        }
        
        if (!empty($this->filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_transaksi', '>=', $this->filters['tanggal_mulai']);
        }
        
        if (!empty($this->filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_transaksi', '<=', $this->filters['tanggal_selesai']);
        }

        return $query->orderBy('tanggal_transaksi', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal Transaksi',
            'Jenis Transaksi',
            'Jumlah',
            'Keterangan',
            'Kode Peminjaman',
            'Peminjam',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->id,
            $transaksi->tanggal_transaksi->format('d/m/Y'),
            ucfirst($transaksi->jenis_transaksi),
            number_format($transaksi->jumlah, 0, ',', '.'),
            $transaksi->keterangan,
            $transaksi->peminjaman->kode_peminjaman ?? '-',
            $transaksi->peminjaman->peminjam->nama_peminjam ?? '-',
        ];
    }
}