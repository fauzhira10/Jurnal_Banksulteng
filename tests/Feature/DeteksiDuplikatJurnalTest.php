<?php

use App\Models\Jurnal;
use App\Services\DeteksiDuplikatService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('jurnal dengan nama, resi, dan tanggal transaksi yang sama ditolak', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]));

    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]))->assertSessionHasErrors('no_resi');

    expect(Jurnal::count())->toBe(1);
});

test('nama dan resi sama dengan tanggal berbeda ditahan sampai petugas menyetujui', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-08-01',
    ]));

    // Percobaan pertama tanpa penanda persetujuan → ditahan, panel dikirim lewat session
    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]))->assertSessionHasErrors('no_resi')->assertSessionHas('duplikat');

    expect(Jurnal::count())->toBe(1);

    // Percobaan kedua dengan penanda yang cocok → tersimpan
    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
        'konfirmasi_duplikat' => 'BUDI SANTOSO|778899',
    ]))->assertRedirect(route('jurnal.index'));

    expect(Jurnal::count())->toBe(2);
});

test('penanda persetujuan milik nasabah lain tidak dapat dipakai ulang', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-08-01',
    ]));

    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
        'konfirmasi_duplikat' => 'SITI AMINAH|111222',
    ]))->assertSessionHasErrors('no_resi');

    expect(Jurnal::count())->toBe(1);
});

test('nama huruf kecil dengan spasi berlebih tetap terdeteksi sebagai keluhan berulang', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]));

    // Aturan lama membandingkan ketikan mentah dengan data tersimpan yang sudah
    // huruf besar, sehingga kombinasi ini lolos begitu saja di SQLite.
    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => '  budi santoso  ',
        'no_resi' => ' 778899 ',
        'tgl_transaksi' => '2026-09-01',
    ]))->assertSessionHasErrors('no_resi');

    expect(Jurnal::count())->toBe(1);
});

test('nama nasabah yang mengandung koma tetap diperiksa dengan benar', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO, S.E.',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]));

    // Aturan lama dirakit lewat sambung-string, sehingga koma merusak daftar parameternya.
    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO, S.E.',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]))->assertSessionHasErrors('no_resi');

    expect(Jurnal::count())->toBe(1);
});

test('nomor resi yang sama milik nasabah berbeda tetap dianggap aman', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    // Nomor trace mesin ATM berputar, jadi resi yang sama bisa dipakai nasabah berbeda.
    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '000000004103',
        'tgl_transaksi' => '2026-09-01',
    ]));

    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'SITI AMINAH',
        'no_resi' => '000000004103',
        'tgl_transaksi' => '2026-09-01',
    ]))->assertRedirect(route('jurnal.index'));

    expect(Jurnal::count())->toBe(2);
});

test('mengubah jurnal tanpa mengganti nama dan resi tidak dianggap duplikat', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    $jurnal = Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]));

    $this->actingAs($admin)->put(route('jurnal.update', $jurnal->id), dataJurnal($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
        'status' => 'Done',
    ]))->assertRedirect(route('jurnal.index'));

    expect($jurnal->fresh()->status)->toBe('Done');
});

test('endpoint pemeriksaan mengembalikan tingkat yang sesuai', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]));

    $kembar = $this->actingAs($admin)->getJson(route('api.duplikat.periksa', [
        'nama' => 'budi santoso', 'resi' => '778899', 'tgl' => '2026-09-01',
    ]));
    $kembar->assertOk()->assertJsonPath('tingkat', DeteksiDuplikatService::KEMBAR);

    $berulang = $this->actingAs($admin)->getJson(route('api.duplikat.periksa', [
        'nama' => 'budi santoso', 'resi' => '778899', 'tgl' => '2026-10-05',
    ]));
    $berulang->assertOk()
        ->assertJsonPath('tingkat', DeteksiDuplikatService::BERULANG)
        ->assertJsonPath('token', 'BUDI SANTOSO|778899');

    $aman = $this->actingAs($admin)->getJson(route('api.duplikat.periksa', [
        'nama' => 'SITI AMINAH', 'resi' => '778899', 'tgl' => '2026-09-01',
    ]));
    $aman->assertOk()->assertJsonPath('tingkat', DeteksiDuplikatService::AMAN);
});

test('pengaduan cs yang belum dijurnal ikut memicu peringatan berulang', function () {
    $cabang = buatCabang();
    $cs = buatCs($cabang);
    $transaksi = buatTransaksi();

    $pengaduan = buatPengaduan($cs, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
    ]);

    $cek = DeteksiDuplikatService::periksa('BUDI SANTOSO', '778899');

    expect($cek['tingkat'])->toBe(DeteksiDuplikatService::BERULANG)
        ->and($cek['jumlah'])->toBe(1);

    // Pengaduan itu sendiri dikecualikan saat halamannya sedang dibuka
    $sendiri = DeteksiDuplikatService::periksa('BUDI SANTOSO', '778899', null, null, $pengaduan->id);
    expect($sendiri['tingkat'])->toBe(DeteksiDuplikatService::AMAN);
});

test('jurnal yang dibuat dari pengaduan cs tidak dianggap berulang terhadap sumbernya', function () {
    $cabang = buatCabang();
    $cs = buatCs($cabang);
    $admin = buatAdmin();
    $transaksi = buatTransaksi();

    // Tautan pengaduan → jurnal baru dibuat setelah jurnalnya tersimpan, jadi saat
    // pemeriksaan berjalan pengaduan ini masih ber-jurnal_id kosong.
    $pengaduan = buatPengaduan($cs, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]);

    $this->actingAs($admin)->post(route('jurnal.store'), dataJurnal($cabang, $transaksi, [
        'pengaduan_id' => $pengaduan->id,
        'nama_nasabah' => 'BUDI SANTOSO',
        'no_resi' => '778899',
        'tgl_transaksi' => '2026-09-01',
    ]))->assertRedirect(route('admin.pengaduan.show', $pengaduan));

    expect(Jurnal::count())->toBe(1)
        ->and($pengaduan->fresh()->jurnal_id)->not->toBeNull();
});

test('filter hanya data berulang menyaring baris yang punya kembaran', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO', 'no_resi' => '778899', 'tgl_transaksi' => '2026-08-01',
    ]));
    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'BUDI SANTOSO', 'no_resi' => '778899', 'tgl_transaksi' => '2026-09-01',
    ]));
    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'nama_nasabah' => 'SITI AMINAH', 'no_resi' => '111222', 'tgl_transaksi' => '2026-09-01',
    ]));

    $tersaring = $this->actingAs($admin)->get(route('jurnal.index', ['duplikat' => 1]));

    $tersaring->assertStatus(200)
        ->assertSee('BUDI SANTOSO')
        ->assertDontSee('SITI AMINAH')
        ->assertSee('Berulang');

    // Tanpa filter, keduanya tetap tampil
    $semua = $this->actingAs($admin)->get(route('jurnal.index'));
    $semua->assertStatus(200)->assertSee('BUDI SANTOSO')->assertSee('SITI AMINAH');
});
