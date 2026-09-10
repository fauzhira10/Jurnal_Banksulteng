<?php

use App\Models\Jurnal;
use App\Support\Penyamaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('nomor disamarkan menyisakan empat digit terakhir', function () {
    expect(Penyamaran::nomor('00900001234'))->toBe('••••1234')
        ->and(Penyamaran::nomor('4213 5566 7788 9900'))->toBe('••••9900');
});

test('nomor pendek disamarkan seluruhnya', function () {
    // Menyisakan empat digit terakhir dari nomor lima digit sama saja dengan
    // menampilkannya utuh.
    expect(Penyamaran::nomor('12345'))->toBe('••••')
        ->and(Penyamaran::nomor('99'))->toBe('••••');
});

test('nilai kosong tetap tampil sebagai strip', function () {
    expect(Penyamaran::nomor(null))->toBe('-')
        ->and(Penyamaran::nomor(''))->toBe('-')
        ->and(Penyamaran::nomor('  '))->toBe('-')
        ->and(Penyamaran::nomor('-'))->toBe('-');
});

test('daftar data keluhan tidak memuat nomor rekening lengkap di mana pun', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'no_rekening' => '00900001234',
        'no_kartu' => '4213556677889900',
    ]));

    $halaman = $this->actingAs($admin)->get(route('jurnal.index'));

    // Bukan hanya kolom yang tampak: nomor lengkap juga tidak boleh tersisa di
    // atribut data-search maupun muatan tombol aksi.
    $halaman->assertOk()
        ->assertSee('••••1234')
        ->assertDontSee('00900001234')
        ->assertDontSee('4213556677889900');
});

test('bekal tombol tabel tidak membawa nomor rekening dan kartu', function () {
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    $jurnal = Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'no_rekening' => '00900001234',
        'no_kartu' => '4213556677889900',
    ]));

    $bekal = $jurnal->bekalTombol();

    expect($bekal)->not->toHaveKey('no_rekening')
        ->and($bekal)->not->toHaveKey('no_kartu')
        ->and($bekal['id'])->toBe($jurnal->id)
        ->and($bekal['master_transaksi']['channel'])->toBe($transaksi->channel);
});

test('rincian satu jurnal tetap memuat nomor lengkap bagi admin', function () {
    $admin = buatAdmin();
    $cabang = buatCabang();
    $transaksi = buatTransaksi();

    $jurnal = Jurnal::create(dataJurnalDb($cabang, $transaksi, [
        'no_rekening' => '00900001234',
        'no_kartu' => '4213556677889900',
    ]));

    // Modal rincian mengambil datanya dari sini, satu baris pada satu waktu.
    $this->actingAs($admin)->getJson(route('api.jurnal.detail', $jurnal->id))
        ->assertOk()
        ->assertJsonPath('no_rekening', '00900001234')
        ->assertJsonPath('no_kartu', '4213556677889900');
});

test('daftar pengaduan admin dan cs menyamarkan nomor rekening', function () {
    $cabang = buatCabang();
    $admin = buatAdmin();
    $cs = buatCs($cabang);
    $transaksi = buatTransaksi();

    buatPengaduan($cs, $transaksi, ['no_rekening' => '00900001234']);

    $this->actingAs($admin)->get(route('admin.pengaduan.index'))
        ->assertOk()
        ->assertSee('••••1234')
        ->assertDontSee('00900001234');

    $this->actingAs($cs)->get(route('cs.pengaduan.index'))
        ->assertOk()
        ->assertSee('••••1234')
        ->assertDontSee('00900001234');

    $this->actingAs($cs)->get(route('cs.dashboard'))
        ->assertOk()
        ->assertSee('••••1234')
        ->assertDontSee('00900001234');
});

test('rincian pengaduan tetap menampilkan nomor lengkap', function () {
    $cabang = buatCabang();
    $cs = buatCs($cabang);
    $transaksi = buatTransaksi();

    // Petugas yang sedang menangani satu pengaduan perlu nomor utuh untuk
    // dicocokkan dengan lampiran KTP nasabah.
    $pengaduan = buatPengaduan($cs, $transaksi, ['no_rekening' => '00900001234']);

    $this->actingAs($cs)->get(route('cs.pengaduan.show', $pengaduan))
        ->assertOk()
        ->assertSee('00900001234');
});
