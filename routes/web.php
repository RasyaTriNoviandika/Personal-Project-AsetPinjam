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
Auth::routes(['register' => false]); // Disable public registration

// Protected routes - All authenticated users

Route::middleware(['auth'])->group(function () {
    // Dashboard - role akan dicek di controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Profile & Settings - All roles
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.password.change');

    // Notifications - All roles
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// USER ROLE - Basic access (Users dapat melihat barang dan peminjaman mereka sendiri)
Route::middleware(['auth', 'role:user,operator,admin'])->group(function () {
    // Barang - View only for users, full access for operator/admin
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
    
    // User's own peminjaman - Users hanya bisa lihat peminjaman mereka sendiri
    Route::get('/my-peminjaman', [PeminjamanController::class, 'userPeminjaman'])->name('peminjaman.user');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])
        ->name('peminjaman.show')
        ->middleware('can:view,peminjaman');
});

// OPERATOR & ADMIN - Operational access (Tidak ada akses laporan keuangan untuk operator)
Route::middleware(['auth', 'role:operator,admin'])->group(function () {
    // Barang Management - Operator bisa kelola barang
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    
    // Kategori Management - Operator bisa kelola kategori
    Route::resource('kategori-barang', KategoriBarangController::class)->except(['destroy']);
    
    // Peminjam Management - Operator bisa kelola peminjam
    Route::resource('peminjam', PeminjamController::class)->except(['destroy']);
    
    // Peminjaman Management - Core function untuk operator
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::get('/peminjaman/{peminjaman}/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('peminjaman.pengembalian');
    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'prosesKembali'])->name('peminjaman.proses-kembali');
    Route::get('/peminjaman/terlambat', [PeminjamanController::class, 'terlambat'])->name('peminjaman.terlambat');
    
    // Pengembalian routes - Operator function
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalianIndex'])->name('pengembalian.index');
    
    // Basic Reports - Operator hanya bisa lihat laporan operasional (bukan keuangan)
    Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::get('/laporan/barang', [LaporanController::class, 'barang'])->name('laporan.barang');
    
    // Exports - Operator bisa export data operasional
    Route::get('/export/barang', [ExportController::class, 'barang'])->name('export.barang');
    Route::get('/export/peminjaman', [ExportController::class, 'peminjaman'])->name('export.peminjaman');
});

// ADMIN ONLY - Full system access (Admin fokus pada laporan, master data, dan permissions)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // User Management - Admin only
    Route::resource('users', UserController::class);
    
    // Financial Management - Admin only
    Route::resource('transaksi-keuangan', TransaksiKeuanganController::class);
    
    // Full Reports Access - Admin dapat akses semua laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('/export/transaksi', [ExportController::class, 'transaksi'])->name('export.transaksi');
    
    // Delete operations - Admin only (Master data control)
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::delete('/kategori-barang/{kategoriBarang}', [KategoriBarangController::class, 'destroy'])->name('kategori-barang.destroy');
    Route::delete('/peminjam/{peminjam}', [PeminjamController::class, 'destroy'])->name('peminjam.destroy');
    Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::delete('/transaksi-keuangan/{transaksiKeuangan}', [TransaksiKeuanganController::class, 'destroy'])->name('transaksi-keuangan.destroy');
    
    // System settings & permissions - Admin only
    Route::get('/settings/permissions', [SettingsController::class, 'permissions'])->name('settings.permissions');
    Route::put('/settings/permissions', [SettingsController::class, 'updatePermissions'])->name('settings.permissions.update');
    Route::get('/system-settings', [SettingsController::class, 'system'])->name('settings.system');
    Route::put('/system-settings', [SettingsController::class, 'updateSystem'])->name('settings.system.update');
});