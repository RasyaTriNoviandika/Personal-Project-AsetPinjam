<?php
// database/migrations/xxxx_create_detail_peminjaman_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman');
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('jumlah');
            $table->decimal('harga_sewa_per_hari', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->enum('kondisi_pinjam', ['baik', 'rusak_ringan'])->default('baik');
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
  