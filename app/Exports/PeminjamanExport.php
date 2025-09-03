<?php
namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Peminjaman::with(['peminjam', 'detailPeminjaman.barang']);
        
        if (!empty($this->filters['status']) && $this->filters['status'] != 'semua') {
            $query->where('status', $this->filters['status']);
        }
        
        if (!empty($this->filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_pinjam', '>=', $this->filters['tanggal_mulai']);
        }
        
        if (!empty($this->filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_pinjam', '<=', $this->filters['tanggal_selesai']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Kode Peminjaman',
            'Peminjam',
            'Tanggal Pinjam',
            'Tanggal Kembali Rencana',
            'Tanggal Kembali Aktual',
            'Total Biaya',
            'Total Denda',
            'Total Bayar',
            'Status',
        ];
    }

    public function map($peminjaman): array
    {
        return [
            $peminjaman->kode_peminjaman,
            $peminjaman->peminjam->nama_peminjam,
            $peminjaman->tanggal_pinjam->format('d/m/Y'),
            $peminjaman->tanggal_kembali_rencana->format('d/m/Y'),
            $peminjaman->tanggal_kembali_aktual ? $peminjaman->tanggal_kembali_aktual->format('d/m/Y') : '-',
            number_format($peminjaman->total_biaya_sewa, 0, ',', '.'),
            number_format($peminjaman->total_denda, 0, ',', '.'),
            number_format($peminjaman->total_bayar, 0, ',', '.'),
            ucfirst($peminjaman->status),
        ];
    }
}