<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjam;

class PeminjamSeeder extends Seeder
{
    public function run(): void
    {
        $peminjam = [
            [
                'nama_peminjam' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@email.com',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'jenis_peminjam' => 'individu',
                'no_identitas' => '3173012345670001',
                'status' => 'aktif'
            ],
            [
                'nama_peminjam' => 'Sari Dewi',
                'email' => 'sari.dewi@email.com',
                'no_telepon' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 456, Bogor',
                'jenis_peminjam' => 'individu',
                'no_identitas' => '3271012345670002',
                'status' => 'aktif'
            ],
            [
                'nama_peminjam' => 'PT. Teknologi Maju',
                'email' => 'info@teknologimaju.com',
                'no_telepon' => '02112345678',
                'alamat' => 'Jl. Industri No. 789, Tangerang',
                'jenis_peminjam' => 'perusahaan',
                'no_identitas' => '1234567890123456',
                'status' => 'aktif'
            ],
            [
                'nama_peminjam' => 'Komunitas Sepeda Bogor',
                'email' => 'admin@sepedabogor.org',
                'no_telepon' => '081234567892',
                'alamat' => 'Jl. Pajajaran No. 321, Bogor',
                'jenis_peminjam' => 'organisasi',
                'no_identitas' => 'ORG-2024-001',
                'status' => 'aktif'
            ]
        ];

        foreach ($peminjam as $item) {
            Peminjam::create($item);
        }
    }
}
