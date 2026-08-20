<?php

use App\Http\Controllers\JurnalController;
use Illuminate\Support\Facades\Route;

// Halaman utama form
Route::get('/', function () {
    $cabangs = \App\Models\MasterCabang::all();
    $transaksis = \App\Models\MasterTransaksi::all();
    return view('jurnal_form', compact('cabangs', 'transaksis'));
});

// Route untuk simpan data
Route::post('/jurnal/simpan', [JurnalController::class, 'store']);

// Route untuk AJAX auto-fill biaya admin
Route::get('/api/transaksi/{id}', [JurnalController::class, 'getDetailTransaksi']);