<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // panggil semua seeder yang kamu butuhkan
        $this->call([
            UserSeeder::class,
            KategoriBarangSeeder::class, // ✅ panggil seeder kategori
            BarangSeeder::class,         // ✅ panggil seeder barang
        ]);
    }
}
