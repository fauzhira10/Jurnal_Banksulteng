<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurnalController;
use Illuminate\Support\Facades\Route;

// Rute Autentikasi (Hanya untuk Tamu / Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Rute Logout (Khusus yang sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Terproteksi Sistem Jurnal (Wajib Login)
Route::middleware('auth')->group(function () {
    // Halaman Form Input Jurnal Keluhan
    Route::get('/', [JurnalController::class, 'create'])->name('jurnal.create');

    // Halaman Data Keluhan & Pencarian Jurnal
    Route::get('/jurnal/data', [JurnalController::class, 'index'])->name('jurnal.index');

    // Route Simpan Data Jurnal Keluhan
    Route::post('/jurnal/simpan', [JurnalController::class, 'store'])->name('jurnal.store');

    // Route Edit & Update Data Jurnal Keluhan
    Route::get('/jurnal/{id}/edit', [JurnalController::class, 'edit'])->name('jurnal.edit');
    Route::put('/jurnal/{id}', [JurnalController::class, 'update'])->name('jurnal.update');

    // Route Hapus Data Jurnal Keluhan
    Route::delete('/jurnal/{id}', [JurnalController::class, 'destroy'])->name('jurnal.destroy');

    // Route API AJAX Auto-Fill Biaya Admin & Channel
    Route::get('/api/transaksi/{id}', [JurnalController::class, 'getDetailTransaksi'])->name('api.transaksi.detail');

    // Route API AJAX Rincian Jurnal Keluhan
    Route::get('/api/jurnal/{id}', [JurnalController::class, 'getDetailJurnal'])->name('api.jurnal.detail');
});