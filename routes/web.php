<?php

use App\Http\Controllers\AtmMonitoringController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LaporanController;
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
    // Halaman Dashboard Utama (Home)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');

    // Halaman Form Input Jurnal Keluhan
    Route::get('/jurnal/input', [JurnalController::class, 'create'])->name('jurnal.create');

    // Halaman Data Keluhan & Pencarian Jurnal
    Route::get('/jurnal/data', [JurnalController::class, 'index'])->name('jurnal.index');

    // Halaman Preview Jurnal (Setelah Input)
    Route::get('/jurnal/{id}/preview', [JurnalController::class, 'preview'])->name('jurnal.preview')->whereNumber('id');

    // Route Export Excel (.xlsx) Multi-Sheet
    Route::get('/jurnal/export-excel', [JurnalController::class, 'exportExcel'])->name('jurnal.export_excel');

    // Route Import Excel (.xlsx / .xls) Multi-Sheet
    Route::post('/jurnal/import-excel', [JurnalController::class, 'importExcel'])->name('jurnal.import_excel');

    // Route Simpan Data Jurnal Keluhan
    Route::post('/jurnal/simpan', [JurnalController::class, 'store'])->name('jurnal.store');

    // Route Reset / Kosongkan Seluruh Data Jurnal & Template
    Route::delete('/jurnal/reset-all', [JurnalController::class, 'resetAllData'])->name('jurnal.reset_all');

    // Route Edit & Update Data Jurnal Keluhan
    Route::get('/jurnal/{id}/edit', [JurnalController::class, 'edit'])->name('jurnal.edit')->whereNumber('id');
    Route::put('/jurnal/{id}', [JurnalController::class, 'update'])->name('jurnal.update')->whereNumber('id');

    // Route Hapus Data Jurnal Keluhan
    Route::delete('/jurnal/{id}', [JurnalController::class, 'destroy'])->name('jurnal.destroy')->whereNumber('id');

    // Route Menu Laporan Rekapitulasi Penyelesaian Keluhan Nasabah (Bulanan & Tahunan)
    Route::get('/laporan/rekapitulasi', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export_excel');

    // Route Menu Monitoring & Rekapitulasi Keluhan Mesin ATM
    Route::get('/atm-monitoring', [AtmMonitoringController::class, 'index'])->name('atm.index');

    // Route API AJAX Auto-Fill Biaya Admin & Channel
    Route::get('/api/transaksi/{id}', [JurnalController::class, 'getDetailTransaksi'])->name('api.transaksi.detail');

    // Route API AJAX Daftar Mesin ATM per Cabang
    Route::get('/api/cabang/{id}/atms', [JurnalController::class, 'getAtmsByCabang'])->name('api.cabang.atms');

    // Route Unduh Dokumen Jurnal (Otomatis deteksi LOKAL / ATMB)
    Route::get('/jurnal/{id}/download', [JurnalController::class, 'downloadDokumen'])->name('jurnal.download')->whereNumber('id');

    // Route Update Keterangan Log (AJAX dari Halaman Cetak/Data)
    Route::post('/jurnal/{id}/update-log', [JurnalController::class, 'updateLog'])->name('jurnal.update_log')->whereNumber('id');

    // Route API AJAX Rincian Jurnal Keluhan
    Route::get('/api/jurnal/{id}', [JurnalController::class, 'getDetailJurnal'])->name('api.jurnal.detail');
});