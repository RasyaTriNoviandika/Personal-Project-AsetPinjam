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
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->onDelete('cascade');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->enum('kategori', ['sewa', 'denda', 'pemeliharaan', 'pembelian', 'lainnya']);
            $table->decimal('jumlah', 15, 2);
            $table->text('deskripsi');
            $table->date('tanggal_transaksi');
            $table->string('bukti_transaksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_keuangans');
    }
};