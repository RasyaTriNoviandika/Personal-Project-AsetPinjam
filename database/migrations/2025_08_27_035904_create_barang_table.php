<?php

// database/migrations/2025_08_29_000001_create_barang_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
              $table->id();
            $table->string('nama_barang');
            $table->unsignedBigInteger('kategori_id');
            $table->integer('stok')->default(0);
            $table->decimal('harga_sewa', 12, 2);
            $table->decimal('denda_per_hari', 12, 2)->nullable(); // FIXED
            $table->string('kondisi')->default('Baik');
            $table->string('status')->default('Aktif');
            $table->timestamps();

            // Relasi ke kategori
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
