@extends('layouts.app')

@section('title', 'Data Keluhan Nasabah')
@section('page_title', 'Data Jurnal Keluhan')
@section('page_subtitle', 'Rekapitulasi dan pencarian data keluhan transaksi nasabah Bank Sulteng')

@push('styles')
<style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--bs-gray-200);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        color: inherit;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-count {
        font-size: 22px;
        font-weight: 800;
        color: var(--bs-gray-900);
        line-height: 1.2;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--bs-gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    /* Icon colors */
    .stat-total .stat-icon { background-color: var(--bs-blue-light); color: var(--bs-blue); }
    .stat-menunggu .stat-icon { background-color: var(--bs-warning-light); color: var(--bs-warning); }
    .stat-success .stat-icon { background-color: #e0f2fe; color: #0284c7; }
    .stat-done .stat-icon { background-color: var(--bs-success-light); color: var(--bs-success); }
    .stat-rejected .stat-icon { background-color: var(--bs-danger-light); color: var(--bs-danger); }

    /* Filter Form */
    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1.3fr 1fr 1fr;
        gap: 14px;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: var(--bs-gray-600);
    }

    .form-control {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid var(--bs-gray-300);
        border-radius: var(--radius-md);
        font-size: 13.5px;
        background-color: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: var(--bs-blue);
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.15);
    }

    /* Live Search Input & Indicators */
    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        color: var(--bs-gray-400);
        pointer-events: none;
        display: flex;
        align-items: center;
    }

    .search-input {
        padding-left: 36px !important;
        padding-right: 32px !important;
    }

    .btn-clear-search {
        position: absolute;
        right: 8px;
        background: var(--bs-gray-200);
        border: none;
        color: var(--bs-gray-600);
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-clear-search:hover {
        background: var(--bs-gray-300);
        color: var(--bs-gray-900);
    }

    .live-search-badge {
        font-size: 11px;
        font-weight: 700;
        color: #0284c7;
        background: #e0f2fe;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .pulse-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #0284c7;
        animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.3; transform: scale(0.7); }
    }

    /* Yellow Match Highlight */
    mark.highlight-yellow, .highlight-yellow {
        background-color: #fef08a !important; /* Soft golden yellow */
        color: #854d0e !important;           /* High-contrast amber text */
        font-weight: 700 !important;
        padding: 1px 4px !important;
        border-radius: 3px !important;
        border: 1px solid #fde047 !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        display: inline;
    }

    /* Data Table */
    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13.5px;
    }

    .custom-table th {
        background-color: var(--bs-gray-50);
        color: var(--bs-gray-700);
        font-weight: 700;
        text-align: left;
        padding: 14px 16px;
        border-bottom: 2px solid var(--bs-gray-200);
        white-space: nowrap;
    }

    .custom-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--bs-gray-200);
        vertical-align: middle;
        color: var(--bs-gray-700);
    }

    .custom-table tr:hover td {
        background-color: #f8fafc;
    }

    .nasabah-title {
        font-weight: 700;
        color: var(--bs-navy);
        font-size: 14px;
    }

    .nasabah-sub {
        font-size: 12px;
        color: var(--bs-gray-500);
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .nominal-badge {
        font-weight: 700;
        color: #047857;
        font-size: 13.5px;
    }

    /* Action Buttons */
    .btn-danger-sm {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 6px 10px;
        font-size: 12px;
        border-radius: var(--radius-sm);
        background-color: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-danger-sm:hover {
        background-color: #ffe4e6;
        color: #be123c;
        border-color: #fda4af;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: var(--bs-gray-500);
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        color: var(--bs-gray-400);
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 17px;
        font-weight: 700;
        color: var(--bs-gray-800);
        margin-bottom: 6px;
    }

    .empty-state p {
        font-size: 13.5px;
        max-width: 400px;
        margin: 0 auto 16px;
    }

    /* Modal Backdrop & Content */
    .modal-backdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.6);
        z-index: 1100;
        backdrop-filter: blur(3px);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-backdrop.show {
        display: flex;
    }

    .modal-content {
        background: #ffffff;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 750px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-xl);
        animation: modalFadeIn 0.25s ease-out;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-header {
        padding: 24px 26px 18px;
        border-bottom: 1px solid var(--bs-gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bs-gray-50);
    }

    .modal-header h3 {
        font-size: 17.5px;
        font-weight: 700;
        color: var(--bs-navy);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        line-height: 1.3;
    }

    .btn-close-modal {
        background: var(--bs-gray-100);
        border: 1px solid var(--bs-gray-300);
        cursor: pointer;
        color: var(--bs-gray-500);
        padding: 6px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-close-modal:hover {
        background: var(--bs-gray-200);
        color: var(--bs-gray-800);
        border-color: var(--bs-gray-400);
    }

    .modal-body {
        padding: 24px;
    }

    .detail-section {
        margin-bottom: 20px;
    }

    .detail-section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--bs-blue);
        border-bottom: 1px solid var(--bs-gray-200);
        padding-bottom: 6px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--bs-gray-500);
        margin-bottom: 3px;
    }

    .detail-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--bs-gray-800);
    }

    .detail-keterangan-box {
        background: var(--bs-gray-50);
        border: 1px solid var(--bs-gray-200);
        border-radius: var(--radius-md);
        padding: 12px 14px;
        font-size: 13px;
        line-height: 1.6;
        color: var(--bs-gray-700);
        white-space: pre-wrap;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--bs-gray-200);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: var(--bs-gray-50);
    }

    /* Pagination Styling */
    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        font-size: 13px;
        color: var(--bs-gray-500);
    }

    @media (max-width: 992px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<!-- Ringkasan Statistik -->
<div id="statsSection" class="stats-grid">
    <a href="{{ route('jurnal.index') }}" class="stat-card stat-total">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-count">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Keluhan</div>
        </div>
    </a>

    <a href="{{ route('jurnal.index', ['status' => 'Menunggu']) }}" class="stat-card stat-menunggu">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-count">{{ $stats['menunggu'] }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
    </a>

    <a href="{{ route('jurnal.index', ['status' => 'Success']) }}" class="stat-card stat-success">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-count">{{ $stats['success'] }}</div>
            <div class="stat-label">Success</div>
        </div>
    </a>

    <a href="{{ route('jurnal.index', ['status' => 'Done']) }}" class="stat-card stat-done">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-count">{{ $stats['done'] }}</div>
            <div class="stat-label">Done</div>
        </div>
    </a>

    <a href="{{ route('jurnal.index', ['status' => 'Rejected']) }}" class="stat-card stat-rejected">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-count">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </a>
</div>

<!-- Card Filter & Pencarian Otomatis -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            <span>Pencarian Otomatis & Filter Jurnal</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('jurnal.index') }}" id="btnResetFilter" class="btn btn-secondary btn-sm" style="display: {{ request()->hasAny(['q', 'status', 'master_cabang_id', 'tgl_dari', 'tgl_sampai']) ? 'inline-flex' : 'none' }};">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span>Reset Filter</span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('jurnal.index') }}" onsubmit="return false;">
            <div class="filter-grid">
                <!-- Search Keyword with Live Indicator -->
                <div class="filter-group">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <label for="searchInput">Pencarian Otomatis</label>
                        <span class="live-search-badge" id="searchBadge">
                            <span class="pulse-dot"></span> Live Detect
                        </span>
                    </div>
                    <div class="search-input-wrapper">
                        <span class="search-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" id="searchInput" name="q" value="{{ request('q') }}" class="form-control search-input" placeholder="Ketik nama, no resi, rekening, tiket..." autocomplete="off">
                        <button type="button" class="btn-clear-search" id="btnClearSearch" title="Hapus Pencarian" style="display: {{ request('q') ? 'flex' : 'none' }};">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Filter Status -->
                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus" name="status" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Success" {{ request('status') == 'Success' ? 'selected' : '' }}>Success</option>
                        <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Filter Cabang -->
                <div class="filter-group">
                    <label for="filterCabang">Kantor Cabang</label>
                    <select id="filterCabang" name="master_cabang_id" class="form-control">
                        <option value="">-- Semua Cabang --</option>
                        @foreach($cabangs as $c)
                            <option value="{{ $c->id }}" {{ request('master_cabang_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->kode_cabang }} - {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Dari -->
                <div class="filter-group">
                    <label for="filterTglDari">Tanggal Dari</label>
                    <input type="date" id="filterTglDari" name="tgl_dari" value="{{ request('tgl_dari') }}" class="form-control">
                </div>

                <!-- Tanggal Sampai -->
                <div class="filter-group">
                    <label for="filterTglSampai">Tanggal Sampai</label>
                    <input type="date" id="filterTglSampai" name="tgl_sampai" value="{{ request('tgl_sampai') }}" class="form-control">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Card Tabel Data Jurnal -->
<div class="card" id="tableCard">
    <div class="card-header">
        <div class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
            <span>Daftar Jurnal Keluhan Tersimpan</span>
        </div>
        <div id="totalCountBadge" style="font-size: 13px; color: var(--bs-gray-500);">
            Menampilkan <strong id="totalCountNum">{{ $jurnals->total() }}</strong> total data keluhan
        </div>
    </div>

    <div class="card-body" id="tableBodyWrapper" style="padding: 0;">
        @if($jurnals->count() > 0)
            <div class="table-container" id="tableContainer">
                <table class="custom-table" id="jurnalTable">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Tanggal</th>
                            <th>Data Nasabah</th>
                            <th>Kantor Cabang</th>
                            <th>Jenis Transaksi</th>
                            <th>Nominal Transaksi</th>
                            <th>Status</th>
                            <th style="text-align: center; width: 75px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jurnalTbody">
                        @foreach($jurnals as $index => $jurnal)
                            <tr class="jurnal-row" data-search="{{ strtolower($jurnal->nama_nasabah . ' ' . $jurnal->no_resi . ' ' . $jurnal->no_rekening . ' ' . $jurnal->no_kartu . ' ' . $jurnal->no_tiket . ' ' . ($jurnal->masterCabang->nama_cabang ?? '') . ' ' . ($jurnal->masterCabang->kode_cabang ?? '') . ' ' . ($jurnal->masterTransaksi->jenis_transaksi ?? '') . ' ' . ($jurnal->masterTransaksi->channel ?? '') . ' ' . $jurnal->status . ' ' . $jurnal->terminal_transaksi) }}">
                                <td style="text-align: center; font-weight: 600; color: var(--bs-gray-500);">
                                    {{ ($jurnals->currentPage() - 1) * $jurnals->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--bs-navy);">
                                        {{ \Carbon\Carbon::parse($jurnal->tgl_transaksi)->translatedFormat('d M Y') }}
                                    </div>
                                    <div style="font-size: 11.5px; color: var(--bs-gray-500); margin-top: 2px;">
                                        Terima: {{ \Carbon\Carbon::parse($jurnal->tgl_terima)->translatedFormat('d/m/Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="nasabah-title highlightable">{{ $jurnal->nama_nasabah }}</div>
                                    <div class="nasabah-sub">
                                        <span>Rek: <strong class="highlightable">{{ $jurnal->no_rekening }}</strong></span>
                                        <span>•</span>
                                        <span>Resi: <strong class="highlightable">{{ $jurnal->no_resi }}</strong></span>
                                    </div>
                                    @if($jurnal->no_tiket)
                                        <div style="font-size: 11px; color: var(--bs-blue); margin-top: 2px;">
                                            Tiket: <strong class="highlightable">{{ $jurnal->no_tiket }}</strong>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="highlightable" style="font-weight: 700; color: var(--bs-gray-800);">
                                        {{ $jurnal->masterCabang->nama_cabang ?? '-' }}
                                    </div>
                                    <div class="highlightable" style="font-size: 11.5px; color: var(--bs-gray-500); margin-top: 2px;">
                                        Kode: {{ $jurnal->masterCabang->kode_cabang ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="highlightable" style="font-weight: 600; color: var(--bs-navy);">
                                        {{ $jurnal->masterTransaksi->jenis_transaksi ?? '-' }}
                                    </div>
                                    <div style="margin-top: 4px;">
                                        <span class="badge badge-channel highlightable">
                                            {{ $jurnal->masterTransaksi->channel ?? 'UMUM' }}
                                        </span>
                                    </div>
                                </td>
                                <td style="white-space: nowrap;">
                                    <div class="nominal-badge highlightable" style="font-size: 14.5px;">
                                        Rp {{ number_format($jurnal->nominal_transaksi, 0, ',', '.') }}
                                    </div>
                                    <div style="font-size: 11.5px; color: var(--bs-gray-500); margin-top: 2px;">
                                        Admin: Rp {{ number_format($jurnal->masterTransaksi->biaya_admin ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match(strtolower($jurnal->status)) {
                                            'menunggu' => 'badge-menunggu',
                                            'success' => 'badge-success',
                                            'done' => 'badge-done',
                                            'rejected' => 'badge-rejected',
                                            default => 'badge-menunggu'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ $jurnal->status }}
                                    </span>
                                </td>
                                <td style="text-align: center; vertical-align: middle; white-space: nowrap;">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="showDetailModal({{ json_encode($jurnal) }})" title="Lihat Rincian & Aksi" style="padding: 6px 12px; font-size: 12.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                        </svg>
                                        <span>Detail</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationWrapper" style="padding: 16px 24px; border-top: 1px solid var(--bs-gray-200);">
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan halaman <strong>{{ $jurnals->currentPage() }}</strong> dari <strong>{{ $jurnals->lastPage() }}</strong>
                    </div>
                    <div class="pagination-links">
                        {{ $jurnals->links() }}
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state" id="emptyState">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                <h3>Tidak Ada Data Jurnal Keluhan</h3>
                <p>Tidak ditemukan data keluhan yang sesuai dengan kata kunci atau filter yang Anda pilih.</p>
                <div style="display: flex; justify-content: center; gap: 10px;">
                    <a href="{{ route('jurnal.index') }}" class="btn btn-secondary btn-sm">Reset Pencarian</a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Pop-Up Detail Jurnal -->
<div class="modal-backdrop" id="detailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Rincian Jurnal Keluhan Nasabah</span>
            </h3>
            <button class="btn-close-modal" onclick="closeDetailModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <!-- Informasi Nasabah -->
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Informasi Nasabah & Identitas</span>
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Nama Nasabah</div>
                        <div class="detail-value" id="modal_nama_nasabah">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nomor Rekening</div>
                        <div class="detail-value" id="modal_no_rekening">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nomor Resi / Trace</div>
                        <div class="detail-value" id="modal_no_resi" style="color: var(--bs-blue);">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nomor Kartu ATM/Debit</div>
                        <div class="detail-value" id="modal_no_kartu">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nomor Tiket CS</div>
                        <div class="detail-value" id="modal_no_tiket">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Cabang Pelapor / Transaksi</div>
                        <div class="detail-value" id="modal_cabang">-</div>
                    </div>
                </div>
            </div>

            <!-- Informasi Transaksi & Finansial -->
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <span>Informasi Transaksi & Finansial</span>
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Jenis Transaksi</div>
                        <div class="detail-value" id="modal_jenis_transaksi">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Channel Transaksi</div>
                        <div class="detail-value" id="modal_channel">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nominal Transaksi (Rp)</div>
                        <div class="detail-value" id="modal_nominal_transaksi" style="color: #047857; font-size: 16px;">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Biaya Admin</div>
                        <div class="detail-value" id="modal_biaya_admin">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Terminal Transaksi / Mesin</div>
                        <div class="detail-value" id="modal_terminal_transaksi">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Status Keluhan</div>
                        <div class="detail-value" id="modal_status">-</div>
                    </div>
                </div>
            </div>

            <!-- Informasi Waktu & Tanggal -->
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Timeline & Riwayat Tanggal</span>
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Transaksi</div>
                        <div class="detail-value" id="modal_tgl_transaksi">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Terima Keluhan</div>
                        <div class="detail-value" id="modal_tgl_terima">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Selesai Penanganan</div>
                        <div class="detail-value" id="modal_tgl_selesai">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Waktu Sistem Dijurnal</div>
                        <div class="detail-value" id="modal_created_at">-</div>
                    </div>
                </div>
            </div>

            <!-- Keterangan Log -->
            <div class="detail-section" style="margin-bottom: 0;">
                <div class="detail-section-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Keterangan Log / Catatan Keluhan</span>
                </div>
                <div class="detail-keterangan-box" id="modal_keterangan_log">
                    Tidak ada keterangan tambahan.
                </div>
            </div>
        </div>

        <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--bs-gray-200); display: flex; justify-content: flex-end; align-items: center; gap: 10px; background: var(--bs-gray-50);">
            <a id="modalBtnEdit" href="#" class="btn btn-warning" style="background-color: #f59e0b; color: #ffffff; border: 1px solid #d97706; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; padding: 8px 16px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <span>Edit Data Jurnal</span>
            </a>
            <button type="button" id="modalBtnDelete" class="btn" style="background-color: #dc2626; color: #ffffff; border: 1px solid #b91c1c; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; padding: 8px 16px; cursor: pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                <span>Hapus Data</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Data Ekstra Aman -->
<div class="modal-backdrop" id="deleteConfirmModal">
    <div class="modal-content" style="max-width: 480px; border-top: 4px solid var(--bs-danger);">
        <div class="modal-header" style="background-color: #fff1f2; border-bottom: 1px solid #fecdd3;">
            <h3 style="color: #991b1b; display: flex; align-items: center; gap: 8px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <span>Konfirmasi Penghapusan Data</span>
            </h3>
            <button class="btn-close-modal" onclick="closeDeleteConfirmModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="modal-body" style="padding: 24px;">
            <p style="font-size: 14.5px; color: var(--bs-gray-800); line-height: 1.5; margin-bottom: 16px;">
                Apakah Anda <strong>benar-benar yakin</strong> ingin menghapus data jurnal keluhan nasabah ini?
            </p>

            <div style="background-color: var(--bs-gray-50); border: 1px solid var(--bs-gray-200); border-radius: var(--radius-md); padding: 14px 16px; margin-bottom: 16px; font-size: 13.5px;">
                <div style="margin-bottom: 6px;">Nama Nasabah: <strong id="deleteNasabahName" style="color: var(--bs-navy);">-</strong></div>
                <div style="margin-bottom: 6px;">No. Resi / Trace: <strong id="deleteNoResi" style="color: var(--bs-blue);">-</strong></div>
                <div>Nominal Transaksi: <strong id="deleteNominal" style="color: #047857;">-</strong></div>
            </div>

            <div style="background-color: #fef2f2; border: 1px dashed #fca5a5; border-radius: var(--radius-md); padding: 10px 12px; font-size: 12.5px; color: #991b1b; display: flex; align-items: flex-start; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span><strong>Perhatian:</strong> Tindakan ini bersifat permanen. Data yang telah dihapus tidak dapat dipulihkan kembali ke dalam sistem.</span>
            </div>
        </div>

        <div class="modal-footer" style="background-color: #fafafa; border-top: 1px solid var(--bs-gray-200); padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteConfirmModal()">
                <span>Batal</span>
            </button>
            <form id="deleteFormSubmit" action="" method="POST" style="margin: 0; display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="background-color: #dc2626; color: #ffffff; border: 1px solid #b91c1c; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Ya, Hapus Data Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Inisialisasi Cache Teks Asli untuk Highlighting
    function initOriginalTextCache() {
        document.querySelectorAll('.highlightable').forEach(el => {
            if (!el.hasAttribute('data-raw-text')) {
                el.setAttribute('data-raw-text', el.innerHTML);
            }
        });
    }

    // Fungsi Utama Penyorotan Kata Kunci (Case-Insensitive Yellow Background Highlight)
    function applyYellowHighlights(keyword) {
        initOriginalTextCache();
        const trimmed = (keyword || '').trim();

        if (!trimmed) {
            document.querySelectorAll('.highlightable').forEach(el => {
                const raw = el.getAttribute('data-raw-text');
                if (raw !== null) el.innerHTML = raw;
            });
            return;
        }

        // Pisahkan kata kunci jika ada spasi (Multi-term)
        const terms = trimmed.split(/\s+/).filter(t => t.length > 0);
        if (terms.length === 0) return;

        // Buat Regex Case-Insensitive (Flag 'gi') yang mengenali huruf besar atau kecil
        const escapedPattern = terms.map(t => t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|');
        const regex = new RegExp(`(${escapedPattern})`, 'gi');

        document.querySelectorAll('.highlightable').forEach(el => {
            const raw = el.getAttribute('data-raw-text') || el.innerHTML;
            // Preservasi teks asli apa adanya (huruf besar/kecil asli tetap terjaga) dengan $1
            el.innerHTML = raw.replace(regex, '<mark class="highlight-yellow">$1</mark>');
        });
    }

    // Live Instant Filter Lokal (Client-Side Case-Insensitive) saat Mengetik
    function performClientSideFilter(query) {
        const rows = document.querySelectorAll('.jurnal-row');
        const q = (query || '').toLowerCase().trim();
        const terms = q.split(/\s+/).filter(t => t.length > 0);
        let visibleCount = 0;

        rows.forEach(row => {
            const searchData = (row.getAttribute('data-search') || '').toLowerCase();
            // Cocokkan seluruh kata kunci tanpa peduli huruf besar atau kecil
            const matches = terms.length === 0 || terms.every(term => searchData.includes(term));

            if (matches) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Tampilkan feedback jika pencarian lokal tidak menemukan data
        const tbody = document.getElementById('jurnalTbody');
        let emptyRow = document.getElementById('localEmptyRow');
        
        if (visibleCount === 0 && rows.length > 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.id = 'localEmptyRow';
                emptyRow.innerHTML = `<td colspan="8" style="text-align: center; padding: 35px 20px; color: var(--bs-gray-500);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 8px; color: var(--bs-gray-400);">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <div style="font-weight: 700; color: var(--bs-gray-700); font-size: 14px;">Tidak Ada Data yang Cocok</div>
                    <div style="font-size: 12.5px; margin-top: 2px;">Tidak ditemukan transaksi dengan kata kunci: <mark class="highlight-yellow">${query}</mark></div>
                </td>`;
                tbody.appendChild(emptyRow);
            }
        } else if (emptyRow) {
            emptyRow.remove();
        }

        applyYellowHighlights(query);
    }

    // Debounced Server-Side AJAX Fetch untuk Sinkronisasi Penuh Database
    let debounceTimer = null;
    function triggerDebouncedServerSearch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchServerFilteredData();
        }, 350);
    }

    // Mengambil data dari server melalui AJAX tanpa reload halaman
    function fetchServerFilteredData(urlOverride = null) {
        const form = document.getElementById('filterForm');
        const qVal = document.getElementById('searchInput').value;
        const statusVal = document.getElementById('filterStatus').value;
        const cabangVal = document.getElementById('filterCabang').value;
        const tglDariVal = document.getElementById('filterTglDari').value;
        const tglSampaiVal = document.getElementById('filterTglSampai').value;

        // Tampilkan / Sembunyikan Tombol Reset
        const hasFilters = qVal || statusVal || cabangVal || tglDariVal || tglSampaiVal;
        const btnReset = document.getElementById('btnResetFilter');
        if (btnReset) btnReset.style.display = hasFilters ? 'inline-flex' : 'none';

        let url = urlOverride;
        if (!url) {
            const params = new URLSearchParams();
            if (qVal) params.set('q', qVal);
            if (statusVal) params.set('status', statusVal);
            if (cabangVal) params.set('master_cabang_id', cabangVal);
            if (tglDariVal) params.set('tgl_dari', tglDariVal);
            if (tglSampaiVal) params.set('tgl_sampai', tglSampaiVal);
            url = "{{ route('jurnal.index') }}?" + params.toString();
        }

        // Update URL Address Bar tanpa reload
        window.history.replaceState({}, '', url);

        // Fetch HTML Parsed Response
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(htmlText => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(htmlText, 'text/html');

                const newCardBody = doc.getElementById('tableBodyWrapper');
                const currentCardBody = document.getElementById('tableBodyWrapper');
                if (newCardBody && currentCardBody) {
                    currentCardBody.innerHTML = newCardBody.innerHTML;
                }

                const newTotalCount = doc.getElementById('totalCountNum');
                const currentTotalCount = document.getElementById('totalCountNum');
                if (newTotalCount && currentTotalCount) {
                    currentTotalCount.textContent = newTotalCount.textContent;
                }

                const newStats = doc.getElementById('statsSection');
                const currentStats = document.getElementById('statsSection');
                if (newStats && currentStats) {
                    currentStats.innerHTML = newStats.innerHTML;
                }

                // Terapkan kembali penyorotan kuning pada data baru
                applyYellowHighlights(document.getElementById('searchInput').value);
                bindPaginationEvents();
            })
            .catch(err => console.error('Gagal mengambil data pencarian:', err));
    }

    // Intercept Pagination Klik untuk AJAX Navigation
    function bindPaginationEvents() {
        document.querySelectorAll('#paginationWrapper a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetchServerFilteredData(this.getAttribute('href'));
            });
        });
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const btnClear = document.getElementById('btnClearSearch');
        const filterStatus = document.getElementById('filterStatus');
        const filterCabang = document.getElementById('filterCabang');
        const filterTglDari = document.getElementById('filterTglDari');
        const filterTglSampai = document.getElementById('filterTglSampai');

        // 1. Live Instant Typing on Search Input
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value;
                if (btnClear) btnClear.style.display = query ? 'flex' : 'none';
                performClientSideFilter(query);
                triggerDebouncedServerSearch();
            });
        }

        // 2. Tombol Clear Search (X)
        if (btnClear) {
            btnClear.addEventListener('click', function() {
                searchInput.value = '';
                this.style.display = 'none';
                searchInput.focus();
                performClientSideFilter('');
                fetchServerFilteredData();
            });
        }

        // 3. Auto Filter on Dropdown & Date Changes
        [filterStatus, filterCabang, filterTglDari, filterTglSampai].forEach(el => {
            if (el) {
                el.addEventListener('change', function() {
                    fetchServerFilteredData();
                });
            }
        });

        // 4. Inisialisasi awal highlight jika ada query default saat load
        initOriginalTextCache();
        if (searchInput && searchInput.value) {
            applyYellowHighlights(searchInput.value);
        }

        bindPaginationEvents();
    });

    // Formatting & Modal Detail Functions
    function formatDateIndo(dateStr) {
        if(!dateStr) return '-';
        const d = new Date(dateStr);
        if(isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function showDetailModal(jurnal) {
        document.getElementById('modal_nama_nasabah').textContent = jurnal.nama_nasabah || '-';
        document.getElementById('modal_no_rekening').textContent = jurnal.no_rekening || '-';
        document.getElementById('modal_no_resi').textContent = jurnal.no_resi || '-';
        document.getElementById('modal_no_kartu').textContent = jurnal.no_kartu || '-';
        document.getElementById('modal_no_tiket').textContent = jurnal.no_tiket || '-';
        document.getElementById('modal_cabang').textContent = (jurnal.master_cabang ? jurnal.master_cabang.kode_cabang + ' - ' + jurnal.master_cabang.nama_cabang : '-');
        
        document.getElementById('modal_jenis_transaksi').textContent = (jurnal.master_transaksi ? jurnal.master_transaksi.jenis_transaksi : '-');
        document.getElementById('modal_channel').textContent = (jurnal.master_transaksi ? jurnal.master_transaksi.channel : '-');
        
        const nominal = Number(jurnal.nominal_transaksi) || 0;
        document.getElementById('modal_nominal_transaksi').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
        
        const admin = (jurnal.master_transaksi && Number(jurnal.master_transaksi.biaya_admin)) ? Number(jurnal.master_transaksi.biaya_admin) : 0;
        document.getElementById('modal_biaya_admin').textContent = 'Rp ' + admin.toLocaleString('id-ID');
        
        document.getElementById('modal_terminal_transaksi').textContent = jurnal.terminal_transaksi || '-';
        document.getElementById('modal_status').innerHTML = '<span class="badge badge-' + (jurnal.status || '').toLowerCase() + '">' + (jurnal.status || '-') + '</span>';
        
        document.getElementById('modal_tgl_transaksi').textContent = formatDateIndo(jurnal.tgl_transaksi);
        document.getElementById('modal_tgl_terima').textContent = formatDateIndo(jurnal.tgl_terima);
        document.getElementById('modal_tgl_selesai').textContent = formatDateIndo(jurnal.tgl_selesai);
        document.getElementById('modal_created_at').textContent = formatDateIndo(jurnal.created_at);
        
        document.getElementById('modal_keterangan_log').textContent = jurnal.keterangan_log || 'Tidak ada keterangan tambahan.';

        // Set Link Edit di Modal Detail
        const editBtn = document.getElementById('modalBtnEdit');
        if (editBtn) {
            editBtn.href = '/jurnal/' + jurnal.id + '/edit';
        }

        // Set Handler Hapus di Modal Detail
        const deleteBtn = document.getElementById('modalBtnDelete');
        if (deleteBtn) {
            deleteBtn.onclick = function() {
                closeDetailModal();
                openDeleteConfirmModal(jurnal.id, jurnal.nama_nasabah, jurnal.no_resi, 'Rp ' + Number(jurnal.nominal_transaksi).toLocaleString('id-ID'));
            };
        }

        document.getElementById('detailModal').classList.add('show');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.remove('show');
    }

    // Modal Konfirmasi Hapus Data Ekstra
    function openDeleteConfirmModal(id, namaNasabah, noResi, nominal) {
        document.getElementById('deleteNasabahName').textContent = namaNasabah || '-';
        document.getElementById('deleteNoResi').textContent = noResi || '-';
        document.getElementById('deleteNominal').textContent = nominal || '-';
        document.getElementById('deleteFormSubmit').action = '/jurnal/' + id;
        document.getElementById('deleteConfirmModal').classList.add('show');
    }

    function closeDeleteConfirmModal() {
        document.getElementById('deleteConfirmModal').classList.remove('show');
    }

    // Close on click outside modal
    document.addEventListener('click', function(e) {
        const detailModal = document.getElementById('detailModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (e.target === detailModal) closeDetailModal();
        if (e.target === deleteModal) closeDeleteConfirmModal();
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeDetailModal();
            closeDeleteConfirmModal();
        }
    });
</script>
@endpush
