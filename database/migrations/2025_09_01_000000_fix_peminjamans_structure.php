<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perbaiki tabel peminjaman
        Schema::table('peminjaman', function (Blueprint $table) {
            // Rename kolom 'denda' menjadi 'total_denda' jika belum
            if (Schema::hasColumn('peminjaman', 'denda')) {
                $table->renameColumn('denda', 'total_denda');
            }
        });

        // Perbaiki tabel detail_peminjaman
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            // Rename kolom 'subtotal' menjadi 'subtotal_sewa' jika belum
            if (Schema::hasColumn('detail_peminjaman', 'subtotal')) {
                $table->renameColumn('subtotal', 'subtotal_sewa');
            }
        });

        // Perbaiki tabel transaksi_keuangan
        Schema::table('transaksi_keuangans', function (Blueprint $table) {
            // Rename kolom 'kategori_transaksi' menjadi 'kategori' jika ada
            if (Schema::hasColumn('transaksi_keuangan', 'kategori_transaksi')) {
                $table->renameColumn('kategori_transaksi', 'kategori');
            }
            // Tambah kolom bukti_transaksi jika belum ada
            if (!Schema::hasColumn('transaksi_keuangans', 'bukti_transaksi')) {
                $table->string('bukti_transaksi')->nullable()->after('deskripsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjamans', 'total_denda')) {
                $table->renameColumn('total_denda', 'denda');
            }
        });

        Schema::table('detail_peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('detail_peminjamans', 'subtotal_sewa')) {
                $table->renameColumn('subtotal_sewa', 'subtotal');
            }
        });

        Schema::table('transaksi_keuangans', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_keuangan', 'kategori')) {
                $table->renameColumn('kategori', 'kategori_transaksi');
            }
            if (Schema::hasColumn('transaksi_keuangans', 'bukti_transaksi')) {
                $table->dropColumn('bukti_transaksi');
            }
        });
    }
};
