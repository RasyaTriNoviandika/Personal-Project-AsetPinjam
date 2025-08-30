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
                'nama_barang'    => 'Laptop ASUS',
                'kategori_id'    => 1, // pastikan ada kategori dengan ID=1
                'harga_sewa'     => 150000,   // harga sewa per hari
                'denda_per_hari' => 50000,
                'kondisi'        => 'baik',
                'status'         => 'aktif',
            ],
            [
                'nama_barang'    => 'Sepeda Gunung',
                'kategori_id'    => 2, // kategori_id=2 (misal "Kendaraan")
                'harga_sewa'     => 50000,
                'denda_per_hari' => 20000,
                'kondisi'        => 'baik',
                'status'         => 'aktif',
            ],
            [
                'nama_barang'    => 'Gitar Akustik',
                'kategori_id'    => 5, // kategori_id=5 (misal "Alat Musik")
                'harga_sewa'     => 75000,
                'denda_per_hari' => 25000,
                'kondisi'        => 'baik',
                'status'         => 'aktif',
            ],
        ];

        foreach ($barangs as $item) {
            Barang::create($item);
        }
    }
}
