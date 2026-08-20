<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login admin
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('jurnal.create');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi login berbasis Username
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
            $request->session()->regenerate();
            $userName = Auth::user()->name;
            return redirect()->intended(route('jurnal.create'))->with('success', "Selamat datang kembali, {$userName}!");
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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}