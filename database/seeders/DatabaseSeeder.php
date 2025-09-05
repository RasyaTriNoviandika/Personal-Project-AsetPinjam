<?php
// Create seeder: php artisan make:seeder DatabaseSeeder

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KategoriBarang;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create operator user
        User::firstOrCreate(
            ['email' => 'operator@example.com'],
            [
                'name' => 'Operator',
                'email' => 'operator@example.com',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create regular user
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create default categories
        $categories = [
            ['nama_kategori' => 'Elektronik', 'deskripsi' => 'Peralatan elektronik', 'jumlah_barang' => 0],
            ['nama_kategori' => 'Furniture', 'deskripsi' => 'Perabotan dan furniture', 'jumlah_barang' => 0],
            ['nama_kategori' => 'Kendaraan', 'deskripsi' => 'Kendaraan dan transportasi', 'jumlah_barang' => 0],
            ['nama_kategori' => 'Alat Kerja', 'deskripsi' => 'Peralatan kerja dan tools', 'jumlah_barang' => 0],
            ['nama_kategori' => 'Olahraga', 'deskripsi' => 'Peralatan olahraga', 'jumlah_barang' => 0],
        ];

        foreach ($categories as $category) {
            KategoriBarang::firstOrCreate(
                ['nama_kategori' => $category['nama_kategori']],
                $category
            );
        }

        echo "Default users and categories created successfully!\n";
        echo "Admin: admin@example.com / password\n";
        echo "Operator: operator@example.com / password\n";
        echo "User: user@example.com / password\n";
    }
}
