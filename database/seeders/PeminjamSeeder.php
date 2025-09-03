<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjam;

class PeminjamSeeder extends Seeder
{
    public function run(): void
    {
        $peminjams = [
            [
                'nama_peminjam' => 'John Doe',
                'email' => 'john@example.com',
                'no_telepon' => '08123456789',
                'alamat' => 'Jl. Contoh No. 1, Jakarta',
                'jenis_peminjam' => 'individu',
                'no_identitas' => '3201234567890123',
                'status' => 'aktif',
            ],
            [
                'nama_peminjam' => 'Jane Smith',
                'email' => 'jane@example.com',
                'no_telepon' => '08123456788',
                'alamat' => 'Jl. Contoh No. 2, Bandung',
                'jenis_peminjam' => 'individu',
                'no_identitas' => '3201234567890124',
                'status' => 'aktif',
            ],
            [
                'nama_peminjam' => 'PT. Contoh Jaya',
                'email' => 'info@contohjaya.com',
                'no_telepon' => '08123456787',
                'alamat' => 'Jl. Industri No. 10, Surabaya',
                'jenis_peminjam' => 'perusahaan',
                'no_identitas' => '1234567890123456',
                'status' => 'aktif',
            ],
        ];

        foreach ($peminjams as $peminjam) {
            Peminjam::create($peminjam);
        }
    }
}
