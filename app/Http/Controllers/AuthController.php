<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditTrailService;
use App\Services\TotpService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Banyaknya percobaan login gagal sebelum akun dikunci sementara.
     */
    private const MAKS_PERCOBAAN = 5;

    /**
     * Lama penguncian setelah percobaan gagal melewati batas (detik).
     */
    private const DURASI_KUNCI = 900;

    /**
     * Penanda sesi untuk akun yang sudah lolos kata sandi tetapi belum melewati
     * langkah kedua. Sesi ini BELUM berstatus masuk.
     */
    private const SESI_MFA = 'mfa_menunggu';

    /**
     * Umur penanda di atas. Layar kode yang ditinggalkan tidak boleh menunggu
     * selamanya.
     */
    private const MENIT_MFA = 5;

    /**
     * Menampilkan halaman login (Admin Pusat & CS Cabang memakai halaman yang sama)
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->homeRoute());
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi login berbasis Username, lalu mengarahkan sesuai peran
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username petugas wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        // Tolak lebih dulu bila kombinasi username + IP ini sedang dikunci
        // karena percobaan gagal beruntun (penahan serangan tebak kata sandi).
        $this->pastikanBelumTerkunci($request);

        // Otentikasi berbasis kolom username
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $remember)) {
            // Kata sandi terbukti benar, jadi hitungan percobaan gagal dinolkan.
            // Penolakan setelah titik ini (akun nonaktif / sesi ganda) bukan
            // indikasi serangan, sehingga tidak boleh ikut menambah hitungan.
            RateLimiter::clear($this->kunciPembatas($request));

            $user = Auth::user();

            // 1. Akun nonaktif tidak boleh masuk
            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Admin Pusat.',
                ])->onlyInput('username');
            }

            // 2. Deteksi Login Bersamaan (Concurrent Login Detection)
            // Cek apakah akun ini sedang aktif digunakan di komputer/perangkat lain
            $currentSessionId = $request->session()->getId();
            $timeoutMenit = config('session.concurrent_timeout', 30);

            if (Schema::hasTable('sessions')) {
                $batasWaktu = now()->subMinutes($timeoutMenit)->timestamp;

                $sesiAktif = DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', '!=', $currentSessionId)
                    ->where('last_activity', '>=', $batasWaktu)
                    ->orderByDesc('last_activity')
                    ->first();

                if ($sesiAktif) {
                    // Batalkan sesi percobaan kedua ini
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    Carbon::setLocale('id');
                    $waktuAktif = Carbon::createFromTimestamp($sesiAktif->last_activity)->diffForHumans();
                    $ipInfo = $sesiAktif->ip_address ? " (IP: {$sesiAktif->ip_address})" : '';

                    return back()->withErrors([
                        'username' => "Akun ini sedang aktif digunakan di komputer/perangkat lain{$ipInfo}, terakhir aktif {$waktuAktif}. Demi keamanan perbankan, akun tidak dapat digunakan di lebih dari 1 komputer secara bersamaan. Silakan keluar terlebih dahulu dari perangkat tersebut atau hubungi Admin Pusat.",
                    ])->onlyInput('username');
                }

                // Bersihkan sesi lama/kedaluwarsa user ini agar tabel sessions tetap bersih
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', '!=', $currentSessionId)
                    ->where('last_activity', '<', $batasWaktu)
                    ->delete();
            }

            // 3. Autentikasi dua faktor: kata sandi saja belum cukup.
            if ($user->mfaAktif()) {
                return $this->tahanUntukMfa($request, $user, $remember);
            }

            $request->session()->regenerate();

            return redirect()
                ->intended(route($user->homeRoute()))
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        RateLimiter::hit($this->kunciPembatas($request), self::DURASI_KUNCI);

        return back()->withErrors([
            'username' => 'Username atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('username');
    }

    // ==================== LANGKAH KEDUA (MFA) ====================

    /**
     * Kata sandi sudah benar, tetapi akun ini memakai dua faktor.
     *
     * Sesi sengaja TIDAK dibiarkan berstatus masuk: yang disimpan hanya penanda
     * siapa yang sedang menunggu langkah kedua. Dengan begitu, penanda yang
     * dicuri pun tidak memberi akses apa pun tanpa kode dari ponsel petugas.
     */
    protected function tahanUntukMfa(Request $request, User $user, bool $remember)
    {
        Auth::logout();

        // Id sesi diputar supaya penanda ini tidak menempel pada sesi lama.
        $request->session()->regenerate();

        $request->session()->put(self::SESI_MFA, [
            'user_id' => $user->id,
            'remember' => $remember,
            'kedaluwarsa' => now()->addMinutes(self::MENIT_MFA)->timestamp,
        ]);

        return redirect()->route('login.mfa');
    }

    /**
     * Akun yang sedang menunggu langkah kedua, atau null bila penandanya tidak
     * ada, sudah kedaluwarsa, atau akunnya sudah tidak layak masuk.
     */
    protected function penungguMfa(Request $request): ?User
    {
        $penanda = $request->session()->get(self::SESI_MFA);

        if (! is_array($penanda) || ($penanda['kedaluwarsa'] ?? 0) < now()->timestamp) {
            $request->session()->forget(self::SESI_MFA);

            return null;
        }

        $user = User::find($penanda['user_id'] ?? null);

        if (! $user || ! $user->is_active || ! $user->mfaAktif()) {
            $request->session()->forget(self::SESI_MFA);

            return null;
        }

        return $user;
    }

    /**
     * Halaman pengisian kode dua faktor.
     */
    public function showMfaForm(Request $request)
    {
        $user = $this->penungguMfa($request);

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'username' => 'Sesi verifikasi sudah berakhir. Silakan masuk kembali.',
            ]);
        }

        return view('auth.mfa', [
            'user' => $user,
            'sisaKodePemulihan' => count($user->mfa_kode_pemulihan ?? []),
        ]);
    }

    /**
     * Memeriksa kode dua faktor (atau kode pemulihan) lalu menyelesaikan login.
     */
    public function mfa(Request $request)
    {
        $user = $this->penungguMfa($request);

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'username' => 'Sesi verifikasi sudah berakhir. Silakan masuk kembali.',
            ]);
        }

        $request->validate(['kode' => 'required|string|max:64'], [
            'kode.required' => 'Kode verifikasi wajib diisi.',
        ]);

        $kunci = 'mfa|'.$user->id.'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($kunci, self::MAKS_PERCOBAAN)) {
            $request->session()->forget(self::SESI_MFA);

            AuditTrailService::catatAman('login.mfa_terkunci', [
                'objek' => $user,
                'pelaku' => $user,
                'keterangan' => "Verifikasi dua faktor untuk \"{$user->username}\" dikunci sementara karena kode salah beruntun.",
            ]);

            return redirect()->route('login')->withErrors([
                'username' => 'Terlalu banyak kode yang salah. Demi keamanan, silakan masuk kembali beberapa saat lagi.',
            ]);
        }

        $kode = (string) $request->input('kode');

        // 1. Kode dari aplikasi autentikator.
        $langkah = TotpService::periksa($user->mfa_rahasia, $kode, $user->mfa_langkah_terakhir);

        if ($langkah !== null) {
            // Langkah yang sudah terpakai disimpan, sehingga kode yang sama tidak
            // dapat dipakai ulang selama sisa jendela 30 detiknya.
            $user->forceFill(['mfa_langkah_terakhir' => $langkah])->save();

            return $this->selesaikanLoginMfa($request, $user, $kunci);
        }

        // 2. Kode pemulihan, untuk petugas yang kehilangan ponselnya.
        if ($user->pakaiKodePemulihan($kode)) {
            AuditTrailService::catatAman('login.mfa_pemulihan', [
                'objek' => $user,
                'pelaku' => $user,
                'keterangan' => 'Masuk memakai kode pemulihan. Sisa kode: '.count($user->mfa_kode_pemulihan ?? []).'.',
            ]);

            return $this->selesaikanLoginMfa($request, $user, $kunci, 'Anda masuk memakai kode pemulihan. Segera buat kode pemulihan baru di halaman Keamanan Akun.');
        }

        RateLimiter::hit($kunci, self::DURASI_KUNCI);

        AuditTrailService::catatAman('login.mfa_gagal', [
            'objek' => $user,
            'pelaku' => $user,
            'keterangan' => "Kode dua faktor salah untuk username \"{$user->username}\".",
        ]);

        return back()->withErrors([
            'kode' => 'Kode verifikasi tidak sesuai atau sudah kedaluwarsa. Periksa kembali aplikasi autentikator Anda.',
        ]);
    }

    /**
     * Langkah kedua terlewati: barulah sesi ini berstatus masuk.
     */
    protected function selesaikanLoginMfa(Request $request, User $user, string $kunci, ?string $peringatan = null)
    {
        $penanda = $request->session()->get(self::SESI_MFA, []);

        RateLimiter::clear($kunci);
        $request->session()->forget(self::SESI_MFA);

        Auth::loginUsingId($user->id, (bool) ($penanda['remember'] ?? false));
        $request->session()->regenerate();

        $tujuan = redirect()
            ->intended(route($user->homeRoute()))
            ->with('success', "Selamat datang kembali, {$user->name}!");

        return $peringatan ? $tujuan->with('error', $peringatan) : $tujuan;
    }

    /**
     * Kunci pembatas percobaan login: username + alamat IP.
     *
     * Sengaja tidak memakai username saja, sebab kunci per-username murni
     * memungkinkan penyerang mengunci akun Admin Pusat dari luar (denial of
     * service) pada sistem yang hanya punya satu akun admin. Serangan tersebar
     * dari banyak IP ditutup belakangan lewat MFA, bukan lewat pembatas ini.
     */
    protected function kunciPembatas(Request $request): string
    {
        return 'login|'.Str::transliterate(Str::lower((string) $request->input('username'))).'|'.$request->ip();
    }

    /**
     * Hentikan permintaan bila percobaan gagal sudah melewati batas.
     *
     * @throws ValidationException
     */
    protected function pastikanBelumTerkunci(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->kunciPembatas($request), self::MAKS_PERCOBAAN)) {
            return;
        }

        // Event bawaan Laravel, supaya pencatatan audit pada tahap berikutnya
        // cukup memasang listener tanpa mengubah controller ini lagi.
        event(new Lockout($request));

        $detik = RateLimiter::availableIn($this->kunciPembatas($request));
        $menit = (int) ceil($detik / 60);

        $sisa = $detik < 60
            ? "{$detik} detik"
            : "{$menit} menit";

        throw ValidationException::withMessages([
            'username' => "Terlalu banyak percobaan masuk yang gagal. Demi keamanan, login untuk akun ini dikunci sementara. Silakan coba lagi dalam {$sisa} atau hubungi Admin Pusat.",
        ]);
    }

    /**
     * Memproses logout pengguna
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus sesi dari database agar akun langsung dapat login di perangkat lain tanpa jeda
        if ($userId && Schema::hasTable('sessions')) {
            DB::table('sessions')
                ->where('id', $sessionId)
                ->orWhere('user_id', $userId)
                ->delete();
        }

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
