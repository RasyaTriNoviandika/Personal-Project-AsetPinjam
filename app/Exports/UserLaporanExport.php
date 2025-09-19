<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserLaporanExport implements FromCollection, WithHeadings
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Mengembalikan data untuk Excel
     */
    public function collection()
    {
        // Ubah objek menjadi collection array sederhana
        return collect($this->users)->map(function($user) {
            return [
                'ID' => $user->id,
                'Nama' => $user->name,
                'Email' => $user->email,
                'Role' => $user->role_display,
                'Status' => $user->status_display,
                'Total Peminjaman' => $user->total_peminjaman,
                'Total Pendapatan' => $user->total_pendapatan,
            ];
        });
    }

    /**
     * Judul kolom Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Role',
            'Status',
            'Total Peminjaman',
            'Total Pendapatan',
        ];
    }
}
