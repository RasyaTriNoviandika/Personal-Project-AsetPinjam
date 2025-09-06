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
Auth::routes(['register' => false]);

// Protected routes - All authenticated users
Route::middleware(['auth'])->group(function () {
    // Dashboard - Akan redirect sesuai role di controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Profile & Settings - Semua role bisa akses
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.password.change');

    // Notifications - Semua role
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/count', [NotificationController::class, 'getUnreadCount'])->name('count');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });
});

// USER ROUTES - Users dapat melihat barang dan mengelola peminjaman mereka sendiri
Route::middleware(['auth', 'role:user'])->group(function () {
    // Barang - View only untuk user
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
    
    // User's own peminjaman - User hanya bisa lihat peminjaman sendiri
    Route::get('/my-peminjaman', [PeminjamanController::class, 'userPeminjaman'])->name('peminjaman.user');
});

// SHARED ROUTES - User dan Admin bisa akses (dengan policy check)
Route::middleware(['auth', 'role:user,admin'])->group(function () {
    // Peminjaman detail - dengan policy check
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])
        ->name('peminjaman.show');
});

// ADMIN ROUTES - Full system access
Route::middleware(['auth', 'role:admin'])->group(function () {
    // User Management
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // Barang Management
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/create', [BarangController::class, 'create'])->name('create');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::get('/{barang}/edit', [BarangController::class, 'edit'])->name('edit');
        Route::put('/{barang}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{barang}', [BarangController::class, 'destroy'])->name('destroy');
    });
    
    // Kategori Barang Management
    Route::resource('kategori-barang', KategoriBarangController::class);
    
    // Peminjam Management
    Route::resource('peminjam', PeminjamController::class);
    
    // Peminjaman Management - Full CRUD untuk admin
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('index');
        Route::get('/create', [PeminjamanController::class, 'create'])->name('create');
        Route::post('/', [PeminjamanController::class, 'store'])->name('store');
        Route::get('/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('edit');
        Route::put('/{peminjaman}', [PeminjamanController::class, 'update'])->name('update');
        Route::delete('/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('destroy');
        
        // Pengembalian process
        Route::get('/{peminjaman}/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('pengembalian');
        Route::post('/{peminjaman}/kembali', [PeminjamanController::class, 'prosesKembali'])->name('proses-kembali');
    });

    // Financial Management
    Route::resource('transaksi-keuangan', TransaksiKeuanganController::class);
    
    // Reports Management
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/peminjaman', [LaporanController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/barang', [LaporanController::class, 'barang'])->name('barang');
    });
    
    // Export Management
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/barang', [ExportController::class, 'barang'])->name('barang');
        Route::get('/peminjaman', [ExportController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/transaksi', [ExportController::class, 'transaksi'])->name('transaksi');
        Route::get('/laporan/{type}', [ExportController::class, 'laporan'])->name('laporan');
    });
});