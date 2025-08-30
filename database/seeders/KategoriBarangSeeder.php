<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBarang;

class KategoriBarangSeeder extends Seeder
{
    public function run()
    {
        $kategori = [
            ['nama_kategori' => 'Elektronik', 'deskripsi' => 'Peralatan elektronik'],
            ['nama_kategori' => 'Kendaraan', 'deskripsi' => 'Kendaraan bermotor dan non-motor'],
            ['nama_kategori' => 'Peralatan Olahraga', 'deskripsi' => 'Alat-alat olahraga'],
            ['nama_kategori' => 'Furniture', 'deskripsi' => 'Meja, kursi, dan furniture lainnya'],
            ['nama_kategori' => 'Alat Musik', 'deskripsi' => 'Berbagai jenis alat musik'],
        ];

        foreach ($kategori as $item) {
            KategoriBarang::create([
                'nama_kategori' => $item['nama_kategori'],
                'deskripsi'     => $item['deskripsi'],
                'brand'         => $item['brand'] ?? '-',   // default kalau tidak ada
                'jumlah_pcs'    => $item['jumlah_pcs'] ?? 0 // default 0
            ]);
        }
    }
}
