<?php

use App\Services\TotpService;

/**
 * Test vector resmi RFC 6238 (lampiran B), varian SHA1.
 *
 * Rahasianya adalah teks "12345678901234567890" (20 bita), yang dalam Base32
 * menjadi GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ. Nilai rujukan RFC ditulis dalam 8
 * digit; aplikasi autentikator memakai 6 digit, jadi yang dibandingkan adalah
 * enam digit terakhirnya.
 */
const RAHASIA_RFC = 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ';

test('kode cocok dengan test vector RFC 6238', function (int $waktu, string $delapanDigit) {
    $enamDigit = substr($delapanDigit, -6);

    expect(TotpService::kode(RAHASIA_RFC, TotpService::langkah($waktu)))->toBe($enamDigit);
})->with([
    [59, '94287082'],
    [1111111109, '07081804'],
    [1111111111, '14050471'],
    [1234567890, '89005924'],
    [2000000000, '69279037'],
]);

test('rahasia baru berbentuk base32 sepanjang 32 huruf', function () {
    $rahasia = TotpService::rahasiaBaru();

    expect($rahasia)->toMatch('/^[A-Z2-7]{32}$/')
        ->and(TotpService::rahasiaBaru())->not->toBe($rahasia);
});

test('kode yang benar diterima, kode yang salah ditolak', function () {
    $rahasia = TotpService::rahasiaBaru();
    $waktu = 1789000000;
    $benar = TotpService::kode($rahasia, TotpService::langkah($waktu));

    expect(TotpService::periksa($rahasia, $benar, null, $waktu))->toBe(TotpService::langkah($waktu))
        ->and(TotpService::periksa($rahasia, '000000', null, $waktu))->toBeNull()
        ->and(TotpService::periksa($rahasia, 'bukan angka', null, $waktu))->toBeNull()
        ->and(TotpService::periksa($rahasia, substr($benar, 0, 5), null, $waktu))->toBeNull();
});

test('selisih jam satu langkah masih diterima', function () {
    $rahasia = TotpService::rahasiaBaru();
    $waktu = 1789000000;

    // Kode dari ponsel yang jamnya tertinggal / mendahului 30 detik.
    $sebelum = TotpService::kode($rahasia, TotpService::langkah($waktu) - 1);
    $sesudah = TotpService::kode($rahasia, TotpService::langkah($waktu) + 1);

    expect(TotpService::periksa($rahasia, $sebelum, null, $waktu))->not->toBeNull()
        ->and(TotpService::periksa($rahasia, $sesudah, null, $waktu))->not->toBeNull();

    // Dua langkah sudah di luar toleransi.
    $jauh = TotpService::kode($rahasia, TotpService::langkah($waktu) - 2);

    expect(TotpService::periksa($rahasia, $jauh, null, $waktu))->toBeNull();
});

test('satu kode tidak dapat dipakai dua kali', function () {
    $rahasia = TotpService::rahasiaBaru();
    $waktu = 1789000000;
    $langkah = TotpService::langkah($waktu);
    $kode = TotpService::kode($rahasia, $langkah);

    // Pemakaian pertama menghasilkan nomor langkahnya.
    expect(TotpService::periksa($rahasia, $kode, null, $waktu))->toBe($langkah);

    // Pemakaian kedua, dengan langkah itu sudah tercatat, ditolak — termasuk
    // bila kodenya diketik ulang orang lain dalam jendela 30 detik yang sama.
    expect(TotpService::periksa($rahasia, $kode, $langkah, $waktu))->toBeNull();
});

test('uri otpauth memuat penerbit dan rahasianya', function () {
    $uri = TotpService::uri('GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ', 'admin', 'Jurnal Bank Sulteng');

    expect($uri)->toStartWith('otpauth://totp/Jurnal%20Bank%20Sulteng:admin?')
        ->and($uri)->toContain('secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ')
        ->and($uri)->toContain('digits=6')
        ->and($uri)->toContain('period=30');
});
