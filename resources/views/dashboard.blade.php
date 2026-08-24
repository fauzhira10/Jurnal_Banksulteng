@extends('layouts.app')

@section('title', 'Dashboard Utama - E-Jurnal Keluhan PT Bank Sulteng')
@section('page_title', 'Dashboard Utama')
@section('page_subtitle', 'Ringkasan performa penyelesaian keluhan, mesin ATM terdampak, dan analitik transaksi')

@section('topbar_action')
    <a href="{{ route('jurnal.create') }}" class="inline-flex items-center gap-2 h-[38px] px-4 rounded-xl bg-gradient-to-r from-brand-blue to-navy text-white text-xs font-bold shadow-sm shadow-brand-blue/20 hover:brightness-110 transition-all">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Input Jurnal Baru</span>
    </a>
@endsection

@section('content')
<!-- Hero Welcome Banner -->
<div class="bg-gradient-to-r from-navy-dark via-navy to-brand-blue rounded-2xl p-6 text-white mb-6 shadow-md flex justify-between items-center flex-wrap gap-4 relative overflow-hidden">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-xs text-xs font-semibold text-amber-300 mb-2 border border-white/20">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Sistem E-Jurnal Keluhan Nasabah Aktif</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-white flex items-center gap-2.5 m-0 mb-1 leading-tight">
            Selamat Datang, {{ Auth::user()->name ?? 'Petugas Bank Sulteng' }}!
        </h1>
        <p class="text-xs sm:text-sm text-slate-200 m-0 max-w-2xl">
            Pantau ringkasan penyelesaian keluhan nasabah, performa terminal mesin ATM, serta status transaksi perbankan secara real-time.
        </p>
    </div>

    <!-- Quick Year Filter -->
    <div class="relative z-10 flex items-center gap-3">
        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
            <label for="filterTahun" class="text-xs font-semibold text-blue-100 hidden sm:inline">Periode:</label>
            <div class="relative">
                <select name="tahun" id="filterTahun" onchange="this.form.submit()" class="h-[38px] pl-3 pr-8 rounded-xl bg-white/15 hover:bg-white/25 border border-white/30 text-white text-xs font-bold appearance-none cursor-pointer focus:outline-none focus:bg-navy-dark transition-all">
                    <option value="" class="bg-navy-dark text-white" {{ empty($selectedYear) ? 'selected' : '' }}>Semua Periode</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" class="bg-navy-dark text-white" {{ $selectedYear == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
                <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-white/70">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Background Decorative Circles -->
    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
    <div class="absolute right-40 -top-10 w-32 h-32 bg-sky-400/10 rounded-full blur-xl pointer-events-none"></div>
</div>

<!-- 4 Primary Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4.5 mb-6">
    <!-- Card 1: Total Kasus Keluhan -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs flex items-center gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
        <div class="w-13 h-13 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center shrink-0 shadow-xs">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div class="overflow-hidden">
            <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider m-0 mb-1">Total Kasus Keluhan</h4>
            <div class="text-2xl font-black text-navy leading-tight">{{ number_format($totalKasus, 0, ',', '.') }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Seluruh laporan tercatat</div>
        </div>
    </div>

    <!-- Card 2: Mesin ATM Terdampak -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs flex items-center gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
        <div class="w-13 h-13 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-xs">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                <line x1="6" y1="6" x2="6.01" y2="6"></line>
                <line x1="6" y1="18" x2="6.01" y2="18"></line>
            </svg>
        </div>
        <div class="overflow-hidden">
            <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider m-0 mb-1">Mesin ATM Terdampak</h4>
            <div class="text-2xl font-black text-navy leading-tight">{{ number_format($totalMesin, 0, ',', '.') }} Unit</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Terminal memiliki riwayat masalah</div>
        </div>
    </div>

    <!-- Card 3: Keluhan Terbanyak (ATM #1) -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs flex items-center gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
        <div class="w-13 h-13 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center shrink-0 shadow-xs">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
        </div>
        <div class="overflow-hidden">
            <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider m-0 mb-1">Keluhan Terbanyak</h4>
            <div class="text-lg font-black text-navy leading-tight truncate max-w-[170px]" title="{{ $topAtm->terminal_transaksi ?? '-' }}">
                {{ $topAtm->terminal_transaksi ?? '-' }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">
                {{ $topAtm ? $topAtm->total_keluhan . ' Kasus Tercatat' : 'Belum ada data' }}
            </div>
        </div>
    </div>

    <!-- Card 4: Total Nominal Masalah -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs flex items-center gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
        <div class="w-13 h-13 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 shadow-xs">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <div class="overflow-hidden">
            <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider m-0 mb-1">Total Nominal Masalah</h4>
            <div class="text-lg sm:text-xl font-black text-navy leading-tight">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Uang transaksi terkait</div>
        </div>
    </div>
</div>

<!-- Status Resolution Grid -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <!-- Menunggu -->
    <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-4 flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-amber-800 uppercase tracking-wider">Menunggu</span>
            <div class="text-xl font-black text-amber-900 mt-0.5">{{ number_format($statusStats['menunggu'], 0, ',', '.') }}</div>
        </div>
        <div class="w-9 h-9 rounded-xl bg-amber-200/60 text-amber-700 flex items-center justify-center font-bold text-xs">
            ⏳
        </div>
    </div>

    <!-- Success -->
    <div class="bg-emerald-50 border border-emerald-200/80 rounded-2xl p-4 flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Success</span>
            <div class="text-xl font-black text-emerald-900 mt-0.5">{{ number_format($statusStats['success'], 0, ',', '.') }}</div>
        </div>
        <div class="w-9 h-9 rounded-xl bg-emerald-200/60 text-emerald-700 flex items-center justify-center font-bold text-xs">
            ✅
        </div>
    </div>

    <!-- Done -->
    <div class="bg-sky-50 border border-sky-200/80 rounded-2xl p-4 flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-sky-800 uppercase tracking-wider">Done</span>
            <div class="text-xl font-black text-sky-900 mt-0.5">{{ number_format($statusStats['done'], 0, ',', '.') }}</div>
        </div>
        <div class="w-9 h-9 rounded-xl bg-sky-200/60 text-sky-700 flex items-center justify-center font-bold text-xs">
            🔵
        </div>
    </div>

    <!-- Rejected -->
    <div class="bg-rose-50 border border-rose-200/80 rounded-2xl p-4 flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-rose-800 uppercase tracking-wider">Rejected</span>
            <div class="text-xl font-black text-rose-900 mt-0.5">{{ number_format($statusStats['rejected'], 0, ',', '.') }}</div>
        </div>
        <div class="w-9 h-9 rounded-xl bg-rose-200/60 text-rose-700 flex items-center justify-center font-bold text-xs">
            ❌
        </div>
    </div>
</div>

<!-- Main 2-Column Analytics Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
    <!-- Left Column (5 Cols): Top Machines & Top Issues -->
    <div class="lg:col-span-5 flex flex-col gap-6">
        <!-- Top 5 Mesin ATM Bermasalah -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-navy flex items-center gap-2 m-0">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                    </svg>
                    <span>Top 5 Mesin ATM Bermasalah</span>
                </h3>
                <a href="{{ route('atm.index') }}" class="text-[11.5px] font-bold text-brand-blue hover:underline">
                    Lihat Semua &rsaquo;
                </a>
            </div>

            <div class="flex flex-col gap-2.5">
                @forelse($topAtms as $idx => $atm)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-300 transition-colors">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <span class="w-6 h-6 rounded-lg bg-navy text-white text-xs font-bold flex items-center justify-center shrink-0">
                                {{ $idx + 1 }}
                            </span>
                            <div class="overflow-hidden">
                                <div class="text-xs font-bold text-slate-800 truncate">{{ $atm->terminal_transaksi }}</div>
                                <div class="text-[11px] text-slate-400">Rp {{ number_format($atm->total_nominal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-extrabold shrink-0">
                            {{ $atm->total_keluhan }} Kasus
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-slate-400">Belum ada data keluhan mesin ATM.</div>
                @endforelse
            </div>
        </div>

        <!-- Top 5 Jenis Gangguan / Transaksi -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-navy flex items-center gap-2 m-0">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 20V10"></path>
                        <path d="M12 20V4"></path>
                        <path d="M6 20v-6"></path>
                    </svg>
                    <span>Top Jenis Gangguan / Transaksi</span>
                </h3>
                <a href="{{ route('laporan.index') }}" class="text-[11.5px] font-bold text-brand-blue hover:underline">
                    Lihat Rekap &rsaquo;
                </a>
            </div>

            <div class="flex flex-col gap-2.5">
                @forelse($topIssues as $issue)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-300 transition-colors">
                        <div class="overflow-hidden pr-2">
                            <div class="text-xs font-bold text-slate-800 truncate">{{ $issue->jenis_transaksi }}</div>
                            <div class="text-[11px] text-slate-400">Channel: {{ $issue->channel ?: '-' }}</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold shrink-0">
                            {{ $issue->total_kasus }} Keluhan
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-slate-400">Belum ada data transaksi keluhan.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column (7 Cols): Recent Complaints & Quick Shortcuts -->
    <div class="lg:col-span-7 flex flex-col gap-6">
        <!-- Riwayat Keluhan Terkini -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs flex-1 flex flex-col">
            <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-slate-100 flex-wrap gap-2">
                <h3 class="text-sm font-bold text-navy flex items-center gap-2 m-0">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Laporan Keluhan Nasabah Terbaru</span>
                </h3>
                <a href="{{ route('jurnal.index') }}" class="text-[11.5px] font-bold text-brand-blue hover:underline">
                    Semua Data Keluhan &rsaquo;
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full border-collapse text-xs text-left">
                    <thead>
                        <tr>
                            <th class="pb-2.5 text-slate-500 font-bold uppercase tracking-wider text-[11px]">Nasabah</th>
                            <th class="pb-2.5 text-slate-500 font-bold uppercase tracking-wider text-[11px]">Jenis Transaksi</th>
                            <th class="pb-2.5 text-slate-500 font-bold uppercase tracking-wider text-[11px] text-right">Nominal</th>
                            <th class="pb-2.5 text-slate-500 font-bold uppercase tracking-wider text-[11px] text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentJurnals as $jurnal)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 pr-3">
                                    <div class="font-bold text-navy text-xs">{{ $jurnal->nama_nasabah }}</div>
                                    <div class="text-[11px] text-slate-400">Resi: {{ $jurnal->no_resi }} &bull; {{ $jurnal->masterCabang->nama_cabang ?? '-' }}</div>
                                </td>
                                <td class="py-3 pr-3">
                                    <div class="font-semibold text-slate-700">{{ $jurnal->masterTransaksi->jenis_transaksi ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $jurnal->terminal_transaksi ?: 'Non-ATM' }}</div>
                                </td>
                                <td class="py-3 pr-3 text-right font-bold text-slate-800 tabular-nums">
                                    Rp {{ number_format($jurnal->nominal_transaksi, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-center">
                                    @php
                                        $st = strtolower($jurnal->status);
                                    @endphp
                                    @if($st == 'success')
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-700">Success</span>
                                    @elseif($st == 'menunggu')
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-700">Menunggu</span>
                                    @elseif($st == 'done')
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-sky-100 text-sky-700">Done</span>
                                    @elseif($st == 'rejected')
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-700">Rejected</span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-700">{{ $jurnal->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-xs text-slate-400">
                                    Belum ada data jurnal keluhan nasabah yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Quick Access Navigation Buttons -->
            <div class="pt-4 mt-4 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a href="{{ route('jurnal.create') }}" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-brand-blue text-xs font-semibold border border-slate-200 transition-colors">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span>Input Keluhan</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-brand-blue text-xs font-semibold border border-slate-200 transition-colors">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    <span>Laporan Rekap</span>
                </a>
                <a href="{{ route('atm.index') }}" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-brand-blue text-xs font-semibold border border-slate-200 transition-colors col-span-2 sm:col-span-1">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect></svg>
                    <span>Monitoring ATM</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
