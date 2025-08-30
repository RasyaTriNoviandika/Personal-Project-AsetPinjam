<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjam', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjam')->unique();
            $table->string('nama_peminjam');
            $table->string('email')->unique();
            $table->string('no_telepon');
            $table->text('alamat');
            $table->enum('jenis_peminjam', ['individu', 'organisasi', 'perusahaan']);
            $table->string('no_identitas'); // KTP/SIM/Passport
            $table->enum('status', ['aktif', 'nonaktif', 'blacklist'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjam');
    }
};