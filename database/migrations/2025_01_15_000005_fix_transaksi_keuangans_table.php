<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('transaksi_keuangans');

        Schema::create('transaksi_keuangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('nama');
            $table->foreignId('peminjaman_id')->nullable()->constrained('peminjamans')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->string('kategori_transaksi')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->dateTime('tanggal_transaksi');
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status', ['berhasil', 'pending', 'gagal'])->default('pending');
            $table->string('bukti_transaksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_keuangans');
    }
};
