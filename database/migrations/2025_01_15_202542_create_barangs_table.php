<?php
// database/migrations/2025_08_27_035904_create_barang_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            
            // stok
            $table->integer('stok_total')->default(0);
            $table->integer('stok_tersedia')->default(0);

            // harga & denda
            $table->decimal('harga_sewa_per_hari', 15, 2)->default(0);
            $table->decimal('denda_per_hari', 15, 2)->default(0);

            // detail barang
            $table->enum('kondisi', ['baik','rusak_ringan','rusak_berat'])->default('baik');
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};