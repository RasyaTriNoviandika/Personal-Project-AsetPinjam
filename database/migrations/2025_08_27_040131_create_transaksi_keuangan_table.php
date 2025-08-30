<?php
// database/migrations/xxxx_create_transaksi_keuangan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('peminjaman_id')->nullable()->constrained('peminjaman');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->enum('kategori_transaksi', ['sewa', 'denda', 'deposit', 'maintenance', 'lainnya']);
            $table->decimal('jumlah', 10, 2);
            $table->date('tanggal_transaksi');
            $table->text('deskripsi');
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'kartu_debit', 'kartu_kredit'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_keuangan');
    }
};