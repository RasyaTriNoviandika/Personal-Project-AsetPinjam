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
                'divider_1' => 'MANAJEMEN MASTER',
                'users' => ['Manajemen User', 'fas fa-user-cog', 'users.index'],
                'barang' => ['Data Barang', 'fas fa-box', 'barang.index'],
                'kategori-barang' => ['Kategori Barang', 'fas fa-tags', 'kategori-barang.index'],
                'peminjam' => ['Data Peminjam', 'fas fa-users', 'peminjam.index'],
                'divider_2' => 'KEUANGAN & TRANSAKSI',
                'transaksi-keuangan' => ['Transaksi Keuangan', 'fas fa-money-bill', 'transaksi-keuangan.index'],
                'peminjaman' => ['Peminjaman', 'fas fa-handshake', 'peminjaman.index'],
                'divider_3' => 'LAPORAN & ANALISIS',
                'laporan' => ['Laporan', 'fas fa-chart-bar', 'laporan.index'],
                'divider_4' => 'PENGATURAN',
                'settings' => ['Pengaturan', 'fas fa-cog', 'settings.profile'],
            ],
            
            'operator' => [
                'dashboard' => ['Dashboard', 'fas fa-tachometer-alt', 'dashboard'],
                'divider_1' => 'OPERASIONAL',
                'peminjaman' => ['Peminjaman', 'fas fa-handshake', 'peminjaman.index'],
                'barang' => ['Data Barang', 'fas fa-box', 'barang.index'],
                'peminjam' => ['Data Peminjam', 'fas fa-users', 'peminjam.index'],
                'divider_2' => 'MASTER DATA',
                'kategori-barang' => ['Kategori Barang', 'fas fa-tags', 'kategori-barang.index'],
                'divider_3' => 'LAPORAN OPERASIONAL',
                'laporan-peminjaman' => ['Laporan Peminjaman', 'fas fa-chart-line', 'laporan.peminjaman'],
                'laporan-barang' => ['Laporan Barang', 'fas fa-boxes', 'laporan.barang'],
                'divider_4' => 'PROFIL',
                'settings' => ['Profil Saya', 'fas fa-user', 'settings.profile'],
            ],
            
            'user' => [
                'dashboard' => ['Dashboard', 'fas fa-tachometer-alt', 'dashboard'],
                'divider_1' => 'PENYEWAAN',
                'barang' => ['Katalog Barang', 'fas fa-search', 'barang.index'],
                'my-peminjaman' => ['Riwayat Sewa Saya', 'fas fa-history', 'peminjaman.user'],
                'divider_2' => 'AKUN',
                'settings' => ['Profil Saya', 'fas fa-user', 'settings.profile'],
            ]
        ];

        return $menus[$role] ?? $menus['user'];
    }

    public static function canAccess($role, $permission)
    {
        $permissions = [
            'admin' => [
                'create', 'read', 'update', 'delete', 
                'manage_users', 'manage_finances', 'view_all_reports', 
                'export_data', 'system_settings', 'delete_records'
            ],
            'operator' => [
                'create', 'read', 'update', 
                'manage_inventory', 'manage_rentals', 'view_operational_reports',
                'export_operational_data', 'manage_borrowers'
            ],
            'user' => [
                'read', 'view_own', 'rent_items', 'view_catalog'
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
            'user' => 'User'
        ];

        return $names[$role] ?? 'Unknown';
    }

    public static function canViewFinancialData($role)
    {
        return $role === 'admin';
    }

    public static function canManageUsers($role)
    {
        return $role === 'admin';
    }

    public static function canDeleteData($role)
    {
        return $role === 'admin';
    }

    public static function canExportData($role, $type = 'operational')
    {
        if ($role === 'admin') {
            return true;
        }
        
        if ($role === 'operator' && $type === 'operational') {
            return true;
        }

        return false;
    }
}