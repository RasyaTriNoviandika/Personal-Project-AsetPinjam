<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiKeuangan;
use Carbon\Carbon;

class TransaksiKeuanganSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama
        TransaksiKeuangan::truncate();

        $faker = \Faker\Factory::create('id_ID');

        // Loop 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);

            // Tambah 5 transaksi pendapatan
            for ($j = 1; $j <= 5; $j++) {
                TransaksiKeuangan::create([
                    'nama'              => $faker->company,
                    'jenis_transaksi'   => 'masuk',
                    'kategori_transaksi'=> $faker->randomElement(['sewa_barang','denda','lainnya']),
                    'jumlah'            => $faker->numberBetween(100000, 1000000),
                    'keterangan'        => 'Pendapatan bulan ' . $bulan->translatedFormat('F'),
                    'tanggal_transaksi' => $bulan->copy()->day(rand(1, 28)),
                    'metode_pembayaran' => $faker->randomElement(['cash','transfer']),
                    'status'            => 'berhasil',
                ]);
            }

            // Tambah 3 transaksi pengeluaran
            for ($k = 1; $k <= 3; $k++) {
                TransaksiKeuangan::create([
                    'nama'              => $faker->company,
                    'jenis_transaksi'   => 'keluar',
                    'kategori_transaksi'=> $faker->randomElement(['maintenance','operasional','lainnya']),
                    'jumlah'            => $faker->numberBetween(50000, 700000),
                    'keterangan'        => 'Pengeluaran bulan ' . $bulan->translatedFormat('F'),
                    'tanggal_transaksi' => $bulan->copy()->day(rand(1, 28)),
                    'metode_pembayaran' => $faker->randomElement(['cash','transfer']),
                    'status'            => 'berhasil',
                ]);
            }
        }
    }
}
