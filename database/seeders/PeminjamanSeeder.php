<?php
// database/seeders/PeminjamanSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\Peminjam;
use App\Models\User;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        // pastikan ada minimal 1 user & 1 peminjam
        $user = User::first();
        $peminjam = Peminjam::first();

        if (!$user || !$peminjam) {
            $this->command->warn('User atau Peminjam belum ada, skip seeding Peminjaman.');
            return;
        }

        // buat contoh peminjaman
        Peminjaman::create([
            'peminjam_id' => $peminjam->id,
            'user_id' => $user->id,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali_rencana' => Carbon::now()->addDays(7),
            'total_biaya_sewa' => 100000,
            'total_denda' => 0,
            'total_bayar' => 100000,
            'status' => 'dipinjam',
            'catatan' => 'Seeder peminjaman contoh',
        ]);
    }
}
