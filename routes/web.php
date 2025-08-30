<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\TransaksiKeuanganController;
use App\Http\Controllers\LaporanController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    // Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('kategori-barang', KategoriBarangController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('peminjam', PeminjamController::class);
    Route::resource('peminjaman', PeminjamanController::class);

    // Route Transaksi Keuangan
       Route::resource('transaksi-keuangan', TransaksiKeuanganController::class);

    // Route Laporan
      Route::resource('laporan', LaporanController::class);
});
