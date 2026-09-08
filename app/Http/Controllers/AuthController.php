<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
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

        // Otentikasi berbasis kolom username
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $remember)) {
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

            $request->session()->regenerate();

            return redirect()
                ->intended(route($user->homeRoute()))
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withErrors([
            'username' => 'Username atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('username');
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
