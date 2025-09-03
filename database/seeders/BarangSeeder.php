<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            [
                'nama_barang' => 'Laptop ASUS VivoBook',
                'kategori_id' => 1,
                'stok_total' => 5,
                'stok_tersedia' => 5,
                'harga_sewa_per_hari' => 150000,
                'denda_per_hari' => 50000,
                'kondisi' => 'baik',
                'status' => 'aktif',
                'deskripsi' => 'Laptop dengan processor Intel Core i5, RAM 8GB, SSD 256GB',
            ],
            [
                'nama_barang' => 'Proyektor Epson',
                'kategori_id' => 2,
                'stok_total' => 3,
                'stok_tersedia' => 3,
                'harga_sewa_per_hari' => 100000,
                'denda_per_hari' => 30000,
                'kondisi' => 'baik',
                'status' => 'aktif',
                'deskripsi' => 'Proyektor dengan resolusi 1080p dan brightness tinggi',
            ],
            [
                'nama_barang' => 'Kamera DSLR Canon',
                'kategori_id' => 3,
                'stok_total' => 2,
                'stok_tersedia' => 2,
                'harga_sewa_per_hari' => 200000,
                'denda_per_hari' => 60000,
                'kondisi' => 'baik',
                'status' => 'aktif',
                'deskripsi' => 'Kamera DSLR Canon dengan lensa kit 18-55mm',
            ],
        ];

        foreach ($barangs as $item) {
            Barang::create($item);
        }
    }
}
