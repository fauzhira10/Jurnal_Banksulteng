<?php

namespace App\Http\Controllers;

use App\Services\AuditTrailService;
use App\Services\TotpService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Halaman Keamanan Akun: pendaftaran dan pencabutan autentikasi dua faktor.
 *
 * Setiap petugas mengurus akunnya sendiri di sini — tidak ada jalan bagi satu
 * pengguna untuk menyalakan atau mematikan MFA milik pengguna lain. Bila ponsel
 * petugas hilang dan kode pemulihannya habis, jalan satu-satunya adalah
 * `php artisan admin:mfa-reset` yang dijalankan langsung di server.
 */
class KeamananAkunController extends Controller
{
    /**
     * Kunci flash untuk kode pemulihan yang baru dibuat.
     *
     * Kode hanya ditampilkan sekali, tepat setelah dibuat, supaya tidak
     * tertinggal di layar petugas yang membuka halaman ini belakangan.
     */
    public const FLASH_KODE = 'kode_pemulihan_baru';

    public function index(Request $request)
    {
        $user = $request->user();
        $menunggu = $user->mfaMenungguKonfirmasi();

        return view('keamanan_akun', [
            'user' => $user,
            'uriOtpauth' => $menunggu ? TotpService::uri($user->mfa_rahasia, $user->username, config('app.name')) : null,
            'rahasiaTerbaca' => $menunggu ? TotpService::rapikan($user->mfa_rahasia) : null,
            'kodePemulihanBaru' => session(self::FLASH_KODE),
            'sisaKodePemulihan' => count($user->mfa_kode_pemulihan ?? []),
        ]);
    }

    /**
     * Membuat rahasia baru untuk dipindai aplikasi autentikator.
     *
     * Rahasianya sudah tersimpan di sini, tetapi MFA BELUM berlaku: lihat
     * `User::mfaAktif()`. Petugas yang menutup halaman di tengah jalan tetap
     * dapat masuk seperti biasa.
     */
    public function mulai(Request $request)
    {
        $user = $request->user();

        if ($user->mfaAktif()) {
            return back()->with('error', 'Autentikasi dua faktor sudah aktif pada akun ini.');
        }

        $user->forceFill([
            'mfa_rahasia' => TotpService::rahasiaBaru(),
            'mfa_dikonfirmasi_pada' => null,
            'mfa_langkah_terakhir' => null,
        ])->save();

        return redirect()->route('keamanan.index');
    }

    /**
     * Membuktikan bahwa aplikasi autentikator petugas benar-benar sudah terpasang.
     */
    public function konfirmasi(Request $request)
    {
        $user = $request->user();

        if (! $user->mfaMenungguKonfirmasi()) {
            return back()->with('error', 'Tidak ada pendaftaran dua faktor yang sedang berjalan.');
        }

        $request->validate(['kode' => 'required|string|max:16'], [
            'kode.required' => 'Kode dari aplikasi autentikator wajib diisi.',
        ]);

        $langkah = TotpService::periksa($user->mfa_rahasia, (string) $request->input('kode'));

        if ($langkah === null) {
            return back()->withErrors([
                'kode' => 'Kode tidak sesuai. Pastikan jam ponsel Anda tepat, lalu coba kode terbaru.',
            ]);
        }

        $user->forceFill([
            'mfa_dikonfirmasi_pada' => now(),
            'mfa_langkah_terakhir' => $langkah,
        ])->save();

        $kode = $user->buatKodePemulihan();

        AuditTrailService::catat('mfa.diaktifkan', [
            'objek' => $user,
            'keterangan' => "Autentikasi dua faktor diaktifkan untuk akun \"{$user->username}\".",
        ]);

        return redirect()->route('keamanan.index')
            ->with('success', 'Autentikasi dua faktor aktif. Simpan kode pemulihan di bawah ini sekarang juga.')
            ->with(self::FLASH_KODE, $kode);
    }

    /**
     * Membuat ulang kode pemulihan; kode lama langsung tidak berlaku.
     */
    public function kodePemulihan(Request $request)
    {
        $user = $request->user();

        if (! $user->mfaAktif()) {
            return back()->with('error', 'Autentikasi dua faktor belum aktif pada akun ini.');
        }

        $this->pastikanKataSandiBenar($request);

        $kode = $user->buatKodePemulihan();

        AuditTrailService::catat('mfa.kode_pemulihan_dibuat', [
            'objek' => $user,
            'keterangan' => "Kode pemulihan dua faktor dibuat ulang untuk akun \"{$user->username}\". Kode lama tidak berlaku lagi.",
        ]);

        return redirect()->route('keamanan.index')
            ->with('success', 'Kode pemulihan baru dibuat. Kode lama sudah tidak berlaku.')
            ->with(self::FLASH_KODE, $kode);
    }

    /**
     * Mematikan dua faktor. Meminta kata sandi lagi, sebab layar yang
     * ditinggalkan terbuka tidak boleh cukup untuk melucuti pengaman akun.
     */
    public function matikan(Request $request)
    {
        $user = $request->user();

        $this->pastikanKataSandiBenar($request);

        $sedangAktif = $user->mfaAktif();
        $user->matikanMfa();

        if ($sedangAktif) {
            AuditTrailService::catat('mfa.dinonaktifkan', [
                'objek' => $user,
                'keterangan' => "Autentikasi dua faktor dimatikan untuk akun \"{$user->username}\".",
            ]);
        }

        return redirect()->route('keamanan.index')
            ->with('success', 'Autentikasi dua faktor dimatikan untuk akun ini.');
    }

    /**
     * @throws ValidationException
     */
    protected function pastikanKataSandiBenar(Request $request): void
    {
        $request->validate([
            'current_password' => 'required|current_password',
        ], [
            'current_password.required' => 'Kata sandi akun wajib diisi untuk melanjutkan.',
            'current_password.current_password' => 'Kata sandi yang Anda masukkan tidak sesuai.',
        ]);
    }
}
