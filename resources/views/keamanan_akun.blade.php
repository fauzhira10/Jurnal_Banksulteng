@extends('layouts.app')

@section('title', 'Keamanan Akun')
@section('page_title', 'Keamanan Akun')
@section('page_subtitle', 'Autentikasi dua faktor untuk akun ' . $user->username)

@section('content')
<div class="max-w-3xl">

    @if($kodePemulihanBaru)
        {{-- Kode pemulihan hanya ditampilkan sekali, tepat setelah dibuat. --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-5 shadow-xs">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <div class="grow min-w-0">
                    <div class="font-bold text-amber-900">Simpan kode pemulihan ini sekarang</div>
                    <p class="text-[0.78125rem] text-amber-800 mt-1 leading-relaxed">
                        Kode ini <strong>tidak akan ditampilkan lagi</strong>. Cetak atau salin ke tempat yang aman
                        dan terpisah dari ponsel Anda. Setiap kode hanya dapat dipakai satu kali, dan hanya inilah
                        jalan masuk bila ponsel Anda hilang.
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3.5">
                        @foreach($kodePemulihanBaru as $kode)
                            <div class="bg-white border border-amber-200 rounded-lg px-3 py-2 text-center font-mono text-[0.8125rem] font-bold text-slate-800 select-all">{{ $kode }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h2 class="font-bold text-navy text-[0.9375rem]">Autentikasi Dua Faktor (2FA)</h2>
                <p class="text-[0.75rem] text-slate-500 mt-0.5">Kode sekali pakai dari aplikasi autentikator di ponsel Anda</p>
            </div>

            @if($user->mfaAktif())
                <span class="badge badge-done">Aktif</span>
            @elseif($user->mfaMenungguKonfirmasi())
                <span class="badge badge-menunggu">Menunggu Konfirmasi</span>
            @else
                <span class="badge badge-strip">Belum Aktif</span>
            @endif
        </div>

        <div class="p-5">

            @if($user->mfaAktif())
                <p class="text-[0.8125rem] text-slate-600 leading-relaxed">
                    Setiap kali masuk, sistem akan meminta enam angka dari aplikasi autentikator Anda
                    setelah kata sandi benar. Kata sandi yang bocor saja tidak lagi cukup untuk membuka akun ini.
                </p>

                <div class="mt-4 bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-[0.78125rem] text-slate-600">
                    Sisa kode pemulihan: <strong class="text-slate-800">{{ $sisaKodePemulihan }}</strong> dari {{ \App\Models\User::JUMLAH_KODE_PEMULIHAN }}.
                    @if($sisaKodePemulihan <= 2)
                        <span class="text-rose-700 font-semibold">Segera buat ulang sebelum habis.</span>
                    @endif
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 grid gap-4 sm:grid-cols-2">
                    <form action="{{ route('keamanan.mfa.kode') }}" method="POST"
                          data-konfirmasi="Buat ulang kode pemulihan?"
                          data-pesan="Seluruh kode pemulihan lama akan langsung tidak berlaku."
                          data-aksi="Ya, buat ulang" data-warna="amber" data-ikon="&#128273;">
                        @csrf
                        <label class="text-[0.75rem] font-semibold text-slate-700">Kata sandi akun</label>
                        <input type="password" name="current_password" required autocomplete="current-password"
                               class="w-full mt-1 px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none"
                               placeholder="Konfirmasi kata sandi">
                        <button type="submit" class="mt-2.5 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-[0.84375rem] font-semibold bg-slate-100 text-slate-700 border border-slate-300 hover:bg-slate-200 transition-colors cursor-pointer">
                            Buat Ulang Kode Pemulihan
                        </button>
                    </form>

                    <form action="{{ route('keamanan.mfa.matikan') }}" method="POST"
                          data-konfirmasi="Matikan autentikasi dua faktor?"
                          data-pesan="Akun ini akan kembali terlindungi kata sandi saja. Kode pemulihan yang tersisa ikut dihapus."
                          data-aksi="Ya, matikan" data-warna="rose" data-ikon="&#9888;">
                        @csrf
                        @method('DELETE')
                        <label class="text-[0.75rem] font-semibold text-slate-700">Kata sandi akun</label>
                        <input type="password" name="current_password" required autocomplete="current-password"
                               class="w-full mt-1 px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none"
                               placeholder="Konfirmasi kata sandi">
                        <button type="submit" class="mt-2.5 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-[0.84375rem] font-semibold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition-colors cursor-pointer">
                            Matikan Dua Faktor
                        </button>
                    </form>
                </div>

            @elseif($user->mfaMenungguKonfirmasi())
                <ol class="text-[0.8125rem] text-slate-600 leading-relaxed list-decimal ml-4 space-y-1.5">
                    <li>Buka aplikasi autentikator di ponsel Anda (Google Authenticator, Microsoft Authenticator, atau Authy).</li>
                    <li>Pilih <strong>tambah akun</strong> lalu <strong>masukkan kunci setelan</strong> (manual entry).</li>
                    <li>Isi nama akun bebas, lalu salin kunci di bawah ini sebagai kuncinya.</li>
                    <li>Masukkan enam angka yang muncul ke kolom di bawah untuk membuktikan pemasangannya berhasil.</li>
                </ol>

                <div class="mt-4 bg-slate-50 border border-slate-200 rounded-lg p-4">
                    <div class="text-[0.71875rem] font-semibold text-slate-500 uppercase tracking-wider">Kunci setelan</div>
                    <div class="font-mono text-[1.0625rem] font-bold text-navy tracking-wide mt-1 select-all break-all">{{ $rahasiaTerbaca }}</div>

                    <details class="mt-3">
                        <summary class="text-[0.75rem] text-slate-500 cursor-pointer hover:text-slate-700">Tautan otpauth untuk aplikasi yang mendukungnya</summary>
                        <div class="font-mono text-[0.71875rem] text-slate-600 mt-1.5 select-all break-all">{{ $uriOtpauth }}</div>
                    </details>
                </div>

                <form action="{{ route('keamanan.mfa.konfirmasi') }}" method="POST" class="mt-5 pt-4 border-t border-slate-100">
                    @csrf
                    <label for="kode" class="text-[0.8125rem] font-semibold text-slate-700">Enam angka dari aplikasi</label>
                    <input type="text" name="kode" id="kode" inputmode="numeric" autocomplete="one-time-code" required autofocus maxlength="16"
                           class="w-full sm:max-w-[14rem] mt-1.5 px-4 py-2.5 border border-slate-300 rounded-xl text-center text-[1.25rem] font-bold tracking-[0.35em] font-mono text-slate-800 placeholder-slate-300 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none"
                           placeholder="000000">

                    <div class="flex items-center gap-3 mt-4 flex-wrap">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-[0.84375rem] font-semibold bg-gradient-to-r from-brand-blue to-navy text-white hover:opacity-95 shadow-sm transition-opacity cursor-pointer">
                            Aktifkan Dua Faktor
                        </button>
                    </div>
                </form>

                <form action="{{ route('keamanan.mfa.matikan') }}" method="POST" class="mt-4 pt-4 border-t border-slate-100">
                    @csrf
                    @method('DELETE')
                    <label class="text-[0.75rem] font-semibold text-slate-700">Batalkan pendaftaran ini</label>
                    <div class="flex items-end gap-2.5 mt-1 flex-wrap">
                        <input type="password" name="current_password" required autocomplete="current-password"
                               class="grow sm:grow-0 sm:w-[16rem] px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none"
                               placeholder="Kata sandi akun">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-[0.84375rem] font-semibold bg-slate-100 text-slate-700 border border-slate-300 hover:bg-slate-200 transition-colors cursor-pointer">
                            Batalkan
                        </button>
                    </div>
                </form>

            @else
                <p class="text-[0.8125rem] text-slate-600 leading-relaxed">
                    Dengan dua faktor, kata sandi yang bocor saja tidak cukup untuk membuka akun ini:
                    sistem juga meminta enam angka yang hanya ada di ponsel Anda dan berganti tiap 30 detik.
                    Ini juga satu-satunya pengaman terhadap percobaan tebak kata sandi yang datang dari banyak
                    alamat IP sekaligus.
                </p>

                <div class="mt-4 bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-[0.78125rem] text-slate-600 leading-relaxed">
                    Siapkan dulu aplikasi autentikator di ponsel Anda &mdash; Google Authenticator, Microsoft
                    Authenticator, atau Authy. Setelah aktif, Anda akan menerima delapan kode pemulihan
                    untuk keadaan darurat.
                </div>

                <form action="{{ route('keamanan.mfa.mulai') }}" method="POST" class="mt-5">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-[0.84375rem] font-semibold bg-gradient-to-r from-brand-blue to-navy text-white hover:opacity-95 shadow-sm transition-opacity cursor-pointer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Aktifkan Dua Faktor
                    </button>
                </form>
            @endif

        </div>
    </div>

    <p class="text-[0.71875rem] text-slate-500 mt-4 leading-relaxed">
        Kehilangan ponsel <strong>dan</strong> kode pemulihan sekaligus hanya dapat dipulihkan dari server,
        dengan perintah <code class="font-mono bg-slate-100 px-1 py-0.5 rounded">php artisan admin:mfa-reset {{ $user->username }}</code>.
    </p>
</div>
@endsection
