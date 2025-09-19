<?php

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

// Public
Route::get('/', fn() => redirect()->route('login'));
Auth::routes(['register' => false]);

// Protected (semua user login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // ✅ User-only
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
      Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');

        // Menu user
        Route::get('/cari-barang', [BarangController::class, 'userIndex'])->name('cari.barang');
        Route::get('/peminjam', [PeminjamController::class, 'userIndex'])->name('peminjam.index');
        Route::get('/riwayat-sewa', [PeminjamanController::class, 'userRiwayat'])->name('riwayat.sewa');

        // Peminjaman saya
        Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [\App\Http\Controllers\User\PeminjamanController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\User\PeminjamanController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\User\PeminjamanController::class, 'store'])->name('store');
    Route::get('/{peminjaman}', [\App\Http\Controllers\User\PeminjamanController::class, 'show'])->name('show');
    Route::put('/{peminjaman}/kembali', [\App\Http\Controllers\User\PeminjamanController::class, 'prosesKembali'])->name('kembali');
});

    });

    // ✅ Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('profile', [SettingsController::class, 'profile'])->name('profile');
        Route::put('profile', [SettingsController::class, 'updateProfile'])->name('updateProfile');
        Route::get('account', [SettingsController::class, 'account'])->name('account');
        Route::put('account', [SettingsController::class, 'updateAccount'])->name('updateAccount');
    });

    // ✅ Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/count', [NotificationController::class, 'getUnreadCount'])->name('count');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });
});

// ✅ Admin-only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // User Management
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // Barang & Kategori
    Route::resource('barang', BarangController::class);
    Route::resource('kategori-barang', KategoriBarangController::class);

    // Peminjam
    Route::resource('peminjam', PeminjamController::class);

    // Peminjaman
    Route::resource('peminjaman', PeminjamanController::class)->except(['create','store']);
    Route::get('peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');

    // Pengembalian
    Route::get('peminjaman/{peminjaman}/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->name('peminjaman.pengembalian');
    Route::put('peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'prosesKembali'])
        ->name('peminjaman.kembali');

    // Transaksi Keuangan
    Route::resource('transaksi-keuangan', TransaksiKeuanganController::class);

    // Laporan Web
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Export (PDF & Excel)
    Route::prefix('laporan/export')->name('laporan.export.')->group(function () {
        Route::get('/pdf', [ExportController::class, 'exportPdf'])->name('pdf');
        Route::get('/excel', [ExportController::class, 'exportExcel'])->name('excel');
        Route::get('/user/pdf', [ExportController::class, 'exportUserPDF'])->name('user.pdf');
        Route::get('/user/excel', [ExportController::class, 'exportUserExcel'])->name('user.excel');
        Route::get('/barang/pdf', [ExportController::class, 'exportBarangPDF'])->name('barang.pdf');
        Route::get('/barang/excel', [ExportController::class, 'exportBarangExcel'])->name('barang.excel');
        Route::get('/peminjaman/pdf', [ExportController::class, 'exportPeminjamanPDF'])->name('peminjaman.pdf');
        Route::get('/peminjaman/excel', [ExportController::class, 'exportPeminjamanExcel'])->name('peminjaman.excel');
        Route::get('/keuangan/pdf', [ExportController::class, 'exportKeuanganPDF'])->name('keuangan.pdf');
        Route::get('/keuangan/excel', [ExportController::class, 'exportKeuanganExcel'])->name('keuangan.excel');
    });
});

// ✅ Shared (Admin + User)
Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
});
