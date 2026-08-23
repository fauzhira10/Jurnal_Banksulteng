@extends('layouts.app')

@section('title', 'Monitoring Mesin ATM Bermasalah - PT Bank Sulteng')

@push('styles')
<style>
    /* Hero Header */
    .atm-hero {
        background: linear-gradient(135deg, #0a2540 0%, #1e3a8a 50%, #0369a1 100%);
        border-radius: var(--bs-radius-lg);
        padding: 24px 28px;
        color: #ffffff;
        margin-bottom: 24px;
        box-shadow: var(--bs-shadow-md);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .atm-hero h1 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 6px 0;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .atm-hero p {
        margin: 0;
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.85);
    }

    .hero-badge-year {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(8px);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: #fef08a;
    }

    /* KPI Cards Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 15px -3px rgba(10, 37, 64, 0.05);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(10, 37, 64, 0.1);
    }

    .kpi-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .kpi-icon-blue { background: #e0f2fe; color: #0284c7; }
    .kpi-icon-amber { background: #fef3c7; color: #d97706; }
    .kpi-icon-emerald { background: #dcfce7; color: #16a34a; }
    .kpi-icon-purple { background: #f3e8ff; color: #9333ea; }

    .kpi-info h4 {
        margin: 0 0 4px 0;
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .kpi-info .kpi-num {
        font-size: 22px;
        font-weight: 800;
        color: var(--bs-navy);
        line-height: 1.2;
    }

    .kpi-info .kpi-sub {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 3px;
    }

    /* Filter Card */
    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px -3px rgba(10, 37, 64, 0.05);
    }

    .filter-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 8px;
    }

    .filter-card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--bs-navy);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-card-title svg {
        color: #0284c7;
    }

    .filter-grid-atm {
        display: grid;
        grid-template-columns: 1fr 1.2fr 1.5fr 1.5fr auto;
        gap: 16px;
        align-items: flex-end;
    }

    .filter-control-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .filter-control-group label {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-control-group label svg {
        color: #0284c7;
    }

    .custom-select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .custom-select-wrapper select, .filter-control-group input[type="text"] {
        width: 100%;
        height: 42px;
        padding: 0 34px 0 14px;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        appearance: none;
        -webkit-appearance: none;
        transition: all 0.2s ease;
    }

    .filter-control-group input[type="text"] {
        padding-right: 14px;
    }

    .custom-select-wrapper select:hover, .filter-control-group input[type="text"]:hover {
        background-color: #ffffff;
        border-color: #cbd5e1;
    }

    .custom-select-wrapper select:focus, .filter-control-group input[type="text"]:focus {
        background-color: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        outline: none;
    }

    .custom-select-wrapper .select-chevron {
        position: absolute;
        right: 12px;
        pointer-events: none;
        color: #64748b;
    }

    .filter-btn-group {
        display: flex;
        align-items: center;
        gap: 10px;
        height: 42px;
    }

    .btn-filter-apply {
        height: 42px;
        padding: 0 20px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--bs-navy) 0%, #1e40af 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(10, 37, 64, 0.2);
        transition: all 0.2s ease;
    }

    .btn-filter-apply:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(10, 37, 64, 0.3);
    }

    .btn-filter-reset {
        height: 42px;
        padding: 0 16px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        border: 1.5px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-filter-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* Table Card */
    .table-leaderboard-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 15px -3px rgba(10, 37, 64, 0.05);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .table-leaderboard-header {
        padding: 18px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-leaderboard-header h2 {
        font-size: 16px;
        font-weight: 700;
        color: var(--bs-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .leaderboard-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .leaderboard-table th, .leaderboard-table td {
        padding: 13px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .leaderboard-table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .leaderboard-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .terminal-name {
        font-weight: 700;
        color: var(--bs-navy);
        font-size: 14px;
    }

    .complaint-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 20px;
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 800;
        font-size: 13.5px;
    }

    /* Per Page Selector */
    .per-page-select-box {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #64748b;
    }

    .per-page-select-box select {
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 12.5px;
        font-weight: 600;
        background: #ffffff;
        color: #1e293b;
        cursor: pointer;
    }

    /* Pagination Bar */
    .pagination-bar-wrapper {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        font-size: 13px;
        color: #64748b;
    }

    .pagination-pills {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pagination-pills a, .pagination-pills span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 8px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        transition: all 0.15s ease;
    }

    .pagination-pills a:hover {
        background: #e0f2fe;
        border-color: #38bdf8;
        color: #0284c7;
    }

    .pagination-pills span.current {
        background: var(--bs-navy);
        border-color: var(--bs-navy);
        color: #ffffff;
    }

    .pagination-pills span.disabled {
        color: #cbd5e1;
        background: #f8fafc;
        cursor: not-allowed;
    }

    @media (max-width: 1024px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .filter-grid-atm {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
        .filter-grid-atm {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Header -->
<div class="atm-hero">
    <div>
        <h1>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                <line x1="6" y1="6" x2="6.01" y2="6"></line>
                <line x1="6" y1="18" x2="6.01" y2="18"></line>
            </svg>
            Monitoring Keluhan Mesin ATM
        </h1>
        <p>Identifikasi dan evaluasi unit mesin ATM / terminal yang paling sering mengalami kendala transaksi perbankan.</p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px;">
        <span class="hero-badge-year">Periode: {{ $selectedMonth ? $monthNames[$selectedMonth] . ' ' : '' }}{{ $selectedYear }}</span>
        <a href="{{ route('atm.export_excel', ['tahun' => $selectedYear, 'bulan' => $selectedMonth, 'master_cabang_id' => $selectedCabang, 'q' => $searchKeyword]) }}" 
           class="btn btn-sm" 
           style="background: #ffffff; color: var(--bs-navy); font-weight: 700; border: none; box-shadow: var(--bs-shadow-sm);"
           title="Unduh laporan performa mesin ATM ke Excel (.xlsx)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Export Excel (.xlsx)
        </a>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="kpi-grid">
    <!-- Total Mesin -->
    <div class="kpi-card">
        <div class="kpi-icon-box kpi-icon-blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
            </svg>
        </div>
        <div class="kpi-info">
            <h4>Mesin ATM Terdampak</h4>
            <div class="kpi-num">{{ number_format($totalMesin, 0, ',', '.') }} Unit</div>
            <div class="kpi-sub">Memiliki riwayat keluhan</div>
        </div>
    </div>

    <!-- Total Kasus Keluhan -->
    <div class="kpi-card">
        <div class="kpi-icon-box kpi-icon-amber">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div class="kpi-info">
            <h4>Total Kasus Keluhan</h4>
            <div class="kpi-num">{{ number_format($totalKasus, 0, ',', '.') }} Kasus</div>
            <div class="kpi-sub">Sepanjang periode terpilih</div>
        </div>
    </div>

    <!-- Mesin ATM #1 Paling Bermasalah -->
    <div class="kpi-card">
        <div class="kpi-icon-box kpi-icon-purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
        </div>
        <div class="kpi-info">
            <h4>Keluhan Terbanyak</h4>
            <div class="kpi-num" style="font-size: 17px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;" title="{{ $topAtm['terminal'] ?? '-' }}">
                {{ $topAtm['terminal'] ?? '-' }}
            </div>
            <div class="kpi-sub">{{ $topAtm ? $topAtm['total_keluhan'] . ' Keluhan' : 'Tidak ada data' }}</div>
        </div>
    </div>

    <!-- Total Nominal Masalah -->
    <div class="kpi-card">
        <div class="kpi-icon-box kpi-icon-emerald">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <div class="kpi-info">
            <h4>Total Nominal Masalah</h4>
            <div class="kpi-num" style="font-size: 18px;">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
            <div class="kpi-sub">Uang transaksi terkait</div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-card">
    <div class="filter-card-header">
        <div class="filter-card-title">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            <span>Parameter Filter Data Mesin ATM</span>
        </div>
        <div style="font-size: 12px; color: #64748b;">
            Pilih tahun, bulan, cabang, atau cari kode mesin spesifik
        </div>
    </div>

    <form action="{{ route('atm.index') }}" method="GET" id="atmFilterForm">
        <input type="hidden" name="per_page" value="{{ $perPage }}">
        <div class="filter-grid-atm">
            <!-- Tahun -->
            <div class="filter-control-group">
                <label for="filterTahun">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                    </svg>
                    Tahun
                </label>
                <div class="custom-select-wrapper">
                    <select name="tahun" id="filterTahun" onchange="this.form.submit()">
                        @foreach($availableYears as $yr)
                            <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>
                                Tahun {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                    <div class="select-chevron">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>
            </div>

            <!-- Bulan -->
            <div class="filter-control-group">
                <label for="filterBulan">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Bulan
                </label>
                <div class="custom-select-wrapper">
                    <select name="bulan" id="filterBulan" onchange="this.form.submit()">
                        <option value="">-- Semua Bulan --</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ $monthNames[$m] }}
                            </option>
                        @endfor
                    </select>
                    <div class="select-chevron">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>
            </div>

            <!-- Cabang -->
            <div class="filter-control-group">
                <label for="filterCabang">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V7l8-4v18"></path>
                    </svg>
                    Kantor Cabang
                </label>
                <div class="custom-select-wrapper">
                    <select name="master_cabang_id" id="filterCabang" onchange="this.form.submit()">
                        <option value="">-- Seluruh Kantor Cabang --</option>
                        @foreach($cabangs as $c)
                            <option value="{{ $c->id }}" {{ $selectedCabang == $c->id ? 'selected' : '' }}>
                                {{ $c->kode_cabang }} - {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                    <div class="select-chevron">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>
            </div>

            <!-- Live Search Terminal -->
            <div class="filter-control-group">
                <label for="searchKeyword">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    Cari Terminal / Mesin
                </label>
                <input type="text" name="q" id="searchKeyword" value="{{ $searchKeyword }}" placeholder="Contoh: ATM-001 / POSO...">
            </div>

            <!-- Tombol Aksi -->
            <div class="filter-btn-group">
                <button type="submit" class="btn-filter-apply">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    Cari
                </button>
                <a href="{{ route('atm.index', ['tahun' => $selectedYear]) }}" class="btn-filter-reset" title="Reset Filter">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="table-leaderboard-card">
    <div class="table-leaderboard-header">
        <h2>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
            </svg>
            Daftar Mesin ATM & Jumlah Keluhan
        </h2>
        
        <!-- Pilihan Tampilkan Baris (10, 50, 100) -->
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div class="per-page-select-box">
                <span>Tampilkan:</span>
                <select onchange="changePerPage(this.value)">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 data</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 data</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 data</option>
                </select>
            </div>
            <div style="font-size: 13px; color: #64748b;">
                Total: <strong>{{ number_format($totalMesin, 0, ',', '.') }}</strong> unit mesin
            </div>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th>Kode / Nama Terminal ATM</th>
                    <th>Kantor Cabang Pengelola</th>
                    <th style="text-align: center;">Jumlah Keluhan</th>
                    <th style="text-align: right;">Total Nominal (Rp)</th>
                    <th>Jenis Gangguan Terbanyak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($atms as $atm)
                    <tr>
                        <!-- No Urut Biasa -->
                        <td style="text-align: center; font-weight: 600; color: #64748b;">
                            {{ ($atms->currentPage() - 1) * $atms->perPage() + $loop->iteration }}
                        </td>

                        <!-- Kode Terminal -->
                        <td>
                            <div class="terminal-name">{{ $atm['terminal'] }}</div>
                        </td>

                        <!-- Kantor Cabang -->
                        <td>
                            <div style="font-weight: 600; color: #334155;">{{ $atm['cabang_nama'] }}</div>
                            <div style="font-size: 11.5px; color: #94a3b8;">Kode Cabang: {{ $atm['cabang_kode'] }}</div>
                        </td>

                        <!-- Jumlah Keluhan -->
                        <td style="text-align: center;">
                            <span class="complaint-count-badge">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                {{ $atm['total_keluhan'] }} Keluhan
                            </span>
                        </td>

                        <!-- Total Nominal -->
                        <td style="text-align: right; font-weight: 700; color: var(--bs-navy); font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($atm['total_nominal'], 0, ',', '.') }}
                        </td>

                        <!-- Jenis Gangguan -->
                        <td>
                            <span style="color: #475569; font-size: 12.5px;">
                                {{ $atm['dominant_issues'] ?: '-' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 10px; opacity: 0.5;">
                                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            </svg>
                            <div style="font-weight: 600; font-size: 15px; color: #475569;">Tidak Ada Data Keluhan Mesin ATM</div>
                            <div style="font-size: 12.5px; margin-top: 4px;">Tidak ditemukan mesin ATM yang sesuai dengan filter pencarian yang Anda tentukan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Bar -->
    @if($atms->total() > 0)
        <div class="pagination-bar-wrapper">
            <div class="pagination-info">
                Menampilkan <strong>{{ $atms->firstItem() ?? 0 }}</strong> sampai <strong>{{ $atms->lastItem() ?? 0 }}</strong> dari <strong>{{ $atms->total() }}</strong> total mesin ATM
            </div>

            @if($atms->hasPages())
                <div class="pagination-pills">
                    {{-- Previous Page Link --}}
                    @if ($atms->onFirstPage())
                        <span class="disabled">‹</span>
                    @else
                        <a href="{{ $atms->previousPageUrl() }}" rel="prev">‹</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($atms->getUrlRange(max(1, $atms->currentPage() - 2), min($atms->lastPage(), $atms->currentPage() + 2)) as $page => $url)
                        @if ($page == $atms->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($atms->hasMorePages())
                        <a href="{{ $atms->nextPageUrl() }}" rel="next">›</a>
                    @else
                        <span class="disabled">›</span>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function changePerPage(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
</script>
@endpush
