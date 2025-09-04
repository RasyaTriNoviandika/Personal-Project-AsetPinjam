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
                'kategori_id' => 1, // Elektronik
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
                'kategori_id' => 1, // Elektronik
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
                'kategori_id' => 1, // Elektronik
                'stok_total' => 2,
                'stok_tersedia' => 2,
                'harga_sewa_per_hari' => 200000,
                'denda_per_hari' => 60000,
                'kondisi' => 'baik',
                'status' => 'aktif',
                'deskripsi' => 'Kamera DSLR Canon dengan lensa kit 18-55mm',
            ],
            [
                'nama_barang' => 'Motor Honda Vario',
                'kategori_id' => 2, // Kendaraan
                'stok_total' => 2,
                'stok_tersedia' => 2,
                'harga_sewa_per_hari' => 75000,
                'denda_per_hari' => 25000,
                'kondisi' => 'baik',
                'status' => 'aktif',
                'deskripsi' => 'Motor Honda Vario 150cc tahun 2023',
            ],
            [
                'nama_barang' => 'Sepeda Gunung Polygon',
                'kategori_id' => 3, // Peralatan Olahraga
                'stok_total' => 4,
                'stok_tersedia' => 4,
                'harga_sewa_per_hari' => 50000,
                'denda_per_hari' => 15000,
                'kondisi' => 'baik',
                'status' => 'aktif',
                'deskripsi' => 'Sepeda gunung 21 speed dengan frame alloy',
            ]
        ];

        foreach ($barangs as $item) {
            Barang::create($item);
        }

        // Update jumlah barang di kategori
        $kategori = \App\Models\KategoriBarang::all();
        foreach ($kategori as $kat) {
            $kat->update([
                'jumlah_barang' => $kat->barang()->count()
            ]);
        }
    }
}
