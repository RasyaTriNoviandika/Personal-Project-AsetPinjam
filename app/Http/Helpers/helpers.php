<?php
// app/Http/Helpers/helpers.php (Enhanced)

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
            'nonaktif' => 'bg-secondary',
            'active' => 'bg-success',
            'inactive' => 'bg-secondary'
        ];

        return $badges[$status] ?? 'bg-secondary';
    }
}

if (!function_exists('statusText')) {
    function statusText($status)
    {
        $texts = [
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan', 
            'terlambat' => 'Terlambat',
            'batal' => 'Dibatalkan',
            'aktif' => 'Aktif',
            'nonaktif' => 'Non Aktif',
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            'hilang' => 'Hilang'
        ];

        return $texts[$status] ?? ucfirst($status);
    }
}

if (!function_exists('kondisiBadge')) {
    function kondisiBadge($kondisi)
    {
        $badges = [
            'baik' => 'bg-success',
            'rusak_ringan' => 'bg-warning',
            'rusak_berat' => 'bg-danger',
            'hilang' => 'bg-dark'
        ];

        return $badges[$kondisi] ?? 'bg-secondary';
    }
}

if (!function_exists('hitungDurasi')) {
    function hitungDurasi($tanggalMulai, $tanggalSelesai)
    {
        return \Carbon\Carbon::parse($tanggalMulai)
            ->diffInDays(\Carbon\Carbon::parse($tanggalSelesai)) + 1;
    }
}

if (!function_exists('userCan')) {
    function userCan($permission, $model = null)
    {
        if (!auth()->check()) {
            return false;
        }

        if ($model) {
            return auth()->user()->can($permission, $model);
        }

        return auth()->user()->can($permission);
    }
}

if (!function_exists('userRole')) {
    function userRole()
    {
        return auth()->check() ? auth()->user()->role : null;
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}

if (!function_exists('isOperator')) {
    function isOperator()
    {
        return auth()->check() && auth()->user()->isOperator();
    }
}

if (!function_exists('isUser')) {
    function isUser()
    {
        return auth()->check() && auth()->user()->isUser();
    }
}

if (!function_exists('canAccessFinancial')) {
    function canAccessFinancial()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}

if (!function_exists('canManageData')) {
    function canManageData()
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'operator']);
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}

if (!function_exists('generateKode')) {
    function generateKode($prefix, $length = 4)
    {
        return $prefix . '-' . date('Ymd') . '-' . str_pad(rand(1, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('maskEmail')) {
    function maskEmail($email)
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];
        
        $nameLength = strlen($name);
        if ($nameLength > 2) {
            $maskedName = substr($name, 0, 2) . str_repeat('*', $nameLength - 2);
        } else {
            $maskedName = $name;
        }
        
        return $maskedName . '@' . $domain;
    }
}

if (!function_exists('getNotificationIcon')) {
    function getNotificationIcon($type)
    {
        $icons = [
            'success' => 'check-circle',
            'info' => 'info-circle', 
            'warning' => 'alert-triangle',
            'danger' => 'x-circle',
            'error' => 'x-circle'
        ];

        return $icons[$type] ?? 'bell';
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($datetime)
    {
        return \Carbon\Carbon::parse($datetime)->diffForHumans();
    }
}

if (!function_exists('shortText')) {
    function shortText($text, $limit = 50, $suffix = '...')
    {
        if (strlen($text) <= $limit) {
            return $text;
        }
        
        return substr($text, 0, $limit) . $suffix;
    }
}