<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dua Faktor - E-JURNAL KELUHAN Bank Sulteng</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[radial-gradient(ellipse_at_top_right,_#13467b_0%,_#0b2f54_45%,_#071f38_100%)] min-h-screen flex items-center justify-center p-5 relative overflow-x-hidden antialiased text-slate-800">

<div class="absolute inset-0 bg-pattern-dots pointer-events-none"></div>

<div class="bg-white w-full max-w-[27.5rem] rounded-2xl shadow-2xl overflow-hidden relative z-10 animate-modal-in">
    <div class="bg-gradient-to-br from-navy to-navy-dark text-white pt-8 px-6 pb-6 text-center border-b-[3px] border-brand-gold relative">
        <div class="inline-flex items-center justify-center bg-white px-5 py-2 rounded-xl mb-3.5 shadow-md border border-white/30">
            <img src="{{ asset('images/logo_bank_sulteng.png') }}" alt="Logo Bank Sulteng" class="h-9 w-auto max-w-[175px] object-contain block">
        </div>
        <h1 class="text-[1.1875rem] font-extrabold tracking-wider mb-1 text-white uppercase">VERIFIKASI DUA FAKTOR</h1>
        <p class="text-[0.78125rem] text-blue-100/90 font-medium tracking-normal">Langkah kedua untuk akun {{ $user->username }}</p>
    </div>

    <div class="p-7 sm:p-8">
        @if (isset($errors) && $errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 rounded-xl p-3.5 text-[0.8125rem] mb-4.5 flex items-center gap-2.5 shadow-xs">
                <svg class="w-4.5 h-4.5 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <p class="text-[0.8125rem] text-slate-600 leading-relaxed mb-5">
            Buka aplikasi autentikator di ponsel Anda, lalu masukkan enam angka yang
            sedang ditampilkan untuk <strong>E-Jurnal Bank Sulteng</strong>.
        </p>

        <form action="{{ route('login.mfa.post') }}" method="POST">
            @csrf

            <div class="mb-5 flex flex-col gap-1.5">
                <label for="kode" class="text-[0.8125rem] font-semibold text-slate-700">Kode Verifikasi</label>
                <input type="text" name="kode" id="kode" inputmode="numeric" autocomplete="one-time-code"
                       class="w-full py-3 px-4 border border-slate-300 rounded-xl text-center text-[1.375rem] font-bold tracking-[0.4em] font-mono text-slate-800 placeholder-slate-300 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none"
                       required autofocus placeholder="000000" maxlength="64">
                <span class="text-[0.71875rem] text-slate-500">
                    Kode berganti setiap 30 detik. Bila ponsel Anda tidak tersedia, masukkan salah satu
                    <strong>kode pemulihan</strong> di kolom yang sama.
                </span>
            </div>

            <button type="submit" class="w-full py-3 px-5 bg-gradient-to-r from-brand-blue to-navy text-white rounded-xl text-[0.9375rem] font-bold cursor-pointer shadow-lg shadow-brand-blue/30 hover:opacity-95 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-blue/40 transition-all duration-200 flex items-center justify-center gap-2 mt-2">
                <span>Verifikasi &amp; Masuk</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
            </button>
        </form>

        @if($sisaKodePemulihan === 0)
            <div class="mt-5 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl p-3.5 text-[0.75rem] leading-relaxed">
                Kode pemulihan Anda sudah habis terpakai. Bila ponsel Anda juga tidak tersedia,
                hubungi Admin Pusat untuk menyetel ulang dua faktor pada akun ini.
            </div>
        @endif

        <div class="mt-5 text-center">
            <a href="{{ route('login') }}" class="text-[0.78125rem] font-semibold text-slate-500 hover:text-slate-700 hover:underline">
                Batal dan masuk sebagai akun lain
            </a>
        </div>
    </div>

    <div class="px-7 py-4 text-center text-[0.71875rem] text-slate-500 border-t border-slate-200 bg-slate-50/80">
        &copy; {{ date('Y') }} PT Bank Sulteng &bull; Layanan Pengaduan & Jurnal Keluhan
    </div>
</div>

</body>
</html>
