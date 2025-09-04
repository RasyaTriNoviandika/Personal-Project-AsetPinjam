<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('deskripsi')->nullable();
            $table->string('status')->default('aktif');
            $table->integer('jumlah_barang')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_barangs');
    }
};