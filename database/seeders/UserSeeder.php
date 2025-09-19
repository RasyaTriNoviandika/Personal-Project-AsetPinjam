<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // ✅ Admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // cari berdasarkan email
            [
                'name' => 'Admin System',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // ✅ Regular user
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Sample User',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // ✅ Tambah 5 sample user kalau belum ada
        if (User::count() < 7) {
            User::factory()->count(5)->create();
        }
    }
}
