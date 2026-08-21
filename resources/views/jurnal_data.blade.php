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
    .btn-export-excel {
        background-color: #059669;
        color: #ffffff !important;
        border: 1px solid #047857;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-export-excel:hover {
        background-color: #047857;
        border-color: #065f46;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2);
    }

    .btn-export-excel:active {
        transform: translateY(0);
    }

    .btn-import-excel {
        background-color: #0284c7;
        color: #ffffff !important;
        border: 1px solid #0369a1;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-import-excel:hover {
        background-color: #0369a1;
        border-color: #075985;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);
    }

    .btn-import-excel:active {
        transform: translateY(0);
    }

    .btn-reset-all {
        background-color: #fff1f2;
        color: #e11d48 !important;
        border: 1px solid #fecdd3;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-reset-all:hover {
        background-color: #ffe4e6;
        color: #be123c !important;
        border-color: #fda4af;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(225, 29, 72, 0.15);
    }

    .btn-reset-all:active {
        transform: translateY(0);
    }

    /* Drag and drop upload zone */
    .upload-dropzone {
        border: 2px dashed #93c5fd;
        background-color: #f0f9ff;
        border-radius: var(--radius-md);
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .upload-dropzone:hover, .upload-dropzone.dragover {
        border-color: #0284c7;
        background-color: #e0f2fe;
    }

    .upload-dropzone svg {
        width: 44px;
        height: 44px;
        color: #0284c7;
        margin-bottom: 10px;
    }

    /* Real-Time Import Step Tracker */
    .import-steps-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-md);
        padding: 14px 16px;
    }

    .import-step-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: var(--bs-gray-500);
        transition: all 0.25s ease;
    }

    .import-step-item.active {
        color: #0284c7;
        font-weight: 700;
    }

    .import-step-item.completed {
        color: #059669;
        font-weight: 600;
    }

    .import-step-badge {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        background-color: #e2e8f0;
        color: #64748b;
        flex-shrink: 0;
        transition: all 0.25s ease;
    }

    .import-step-item.active .import-step-badge {
        background-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
    }

    .import-step-item.completed .import-step-badge {
        background-color: #059669;
        color: #ffffff;
    }

    /* Success & Error Modal Custom Elements */
    .modal-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .modal-icon-success {
        background-color: #d1fae5;
        color: #059669;
    }

    .modal-icon-error {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .stat-result-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin: 18px 0;
    }

    .stat-result-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-md);
        padding: 14px 10px;
        text-align: center;
    }

    .stat-result-card.card-total {
        border-top: 3px solid #0284c7;
    }

    .stat-result-card.card-new {
        border-top: 3px solid #059669;
        background-color: #f0fdf4;
    }

    .stat-result-card.card-sync {
        border-top: 3px solid #d97706;
    }

    .stat-result-val {
        font-size: 20px;
        font-weight: 800;
        color: var(--bs-gray-900);
        line-height: 1.2;
    }

    .stat-result-lbl {
        font-size: 11px;
        font-weight: 600;
        color: var(--bs-gray-500);
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

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

    /* Toolbar & Pagination Styling */
    .table-toolbar {
        padding: 10px 20px;
        background: #f8fafc;
        border-bottom: 1px solid var(--bs-gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .per-page-select {
        padding: 5px 10px;
        font-size: 13px;
        font-weight: 700;
        height: 34px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--bs-gray-300);
        background-color: #ffffff;
        color: var(--bs-navy);
        cursor: pointer;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .per-page-select:focus {
        border-color: var(--bs-blue);
        box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
    }

    .custom-pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        width: 100%;
    }

    .custom-pagination-nav {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }

    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        min-width: 34px;
        height: 34px;
        font-size: 12.5px;
        font-weight: 600;
        border-radius: var(--radius-sm);
        border: 1px solid var(--bs-gray-300);
        background-color: #ffffff;
        color: var(--bs-navy);
        text-decoration: none;
        transition: all 0.18s ease;
        user-select: none;
        cursor: pointer;
    }

    .page-btn:hover:not(.disabled):not(.active) {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        color: var(--bs-blue);
        transform: translateY(-1px);
    }

    .page-btn.active {
        background-color: var(--bs-navy);
        border-color: var(--bs-navy);
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(0, 51, 102, 0.2);
    }

    .page-btn.disabled {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .page-btn-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 34px;
        color: #94a3b8;
        font-weight: 700;
        letter-spacing: 1px;
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
                <!-- Search Keyword -->
                <div class="filter-group">
                    <label for="searchInput">Pencarian Otomatis</label>
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
                        <option value="-" {{ request('status') === '-' ? 'selected' : '' }}>- (Belum Ditentukan)</option>
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

                <!-- Tanggal Transaksi Dari -->
                <div class="filter-group">
                    <label for="filterTglDari">Tgl Transaksi (Dari)</label>
                    <input type="date" id="filterTglDari" name="tgl_dari" value="{{ request('tgl_dari') }}" class="form-control" title="Filter awal tanggal transaksi">
                </div>

                <!-- Tanggal Transaksi Sampai -->
                <div class="filter-group">
                    <label for="filterTglSampai">Tgl Transaksi (Sampai)</label>
                    <input type="date" id="filterTglSampai" name="tgl_sampai" value="{{ request('tgl_sampai') }}" class="form-control" title="Filter batas akhir tanggal transaksi">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Card Tabel Data Jurnal -->
<div class="card" id="tableCard">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
            <span>Daftar Jurnal Keluhan Tersimpan</span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <div id="totalCountBadge" style="font-size: 13px; color: var(--bs-gray-500); margin-right: 4px;">
                Menampilkan <strong id="totalCountNum">{{ $jurnals->total() }}</strong> total data keluhan
            </div>
            <button type="button" class="btn btn-import-excel" onclick="openImportModal()" title="Unggah / Import File Excel Master (.xlsx / .xls)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <span>Import Excel Master</span>
            </button>
            <button type="button" onclick="startExportProcess()" id="btnExportExcel" class="btn-export-excel" title="Ekspor Data ke Master Excel Multi-Sheet (.xlsx)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Export Excel (.xlsx)</span>
            </button>
            <button type="button" class="btn btn-reset-all" onclick="openResetAllModal()" title="Kosongkan / Reset Seluruh Data Jurnal & Master Template">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                <span>Hapus Semua Data</span>
            </button>
        </div>
    </div>

    <div class="card-body" id="tableBodyWrapper" style="padding: 0;">
        @if($jurnals->count() > 0)
            <!-- Toolbar Pilihan Baris Data (10, 50, 100) & Info Range -->
            <div class="table-toolbar">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--bs-gray-700);">
                    <span>Tampilkan</span>
                    <select id="perPageSelect" class="per-page-select" onchange="handlePerPageChange(this.value)" title="Pilih jumlah baris data yang ditampilkan per halaman">
                        <option value="10" {{ $jurnals->perPage() == 10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{ $jurnals->perPage() == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $jurnals->perPage() == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span>data per halaman</span>
                </div>
                <div style="font-size: 12.5px; color: var(--bs-gray-500); font-weight: 500;">
                    Menampilkan <strong>{{ $jurnals->firstItem() ?? 0 }} - {{ $jurnals->lastItem() ?? 0 }}</strong> dari total <strong style="color: var(--bs-navy);">{{ number_format($jurnals->total(), 0, ',', '.') }}</strong> data
                </div>
            </div>

            <div class="table-container" id="tableContainer">
                <table class="custom-table" id="jurnalTable">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Tanggal</th>
                            <th>Data Nasabah</th>
                            <th>Kantor Cabang</th>
                            <th>Jenis Transaksi</th>
                            <th style="text-align: center; width: 110px;">Channel</th>
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
                                        Transaksi: {{ \Carbon\Carbon::parse($jurnal->tgl_transaksi)->translatedFormat('d M Y') }}
                                    </div>
                                    <div style="font-size: 11.5px; color: var(--bs-gray-500); margin-top: 5px;">
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
                                </td>
                                <td style="text-align: center; vertical-align: middle; white-space: nowrap;">
                                    <span class="badge badge-channel highlightable">
                                        {{ $jurnal->masterTransaksi->channel ?? '-' }}
                                    </span>
                                </td>
                                <td style="white-space: nowrap;">
                                    <div class="nominal-badge highlightable" style="font-size: 14.5px;">
                                        Rp {{ number_format($jurnal->nominal_transaksi, 0, ',', '.') }}
                                    </div>
                                    <div style="font-size: 11.5px; color: var(--bs-gray-500); margin-top: 2px;">
                                        Admin: Rp {{ number_format($jurnal->biaya_admin ?? $jurnal->masterTransaksi->biaya_admin ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $st = strtolower(trim($jurnal->status ?? '-'));
                                        $statusClass = match($st) {
                                            'menunggu' => 'badge-menunggu',
                                            'success' => 'badge-success',
                                            'done' => 'badge-done',
                                            'rejected' => 'badge-rejected',
                                            default => 'badge-strip'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ $jurnal->status ?: '-' }}
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

            <!-- Custom Pagination Navigation Footer -->
            <div id="paginationWrapper" style="padding: 16px 24px; border-top: 1px solid var(--bs-gray-200); background: #ffffff;">
                <div class="custom-pagination-container">
                    <div class="pagination-info">
                        Halaman <strong>{{ $jurnals->currentPage() }}</strong> dari <strong>{{ $jurnals->lastPage() }}</strong>
                        <span style="color: var(--bs-gray-400); margin: 0 4px;">•</span>
                        (Total <strong style="color: var(--bs-navy);">{{ number_format($jurnals->total(), 0, ',', '.') }}</strong> transaksi)
                    </div>

                    @if($jurnals->hasPages())
                        <nav class="custom-pagination-nav" aria-label="Navigasi Halaman Data">
                            {{-- Tombol Pertama & Sebelumnya --}}
                            @if ($jurnals->onFirstPage())
                                <span class="page-btn disabled" title="Halaman Pertama">«</span>
                                <span class="page-btn disabled" title="Halaman Sebelumnya">‹ Sebelumnya</span>
                            @else
                                <a href="{{ $jurnals->url(1) }}" class="page-btn" title="Halaman Pertama">«</a>
                                <a href="{{ $jurnals->previousPageUrl() }}" class="page-btn" title="Halaman Sebelumnya">‹ Sebelumnya</a>
                            @endif

                            {{-- Nomor Halaman Pintar --}}
                            @php
                                $current = $jurnals->currentPage();
                                $last = $jurnals->lastPage();
                                $start = max(1, $current - 2);
                                $end = min($last, $current + 2);
                                if ($end - $start < 4) {
                                    if ($start == 1) $end = min($last, $start + 4);
                                    else if ($end == $last) $start = max(1, $end - 4);
                                }
                            @endphp

                            @if($start > 1)
                                <a href="{{ $jurnals->url(1) }}" class="page-btn">1</a>
                                @if($start > 2)
                                    <span class="page-btn-dots">...</span>
                                @endif
                            @endif

                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $current)
                                    <span class="page-btn active">{{ $i }}</span>
                                @else
                                    <a href="{{ $jurnals->url($i) }}" class="page-btn">{{ $i }}</a>
                                @endif
                            @endfor

                            @if($end < $last)
                                @if($end < $last - 1)
                                    <span class="page-btn-dots">...</span>
                                @endif
                                <a href="{{ $jurnals->url($last) }}" class="page-btn">{{ $last }}</a>
                            @endif

                            {{-- Tombol Selanjutnya & Terakhir --}}
                            @if ($jurnals->hasMorePages())
                                <a href="{{ $jurnals->nextPageUrl() }}" class="page-btn" title="Halaman Selanjutnya">Selanjutnya ›</a>
                                <a href="{{ $jurnals->url($last) }}" class="page-btn" title="Halaman Terakhir">»</a>
                            @else
                                <span class="page-btn disabled" title="Halaman Selanjutnya">Selanjutnya ›</span>
                                <span class="page-btn disabled" title="Halaman Terakhir">»</span>
                            @endif
                        </nav>
                    @endif
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

<!-- Modal Import Excel Master -->
<div class="modal-backdrop" id="importModal">
    <div class="modal-content" style="max-width: 580px; border-top: 4px solid #0284c7;">
        <div class="modal-header" style="background-color: #f0f9ff; border-bottom: 1px solid #bae6fd;">
            <h3 style="color: #0369a1; display: flex; align-items: center; gap: 8px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <span>Import Data Excel Master ke Sistem</span>
            </h3>
            <button class="btn-close-modal" onclick="closeImportModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="importForm" action="{{ route('jurnal.import_excel') }}" method="POST" enctype="multipart/form-data" onsubmit="handleImportSubmit(event)">
            @csrf
            <div class="modal-body" style="padding: 24px;">
                <p style="font-size: 13.5px; color: var(--bs-gray-700); margin-bottom: 16px; line-height: 1.5;">
                    Unggah file <strong>Excel Master (.xlsx / .xls)</strong> Anda yang sudah terisi ribuan data transaksi. Sistem akan memetakan dan memasukkan seluruh baris data ke database secara otomatis.
                </p>

                <!-- Drag & Drop Upload Zone -->
                <div class="upload-dropzone" id="dropzone" onclick="document.getElementById('fileExcelInput').click()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                    <div id="dropzoneText" style="font-weight: 700; color: #0369a1; font-size: 14px;">Klik untuk memilih file Excel atau seret file ke sini</div>
                    <div style="font-size: 12px; color: var(--bs-gray-500); margin-top: 4px;">Mendukung format: .xlsx, .xls, .csv (Maks. 50MB)</div>
                    <div id="selectedFileName" style="display: none; margin-top: 10px; font-weight: 700; color: #047857; background: #ecfdf5; padding: 6px 12px; border-radius: var(--radius-sm); border: 1px solid #a7f3d0; font-size: 13px;"></div>
                </div>
                <input type="file" id="fileExcelInput" name="file_excel" accept=".xlsx,.xls,.csv" style="display: none;" onchange="handleFileSelected(this)" required>

                <!-- Option Checkbox -->
                <div id="templateOptionBox" style="margin-top: 18px; display: flex; align-items: flex-start; gap: 10px; background: #f8fafc; padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--bs-gray-200);">
                    <input type="checkbox" id="chkSetAsTemplate" name="set_as_template" value="1" checked style="margin-top: 3px; cursor: pointer; width: 16px; height: 16px;">
                    <label for="chkSetAsTemplate" style="font-size: 12.5px; color: var(--bs-gray-700); cursor: pointer; margin: 0; line-height: 1.4;">
                        <strong>Jadikan sebagai Master Template aktif</strong><br>
                        <span style="color: var(--bs-gray-500);">Format cetak slip, rekapitulasi cabang, dan seluruh rumus bawaan dari file ini akan disimpan dan digunakan saat ekspor berikutnya.</span>
                    </label>
                </div>

                <!-- Info Box -->
                <div id="tipsInfoBox" style="margin-top: 14px; font-size: 12px; color: #0369a1; background-color: #f0f9ff; border-left: 3px solid #0284c7; padding: 10px 12px; border-radius: 0 var(--radius-sm) var(--radius-sm) 0;">
                    💡 <strong>Tips Cerdas:</strong> Awalan angka nol pada Nomor Rekening, Nomor Kartu, dan Kode Cabang akan tetap dipertahankan. Data yang sudah pernah dimasukkan akan diperbarui secara otomatis tanpa menimbulkan duplikat.
                </div>

                <!-- Live Real-Time Progress Wrapper (Hidden by default) -->
                <div id="importProgressBarWrapper" style="display: none; margin-top: 18px;">
                    <!-- Status & Percentage -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span id="importProgressText" style="font-size: 13px; font-weight: 700; color: #0369a1;">Mengunggah berkas Excel ke server...</span>
                        <span id="importProgressPercent" style="font-size: 14px; font-weight: 800; color: #0284c7;">0%</span>
                    </div>

                    <!-- Progress Bar Track -->
                    <div style="width: 100%; height: 10px; background-color: #e2e8f0; border-radius: 5px; overflow: hidden;">
                        <div id="importProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #0284c7, #059669); transition: width 0.3s ease; border-radius: 5px;"></div>
                    </div>

                    <!-- Live Step-by-Step Tracker -->
                    <div class="import-steps-container">
                        <div class="import-step-item" id="step1">
                            <div class="import-step-badge" id="stepBadge1">1</div>
                            <span>Mengunggah berkas Excel ke server</span>
                        </div>
                        <div class="import-step-item" id="step2">
                            <div class="import-step-badge" id="stepBadge2">2</div>
                            <span>Membaca sheet data & memvalidasi struktur kolom</span>
                        </div>
                        <div class="import-step-item" id="step3">
                            <div class="import-step-badge" id="stepBadge3">3</div>
                            <span>Menyimpan & memetakan ribuan data transaksi ke database</span>
                        </div>
                        <div class="import-step-item" id="step4">
                            <div class="import-step-badge" id="stepBadge4">4</div>
                            <span>Sinkronisasi master template & formula otomatis</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background-color: #fafafa; border-top: 1px solid var(--bs-gray-200); padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeImportModal()" id="btnCancelImport">
                    <span>Batal</span>
                </button>
                <button type="submit" class="btn" id="btnSubmitImport" style="background-color: #0284c7; color: #ffffff; border: 1px solid #0369a1; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <span>Mulai Proses Import</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sukses Import (Custom UI Feedback) -->
<div class="modal-backdrop" id="importSuccessModal">
    <div class="modal-content" style="max-width: 520px; text-align: center; border-top: 4px solid #059669; animation: modalFadeIn 0.25s ease-out;">
        <div class="modal-body" style="padding: 30px 24px 20px;">
            <div class="modal-icon-wrapper modal-icon-success">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            
            <h3 style="font-size: 20px; font-weight: 800; color: var(--bs-navy); margin-bottom: 6px;">Import Data Berhasil!</h3>
            <p style="font-size: 13.5px; color: var(--bs-gray-600); margin-bottom: 0; line-height: 1.5;">
                Seluruh data dari berkas Excel Master telah berhasil diproses dan tersimpan ke dalam sistem.
            </p>

            <!-- Stat Summary Cards -->
            <div class="stat-result-grid">
                <div class="stat-result-card card-total">
                    <div class="stat-result-val" id="resTotalCount" style="color: #0284c7;">0</div>
                    <div class="stat-result-lbl">Total Dibaca</div>
                </div>
                <div class="stat-result-card card-new">
                    <div class="stat-result-val" id="resInsertedCount" style="color: #059669;">0</div>
                    <div class="stat-result-lbl">Data Baru</div>
                </div>
                <div class="stat-result-card card-sync">
                    <div class="stat-result-val" id="resUpdatedCount" style="color: #d97706;">0</div>
                    <div class="stat-result-lbl">Diperbarui</div>
                </div>
            </div>

            <!-- Details Note -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 12px 14px; text-align: left; font-size: 12.5px; color: var(--bs-gray-700);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="color: var(--bs-gray-500);">Berkas:</span>
                    <strong id="resFileName" style="color: var(--bs-navy);">-</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--bs-gray-500);">Status Template:</span>
                    <strong style="color: #059669;">✓ Aktif Sebagai Master Template</strong>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="background-color: #fafafa; border-top: 1px solid var(--bs-gray-200); padding: 16px 24px; display: flex; justify-content: center;">
            <button type="button" class="btn" onclick="closeImportSuccessModal()" style="background-color: #059669; color: #ffffff; min-width: 180px; padding: 10px 20px; font-weight: 700; border: none; border-radius: var(--radius-md); box-shadow: 0 2px 4px rgba(5, 150, 105, 0.25); cursor: pointer;">
                Lihat Data di Tabel
            </button>
        </div>
    </div>
</div>

<!-- Modal Error Import (Custom UI Feedback) -->
<div class="modal-backdrop" id="importErrorModal">
    <div class="modal-content" style="max-width: 480px; text-align: center; border-top: 4px solid #dc2626; animation: modalFadeIn 0.25s ease-out;">
        <div class="modal-body" style="padding: 30px 24px 20px;">
            <div class="modal-icon-wrapper modal-icon-error">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            
            <h3 id="resErrorTitle" style="font-size: 19px; font-weight: 800; color: #991b1b; margin-bottom: 6px;">Proses Gagal</h3>
            <p id="resErrorMessage" style="font-size: 13.5px; color: var(--bs-gray-700); margin-bottom: 0; line-height: 1.5;">
                Terjadi kesalahan saat memproses berkas Excel.
            </p>
        </div>

        <div class="modal-footer" style="background-color: #fafafa; border-top: 1px solid var(--bs-gray-200); padding: 16px 24px; display: flex; justify-content: center; gap: 10px;">
            <button type="button" class="btn btn-secondary" onclick="closeImportErrorModal()">
                Tutup
            </button>
            <button type="button" class="btn" onclick="retryImport()" style="background-color: #0284c7; color: #ffffff; font-weight: 600; border: none; cursor: pointer;">
                Coba Lagi
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Reset Semua Data -->
<div class="modal-backdrop" id="resetAllModal">
    <div class="modal-content" style="max-width: 500px; border-top: 4px solid #dc2626; animation: modalFadeIn 0.25s ease-out;">
        <div class="modal-header" style="background-color: #fff1f2; border-bottom: 1px solid #fecdd3;">
            <h3 style="color: #991b1b; display: flex; align-items: center; gap: 8px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <span>Konfirmasi Reset Semua Data Jurnal</span>
            </h3>
            <button class="btn-close-modal" onclick="closeResetAllModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="resetAllForm" action="{{ route('jurnal.reset_all') }}" method="POST" onsubmit="handleResetAllSubmit(event)">
            @csrf
            @method('DELETE')
            <div class="modal-body" style="padding: 24px;">
                <p style="font-size: 14px; color: var(--bs-gray-800); line-height: 1.5; margin-bottom: 16px;">
                    Apakah Anda <strong>benar-benar yakin</strong> ingin mengosongkan seluruh data jurnal keluhan dari sistem?
                </p>

                <div style="background-color: #fef2f2; border: 1px dashed #fca5a5; border-radius: var(--radius-md); padding: 12px 14px; font-size: 13px; color: #991b1b; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 16px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span><strong>Peringatan Kritis:</strong> Seluruh baris transaksi yang telah terdaftar saat ini akan dihapus secara permanen. Anda dapat mengimpor kembali berkas Master Excel yang baru setelah proses ini selesai.</span>
                </div>

                <!-- Opsi Hapus Master Template -->
                <div style="display: flex; align-items: flex-start; gap: 10px; background: #f8fafc; padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--bs-gray-200);">
                    <input type="checkbox" id="chkDeleteTemplate" name="delete_template" value="1" checked style="margin-top: 3px; cursor: pointer; width: 16px; height: 16px;">
                    <label for="chkDeleteTemplate" style="font-size: 12.5px; color: var(--bs-gray-700); cursor: pointer; margin: 0; line-height: 1.4;">
                        <strong>Hapus juga berkas Master Template tersimpan</strong><br>
                        <span style="color: var(--bs-gray-500);">Mengembalikan template ekspor ke format bawaan default sistem Bank Sulteng.</span>
                    </label>
                </div>
            </div>

            <div class="modal-footer" style="background-color: #fafafa; border-top: 1px solid var(--bs-gray-200); padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeResetAllModal()" id="btnCancelResetAll">
                    <span>Batal</span>
                </button>
                <button type="submit" class="btn" id="btnSubmitResetAll" style="background-color: #dc2626; color: #ffffff; border: 1px solid #b91c1c; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Ya, Bersihkan Seluruh Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Proses Export Excel (Live Real-Time UI Progress) -->
<div class="modal-backdrop" id="exportProgressModal">
    <div class="modal-content" style="max-width: 520px; border-top: 4px solid #059669; animation: modalFadeIn 0.25s ease-out;">
        <div class="modal-header" style="background-color: #f0fdf4; border-bottom: 1px solid #bbf7d0;">
            <h3 style="color: #065f46; display: flex; align-items: center; gap: 8px; font-size: 17px; font-weight: 700;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span id="exportModalTitle">Mengekspor Berkas Master Excel...</span>
            </h3>
        </div>
        
        <div class="modal-body" style="padding: 24px;">
            <!-- Animated Icon / Spinner -->
            <div style="text-align: center; margin-bottom: 18px;">
                <div id="exportIconWrapper" style="display: inline-flex; align-items: center; justify-content: center; width: 68px; height: 68px; border-radius: 50%; background: #ecfdf5; border: 2px solid #a7f3d0; margin-bottom: 12px;">
                    <svg id="exportSpinnerSvg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
                        <line x1="12" y1="2" x2="12" y2="6"></line>
                        <line x1="12" y1="18" x2="12" y2="22"></line>
                        <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                        <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                        <line x1="2" y1="12" x2="6" y2="12"></line>
                        <line x1="18" y1="12" x2="22" y2="12"></line>
                        <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                        <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                    </svg>
                    <svg id="exportSuccessCheckSvg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <h4 id="exportStatusTitle" style="font-size: 16px; font-weight: 700; color: var(--bs-navy); margin-bottom: 4px;">Menyiapkan Data Transaksi</h4>
                <p id="exportStatusSub" style="font-size: 13px; color: var(--bs-gray-600); margin: 0;">Mengagregasikan ribuan baris data jurnal dengan relasi master...</p>
            </div>

            <!-- Status & Percentage -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span id="exportProgressText" style="font-size: 13px; font-weight: 700; color: #065f46;">Proses ekspor sedang berjalan...</span>
                <span id="exportProgressPercent" style="font-size: 14px; font-weight: 800; color: #059669;">25%</span>
            </div>

            <!-- Progress Bar Track -->
            <div style="width: 100%; height: 10px; background-color: #e2e8f0; border-radius: 5px; overflow: hidden; margin-bottom: 20px;">
                <div id="exportProgressBar" style="width: 25%; height: 100%; background: linear-gradient(90deg, #059669, #10b981); transition: width 0.25s ease; border-radius: 5px;"></div>
            </div>

            <!-- Step Tracker -->
            <div class="import-steps-container">
                <div class="import-step-item active" id="expStep1">
                    <div class="import-step-badge" id="expStepBadge1">1</div>
                    <span>Menarik seluruh data transaksi dari database server</span>
                </div>
                <div class="import-step-item" id="expStep2">
                    <div class="import-step-badge" id="expStepBadge2">2</div>
                    <span>Menyusun lembar data master (Sheet DATA_KELUHAN)</span>
                </div>
                <div class="import-step-item" id="expStep3">
                    <div class="import-step-badge" id="expStepBadge3">3</div>
                    <span>Menghubungkan formula dinamis Slip Jurnal & Rekapitulasi Cabang</span>
                </div>
                <div class="import-step-item" id="expStep4">
                    <div class="import-step-badge" id="expStepBadge4">4</div>
                    <span>Mengunduh berkas Excel .xlsx ke komputer Anda</span>
                </div>
            </div>
        </div>
        
        <div class="modal-footer" style="background-color: #fafafa; border-top: 1px solid var(--bs-gray-200); padding: 14px 24px; display: flex; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeExportModal()" id="btnCancelExport" style="display: none;">
                <span>Tutup</span>
            </button>
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
                emptyRow.innerHTML = `<td colspan="9" style="text-align: center; padding: 35px 20px; color: var(--bs-gray-500);">
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
        const perPageEl = document.getElementById('perPageSelect');
        const perPageVal = perPageEl ? perPageEl.value : '10';

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
            if (perPageVal && perPageVal !== '10') params.set('per_page', perPageVal);
            url = "{{ route('jurnal.index') }}?" + params.toString();
        }

        // Update URL Address Bar tanpa reload
        window.history.replaceState({}, '', url);

        // Update Link Tombol Export Excel dengan parameter filter aktif
        const btnExport = document.getElementById('btnExportExcel');
        if (btnExport) {
            const exportParams = new URLSearchParams();
            if (qVal) exportParams.set('q', qVal);
            if (statusVal) exportParams.set('status', statusVal);
            if (cabangVal) exportParams.set('master_cabang_id', cabangVal);
            if (tglDariVal) exportParams.set('tgl_dari', tglDariVal);
            if (tglSampaiVal) exportParams.set('tgl_sampai', tglSampaiVal);
            btnExport.href = "{{ route('jurnal.export_excel') }}?" + exportParams.toString();
        }

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

                const newSidebarBadge = doc.getElementById('sidebarBadgeCount');
                const currentSidebarBadge = document.getElementById('sidebarBadgeCount');
                if (newSidebarBadge && currentSidebarBadge) {
                    currentSidebarBadge.textContent = newSidebarBadge.textContent;
                }

                // Terapkan kembali penyorotan kuning pada data baru
                applyYellowHighlights(document.getElementById('searchInput').value);
                bindPaginationEvents();
            })
            .catch(err => console.error('Gagal mengambil data pencarian:', err));
    }

    // Handler Ganti Jumlah Baris Per Halaman (10, 50, 100)
    function handlePerPageChange(val) {
        const qVal = document.getElementById('searchInput').value;
        const statusVal = document.getElementById('filterStatus').value;
        const cabangVal = document.getElementById('filterCabang').value;
        const tglDariVal = document.getElementById('filterTglDari').value;
        const tglSampaiVal = document.getElementById('filterTglSampai').value;

        const params = new URLSearchParams();
        if (qVal) params.set('q', qVal);
        if (statusVal) params.set('status', statusVal);
        if (cabangVal) params.set('master_cabang_id', cabangVal);
        if (tglDariVal) params.set('tgl_dari', tglDariVal);
        if (tglSampaiVal) params.set('tgl_sampai', tglSampaiVal);
        if (val && val !== '10') params.set('per_page', val);
        params.set('page', '1'); // Reset ke halaman 1 saat mengubah jumlah baris

        const newUrl = "{{ route('jurnal.index') }}?" + params.toString();
        fetchServerFilteredData(newUrl);
    }

    // Intercept Pagination Klik untuk AJAX Navigation
    function bindPaginationEvents() {
        document.querySelectorAll('#paginationWrapper a.page-btn').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetUrl = this.getAttribute('href');
                if (targetUrl && targetUrl !== '#' && !this.classList.contains('disabled')) {
                    fetchServerFilteredData(targetUrl);
                }
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
        if(!dateStr || dateStr === '-' || dateStr === 'null') return '-';
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
        
        const admin = Number(jurnal.biaya_admin !== null && jurnal.biaya_admin !== undefined ? jurnal.biaya_admin : (jurnal.master_transaksi ? jurnal.master_transaksi.biaya_admin : 0)) || 0;
        document.getElementById('modal_biaya_admin').textContent = 'Rp ' + admin.toLocaleString('id-ID');
        
        document.getElementById('modal_terminal_transaksi').textContent = jurnal.terminal_transaksi || '-';
        
        const rawSt = (jurnal.status || '-').toLowerCase().trim();
        let statusBadgeClass = 'badge-strip';
        if (rawSt === 'menunggu') statusBadgeClass = 'badge-menunggu';
        else if (rawSt === 'success') statusBadgeClass = 'badge-success';
        else if (rawSt === 'done') statusBadgeClass = 'badge-done';
        else if (rawSt === 'rejected') statusBadgeClass = 'badge-rejected';
        document.getElementById('modal_status').innerHTML = '<span class="badge ' + statusBadgeClass + '">' + (jurnal.status || '-') + '</span>';
        
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
        const importModal = document.getElementById('importModal');
        const successModal = document.getElementById('importSuccessModal');
        const errorModal = document.getElementById('importErrorModal');
        const resetModal = document.getElementById('resetAllModal');
        if (e.target === detailModal) closeDetailModal();
        if (e.target === deleteModal) closeDeleteConfirmModal();
        if (e.target === importModal) closeImportModal();
        if (e.target === successModal) closeImportSuccessModal();
        if (e.target === errorModal) closeImportErrorModal();
        if (e.target === resetModal) closeResetAllModal();
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeDetailModal();
            closeDeleteConfirmModal();
            closeImportModal();
            closeImportSuccessModal();
            closeImportErrorModal();
            closeResetAllModal();
        }
    });

    // Modal Reset Semua Data
    function openResetAllModal() {
        const modal = document.getElementById('resetAllModal');
        if (modal) modal.classList.add('show');
    }

    function closeResetAllModal() {
        const modal = document.getElementById('resetAllModal');
        if (modal) modal.classList.remove('show');
    }

    function handleResetAllSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('resetAllForm');
        const submitBtn = document.getElementById('btnSubmitResetAll');
        const cancelBtn = document.getElementById('btnCancelResetAll');

        submitBtn.disabled = true;
        if (cancelBtn) cancelBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s infinite linear;">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 2a10 10 0 0 1 10 10"></path>
            </svg>
            <span>Membersihkan Data...</span>`;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            closeResetAllModal();
            if (data.success) {
                // Refresh data table and stats immediately
                fetchServerFilteredData();
                showImportSuccessModal({ total: data.deleted_count || 0, inserted: 0, updated: 0 }, 'Seluruh Data Dibersihkan');
                const fileEl = document.getElementById('resFileName');
                if (fileEl) fileEl.textContent = 'Database Dikosongkan (Siap Import Baru)';

                // Reset angka counter di sidebar ke 0
                const sidebarBadge = document.getElementById('sidebarBadgeCount');
                if (sidebarBadge) sidebarBadge.textContent = '0';
            } else {
                showImportErrorModal(data.message || 'Gagal mereset data.');
            }
            submitBtn.disabled = false;
            if (cancelBtn) cancelBtn.disabled = false;
            submitBtn.innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                <span>Ya, Bersihkan Seluruh Data</span>`;
        })
        .catch(err => {
            console.error('Error resetting data:', err);
            closeResetAllModal();
            showImportErrorModal('Terjadi kesalahan jaringan atau server saat mereset data.');
            submitBtn.disabled = false;
            if (cancelBtn) cancelBtn.disabled = false;
        });
    }

    // Modal Import Excel Functions
    function openImportModal() {
        const modal = document.getElementById('importModal');
        if (modal) {
            modal.classList.add('show');
            resetImportForm();
        }
    }

    function closeImportModal() {
        const modal = document.getElementById('importModal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function resetImportForm() {
        const form = document.getElementById('importForm');
        if (form) form.reset();
        const selectedFileName = document.getElementById('selectedFileName');
        if (selectedFileName) {
            selectedFileName.style.display = 'none';
            selectedFileName.textContent = '';
        }
        const progressWrapper = document.getElementById('importProgressBarWrapper');
        if (progressWrapper) progressWrapper.style.display = 'none';
        const progressBar = document.getElementById('importProgressBar');
        if (progressBar) progressBar.style.width = '0%';
        const progressPercent = document.getElementById('importProgressPercent');
        if (progressPercent) progressPercent.textContent = '0%';
        const progressText = document.getElementById('importProgressText');
        if (progressText) progressText.textContent = 'Mengunggah berkas Excel ke server...';

        const dropzone = document.getElementById('dropzone');
        if (dropzone) dropzone.style.pointerEvents = 'auto';
        const templateBox = document.getElementById('templateOptionBox');
        if (templateBox) templateBox.style.opacity = '1';
        const tipsBox = document.getElementById('tipsInfoBox');
        if (tipsBox) tipsBox.style.display = 'block';

        const submitBtn = document.getElementById('btnSubmitImport');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <span>Mulai Proses Import</span>`;
        }
        const cancelBtn = document.getElementById('btnCancelImport');
        if (cancelBtn) cancelBtn.disabled = false;

        setStepState(1, 'idle');
        setStepState(2, 'idle');
        setStepState(3, 'idle');
        setStepState(4, 'idle');
    }

    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const nameEl = document.getElementById('selectedFileName');
            if (nameEl) {
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                nameEl.innerHTML = `📄 <strong>${file.name}</strong> (${sizeMb} MB) dipilih`;
                nameEl.style.display = 'block';
            }
        }
    }

    // Modal Success & Error UI Handlers (In-App Modal, No Browser Alert)
    function showImportSuccessModal(data, filename) {
        document.getElementById('resTotalCount').textContent = Number(data.total || 0).toLocaleString('id-ID');
        document.getElementById('resInsertedCount').textContent = Number(data.inserted || 0).toLocaleString('id-ID');
        document.getElementById('resUpdatedCount').textContent = Number(data.updated || 0).toLocaleString('id-ID');
        document.getElementById('resFileName').textContent = filename || 'Master_Excel_BankSulteng.xlsx';
        
        document.getElementById('importSuccessModal').classList.add('show');
    }

    function closeImportSuccessModal() {
        document.getElementById('importSuccessModal').classList.remove('show');
        // Refresh tabel data secara instan
        fetchServerFilteredData();
    }

    function showImportErrorModal(message, title = 'Terjadi Kesalahan') {
        const titleEl = document.getElementById('resErrorTitle');
        if (titleEl) titleEl.textContent = title;
        document.getElementById('resErrorMessage').textContent = message || 'Terjadi kesalahan saat memproses data.';
        document.getElementById('importErrorModal').classList.add('show');
    }

    function closeImportErrorModal() {
        document.getElementById('importErrorModal').classList.remove('show');
        resetImportForm();
    }

    function retryImport() {
        closeImportErrorModal();
        openImportModal();
    }

    // Step state helper
    function setStepState(stepNum, state) {
        const item = document.getElementById('step' + stepNum);
        const badge = document.getElementById('stepBadge' + stepNum);
        if (!item || !badge) return;

        item.classList.remove('active', 'completed');
        if (state === 'active') {
            item.classList.add('active');
            badge.innerHTML = `<span style="animation: spin 1s infinite linear; display: inline-block;">⟳</span>`;
        } else if (state === 'completed') {
            item.classList.add('completed');
            badge.innerHTML = `✓`;
        } else {
            badge.textContent = stepNum;
        }
    }

    // Drag & Drop event bindings
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileExcelInput');

        if (dropzone && fileInput) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                }, false);
            });

            dropzone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length > 0) {
                    fileInput.files = files;
                    handleFileSelected(fileInput);
                }
            }, false);
        }
    });

    // Real-Time Upload & Processing Handler
    let progressTimer = null;

    function handleImportSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('importForm');
        const fileInput = document.getElementById('fileExcelInput');

        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            showImportErrorModal('Silakan pilih berkas Excel (.xlsx / .xls) terlebih dahulu.');
            return;
        }

        const selectedFile = fileInput.files[0];
        const fileName = selectedFile.name;

        const submitBtn = document.getElementById('btnSubmitImport');
        const cancelBtn = document.getElementById('btnCancelImport');
        const progressWrapper = document.getElementById('importProgressBarWrapper');
        const progressBar = document.getElementById('importProgressBar');
        const progressText = document.getElementById('importProgressText');
        const progressPercent = document.getElementById('importProgressPercent');
        const dropzone = document.getElementById('dropzone');
        const templateBox = document.getElementById('templateOptionBox');
        const tipsBox = document.getElementById('tipsInfoBox');

        // UI state saat proses aktif
        submitBtn.disabled = true;
        if (cancelBtn) cancelBtn.disabled = true;
        if (dropzone) dropzone.style.pointerEvents = 'none';
        if (templateBox) templateBox.style.opacity = '0.5';
        if (tipsBox) tipsBox.style.display = 'none';

        submitBtn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s infinite linear;">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 2a10 10 0 0 1 10 10"></path>
            </svg>
            <span>Memproses Data...</span>`;
        
        progressWrapper.style.display = 'block';

        // Step 1: Uploading
        setStepState(1, 'active');
        setStepState(2, 'idle');
        setStepState(3, 'idle');
        setStepState(4, 'idle');

        progressBar.style.width = '10%';
        progressPercent.textContent = '10%';
        progressText.textContent = 'Mengunggah berkas ' + fileName + ' (' + (selectedFile.size / (1024*1024)).toFixed(2) + ' MB)...';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        // Pantau real-time upload progress (0% - 35%)
        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const percent = Math.round((event.loaded / event.total) * 35);
                progressBar.style.width = percent + '%';
                progressPercent.textContent = percent + '%';
            }
        };

        xhr.upload.onload = function() {
            // Upload selesai, backend mulai parsing
            setStepState(1, 'completed');
            setStepState(2, 'active');
            progressBar.style.width = '45%';
            progressPercent.textContent = '45%';
            progressText.textContent = 'Membaca lembar data & memvalidasi header...';

            let curPct = 45;
            progressTimer = setInterval(() => {
                curPct += 5;
                if (curPct >= 65 && curPct < 85) {
                    setStepState(2, 'completed');
                    setStepState(3, 'active');
                    progressText.textContent = 'Menyimpan & memetakan ribuan data transaksi ke database...';
                } else if (curPct >= 85 && curPct < 96) {
                    setStepState(3, 'completed');
                    setStepState(4, 'active');
                    progressText.textContent = 'Sinkronisasi master template & kalkulasi formula...';
                }
                if (curPct > 96) curPct = 96;
                progressBar.style.width = curPct + '%';
                progressPercent.textContent = curPct + '%';
            }, 300);
        };

        xhr.onload = function() {
            clearInterval(progressTimer);
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        setStepState(1, 'completed');
                        setStepState(2, 'completed');
                        setStepState(3, 'completed');
                        setStepState(4, 'completed');
                        progressBar.style.width = '100%';
                        progressPercent.textContent = '100%';
                        progressText.textContent = 'Proses import dan sinkronisasi selesai!';

                        setTimeout(() => {
                            closeImportModal();
                            showImportSuccessModal(data.data || {}, fileName);
                        }, 400);
                    } else {
                        closeImportModal();
                        showImportErrorModal(data.message || 'Gagal memproses berkas Excel.');
                    }
                } catch (e) {
                    closeImportModal();
                    showImportErrorModal('Format respon server tidak valid.');
                }
            } else {
                closeImportModal();
                try {
                    const errObj = JSON.parse(xhr.responseText);
                    const msg = errObj.message || (errObj.errors && Object.values(errObj.errors).flat().join(', ')) || 'Terjadi kesalahan pada server.';
                    showImportErrorModal(msg);
                } catch (e) {
                    showImportErrorModal('Gagal mengunggah berkas (Status: ' + xhr.status + ').');
                }
            }
        };

        xhr.onerror = function() {
            clearInterval(progressTimer);
            closeImportModal();
            showImportErrorModal('Terjadi kegagalan jaringan atau koneksi ke server terputus.');
        };

        const formData = new FormData(form);
        xhr.send(formData);
    }

    // Real-Time High-Speed Excel Export UI Handler
    let exportTimer = null;

    function setExportStepState(stepNum, state) {
        const item = document.getElementById('expStep' + stepNum);
        const badge = document.getElementById('expStepBadge' + stepNum);
        if (!item || !badge) return;

        item.classList.remove('active', 'completed');
        if (state === 'active') {
            item.classList.add('active');
            badge.innerHTML = `<span style="animation: spin 1s infinite linear; display: inline-block;">⟳</span>`;
        } else if (state === 'completed') {
            item.classList.add('completed');
            badge.innerHTML = `✓`;
        } else {
            badge.textContent = stepNum;
        }
    }

    function openExportModal() {
        const modal = document.getElementById('exportProgressModal');
        if (!modal) return;

        // Reset export UI elements
        document.getElementById('exportSpinnerSvg').style.display = 'block';
        document.getElementById('exportSuccessCheckSvg').style.display = 'none';
        document.getElementById('exportIconWrapper').style.background = '#ecfdf5';
        document.getElementById('exportIconWrapper').style.borderColor = '#a7f3d0';
        document.getElementById('exportModalTitle').textContent = 'Mengekspor Berkas Master Excel...';
        document.getElementById('exportStatusTitle').textContent = 'Menyiapkan Data Transaksi';
        document.getElementById('exportStatusSub').textContent = 'Mengagregasikan ribuan baris data jurnal dengan relasi master...';
        document.getElementById('exportProgressBar').style.width = '15%';
        document.getElementById('exportProgressPercent').textContent = '15%';
        document.getElementById('exportProgressText').textContent = 'Mengambil data dari server...';
        document.getElementById('btnCancelExport').style.display = 'none';

        setExportStepState(1, 'active');
        setExportStepState(2, 'idle');
        setExportStepState(3, 'idle');
        setExportStepState(4, 'idle');

        modal.classList.add('show');
    }

    function closeExportModal() {
        const modal = document.getElementById('exportProgressModal');
        if (modal) modal.classList.remove('show');
        if (exportTimer) clearInterval(exportTimer);
    }

    function startExportProcess() {
        openExportModal();

        const searchParams = new URLSearchParams(window.location.search);
        const exportBaseUrl = "{{ route('jurnal.export_excel') }}";
        const finalExportUrl = exportBaseUrl + (searchParams.toString() ? '?' + searchParams.toString() : '');

        let curPct = 15;
        exportTimer = setInterval(() => {
            curPct += 15;
            if (curPct >= 35 && curPct < 65) {
                setExportStepState(1, 'completed');
                setExportStepState(2, 'active');
                document.getElementById('exportStatusTitle').textContent = 'Menyusun Lembar Data';
                document.getElementById('exportStatusSub').textContent = 'Menyusun Sheet DATA_KELUHAN dengan format perbankan...';
                document.getElementById('exportProgressText').textContent = 'Menyusun lembar data master...';
            } else if (curPct >= 65 && curPct < 88) {
                setExportStepState(2, 'completed');
                setExportStepState(3, 'active');
                document.getElementById('exportStatusTitle').textContent = 'Menghubungkan Formula Multi-Sheet';
                document.getElementById('exportStatusSub').textContent = 'Menautkan formula interaktif Slip Jurnal & Rekapitulasi Cabang...';
                document.getElementById('exportProgressText').textContent = 'Kalkulasi relasi formula...';
            } else if (curPct >= 88 && curPct < 96) {
                setExportStepState(3, 'completed');
                setExportStepState(4, 'active');
                document.getElementById('exportStatusTitle').textContent = 'Mengompresi Berkas Excel';
                document.getElementById('exportStatusSub').textContent = 'Menyiapkan berkas .xlsx untuk diunduh ke browser...';
                document.getElementById('exportProgressText').textContent = 'Mempersiapkan unduhan...';
            }
            if (curPct > 95) curPct = 95;
            document.getElementById('exportProgressBar').style.width = curPct + '%';
            document.getElementById('exportProgressPercent').textContent = curPct + '%';
        }, 200);

        fetch(finalExportUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            clearInterval(exportTimer);

            if (!response.ok) {
                throw new Error('Gagal mengekspor data (Status: ' + response.status + ')');
            }

            // Dapatkan nama file dari header Content-Disposition
            let filename = 'Jurnal_Keluhan_BankSulteng_' + new Date().toISOString().slice(0, 10) + '.xlsx';
            const disposition = response.headers.get('Content-Disposition');
            if (disposition && disposition.indexOf('filename=') !== -1) {
                const matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                if (matches != null && matches[1]) {
                    filename = matches[1].replace(/['"]/g, '');
                }
            }

            const totalRows = response.headers.get('X-Total-Count') || '';

            const blob = await response.blob();
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = downloadUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(downloadUrl);
            }, 200);

            // Tampilkan UI Sukses
            setExportStepState(1, 'completed');
            setExportStepState(2, 'completed');
            setExportStepState(3, 'completed');
            setExportStepState(4, 'completed');

            document.getElementById('exportProgressBar').style.width = '100%';
            document.getElementById('exportProgressPercent').textContent = '100%';
            document.getElementById('exportProgressText').textContent = 'Berkas Excel berhasil diunduh!';

            document.getElementById('exportSpinnerSvg').style.display = 'none';
            document.getElementById('exportSuccessCheckSvg').style.display = 'block';
            document.getElementById('exportIconWrapper').style.background = '#dcfce7';
            document.getElementById('exportIconWrapper').style.borderColor = '#86efac';

            document.getElementById('exportStatusTitle').textContent = 'Ekspor Selesai!';
            document.getElementById('exportStatusSub').textContent = (totalRows ? totalRows + ' baris transaksi' : 'Seluruh data transaksi') + ' berhasil diekspor ke format multi-sheet.';

            setTimeout(() => {
                closeExportModal();
            }, 1800);
        })
        .catch(err => {
            clearInterval(exportTimer);
            console.error('Export error:', err);
            closeExportModal();
            showImportErrorModal('Gagal mengekspor berkas Excel: ' + (err.message || 'Terjadi kesalahan server.'), 'Ekspor Gagal');
        });
    }
</script>
@endpush
