<?php

use App\Enums\PengaduanStatus;
use App\Models\Jurnal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

/**
 * Smoke test: memastikan seluruh halaman modul pengaduan (CS & Admin) dapat dirender tanpa error.
 */
test('seluruh halaman cs dapat dirender', function () {
    Storage::fake('local');
    $cabang = buatCabang();
    $cs = buatCs($cabang);
    $transaksi = buatTransaksi();
    $pengaduan = buatPengaduan($cs, $transaksi);

    $this->actingAs($cs)->get(route('cs.dashboard'))->assertStatus(200)->assertSee($pengaduan->nomor_pengaduan);
    $this->actingAs($cs)->get(route('cs.pengaduan.index'))->assertStatus(200)->assertSee('BUDI SANTOSO');
    $this->actingAs($cs)->get(route('cs.pengaduan.index', ['status' => 'Terkirim', 'q' => 'BUDI']))->assertStatus(200)->assertSee($pengaduan->nomor_pengaduan);
    $this->actingAs($cs)->get(route('cs.pengaduan.create'))->assertStatus(200)->assertSee('Kirim Pengaduan ke Pusat');
    $this->actingAs($cs)->get(route('cs.pengaduan.edit', $pengaduan))->assertStatus(200)->assertSee('Simpan Perubahan');
    $this->actingAs($cs)->get(route('cs.pengaduan.show', $pengaduan))->assertStatus(200)->assertSee('Menunggu Verifikasi Pusat');
});

test('seluruh halaman admin pengaduan dan pengguna dapat dirender', function () {
    $cabang = buatCabang();
    $cs = buatCs($cabang);
    $admin = buatAdmin();
    $transaksi = buatTransaksi();
    $pengaduan = buatPengaduan($cs, $transaksi);

    $this->actingAs($admin)->get(route('admin.pengaduan.index'))->assertStatus(200);
    $this->actingAs($admin)->get(route('admin.pengaduan.index', ['status' => 'semua', 'master_cabang_id' => $cabang->id, 'tgl_dari' => '2026-01-01']))->assertStatus(200)->assertSee($pengaduan->nomor_pengaduan);
    $this->actingAs($admin)->get(route('admin.pengaduan.show', $pengaduan))->assertStatus(200)->assertSee('Terima & Input ke Jurnal');

    $this->actingAs($admin)->get(route('admin.pengguna.index'))->assertStatus(200)->assertSee($cs->name);
    $this->actingAs($admin)->get(route('admin.pengguna.create'))->assertStatus(200);
    $this->actingAs($admin)->get(route('admin.pengguna.edit', $cs))->assertStatus(200)->assertSee($cs->username);
});

test('halaman jurnal yang tertaut pengaduan menampilkan sumber pengaduan', function () {
    $cabang = buatCabang();
    $cs = buatCs($cabang);
    $admin = buatAdmin();
    $transaksi = buatTransaksi();
    $pengaduan = buatPengaduan($cs, $transaksi);

    $jurnal = Jurnal::create([
        'nama_nasabah' => 'BUDI SANTOSO', 'no_resi' => '123456', 'no_rekening' => '00900001234', 'no_kartu' => '-',
        'no_tiket' => $pengaduan->nomor_pengaduan, 'master_cabang_id' => $cabang->id, 'master_transaksi_id' => $transaksi->id,
        'terminal_transaksi' => '-', 'nominal_transaksi' => 500000, 'biaya_admin' => 0,
        'tgl_transaksi' => '2026-09-01', 'tgl_terima' => '2026-09-04', 'tgl_selesai' => null,
        'status' => 'Menunggu', 'permasalahan' => '-', 'keterangan_log' => '-',
    ]);
    $pengaduan->update(['jurnal_id' => $jurnal->id, 'status' => PengaduanStatus::Diproses, 'diproses_at' => now()]);

    $this->actingAs($admin)->get(route('jurnal.preview', $jurnal->id))->assertStatus(200)->assertSee('Sumber: Pengaduan CS');
    $this->actingAs($admin)->get(route('jurnal.edit', $jurnal->id))->assertStatus(200)->assertSee('bersumber dari pengaduan CS');
    $this->actingAs($admin)->get(route('jurnal.index'))->assertStatus(200)->assertSee($pengaduan->nomor_pengaduan);
    $this->actingAs($admin)->get(route('admin.pengaduan.show', $pengaduan))->assertStatus(200)->assertSee('Tertaut ke Jurnal Keluhan');

    // CS melihat status "Dalam Proses Jurnal" dan info tiket dari pusat
    $this->actingAs($cs)->get(route('cs.pengaduan.show', $pengaduan))->assertStatus(200)->assertSee('Dalam Proses Jurnal')->assertSee($pengaduan->nomor_pengaduan);
});
