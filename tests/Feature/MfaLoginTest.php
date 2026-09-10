<?php

use App\Models\AuditTrail;
use App\Models\User;
use App\Services\TotpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Siapkan akun yang dua faktornya sudah aktif, lalu kembalikan rahasianya.
 */
function nyalakanMfa(User $user): string
{
    $rahasia = TotpService::rahasiaBaru();

    $user->forceFill([
        'mfa_rahasia' => $rahasia,
        'mfa_dikonfirmasi_pada' => now(),
        'mfa_langkah_terakhir' => null,
    ])->save();

    $user->buatKodePemulihan();

    return $rahasia;
}

test('kata sandi benar belum cukup ketika dua faktor aktif', function () {
    $admin = buatAdmin();
    nyalakanMfa($admin);

    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123'])
        ->assertRedirect(route('login.mfa'));

    // Sesinya belum berstatus masuk: penanda yang dicuri pun tidak memberi akses.
    $this->assertGuest();
});

test('kode dari aplikasi autentikator menyelesaikan login', function () {
    $admin = buatAdmin();
    $rahasia = nyalakanMfa($admin);

    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123']);

    $this->post(route('login.mfa.post'), ['kode' => TotpService::kode($rahasia)])
        ->assertRedirect(route($admin->homeRoute()));

    $this->assertAuthenticatedAs($admin);
});

test('kode yang salah ditolak dan tercatat pada jejak audit', function () {
    $admin = buatAdmin();
    nyalakanMfa($admin);

    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123']);

    $this->from(route('login.mfa'))
        ->post(route('login.mfa.post'), ['kode' => '000000'])
        ->assertRedirect(route('login.mfa'))
        ->assertSessionHasErrors('kode');

    $this->assertGuest();

    $jejak = AuditTrail::where('aksi', 'login.mfa_gagal')->first();

    expect($jejak)->not->toBeNull()
        ->and($jejak->keterangan)->toContain($admin->username);
});

test('satu kode tidak dapat dipakai dua kali', function () {
    $admin = buatAdmin();
    $rahasia = nyalakanMfa($admin);
    $kode = TotpService::kode($rahasia);

    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123']);
    $this->post(route('login.mfa.post'), ['kode' => $kode]);
    $this->assertAuthenticatedAs($admin);

    $this->post(route('logout'));

    // Kode yang sama, masih dalam jendela 30 detik yang sama.
    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123']);
    $this->post(route('login.mfa.post'), ['kode' => $kode])->assertSessionHasErrors('kode');

    $this->assertGuest();
});

test('kode pemulihan dapat dipakai sekali lalu hangus', function () {
    $admin = buatAdmin();
    nyalakanMfa($admin);

    $kodePemulihan = $admin->fresh()->mfa_kode_pemulihan;
    $satu = $kodePemulihan[0];

    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123']);
    $this->post(route('login.mfa.post'), ['kode' => $satu])
        ->assertRedirect(route($admin->homeRoute()));

    $this->assertAuthenticatedAs($admin);

    // Kode yang terpakai langsung dibuang dari daftar.
    expect($admin->fresh()->mfa_kode_pemulihan)->toHaveCount(User::JUMLAH_KODE_PEMULIHAN - 1)
        ->and($admin->fresh()->mfa_kode_pemulihan)->not->toContain($satu);

    $this->post(route('logout'));

    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123']);
    $this->post(route('login.mfa.post'), ['kode' => $satu])->assertSessionHasErrors('kode');
    $this->assertGuest();
});

test('halaman verifikasi tidak dapat dibuka tanpa lolos kata sandi', function () {
    $admin = buatAdmin();
    nyalakanMfa($admin);

    $this->get(route('login.mfa'))->assertRedirect(route('login'));
    $this->post(route('login.mfa.post'), ['kode' => '123456'])->assertRedirect(route('login'));

    $this->assertGuest();
});

test('penanda verifikasi yang kedaluwarsa ditolak', function () {
    $admin = buatAdmin();
    $rahasia = nyalakanMfa($admin);

    $this->withSession(['mfa_menunggu' => [
        'user_id' => $admin->id,
        'remember' => false,
        'kedaluwarsa' => now()->subMinute()->timestamp,
    ]])
        ->post(route('login.mfa.post'), ['kode' => TotpService::kode($rahasia)])
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('akun nonaktif tidak dapat menyelesaikan verifikasi', function () {
    $admin = buatAdmin();
    $rahasia = nyalakanMfa($admin);

    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123']);

    // Dinonaktifkan Admin Pusat setelah kata sandi lolos, sebelum kode diisi.
    $admin->forceFill(['is_active' => false])->save();

    $this->post(route('login.mfa.post'), ['kode' => TotpService::kode($rahasia)])
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('akun tanpa dua faktor tetap masuk seperti biasa', function () {
    $admin = buatAdmin();

    $this->post(route('login.post'), ['username' => $admin->username, 'password' => 'admin123'])
        ->assertRedirect(route($admin->homeRoute()));

    $this->assertAuthenticatedAs($admin);
});
