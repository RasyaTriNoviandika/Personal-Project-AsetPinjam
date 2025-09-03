<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBarang;

class KategoriBarangSeeder extends Seeder
{
    public function run()
    {
        $kategori = [
            [
                'nama_kategori' => 'Elektronik',
                'deskripsi' => 'Peralatan elektronik seperti laptop, projector, dll',
                'jumlah_barang' => 0
            ],
            [
                'nama_kategori' => 'Kendaraan',
                'deskripsi' => 'Kendaraan bermotor dan non-motor',
                'jumlah_barang' => 0
            ],
            [
                'nama_kategori' => 'Peralatan Olahraga',
                'deskripsi' => 'Alat-alat olahraga dan fitness',
                'jumlah_barang' => 0
            ],
            [
                'nama_kategori' => 'Furniture',
                'deskripsi' => 'Meja, kursi, dan furniture lainnya',
                'jumlah_barang' => 0
            ],
            [
                'nama_kategori' => 'Alat Musik',
                'deskripsi' => 'Berbagai jenis alat musik',
                'jumlah_barang' => 0
            ],
            [
                'nama_kategori' => 'Peralatan Event',
                'deskripsi' => 'Sound system, lighting, backdrop, dll',
                'jumlah_barang' => 0
            ],
            [
                'nama_kategori' => 'Peralatan Camping',
                'deskripsi' => 'Tenda, sleeping bag, kompor portable, dll',
                'jumlah_barang' => 0
            ]
        ];

        foreach ($kategori as $item) {
            KategoriBarang::create($item);
        }
    }

}
