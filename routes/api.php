<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    BarangController,
    DashboardController,
    PeminjamanController
};

// Public API routes
Route::get('/status', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API is running',
        'timestamp' => now()->toISOString()
    ]);
});

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    
    // User info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });
    
    // Dashboard API
    Route::get('/dashboard/statistics', [DashboardController::class, 'statistics']);
    Route::get('/dashboard/charts', [DashboardController::class, 'chartData']);
    
    // Barang API
    Route::get('/barang', [BarangController::class, 'index']);
    Route::get('/barang/{id}', [BarangController::class, 'show']);
    Route::get('/barang/kategori/{kategoriId}', [BarangController::class, 'getByKategori']);
    Route::post('/barang/cek-stok', [BarangController::class, 'cekStok']);
    
    // Peminjaman API
    Route::get('/peminjaman', [PeminjamanController::class, 'index']);
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show']);
    
    // Admin/Operator only API routes
    Route::middleware(['role:admin,operator'])->group(function () {
        Route::post('/peminjaman', [PeminjamanController::class, 'store']);
        Route::post('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'prosesKembali']);
    });
    
});