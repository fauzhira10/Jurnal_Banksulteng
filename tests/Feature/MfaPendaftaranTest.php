<?php

use App\Models\AuditTrail;
use App\Models\User;
use App\Services\TotpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman keamanan akun terbuka untuk kedua peran', function () {
    $cabang = buatCabang();
    $admin = buatAdmin();
    $cs = buatCs($cabang);

    $this->actingAs($admin)->get(route('keamanan.index'))->assertOk()->assertSee('Autentikasi Dua Faktor');
    $this->actingAs($cs)->get(route('keamanan.index'))->assertOk()->assertSee('Autentikasi Dua Faktor');
});

test('memulai pendaftaran membuat rahasia tetapi belum memberlakukan dua faktor', function () {
    $admin = buatAdmin();

    $this->actingAs($admin)->post(route('keamanan.mfa.mulai'))->assertRedirect(route('keamanan.index'));

    $admin->refresh();

    // Rahasianya sudah ada, tetapi petugas yang gagal memindai tidak boleh
    // langsung terkunci dari akunnya sendiri.
    expect($admin->mfa_rahasia)->not->toBeEmpty()
        ->and($admin->mfaMenungguKonfirmasi())->toBeTrue()
        ->and($admin->mfaAktif())->toBeFalse();

    // Login masih berjalan seperti biasa selama belum dikonfirmasi.
    $this->post(route('logout'));
    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123'])
        ->assertRedirect(route($admin->homeRoute()));
});

test('kode yang benar mengaktifkan dua faktor dan memberi kode pemulihan', function () {
    $admin = buatAdmin();

    $this->actingAs($admin)->post(route('keamanan.mfa.mulai'));
    $rahasia = $admin->fresh()->mfa_rahasia;

    $this->actingAs($admin)
        ->post(route('keamanan.mfa.konfirmasi'), ['kode' => TotpService::kode($rahasia)])
        ->assertRedirect(route('keamanan.index'))
        ->assertSessionHas('kode_pemulihan_baru');

    $admin->refresh();

    expect($admin->mfaAktif())->toBeTrue()
        ->and($admin->mfa_kode_pemulihan)->toHaveCount(User::JUMLAH_KODE_PEMULIHAN);

    expect(AuditTrail::where('aksi', 'mfa.diaktifkan')->exists())->toBeTrue();
});

test('kode yang salah tidak mengaktifkan apa pun', function () {
    $admin = buatAdmin();

    $this->actingAs($admin)->post(route('keamanan.mfa.mulai'));

    $this->actingAs($admin)
        ->from(route('keamanan.index'))
        ->post(route('keamanan.mfa.konfirmasi'), ['kode' => '000000'])
        ->assertSessionHasErrors('kode');

    expect($admin->fresh()->mfaAktif())->toBeFalse();
});

test('rahasia dua faktor tersimpan terenkripsi di basis data', function () {
    $admin = buatAdmin();

    $this->actingAs($admin)->post(route('keamanan.mfa.mulai'));

    $mentah = DB::table('users')->where('id', $admin->id)->value('mfa_rahasia');

    // Salinan basis data yang bocor tidak boleh cukup untuk membuat kodenya.
    expect($mentah)->not->toBe($admin->fresh()->mfa_rahasia)
        ->and($mentah)->not->toContain($admin->fresh()->mfa_rahasia);
});

test('mematikan dua faktor menuntut kata sandi yang benar', function () {
    $admin = buatAdmin();
    $admin->forceFill([
        'mfa_rahasia' => TotpService::rahasiaBaru(),
        'mfa_dikonfirmasi_pada' => now(),
    ])->save();

    $this->actingAs($admin)
        ->from(route('keamanan.index'))
        ->delete(route('keamanan.mfa.matikan'), ['current_password' => 'bukan-kata-sandinya'])
        ->assertSessionHasErrors('current_password');

    expect($admin->fresh()->mfaAktif())->toBeTrue();

    $this->actingAs($admin)
        ->delete(route('keamanan.mfa.matikan'), ['current_password' => 'admin123'])
        ->assertRedirect(route('keamanan.index'));

    expect($admin->fresh()->mfaAktif())->toBeFalse()
        ->and(AuditTrail::where('aksi', 'mfa.dinonaktifkan')->exists())->toBeTrue();
});

test('membuat ulang kode pemulihan menuntut kata sandi dan menghanguskan yang lama', function () {
    $admin = buatAdmin();
    $admin->forceFill([
        'mfa_rahasia' => TotpService::rahasiaBaru(),
        'mfa_dikonfirmasi_pada' => now(),
    ])->save();

    $lama = $admin->buatKodePemulihan();

    $this->actingAs($admin)
        ->from(route('keamanan.index'))
        ->post(route('keamanan.mfa.kode'), ['current_password' => 'salah'])
        ->assertSessionHasErrors('current_password');

    expect($admin->fresh()->mfa_kode_pemulihan)->toBe($lama);

    $this->actingAs($admin)
        ->post(route('keamanan.mfa.kode'), ['current_password' => 'admin123'])
        ->assertSessionHas('kode_pemulihan_baru');

    expect($admin->fresh()->mfa_kode_pemulihan)->not->toBe($lama)
        ->and($admin->fresh()->mfa_kode_pemulihan)->toHaveCount(User::JUMLAH_KODE_PEMULIHAN);
});

test('perintah admin mfa-reset mematikan dua faktor satu akun', function () {
    $admin = buatAdmin();
    $admin->forceFill([
        'mfa_rahasia' => TotpService::rahasiaBaru(),
        'mfa_dikonfirmasi_pada' => now(),
    ])->save();

    $this->artisan('admin:mfa-reset', ['username' => $admin->username, '--force' => true])
        ->assertExitCode(0);

    expect($admin->fresh()->mfaAktif())->toBeFalse()
        ->and($admin->fresh()->mfa_kode_pemulihan)->toBeNull()
        ->and(AuditTrail::where('aksi', 'mfa.direset')->exists())->toBeTrue();
});

test('perintah admin mfa-reset menolak username yang tidak ada', function () {
    $this->artisan('admin:mfa-reset', ['username' => 'tidak.ada', '--force' => true])
        ->assertExitCode(1);
});

test('mengurus dua faktor tidak menyentuh akun lain', function () {
    $cabang = buatCabang();
    $admin = buatAdmin();
    $cs = buatCs($cabang);

    $cs->forceFill([
        'mfa_rahasia' => TotpService::rahasiaBaru(),
        'mfa_dikonfirmasi_pada' => now(),
    ])->save();

    $this->actingAs($admin)->post(route('keamanan.mfa.mulai'));
    $this->actingAs($admin)->delete(route('keamanan.mfa.matikan'), ['current_password' => 'admin123']);

    // Halaman ini hanya mengurus akun yang sedang masuk.
    expect($cs->fresh()->mfaAktif())->toBeTrue();
});
