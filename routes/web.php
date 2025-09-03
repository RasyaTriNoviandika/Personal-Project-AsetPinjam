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
use App\Http\Controllers\ExportController;

// Redirect ke login jika belum auth
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Auth::routes(['register' => false]); // Disable registration

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', function() {
        return redirect()->route('dashboard');
    })->name('home');

    Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // API Barang
    Route::prefix('barang')->group(function () {
        Route::get('/kategori', [BarangController::class, 'getByKategori']);
        Route::post('/cek-stok', [BarangController::class, 'cekStok']);
    });
});

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
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/peminjaman', [LaporanController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/barang', [LaporanController::class, 'barang'])->name('barang');
    });

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // API Routes untuk AJAX
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/barang/kategori', [ApiBarangController::class, 'getByKategori'])->name('barang.kategori');
        Route::post('/barang/cek-stok', [ApiBarangController::class, 'cekStok'])->name('barang.cek-stok');
    });

    Route::get('/export/barang', [ExportController::class, 'barang'])->name('export.barang');
Route::get('/export/peminjaman', [ExportController::class, 'peminjaman'])->name('export.peminjaman');
Route::get('/export/transaksi', [ExportController::class, 'transaksi'])->name('export.transaksi');
});
