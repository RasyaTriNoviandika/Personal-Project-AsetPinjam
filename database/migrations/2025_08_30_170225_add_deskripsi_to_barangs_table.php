<?php
// 1. Perbaiki database/migrations/2025_08_30_170225_add_deskripsi_to_barang_table.php
// File ini seharusnya ALTER table, bukan CREATE table baru

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('barangs', function (Blueprint $table) {
        // Kolom deskripsi sudah ada di migration utama, jadi tidak perlu ditambah lagi
        // $table->text('deskripsi')->nullable()->after('status');
        $table->string('gambar')->nullable()->after('deskripsi');
        
        // // Rename kolom jika memang belum direname di migration lain
        //  if (Schema::hasColumn('barangs', 'stok')) {
        //     $table->renameColumn('stok', 'stok_total');
        // }
        // if (Schema::hasColumn('barangs', 'harga_sewa')) {
        //     $table->renameColumn('harga_sewa', 'harga_sewa_per_hari');
        // }
    });
}

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'gambar']);
            $table->renameColumn('stok_total', 'stok');
            $table->renameColumn('harga_sewa_per_hari', 'harga_sewa');
        });
    }
};
