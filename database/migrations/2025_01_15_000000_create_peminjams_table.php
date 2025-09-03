<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('peminjams', function (Blueprint $table) {
        $table->id();
        $table->string('kode_peminjam')->unique();
        $table->string('nama_peminjam');
        $table->string('email')->unique();
        $table->string('no_telepon')->nullable();
        $table->text('alamat')->nullable();
        $table->string('jenis_peminjam')->nullable();
        $table->string('no_identitas')->nullable();
        $table->string('status')->default('aktif');
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('peminjams');
    }
};