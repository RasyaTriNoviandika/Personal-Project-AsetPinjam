<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('hitungDenda')) {
    function hitungDenda($tanggalKembali, $tanggalRencana, $dendaPerHari)
    {
        $terlambat = \Carbon\Carbon::parse($tanggalKembali)
                    ->diffInDays(\Carbon\Carbon::parse($tanggalRencana), false);
        
        return $terlambat > 0 ? $terlambat * $dendaPerHari : 0;
    }
}

if (!function_exists('statusBadge')) {
    function statusBadge($status)
    {
        $badges = [
            'dipinjam' => 'bg-primary',
            'dikembalikan' => 'bg-success', 
            'terlambat' => 'bg-danger',
            'batal' => 'bg-secondary',
            'aktif' => 'bg-success',
            'nonaktif' => 'bg-secondary'
        ];

        return $badges[$status] ?? 'bg-secondary';
    }
}
