<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    BarangController,
    KategoriBarangController,
    PeminjamController,
    PeminjamanController,
    TransaksiKeuanganController,
    UserController,
    LaporanController,
    SettingsController,
    NotificationController,
    ExportController
};

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Auth::routes();

// Protected routes - Semua user yang sudah login
Route::middleware('auth')->group(function () {
    // Dashboard - Semua role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Profile & Settings - Semua role
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.password.change');

    // Notifications - Semua role
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // ================================
    // USER ROLE - Akses Terbatas
    // ================================
    Route::middleware(['role:user,operator,admin'])->group(function () {
        // Barang - Hanya bisa lihat
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
        
        // Peminjaman - User hanya bisa lihat dan buat peminjaman sendiri
        Route::get('/my-peminjaman', [PeminjamanController::class, 'userPeminjaman'])->name('peminjaman.user');
        Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show')
            ->middleware('can:view,peminjaman'); // Policy untuk cek kepemilikan
    });

    // ================================
    // OPERATOR & ADMIN - Akses Operasional
    // ================================
    Route::middleware(['role:operator,admin'])->group(function () {
        // Barang - CRUD (kecuali delete untuk operator)
        Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
        
        // Kategori Barang - CRUD (kecuali delete untuk operator)
        Route::get('/kategori-barang', [KategoriBarangController::class, 'index'])->name('kategori-barang.index');
        Route::get('/kategori-barang/create', [KategoriBarangController::class, 'create'])->name('kategori-barang.create');
        Route::post('/kategori-barang', [KategoriBarangController::class, 'store'])->name('kategori-barang.store');
        Route::get('/kategori-barang/{kategoriBarang}', [KategoriBarangController::class, 'show'])->name('kategori-barang.show');
        Route::get('/kategori-barang/{kategoriBarang}/edit', [KategoriBarangController::class, 'edit'])->name('kategori-barang.edit');
        Route::put('/kategori-barang/{kategoriBarang}', [KategoriBarangController::class, 'update'])->name('kategori-barang.update');
        
        // Peminjam - CRUD (kecuali delete untuk operator)
        Route::get('/peminjam', [PeminjamController::class, 'index'])->name('peminjam.index');
        Route::get('/peminjam/create', [PeminjamController::class, 'create'])->name('peminjam.create');
        Route::post('/peminjam', [PeminjamController::class, 'store'])->name('peminjam.store');
        Route::get('/peminjam/{peminjam}', [PeminjamController::class, 'show'])->name('peminjam.show');
        Route::get('/peminjam/{peminjam}/edit', [PeminjamController::class, 'edit'])->name('peminjam.edit');
        Route::put('/peminjam/{peminjam}', [PeminjamController::class, 'update'])->name('peminjam.update');
        
        // Peminjaman - Full management
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
        Route::put('/peminjaman/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
        Route::get('/peminjaman/{peminjaman}/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('peminjaman.pengembalian');
        Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'prosesKembali'])->name('peminjaman.proses-kembali');
        
        // Transaksi Keuangan - CRUD (kecuali delete untuk operator)
        Route::get('/transaksi-keuangan', [TransaksiKeuanganController::class, 'index'])->name('transaksi-keuangan.index');
        Route::get('/transaksi-keuangan/create', [TransaksiKeuanganController::class, 'create'])->name('transaksi-keuangan.create');
        Route::post('/transaksi-keuangan', [TransaksiKeuanganController::class, 'store'])->name('transaksi-keuangan.store');
        Route::get('/transaksi-keuangan/{transaksiKeuangan}', [TransaksiKeuanganController::class, 'show'])->name('transaksi-keuangan.show');
        Route::get('/transaksi-keuangan/{transaksiKeuangan}/edit', [TransaksiKeuanganController::class, 'edit'])->name('transaksi-keuangan.edit');
        Route::put('/transaksi-keuangan/{transaksiKeuangan}', [TransaksiKeuanganController::class, 'update'])->name('transaksi-keuangan.update');
        
        // Laporan - Read only untuk operator
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
        Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
        Route::get('/laporan/barang', [LaporanController::class, 'barang'])->name('laporan.barang');
        
        // Export - Operator bisa export
        Route::get('/export/barang', [ExportController::class, 'barang'])->name('export.barang');
        Route::get('/export/peminjaman', [ExportController::class, 'peminjaman'])->name('export.peminjaman');
        Route::get('/export/transaksi', [ExportController::class, 'transaksi'])->name('export.transaksi');
    });

    // ================================
    // ADMIN ONLY - Full System Access
    // ================================
    Route::middleware(['role:admin'])->group(function () {
        // User Management - Admin only
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Delete Routes - Admin only
        Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
        Route::delete('/kategori-barang/{kategoriBarang}', [KategoriBarangController::class, 'destroy'])->name('kategori-barang.destroy');
        Route::delete('/peminjam/{peminjam}', [PeminjamController::class, 'destroy'])->name('peminjam.destroy');
        Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
        Route::delete('/transaksi-keuangan/{transaksiKeuangan}', [TransaksiKeuanganController::class, 'destroy'])->name('transaksi-keuangan.destroy');
    });
});