<?php

use App\Models\AuditTrail;
use App\Models\Jurnal;
use App\Services\AuditTrailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('membuat jurnal menulis jejak audit lengkap dengan pelakunya', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi));

    $jejak = AuditTrail::where('aksi', 'jurnal.dibuat')->first();

    expect($jejak)->not->toBeNull()
        ->and($jejak->user_id)->toBe($admin->id)
        ->and($jejak->username)->toBe($admin->username)
        ->and($jejak->auditable_type)->toBe(Jurnal::class)
        ->and($jejak->nilai_baru['nama_nasabah'])->toBe('AHMAD RIFAI')
        ->and($jejak->hash_sekarang)->not->toBeEmpty();
});

test('mengubah jurnal hanya mencatat kolom yang benar-benar berubah', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();
    $jurnal = Jurnal::create(dataJurnalDb($cabang, $transaksi));

    AuditTrail::query()->delete();

    $this->actingAs($admin)->put(route('jurnal.update', $jurnal->id), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => $jurnal->nama_nasabah,
        'no_resi' => $jurnal->no_resi,
        'no_rekening' => $jurnal->no_rekening,
        'tgl_transaksi' => '2026-09-01',
        'status' => 'Done',
    ]));

    $jejak = AuditTrail::where('aksi', 'jurnal.diubah')->first();

    expect($jejak)->not->toBeNull()
        ->and($jejak->nilai_baru)->toHaveKey('status')
        ->and($jejak->nilai_baru['status'])->toBe('Done')
        ->and($jejak->nilai_lama['status'])->toBe('Menunggu')
        // Kolom yang tidak berubah tidak ikut dicatat
        ->and($jejak->nilai_baru)->not->toHaveKey('nama_nasabah');
});

test('jejak audit tetap ada setelah jurnalnya dihapus', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();
    $jurnal = Jurnal::create(dataJurnalDb($cabang, $transaksi));
    $idJurnal = $jurnal->id;

    // Ada jejak pembuatan lebih dulu
    expect(AuditTrail::where('aksi', 'jurnal.dibuat')->count())->toBe(1);

    $this->actingAs($admin)->delete(route('jurnal.destroy', $idJurnal));

    expect(Jurnal::find($idJurnal))->toBeNull();

    // Jejak pembuatan TIDAK ikut terhapus — dulu destroy() membuangnya,
    // yang justru menghilangkan bukti saat paling dibutuhkan.
    expect(AuditTrail::where('aksi', 'jurnal.dibuat')->count())->toBe(1);

    $jejakHapus = AuditTrail::where('aksi', 'jurnal.dihapus')->first();

    expect($jejakHapus)->not->toBeNull()
        // Tautan foreign key terlepas, tetapi id jurnal yang dihapus tetap terekam
        ->and($jejakHapus->jurnal_id)->toBeNull()
        ->and((int) $jejakHapus->auditable_id)->toBe($idJurnal)
        ->and($jejakHapus->nilai_lama['nama_nasabah'])->toBe('BUDI SANTOSO');
});

test('membuka lampiran ktp tercatat pada jejak audit', function () {
    Storage::fake('local');

    $cabang = buatCabang();
    $cs = buatCs($cabang);
    $transaksi = buatTransaksi();
    $pengaduan = buatPengaduan($cs, $transaksi);
    $lampiran = buatLampiran($pengaduan);

    AuditTrail::query()->delete();

    $this->actingAs($cs)->get(route('pengaduan.lampiran.show', [$pengaduan, $lampiran]))
        ->assertStatus(200);

    $jejak = AuditTrail::where('aksi', 'lampiran.dibuka')->first();

    expect($jejak)->not->toBeNull()
        ->and($jejak->user_id)->toBe($cs->id)
        ->and($jejak->keterangan)->toContain($pengaduan->nomor_tiket);
});

test('login gagal tercatat tanpa pernah menyimpan kata sandinya', function () {
    $cabang = buatCabang();
    buatCs($cabang, ['username' => 'cs.palu']);

    $this->from('/login')->post('/login', [
        'username' => 'cs.palu',
        'password' => 'RahasiaBanget123',
    ]);

    $jejak = AuditTrail::where('aksi', 'login.gagal')->first();

    expect($jejak)->not->toBeNull()
        ->and($jejak->keterangan)->toContain('cs.palu');

    // Kata sandi percobaan tidak boleh bocor ke kolom mana pun.
    // Event Failed bawaan Laravel membawa kredensial lengkap, jadi ini yang dijaga.
    $seluruhBaris = json_encode(AuditTrail::all()->toArray());
    expect($seluruhBaris)->not->toContain('RahasiaBanget123');
});

test('penguncian login tercatat sebagai kejadian tersendiri', function () {
    $cabang = buatCabang();
    buatCs($cabang, ['username' => 'cs.palu']);

    for ($i = 0; $i < 6; $i++) {
        $this->from('/login')->post('/login', [
            'username' => 'cs.palu',
            'password' => 'salah',
        ]);
    }

    expect(AuditTrail::where('aksi', 'login.terkunci')->count())->toBeGreaterThan(0);
});

test('rantai hash utuh untuk jejak yang ditulis secara normal', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi));
    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'Siti Aminah',
        'no_resi' => '87654321',
    ]));

    $hasil = AuditTrailService::periksaRantai();

    expect($hasil['jumlah'])->toBeGreaterThanOrEqual(2)
        ->and($hasil['utuh'])->toBeTrue()
        ->and($hasil['masalah'])->toBe([]);
});

test('penyuntingan langsung ke tabel audit terdeteksi rantai hash', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi));

    $target = AuditTrail::query()->orderBy('id')->first();

    // Menyunting lewat query builder, meniru orang yang mengubah data langsung
    // di phpMyAdmin tanpa melewati aplikasi.
    DB::table('audit_trails')->where('id', $target->id)->update([
        'keterangan' => 'Catatan ini dipalsukan.',
    ]);

    $hasil = AuditTrailService::periksaRantai();

    expect($hasil['utuh'])->toBeFalse()
        ->and($hasil['masalah'][0]['id'])->toBe((int) $target->id)
        ->and($hasil['masalah'][0]['sebab'])->toContain('diubah setelah ditulis');
});

test('penghapusan baris audit di tengah rantai terdeteksi', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi));
    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'Siti Aminah',
        'no_resi' => '87654321',
    ]));

    expect(AuditTrailService::periksaRantai()['utuh'])->toBeTrue();

    // Buang baris pertama, sisakan yang berikutnya
    $pertama = AuditTrail::query()->orderBy('id')->first();
    DB::table('audit_trails')->where('id', $pertama->id)->delete();

    $hasil = AuditTrailService::periksaRantai();

    expect($hasil['utuh'])->toBeFalse()
        ->and($hasil['masalah'][0]['sebab'])->toContain('Rantai terputus');
});
