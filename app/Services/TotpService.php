<?php

namespace App\Services;

/**
 * Kode sekali pakai berbasis waktu (TOTP, RFC 6238) untuk autentikasi dua faktor.
 *
 * Ditulis sendiri, bukan memakai paket luar, karena algoritmanya pendek dan
 * seluruhnya bersandar pada `hash_hmac` bawaan PHP — sementara menambah paket
 * berarti menambah rantai pasok yang harus ikut diaudit pada sistem perbankan.
 * Kebenarannya dikunci oleh test vector resmi RFC 6238 di
 * `tests/Feature/TotpServiceTest.php`; jangan mengubah berkas ini tanpa
 * menjalankan test tersebut.
 *
 * Aplikasi autentikator (Google Authenticator, Authy, Microsoft Authenticator)
 * memakai SHA1 / 6 digit / 30 detik sebagai bawaan, jadi nilai itulah yang
 * dipakai di sini.
 */
class TotpService
{
    public const DIGIT = 6;

    public const PERIODE = 30;

    /**
     * Jam ponsel petugas jarang persis sama dengan jam server, jadi satu langkah
     * sebelum dan sesudah ikut diterima (rentang total 90 detik).
     */
    public const TOLERANSI = 1;

    private const ALFABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Rahasia acak 160 bit, dikodekan Base32 seperti yang diminta aplikasi
     * autentikator.
     */
    public static function rahasiaBaru(int $bita = 20): string
    {
        return self::base32Encode(random_bytes($bita));
    }

    /**
     * URI `otpauth://` untuk dipindai atau dimasukkan manual ke aplikasi
     * autentikator.
     */
    public static function uri(string $rahasia, string $akun, string $penerbit): string
    {
        $label = rawurlencode($penerbit).':'.rawurlencode($akun);

        $parameter = http_build_query([
            'secret' => $rahasia,
            'issuer' => $penerbit,
            'algorithm' => 'SHA1',
            'digits' => self::DIGIT,
            'period' => self::PERIODE,
        ]);

        return 'otpauth://totp/'.$label.'?'.$parameter;
    }

    /**
     * Rahasia dipecah per empat huruf supaya terbaca saat diketik ulang petugas.
     */
    public static function rapikan(string $rahasia): string
    {
        return trim(chunk_split($rahasia, 4, ' '));
    }

    /**
     * Langkah waktu TOTP: banyaknya periode 30 detik sejak 1 Januari 1970.
     */
    public static function langkah(?int $waktu = null): int
    {
        return intdiv($waktu ?? time(), self::PERIODE);
    }

    /**
     * Kode untuk satu langkah waktu tertentu.
     */
    public static function kode(string $rahasia, ?int $langkah = null): string
    {
        $kunci = self::base32Decode($rahasia);
        $langkah ??= self::langkah();

        // 'J' = bilangan bulat 64 bit, urutan bita besar-dulu, sesuai RFC 4226.
        $hash = hash_hmac('sha1', pack('J', $langkah), $kunci, true);

        // Pemotongan dinamis: empat bita mulai dari posisi yang ditunjuk empat
        // bit terakhir hash, dengan bit tanda dibuang.
        $awal = ord($hash[19]) & 0x0F;

        $angka = ((ord($hash[$awal]) & 0x7F) << 24)
            | ((ord($hash[$awal + 1]) & 0xFF) << 16)
            | ((ord($hash[$awal + 2]) & 0xFF) << 8)
            | (ord($hash[$awal + 3]) & 0xFF);

        return str_pad((string) ($angka % (10 ** self::DIGIT)), self::DIGIT, '0', STR_PAD_LEFT);
    }

    /**
     * Periksa satu kode dari petugas.
     *
     * @param  int|null  $langkahMinimal  Langkah terakhir yang pernah diterima akun ini.
     *                                    Kode dari langkah yang sama atau lebih lama ditolak,
     *                                    sehingga satu kode benar-benar hanya berlaku sekali.
     * @return int|null Langkah yang cocok, atau null bila tidak ada yang cocok.
     */
    public static function periksa(string $rahasia, string $kode, ?int $langkahMinimal = null, ?int $waktu = null): ?int
    {
        $kode = preg_replace('/[^0-9]/', '', $kode) ?? '';

        if (strlen($kode) !== self::DIGIT || $rahasia === '') {
            return null;
        }

        $sekarang = self::langkah($waktu);

        for ($geser = -self::TOLERANSI; $geser <= self::TOLERANSI; $geser++) {
            $langkah = $sekarang + $geser;

            if ($langkahMinimal !== null && $langkah <= $langkahMinimal) {
                continue;
            }

            if (hash_equals(self::kode($rahasia, $langkah), $kode)) {
                return $langkah;
            }
        }

        return null;
    }

    protected static function base32Encode(string $data): string
    {
        $bit = '';

        foreach (str_split($data) as $bita) {
            $bit .= str_pad(decbin(ord($bita)), 8, '0', STR_PAD_LEFT);
        }

        $hasil = '';

        foreach (str_split($bit, 5) as $potongan) {
            $hasil .= self::ALFABET[bindec(str_pad($potongan, 5, '0', STR_PAD_RIGHT))];
        }

        return $hasil;
    }

    protected static function base32Decode(string $base32): string
    {
        $base32 = strtoupper(preg_replace('/[^A-Za-z2-7]/', '', $base32) ?? '');
        $bit = '';

        foreach (str_split($base32) as $huruf) {
            $posisi = strpos(self::ALFABET, $huruf);

            if ($posisi === false) {
                continue;
            }

            $bit .= str_pad(decbin($posisi), 5, '0', STR_PAD_LEFT);
        }

        $data = '';

        foreach (str_split($bit, 8) as $potongan) {
            // Sisa bit yang tidak genap satu bita adalah isian, bukan data.
            if (strlen($potongan) < 8) {
                break;
            }

            $data .= chr(bindec($potongan));
        }

        return $data;
    }
}
