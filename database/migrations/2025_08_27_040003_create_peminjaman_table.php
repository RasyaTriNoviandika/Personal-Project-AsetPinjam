<?php
// database/migrations/xxxx_create_peminjaman_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('peminjam_id')->constrained('peminjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->decimal('total_biaya_sewa', 10, 2);
            $table->decimal('denda', 10, 2)->default(0);
            $table->decimal('total_bayar', 10, 2);
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat', 'batal']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};