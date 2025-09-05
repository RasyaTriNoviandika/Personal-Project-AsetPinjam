<?php
// app/Helpers/RoleHelper.php

namespace App\Helpers;

class RoleHelper
{
    public static function getMenuByRole($role)
    {
        $menus = [
            'admin' => [
                'dashboard' => ['Dashboard', 'fas fa-tachometer-alt', 'dashboard'],
                'divider_1' => 'MANAJEMEN DATA',
                'barang' => ['Data Barang', 'fas fa-box', 'barang.index'],
                'kategori-barang' => ['Kategori Barang', 'fas fa-tags', 'kategori-barang.index'],
                'peminjam' => ['Data Peminjam', 'fas fa-users', 'peminjam.index'],
                'divider_2' => 'TRANSAKSI',
                'peminjaman' => ['Peminjaman', 'fas fa-handshake', 'peminjaman.index'],
                'transaksi-keuangan' => ['Transaksi Keuangan', 'fas fa-money-bill', 'transaksi-keuangan.index'],
                'divider_3' => 'LAPORAN',
                'laporan' => ['Laporan', 'fas fa-chart-bar', 'laporan.index'],
                'divider_4' => 'SISTEM',
                'users' => ['Manajemen User', 'fas fa-user-cog', 'users.index'],
                'settings' => ['Pengaturan', 'fas fa-cog', 'settings.profile'],
            ],
            
            'operator' => [
                'dashboard' => ['Dashboard', 'fas fa-tachometer-alt', 'dashboard'],
                'divider_1' => 'MANAJEMEN DATA',
                'barang' => ['Data Barang', 'fas fa-box', 'barang.index'],
                'kategori-barang' => ['Kategori Barang', 'fas fa-tags', 'kategori-barang.index'],
                'peminjam' => ['Data Peminjam', 'fas fa-users', 'peminjam.index'],
                'divider_2' => 'TRANSAKSI',
                'peminjaman' => ['Peminjaman', 'fas fa-handshake', 'peminjaman.index'],
                'transaksi-keuangan' => ['Transaksi Keuangan', 'fas fa-money-bill', 'transaksi-keuangan.index'],
                'divider_3' => 'LAPORAN',
                'laporan' => ['Laporan', 'fas fa-chart-bar', 'laporan.index'],
                'divider_4' => 'PENGATURAN',
                'settings' => ['Profil Saya', 'fas fa-user', 'settings.profile'],
            ],
            
            'user' => [
                'dashboard' => ['Dashboard', 'fas fa-tachometer-alt', 'dashboard'],
                'divider_1' => 'SEWA BARANG',
                'barang' => ['Lihat Barang', 'fas fa-box', 'barang.index'],
                'my-peminjaman' => ['Riwayat Sewa', 'fas fa-history', 'peminjaman.user'],
                'divider_2' => 'PROFIL',
                'settings' => ['Profil Saya', 'fas fa-user', 'settings.profile'],
            ]
        ];

        return $menus[$role] ?? $menus['user'];
    }

    public static function canAccess($role, $permission)
    {
        $permissions = [
            'admin' => [
                'create', 'read', 'update', 'delete', 'manage_users', 'view_reports', 
                'export_data', 'manage_finances', 'system_settings'
            ],
            'operator' => [
                'create', 'read', 'update', 'view_reports', 'export_data', 'manage_finances'
            ],
            'user' => [
                'read', 'view_own', 'rent_items'
            ]
        ];

        return in_array($permission, $permissions[$role] ?? []);
    }

    public static function getRoleColor($role)
    {
        $colors = [
            'admin' => 'danger',
            'operator' => 'warning', 
            'user' => 'primary'
        ];

        return $colors[$role] ?? 'secondary';
    }

    public static function getRoleName($role)
    {
        $names = [
            'admin' => 'Administrator',
            'operator' => 'Operator',
            'user' => 'Pengguna'
        ];

        return $names[$role] ?? 'Unknown';
    }

    public static function getRolePermissions($role)
    {
        $permissions = [
            'admin' => [
                'Mengelola semua data',
                'Mengelola user',
                'Melihat laporan keuangan',
                'Export data',
                'Menghapus data',
                'Pengaturan sistem'
            ],
            'operator' => [
                'Mengelola barang',
                'Mengelola peminjaman',
                'Mengelola transaksi',
                'Melihat laporan',
                'Export data'
            ],
            'user' => [
                'Melihat barang',
                'Menyewa barang',
                'Melihat riwayat sewa',
                'Mengelola profil'
            ]
        ];

        return $permissions[$role] ?? [];
    }
}