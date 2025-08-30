<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\TransaksiKeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\BarangController as ApiBarangController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('kategori-barang', KategoriBarangController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('peminjam', PeminjamController::class);
    Route::resource('peminjaman', PeminjamanController::class);
    Route::resource('transaksi-keuangan', TransaksiKeuanganController::class);

    // Peminjaman additional routes
    Route::get('/peminjaman/{peminjaman}/pengembalian', [PeminjamanController::class, 'pengembalian'])
         ->name('peminjaman.pengembalian');
    Route::put('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'prosesKembali'])
         ->name('peminjaman.proses-kembali');

    // Laporan routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('/laporan/barang', [LaporanController::class, 'barang'])->name('laporan.barang');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // API Routes untuk AJAX
    Route::prefix('api')->group(function () {
        Route::get('/barang/kategori', [ApiBarangController::class, 'getByKategori']);
        Route::post('/barang/cek-stok', [ApiBarangController::class, 'cekStok']);
    });
});