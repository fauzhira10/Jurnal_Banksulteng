@extends('layouts.app')

@section('title', 'Rekap Laporan Keluhan Nasabah - PT Bank Sulteng')

@push('styles')
<style>
    /* Hero Header */
    .laporan-hero {
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

    .laporan-hero h1 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 6px 0;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .laporan-hero p {
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

    /* Filter Card Ultra Modern */
    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px -3px rgba(10, 37, 64, 0.05), 0 2px 6px -2px rgba(10, 37, 64, 0.03);
        transition: all 0.2s ease;
    }

    .filter-card:hover {
        box-shadow: 0 8px 25px -4px rgba(10, 37, 64, 0.08), 0 4px 10px -2px rgba(10, 37, 64, 0.04);
        border-color: #cbd5e1;
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

    .filter-card-subtitle {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    .filter-grid-modern {
        display: grid;
        grid-template-columns: 1fr 1.6fr 1.3fr auto;
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
        letter-spacing: 0.2px;
    }

    .filter-control-group label svg {
        color: #0284c7;
        opacity: 0.9;
    }

    .custom-select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .custom-select-wrapper select {
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
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .custom-select-wrapper select:hover {
        background-color: #ffffff;
        border-color: #cbd5e1;
    }

    .custom-select-wrapper select:focus {
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
        background: linear-gradient(135deg, #0a2540 0%, #1d4ed8 100%);
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
        border-color: #cbd5e1;
    }

    /* Month Selector Tabs */
    .month-tabs-wrapper {
        background: #ffffff;
        border: 1px solid var(--bs-gray-200);
        border-radius: var(--bs-radius);
        padding: 12px 14px;
        margin-bottom: 24px;
        box-shadow: var(--bs-shadow-sm);
    }

    .month-tabs-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--bs-gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .month-pills-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 8px;
    }

    .month-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 8px 4px;
        border-radius: 8px;
        border: 1px solid var(--bs-gray-200);
        background: #f8fafc;
        color: var(--bs-navy);
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        user-select: none;
    }

    .month-pill:hover {
        background: #e0f2fe;
        border-color: #38bdf8;
        transform: translateY(-2px);
    }

    .month-pill.active {
        background: linear-gradient(135deg, var(--bs-navy) 0%, #1e40af 100%);
        border-color: var(--bs-navy);
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(10, 37, 64, 0.25);
    }

    .month-pill .m-name {
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .month-pill .m-count {
        font-size: 11px;
        font-weight: 600;
        margin-top: 2px;
        padding: 1px 6px;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.06);
    }

    .month-pill.active .m-count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Drilldown Month Card */
    .drilldown-card {
        background: #ffffff;
        border: 1px solid #bae6fd;
        border-left: 4px solid var(--bs-navy);
        border-radius: var(--bs-radius);
        padding: 18px 22px;
        margin-bottom: 24px;
        box-shadow: var(--bs-shadow-sm);
        display: grid;
        grid-template-columns: 1.5fr 2.5fr auto;
        gap: 20px;
        align-items: center;
    }

    .drilldown-stat h3 {
        margin: 0 0 4px 0;
        font-size: 14px;
        color: var(--bs-gray-500);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .drilldown-stat .stat-num {
        font-size: 32px;
        font-weight: 800;
        color: var(--bs-navy);
        line-height: 1.1;
    }

    .rank-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .rank-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12.5px;
        gap: 10px;
    }

    .rank-label {
        font-weight: 600;
        color: #334155;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 260px;
    }

    .rank-bar-bg {
        flex-grow: 1;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }

    .rank-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #0284c7, #0ea5e9);
        border-radius: 3px;
    }

    .rank-badge {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--bs-navy);
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 12px;
    }

    /* Master Report Table */
    .table-report-card {
        background: #ffffff;
        border: 1px solid var(--bs-gray-200);
        border-radius: var(--bs-radius);
        box-shadow: var(--bs-shadow-sm);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .table-report-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--bs-gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        background: #f8fafc;
    }

    .table-report-header h2 {
        font-size: 15px;
        font-weight: 700;
        color: var(--bs-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-responsive-matrix {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .matrix-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        text-align: left;
    }

    .matrix-table th, .matrix-table td {
        padding: 9px 10px;
        border: 1px solid #cbd5e1;
        vertical-align: middle;
    }

    .matrix-table thead th {
        background-color: #f1f5f9;
        color: var(--bs-navy);
        font-weight: 700;
        text-align: center;
        font-size: 11.5px;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    /* Custom Theme Headers for Bank Sulteng */
    .th-group-sulteng {
        background-color: #d9ead3 !important;
        color: #1e3a1e !important;
        font-weight: 700;
    }

    .th-group-lain {
        background-color: #bae6fd !important;
        color: #03446a !important;
        font-weight: 700;
    }

    .tr-group-header-sulteng td {
        background-color: #eaf4e7;
        font-weight: 700;
        color: #14532d;
    }

    .tr-group-header-lain td {
        background-color: #e0f2fe;
        font-weight: 700;
        color: #0369a1;
    }

    .matrix-cell-num {
        text-align: center;
        font-variant-numeric: tabular-nums;
        font-weight: 500;
    }

    .matrix-cell-num.has-val {
        font-weight: 700;
        color: var(--bs-navy);
    }

    .matrix-cell-num.is-active-col {
        background-color: rgba(14, 165, 233, 0.08);
        border-left: 2px solid #0ea5e9;
        border-right: 2px solid #0ea5e9;
    }

    .matrix-cell-zero {
        color: #94a3b8;
        font-weight: 400;
    }

    .matrix-cell-link {
        color: inherit;
        text-decoration: none;
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        transition: background 0.15s;
    }

    .matrix-cell-link:hover {
        background-color: #e0f2fe;
        color: #0284c7;
        text-decoration: underline;
    }

    .matrix-row-total {
        background-color: #f8fafc;
        font-weight: 700;
        text-align: center;
        color: var(--bs-navy);
    }

    .tr-footer-total td {
        background-color: #fef08a !important;
        color: #713f12 !important;
        font-weight: 800;
        font-size: 13px;
        border-top: 2px solid #ca8a04;
    }

    @media (max-width: 1024px) {
        .month-pills-grid {
            grid-template-columns: repeat(6, 1fr);
        }
        .filter-grid-modern {
            grid-template-columns: 1fr 1fr;
        }
        .drilldown-card {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .month-pills-grid {
            grid-template-columns: repeat(4, 1fr);
        }
        .filter-grid-modern {
            grid-template-columns: 1fr;
        }
        .filter-card {
            padding: 16px;
        }
        .filter-btn-group {
            width: 100%;
        }
        .btn-filter-apply, .btn-filter-reset {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Header -->
<div class="laporan-hero">
    <div>
        <h1>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
            Laporan Penyelesaian Keluhan Nasabah
        </h1>
        <p>Rekapitulasi statistik penyelesaian pengaduan keluhan nasabah per bulan & tahunan PT Bank Sulteng.</p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px;">
        <span class="hero-badge-year">Tahun Periode: {{ $selectedYear }}</span>
        <a href="{{ route('laporan.export_excel', ['tahun' => $selectedYear, 'master_cabang_id' => $selectedCabang, 'status' => $selectedStatus]) }}" 
           class="btn btn-sm" 
           style="background: #ffffff; color: var(--bs-navy); font-weight: 700; border: none; box-shadow: var(--bs-shadow-sm);"
           title="Unduh file Excel (.xlsx) dengan tata letak matriks 12 bulan identik">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Export Excel (.xlsx)
        </a>
    </div>
</div>

<!-- Filter Bar Modern & Rapi -->
<div class="filter-card">
    <div class="filter-card-header">
        <div class="filter-card-title">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            <span>Parameter Filter Laporan</span>
        </div>
        <div class="filter-card-subtitle">
            Saring data matriks berdasarkan tahun laporan, kantor cabang, dan status penyelesaian keluhan
        </div>
    </div>

    <form action="{{ route('laporan.index') }}" method="GET" id="laporanFilterForm">
        <input type="hidden" name="bulan" id="hiddenBulanInput" value="{{ $selectedMonth }}">
        
        <div class="filter-grid-modern">
            <!-- Pilihan Tahun -->
            <div class="filter-control-group">
                <label for="filterTahun">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    Tahun Laporan
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
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pilihan Cabang -->
            <div class="filter-control-group">
                <label for="filterCabang">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V7l8-4v18"></path>
                        <path d="M19 21V11l-6-4"></path>
                    </svg>
                    Kantor Cabang
                </label>
                <div class="custom-select-wrapper">
                    <select name="master_cabang_id" id="filterCabang" onchange="this.form.submit()">
                        <option value="">-- Seluruh Kantor Cabang & KCP --</option>
                        @foreach($cabangs as $c)
                            <option value="{{ $c->id }}" {{ $selectedCabang == $c->id ? 'selected' : '' }}>
                                {{ $c->kode_cabang }} - {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                    <div class="select-chevron">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pilihan Status -->
            <div class="filter-control-group">
                <label for="filterStatus">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    Status Keluhan
                </label>
                <div class="custom-select-wrapper">
                    <select name="status" id="filterStatus" onchange="this.form.submit()">
                        <option value="">-- Semua Status Keluhan --</option>
                        <option value="Done" {{ $selectedStatus === 'Done' ? 'selected' : '' }}>Done (Selesai)</option>
                        <option value="Success" {{ $selectedStatus === 'Success' ? 'selected' : '' }}>Success (Berhasil)</option>
                        <option value="Menunggu" {{ $selectedStatus === 'Menunggu' ? 'selected' : '' }}>Menunggu (Dalam Proses)</option>
                        <option value="Rejected" {{ $selectedStatus === 'Rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                        <option value="-" {{ $selectedStatus === '-' ? 'selected' : '' }}>- (Belum Ditentukan)</option>
                    </select>
                    <div class="select-chevron">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="filter-btn-group">
                <button type="submit" class="btn-filter-apply">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    Terapkan
                </button>
                <a href="{{ route('laporan.index', ['tahun' => $selectedYear]) }}" class="btn-filter-reset" title="Reset Filter Cabang & Status">
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

<!-- Month Tabs Navigation Bar -->
<div class="month-tabs-wrapper">
    <div class="month-tabs-label">
        <span>PILIH BULAN UNTUK DETAIL DRILL-DOWN</span>
        <span style="font-weight: 500; font-size: 11.5px; color: var(--bs-gray-500);">Klik salah satu bulan untuk melihat rincian & grafik</span>
    </div>
    <div class="month-pills-grid">
        @for($m = 1; $m <= 12; $m++)
            @php
                $mTotal = $colTotals[$m] ?? 0;
                $isActive = $selectedMonth == $m;
            @endphp
            <div class="month-pill {{ $isActive ? 'active' : '' }}" onclick="selectMonthTab({{ $m }})">
                <span class="m-name">{{ strtoupper(substr($monthNames[$m], 0, 3)) }}</span>
                <span class="m-count">{{ $mTotal }}</span>
            </div>
        @endfor
    </div>
</div>

<!-- Drill-down Card untuk Bulan Terpilih -->
<div class="drilldown-card">
    <!-- Stat 1: Total Bulan -->
    <div class="drilldown-stat">
        <h3>Total Klaim {{ $activeMonthName }} {{ $selectedYear }}</h3>
        <div class="stat-num">{{ number_format($activeMonthTotal, 0, ',', '.') }}</div>
        <div style="font-size: 12px; color: var(--bs-gray-500); margin-top: 4px;">
            Dari total <strong>{{ number_format($grandTotal, 0, ',', '.') }}</strong> klaim tahun {{ $selectedYear }}
        </div>
    </div>

    <!-- Stat 2: Top Jenis Keluhan Terbanyak -->
    <div>
        <div style="font-size: 12px; font-weight: 700; color: var(--bs-navy); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.4px;">
            Top Kategori Keluhan Bulan {{ $activeMonthName }}
        </div>
        @if(count($monthCategoryRank) > 0)
            <div class="rank-list">
                @foreach(array_slice($monthCategoryRank, 0, 3) as $rk)
                    <div class="rank-item">
                        <span class="rank-label" title="{{ $rk['label'] }}">{{ $rk['label'] }}</span>
                        <div class="rank-bar-bg">
                            <div class="rank-bar-fill" style="width: {{ $rk['percentage'] }}%;"></div>
                        </div>
                        <span class="rank-badge">{{ $rk['count'] }} ({{ $rk['percentage'] }}%)</span>
                    </div>
                @endforeach
            </div>
        @else
            <div style="font-size: 12.5px; color: var(--bs-gray-500); font-style: italic;">
                Tidak ada data keluhan tercatat di bulan {{ $activeMonthName }} {{ $selectedYear }}.
            </div>
        @endif
    </div>

    <!-- Aksi Drilldown ke Halaman Data Keluhan -->
    <div style="display: flex; flex-direction: column; gap: 8px; justify-content: center;">
        @php
            $startDate = sprintf('%04d-%02d-01', $selectedYear, $selectedMonth);
            $lastDay = date('t', strtotime($startDate));
            $endDate = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $lastDay);
        @endphp
        <a href="{{ route('jurnal.index', ['tgl_dari' => $startDate, 'tgl_sampai' => $endDate, 'master_cabang_id' => $selectedCabang, 'status' => $selectedStatus]) }}" 
           class="btn btn-primary btn-sm" 
           style="white-space: nowrap;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            Buka Transaksi {{ $activeMonthName }}
        </a>
    </div>
</div>

<!-- Matriks Tabel Laporan 12 Bulan (Identik Format Excel Bank Sulteng) -->
<div class="table-report-card">
    <div class="table-report-header">
        <h2>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
            Matriks Rekapitulasi Tahunan (Januari - Desember {{ $selectedYear }})
        </h2>
        <div style="font-size: 12.5px; color: var(--bs-gray-500);">
            Total Seluruh Klaim: <strong style="color: var(--bs-navy); font-size: 14px;">{{ number_format($grandTotal, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="table-responsive-matrix">
        <table class="matrix-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 40px;">NO</th>
                    <th rowspan="2" style="width: 35px;"></th>
                    <th rowspan="2" style="min-width: 240px; text-align: left;">JENIS KLAIM</th>
                    @for($m = 1; $m <= 12; $m++)
                        <th style="min-width: 75px;" class="{{ $selectedMonth == $m ? 'th-active-col' : '' }}">
                            {{ strtoupper($monthNames[$m]) }}
                        </th>
                    @endfor
                    <th rowspan="2" style="min-width: 85px; background: #e2e8f0;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($structure as $key => $meta)
                    @php
                        $isHeaderSulteng = ($meta['group_header'] ?? false) && ($meta['theme'] ?? '') !== 'blue';
                        $isHeaderLain = ($meta['group_header'] ?? false) && ($meta['theme'] ?? '') === 'blue';
                        $isSingleSulteng = ($meta['single'] ?? false) && ($meta['theme'] ?? '') !== 'blue';
                        $isSingleLain = ($meta['single'] ?? false) && ($meta['theme'] ?? '') === 'blue';
                        $rowTotal = $rowTotals[$key] ?? 0;
                    @endphp

                    <tr class="{{ $isHeaderSulteng ? 'tr-group-header-sulteng' : ($isHeaderLain ? 'tr-group-header-lain' : '') }}">
                        <!-- NO -->
                        <td style="text-align: center; font-weight: bold;">
                            {{ $meta['no'] }}
                        </td>

                        <!-- SUB (A, B, C, D...) -->
                        <td style="text-align: center; font-weight: 600; color: var(--bs-gray-600);">
                            {{ $meta['sub'] }}
                        </td>

                        <!-- JENIS KLAIM -->
                        <td style="{{ ($meta['group_header'] ?? false) || ($meta['single'] ?? false) ? 'font-weight: 700;' : 'padding-left: 20px;' }}">
                            @if($meta['group_header'] ?? false)
                                <span style="text-transform: uppercase;">{{ $meta['group'] }} - {{ $meta['label'] }}</span>
                            @elseif($meta['single'] ?? false)
                                <span style="text-transform: uppercase;">{{ $meta['label'] }}</span>
                            @else
                                <span>{{ $meta['label'] }}</span>
                            @endif
                        </td>

                        <!-- 12 MONTH VALUES -->
                        @for($m = 1; $m <= 12; $m++)
                            @php
                                $val = $matrix[$key][$m] ?? 0;
                                $isActiveCol = $selectedMonth == $m;
                                $sDate = sprintf('%04d-%02d-01', $selectedYear, $m);
                                $eDate = sprintf('%04d-%02d-%02d', $selectedYear, $m, date('t', strtotime($sDate)));
                            @endphp
                            <td class="matrix-cell-num {{ $val > 0 ? 'has-val' : 'matrix-cell-zero' }} {{ $isActiveCol ? 'matrix-cell-num is-active-col' : '' }}">
                                @if($val > 0)
                                    <a href="{{ route('jurnal.index', ['tgl_dari' => $sDate, 'tgl_sampai' => $eDate, 'master_cabang_id' => $selectedCabang, 'status' => $selectedStatus]) }}" 
                                       class="matrix-cell-link" 
                                       title="Lihat {{ $val }} data {{ $meta['label'] }} bulan {{ $monthNames[$m] }}">
                                        {{ $val }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        @endfor

                        <!-- ROW TOTAL -->
                        <td class="matrix-row-total">
                            {{ $rowTotal > 0 ? $rowTotal : '-' }}
                        </td>
                    </tr>
                @endforeach

                <!-- FOOTER TOTAL KLAIM -->
                <tr class="tr-footer-total">
                    <td colspan="3" style="text-align: center; letter-spacing: 0.5px;">
                        TOTAL KLAIM
                    </td>
                    @for($m = 1; $m <= 12; $m++)
                        @php
                            $cTot = $colTotals[$m] ?? 0;
                            $isActiveCol = $selectedMonth == $m;
                        @endphp
                        <td style="text-align: center;">
                            {{ $cTot > 0 ? $cTot : '-' }}
                        </td>
                    @endfor
                    <td style="text-align: center; font-size: 14px;">
                        {{ $grandTotal > 0 ? $grandTotal : '-' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectMonthTab(monthNum) {
        document.getElementById('hiddenBulanInput').value = monthNum;
        document.getElementById('laporanFilterForm').submit();
    }
</script>
@endpush
