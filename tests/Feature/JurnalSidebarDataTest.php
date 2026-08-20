<?php

use App\Models\MasterCabang;
use App\Models\MasterTransaksi;
use App\Models\Jurnal;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman formulir jurnal keluhan dapat diakses dan memuat sidebar', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('E-JURNAL KELUHAN');
    $response->assertSee('Input Jurnal Keluhan');
    $response->assertSee('Data Keluhan');
    $response->assertSee('Formulir Pengaduan & Jurnal Transaksi');
});

test('halaman data keluhan dapat diakses dan menampilkan filter serta statistik', function () {
    $response = $this->get('/jurnal/data');

    $response->assertStatus(200);
    $response->assertSee('E-JURNAL KELUHAN');
    $response->assertSee('Data Jurnal Keluhan');
    $response->assertSee('Filter & Pencarian Jurnal');
    $response->assertSee('Total Keluhan');
    $response->assertSee('Menunggu');
    $response->assertSee('Success');
    $response->assertSee('Done');
    $response->assertSee('Rejected');
});

test('api auto-fill transaksi mengembalikan biaya admin dan channel', function () {
    $cabang = MasterCabang::create([
        'kode_cabang' => '001',
        'nama_cabang' => 'Cabang Utama Palu'
    ]);

    $transaksi = MasterTransaksi::create([
        'jenis_transaksi' => 'ATM_TARIK TUNAI ATM BANK SULTENG',
        'channel' => 'ATM LOKAL',
        'biaya_admin' => 0
    ]);

    $response = $this->getJson("/api/transaksi/{$transaksi->id}");

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $transaksi->id,
        'channel' => 'ATM LOKAL',
        'biaya_admin' => 0
    ]);
});

test('dapat menyimpan jurnal keluhan baru dan tertera pada halaman data keluhan', function () {
    $cabang = MasterCabang::create([
        'kode_cabang' => '002',
        'nama_cabang' => 'KCP Tinombo'
    ]);

    $transaksi = MasterTransaksi::create([
        'jenis_transaksi' => 'ATM_TARIK TUNAI DI BANK LAIN',
        'channel' => 'ATM BERSAMA',
        'biaya_admin' => 7500
    ]);

    $postData = [
        'nama_nasabah'       => 'Ahmad Rifai',
        'no_resi'            => '12345678',
        'no_rekening'        => '001099887766',
        'no_kartu'           => '6019001234567890',
        'no_tiket'           => 'TKT-2026-TEST',
        'tgl_terima'         => '2026-08-20',
        'tgl_transaksi'      => '2026-08-19',
        'tgl_selesai'        => null,
        'master_cabang_id'   => $cabang->id,
        'master_transaksi_id'=> $transaksi->id,
        'terminal_transaksi' => 'ATM-TINOMBO-01',
        'nominal_transaksi'  => 500000,
        'keterangan_log'     => 'Uang tidak keluar tapi saldo terdebet',
        'status'             => 'Menunggu',
    ];

    $response = $this->post('/jurnal/simpan', $postData);

    $response->assertRedirect('/jurnal/data');
    $response->assertSessionHas('success');

    // Cek di halaman data keluhan
    $dataResponse = $this->get('/jurnal/data?q=12345678');
    $dataResponse->assertStatus(200);
    $dataResponse->assertSee('Ahmad Rifai');
    $dataResponse->assertSee('12345678');
    $dataResponse->assertSee('500.000');
});
