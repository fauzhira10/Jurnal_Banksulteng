@extends('layouts.app')

@section('title', 'Data Keluhan Nasabah')
@section('page_title', 'Data Jurnal Keluhan')
@section('page_subtitle', 'Rekapitulasi dan pencarian data keluhan transaksi nasabah Bank Sulteng')

@section('content')

<!-- Card Filter & Pencarian Cerdas -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs mb-6 overflow-hidden transition-all duration-200 hover:shadow-md">
    <!-- Header Card -->
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50/80 via-white to-slate-50/50 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-blue to-navy text-white flex items-center justify-center shadow-xs shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
            </div>
            <div>
                <h3 class="text-[15px] font-bold text-navy leading-tight flex items-center gap-2">
                    <span>Pencarian & Filter Data Keluhan</span>
                    <span id="activeFilterBadgeCount" class="hidden text-[11px] font-bold px-2 py-0.5 rounded-full bg-brand-blue/10 text-brand-blue border border-brand-blue/20">0 filter aktif</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Filter cepat berdasarkan kata kunci, kantor cabang, terminal mesin, status, atau periode transaksi</p>
            </div>
        </div>

        <!-- Action Header (Reset Filter Button) -->
        <div class="flex items-center gap-2">
            <button type="button" onclick="resetAllFilters()" id="btnResetFilter" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100 hover:border-rose-300 transition-all cursor-pointer shadow-2xs">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                    <path d="M3 3v5h5"></path>
                </svg>
                <span>Reset Filter</span>
            </button>
        </div>
    </div>

    <!-- Body Card -->
    <div class="p-6 space-y-4">
        <form id="filterForm" method="GET" action="{{ route('jurnal.index') }}" onsubmit="return false;">
            <!-- Tier 1: Hero Search Input -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-blue">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="searchInput" 
                    name="q" 
                    value="{{ request('q') }}" 
                    class="w-full h-12 pl-11 pr-24 text-[13.5px] font-medium text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl placeholder:text-slate-400 placeholder:text-xs sm:placeholder:text-[13px] focus:bg-white focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 focus:outline-none transition-all shadow-2xs" 
                    placeholder="Ketik nama nasabah, nomor resi/trace, nomor rekening, nomor kartu debit, atau nomor tiket CS..." 
                    autocomplete="off"
                >
                <!-- Right Action Buttons inside Search Input -->
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center gap-1.5">
                    <button 
                        type="button" 
                        id="btnClearSearch" 
                        title="Hapus Kata Kunci" 
                        class="hidden w-7 h-7 rounded-lg bg-slate-200/80 hover:bg-slate-300 text-slate-600 items-center justify-center transition-colors cursor-pointer text-xs"
                    >
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <span class="hidden sm:inline-flex items-center text-[10.5px] font-bold text-slate-400 bg-slate-200/60 px-2 py-0.5 rounded-md border border-slate-300/50">
                        Live Search
                    </span>
                </div>
            </div>

            <!-- Tier 2: Filter Grid Controls (4 Balanced Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-1">
                <!-- 1. Filter Kantor Cabang -->
                <div class="flex flex-col gap-1.5">
                    <label for="filterCabang" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-blue">
                            <path d="M3 21h18"></path>
                            <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
                            <path d="M9 9h1"></path>
                            <path d="M9 13h1"></path>
                            <path d="M9 17h1"></path>
                            <path d="M14 9h1"></path>
                            <path d="M14 13h1"></path>
                            <path d="M14 17h1"></path>
                        </svg>
                        <span>Kantor Cabang</span>
                    </label>
                    <div class="relative">
                        <select id="filterCabang" name="master_cabang_id" class="w-full h-10 px-3 pr-8 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl appearance-none cursor-pointer focus:bg-white focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none transition-all">
                            <option value="" data-kode="">-- Semua Cabang --</option>
                            @foreach($cabangs as $c)
                                <option value="{{ $c->id }}" data-kode="{{ $c->kode_cabang ?? '' }}" {{ request('master_cabang_id') == $c->id ? 'selected' : '' }}>
                                    {{ !empty($c->kode_cabang) && strtoupper(trim($c->nama_cabang)) !== 'CALL CENTER' ? $c->kode_cabang . ' - ' : '' }}{{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 2. Filter Terminal / Mesin ATM -->
                <div class="flex flex-col gap-1.5">
                    <label for="filterTerminal" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-blue">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="6" x2="6.01" y2="6"></line>
                            <line x1="6" y1="18" x2="6.01" y2="18"></line>
                        </svg>
                        <span>Terminal / Mesin ATM</span>
                    </label>
                    <div class="relative">
                        <select id="filterTerminal" name="terminal_transaksi" class="w-full h-10 px-3 pr-8 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl appearance-none cursor-pointer focus:bg-white focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none transition-all">
                            <option value="">-- Semua Terminal / Mesin --</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 3. Filter Status Penanganan -->
                <div class="flex flex-col gap-1.5">
                    <label for="filterStatus" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-blue">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 14 14"></polyline>
                        </svg>
                        <span>Status Keluhan</span>
                    </label>
                    <div class="relative">
                        <select id="filterStatus" name="status" class="w-full h-10 px-3 pr-8 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl appearance-none cursor-pointer focus:bg-white focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none transition-all">
                            <option value="">-- Semua Status --</option>
                            <option value="-" {{ request('status') === '-' ? 'selected' : '' }}>⚪ - (Belum Ditentukan)</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>🟡 Menunggu</option>
                            <option value="Success" {{ request('status') == 'Success' ? 'selected' : '' }}>🔵 Success</option>
                            <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>🟢 Done</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>🔴 Rejected</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 4. Filter Rentang Tanggal Transaksi (Dari s/d Sampai) -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-blue">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Periode Transaksi (Dari - Sampai)</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <input 
                            type="date" 
                            id="filterTglDari" 
                            name="tgl_dari" 
                            value="{{ request('tgl_dari') }}" 
                            class="w-full h-10 px-2.5 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none transition-all cursor-pointer" 
                            title="Tanggal Transaksi (Dari)"
                        >
                        <input 
                            type="date" 
                            id="filterTglSampai" 
                            name="tgl_sampai" 
                            value="{{ request('tgl_sampai') }}" 
                            class="w-full h-10 px-2.5 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none transition-all cursor-pointer" 
                            title="Tanggal Transaksi (Sampai)"
                        >
                    </div>
                </div>
            </div>

            <!-- Tier 3: Active Filter Chips Bar (Dynamic Pills) -->
            <div id="activeFilterChipsContainer" class="hidden pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Filter Aktif:</span>
                <div id="activeFilterChipsList" class="flex flex-wrap items-center gap-1.5">
                    <!-- Populated dynamically via JS -->
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

            <div class="table-container" id="tableContainer" style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
                <table class="custom-table" id="jurnalTable" style="min-width: 1100px; width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th style="min-width: 130px;">Tanggal</th>
                            <th style="min-width: 200px;">Data Nasabah</th>
                            <th style="min-width: 170px;">Kantor Cabang</th>
                            <th style="min-width: 170px;">Jenis Transaksi</th>
                            <th style="text-align: center; width: 110px; min-width: 105px;">Channel</th>
                            <th style="min-width: 160px;">Nominal Transaksi</th>
                            <th style="min-width: 105px;">Status</th>
                            <th style="text-align: center; width: 100px; min-width: 95px;">Aksi</th>
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
                                    @if(!empty($jurnal->masterCabang->kode_cabang) && strtoupper(trim($jurnal->masterCabang->nama_cabang ?? '')) !== 'CALL CENTER')
                                        <div class="highlightable" style="font-size: 11.5px; color: var(--bs-gray-500); margin-top: 2px;">
                                            Kode: {{ $jurnal->masterCabang->kode_cabang }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="highlightable" style="font-weight: 600; color: var(--bs-navy);">
                                        {{ $jurnal->masterTransaksi->jenis_transaksi ?? '-' }}
                                    </div>
                                    @if(!empty($jurnal->terminal_transaksi) && $jurnal->terminal_transaksi !== '-')
                                        @php
                                            $atmInfoRow = \App\Models\MasterAtm::findAtmInfo($jurnal->terminal_transaksi);
                                            // Ambil nama profil murni (tanpa prefix ID Mesin, misal "187 - CRM.SALAKAN" -> "CRM.SALAKAN")
                                            $displayNamaMesin = $atmInfoRow['profil'] ?? $jurnal->terminal_transaksi;
                                            $displayNamaMesin = preg_replace('/^\d+\s*[-_]\s*/', '', $displayNamaMesin);
                                            $idMesin = $atmInfoRow['id_luno'] ?? '';
                                            if (empty($idMesin) && preg_match('/^(\d+)\s*[-_]/', $jurnal->terminal_transaksi, $matches)) {
                                                $idMesin = $matches[1];
                                            }
                                        @endphp
                                        <div style="font-size: 12.5px; color: var(--bs-blue); margin-top: 4px; font-weight: 500; display: flex; flex-direction: column; align-items: flex-start; gap: 3px;">
                                            <span>Mesin: <strong class="highlightable font-semibold" style="color: var(--bs-navy); font-size: 13px;">{{ $displayNamaMesin }}</strong></span>
                                            @if(!empty($idMesin))
                                                <span class="highlightable" style="padding: 2px 7px; background: #e0f2fe; color: #0369a1; border-radius: 5px; font-size: 11.5px; font-family: monospace; font-weight: 800; border: 1.5px solid #bae6fd; letter-spacing: 0.2px;">
                                                    ID Mesin: {{ $idMesin }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
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
                                    @php
                                        $hasLog = !empty($jurnal->keterangan_log) && trim($jurnal->keterangan_log) !== '-' && trim($jurnal->keterangan_log) !== '' && trim(strtolower($jurnal->keterangan_log)) !== 'tidak ada keterangan tambahan.';
                                    @endphp
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="showDetailModal({{ json_encode($jurnal) }})" title="Lihat Rincian & Aksi" style="padding: 6px 12px; font-size: 12.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                        </svg>
                                        <span>Detail</span>
                                    </button>
                                    @if($hasLog)
                                        <a href="/jurnal/{{ $jurnal->id }}/download" target="_blank" class="btn btn-sm btn-cetak-action" data-jurnal-id="{{ $jurnal->id }}" style="background-color: #059669; color: white; padding: 6px 12px; font-size: 12.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; border-radius: 4px; margin-left: 5px; border: 1px solid #047857;" title="Preview Cetak Dokumen Jurnal">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                                <rect x="6" y="14" width="12" height="8"></rect>
                                            </svg>
                                            <span>Cetak</span>
                                        </a>
                                    @else
                                        <button type="button" onclick="openQuickLogModal({{ json_encode($jurnal) }}, true)" class="btn btn-sm btn-cetak-action" data-jurnal-id="{{ $jurnal->id }}" style="background-color: #fff7ed; color: #c2410c; padding: 6px 11px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; border-radius: 4px; margin-left: 5px; border: 1px dashed #ea580c; cursor: pointer;" title="Keterangan log masih kosong. Klik untuk mengisi log & mencetak.">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                            </svg>
                                            <span>Log Kosong 🔒</span>
                                        </button>
                                    @endif
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

            <!-- Permasalahan -->
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>Keterangan Keluhan</span>
                </div>
                <div class="detail-keterangan-box" id="modal_permasalahan">
                    -
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
                <div id="modal_keterangan_log" style="margin-top: 4px;">
                    <!-- Konten dinamis di-render via JavaScript -->
                </div>
            </div>
        </div>

        <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--bs-gray-200); display: flex; justify-content: flex-end; align-items: center; gap: 10px; background: var(--bs-gray-50); flex-wrap: wrap;">
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
            <a id="modalBtnCetak" href="#" target="_blank" class="btn" style="background-color: #059669; color: #ffffff; border: 1px solid #047857; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; padding: 8px 16px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span id="modalBtnCetakText">Cetak Dokumen</span>
            </a>
        </div>
    </div>
</div>

<!-- Modal Pop-Up Cepat Isi Keterangan Log (Quick Log Modal) -->
<div class="modal-backdrop" id="quickLogModal" style="z-index: 1200;">
    <div class="modal-content" style="max-width: 580px; border-top: 4px solid #ea580c; animation: modalFadeIn 0.2s ease-out;">
        <div class="modal-header" style="background-color: #fff7ed; border-bottom: 1px solid #ffedd5;">
            <h3 style="color: #c2410c; display: flex; align-items: center; gap: 8px; font-size: 15.5px; font-weight: 700;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Isi Keterangan Log Keluhan</span>
            </h3>
            <button class="btn-close-modal" onclick="closeQuickLogModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="modal-body" style="padding: 20px 24px;">
            <!-- Brief Context Box -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; margin-bottom: 14px; font-size: 12px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 8px;">
                    <div><span style="color: #64748b;">Nasabah:</span> <strong id="quickLogNasabah" style="color: #0f172a;">-</strong></div>
                    <div><span style="color: #64748b;">No. Resi/Trace:</span> <strong id="quickLogNoResi" style="color: #0284c7;">-</strong></div>
                    <div><span style="color: #64748b;">Nominal:</span> <strong id="quickLogNominal" style="color: #059669;">-</strong></div>
                    <div><span style="color: #64748b;">Terminal/Channel:</span> <strong id="quickLogTerminal" style="color: #0f172a;">-</strong></div>
                </div>
            </div>

            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; font-size: 11.5px; color: #92400e; display: flex; align-items: flex-start; gap: 8px; line-height: 1.45;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>Format cetak dokumen resmi mewajibkan adanya catatan hasil pemeriksaan log mesin atau switching transaksi sebelum dapat dicetak.</span>
            </div>

            <!-- Form Control: Textarea Edit Log -->
            <div style="margin-bottom: 4px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label for="quickLogTextarea" style="font-size: 12px; font-weight: 700; color: #334155;">
                        ✏️ Keterangan Log Hasil Pemeriksaan:
                    </label>
                    <span id="quickLogCharCount" style="font-size: 11px; color: #94a3b8; font-weight: 600;">0 karakter</span>
                </div>
                <textarea id="quickLogTextarea" rows="4" oninput="updateQuickLogCharCount()" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 12.5px; color: #1e293b; outline: none; resize: vertical; box-sizing: border-box; line-height: 1.5;" placeholder="Ketik keterangan hasil pemeriksaan log transaksi di sini..."></textarea>
            </div>

            <div id="quickLogFeedback" style="display: none; font-size: 12px; font-weight: 600; padding: 8px 12px; border-radius: 6px; margin-top: 10px;"></div>
        </div>

        <div class="modal-footer" style="background-color: #fafafa; border-top: 1px solid var(--bs-gray-200); padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <button type="button" class="btn btn-secondary" onclick="closeQuickLogModal()">
                <span>Batal</span>
            </button>
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" id="btnSaveQuickLogOnly" onclick="saveQuickLog(false)" class="btn" style="background-color: #0284c7; color: #ffffff; border: 1px solid #0369a1; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 8px 15px; cursor: pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span>Simpan Log</span>
                </button>
                <button type="button" id="btnSaveQuickLogAndPrint" onclick="saveQuickLog(true)" class="btn" style="background-color: #059669; color: #ffffff; border: 1px solid #047857; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 8px 15px; cursor: pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span>Simpan & Buka Cetak</span>
                </button>
            </div>
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
    // Master ATM Lookup & Cascading Data
    const atmsGrouped = @json($atmsGrouped);

    function findAtmInfoJs(terminal) {
        if (!terminal || terminal === '-' || terminal.toUpperCase() === 'BANK LAIN' || terminal.toUpperCase() === 'MOBILE BANKING' || terminal.toUpperCase() === 'SMS BANKING') {
            return null;
        }
        const termClean = terminal.trim().toUpperCase();
        for (const kode in atmsGrouped) {
            const atms = atmsGrouped[kode];
            for (const atm of atms) {
                const val = (atm.value || '').toUpperCase().trim();
                const prof = (atm.profil || '').toUpperCase().trim();
                const id = String(atm.id_luno || '').trim();
                if (termClean === val || termClean === prof || termClean.includes(prof) || val.includes(termClean) || (id && termClean === id)) {
                    return atm;
                }
            }
        }
        return null;
    }

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
        const regexParts = terms.map(t => {
            const esc = t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            if (/^[a-z0-9]+\.[a-z0-9]+$/i.test(t)) {
                return `(?:^|\\b)${esc}(?:\\b|$)`;
            }
            return esc;
        });
        const regex = new RegExp(`(${regexParts.join('|')})`, 'gi');

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
        const terms = q.split(/\s+/).filter(t => t.length > 0 && t !== '-');
        let visibleCount = 0;

        rows.forEach(row => {
            const searchData = (row.getAttribute('data-search') || '').toLowerCase();
            const matches = terms.length === 0 || terms.every(term => {
                if (/^[a-z0-9]+\.[a-z0-9]+$/i.test(term)) {
                    const escaped = term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                    const regex = new RegExp('(?:^|[\\s\\-_/\\(\\[])' + escaped + '(?=$|[\\s\\-_/\\)\\]])', 'i');
                    return regex.test(searchData);
                }
                return searchData.includes(term);
            });

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
        const qVal = document.getElementById('searchInput') ? document.getElementById('searchInput').value : '';
        const statusVal = document.getElementById('filterStatus') ? document.getElementById('filterStatus').value : '';
        const cabangVal = document.getElementById('filterCabang') ? document.getElementById('filterCabang').value : '';
        const terminalVal = document.getElementById('filterTerminal') ? document.getElementById('filterTerminal').value : '';
        const tglDariVal = document.getElementById('filterTglDari') ? document.getElementById('filterTglDari').value : '';
        const tglSampaiVal = document.getElementById('filterTglSampai') ? document.getElementById('filterTglSampai').value : '';
        const perPageEl = document.getElementById('perPageSelect');
        const perPageVal = perPageEl ? perPageEl.value : '10';

        // Tampilkan / Sembunyikan Tombol Reset
        const hasFilters = qVal || statusVal || cabangVal || terminalVal || tglDariVal || tglSampaiVal;
        const btnReset = document.getElementById('btnResetFilter');
        if (btnReset) btnReset.style.display = hasFilters ? 'inline-flex' : 'none';

        let url = urlOverride;
        if (!url) {
            const params = new URLSearchParams();
            if (qVal) params.set('q', qVal);
            if (statusVal) params.set('status', statusVal);
            if (cabangVal) params.set('master_cabang_id', cabangVal);
            if (terminalVal) params.set('terminal_transaksi', terminalVal);
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
            if (terminalVal) exportParams.set('terminal_transaksi', terminalVal);
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
                updateActiveFilterChips();
            })
            .catch(err => console.error('Gagal mengambil data pencarian:', err));
    }

    // Dynamic Filter Chips (Pill Badges) & Reset Helpers
    function updateActiveFilterChips() {
        const qVal = document.getElementById('searchInput') ? document.getElementById('searchInput').value.trim() : '';
        const statusVal = document.getElementById('filterStatus') ? document.getElementById('filterStatus').value : '';
        const cabangEl = document.getElementById('filterCabang');
        const cabangVal = cabangEl ? cabangEl.value : '';
        const cabangText = cabangEl && cabangEl.selectedIndex > 0 ? cabangEl.options[cabangEl.selectedIndex].text.trim() : '';
        const terminalEl = document.getElementById('filterTerminal');
        const terminalVal = terminalEl ? terminalEl.value : '';
        const tglDariVal = document.getElementById('filterTglDari') ? document.getElementById('filterTglDari').value : '';
        const tglSampaiVal = document.getElementById('filterTglSampai') ? document.getElementById('filterTglSampai').value : '';

        const chipsContainer = document.getElementById('activeFilterChipsContainer');
        const chipsList = document.getElementById('activeFilterChipsList');
        const badgeCount = document.getElementById('activeFilterBadgeCount');
        const btnReset = document.getElementById('btnResetFilter');

        if (!chipsContainer || !chipsList) return;

        chipsList.innerHTML = '';
        let count = 0;

        if (qVal) {
            count++;
            chipsList.appendChild(createChipElement('Pencarian', `"${qVal}"`, () => {
                document.getElementById('searchInput').value = '';
                const btnClear = document.getElementById('btnClearSearch');
                if (btnClear) btnClear.style.display = 'none';
                performClientSideFilter('');
                fetchServerFilteredData();
            }));
        }

        if (cabangVal) {
            count++;
            chipsList.appendChild(createChipElement('Cabang', cabangText, () => {
                cabangEl.value = '';
                populateFilterTerminalData('');
                fetchServerFilteredData();
            }));
        }

        if (terminalVal) {
            count++;
            chipsList.appendChild(createChipElement('Terminal', terminalVal, () => {
                terminalEl.value = '';
                fetchServerFilteredData();
            }));
        }

        if (statusVal) {
            count++;
            chipsList.appendChild(createChipElement('Status', statusVal, () => {
                document.getElementById('filterStatus').value = '';
                fetchServerFilteredData();
            }));
        }

        if (tglDariVal || tglSampaiVal) {
            count++;
            const rangeText = (tglDariVal ? tglDariVal : '...') + ' s/d ' + (tglSampaiVal ? tglSampaiVal : '...');
            chipsList.appendChild(createChipElement('Periode', rangeText, () => {
                if (document.getElementById('filterTglDari')) document.getElementById('filterTglDari').value = '';
                if (document.getElementById('filterTglSampai')) document.getElementById('filterTglSampai').value = '';
                fetchServerFilteredData();
            }));
        }

        if (count > 0) {
            chipsContainer.classList.remove('hidden');
            chipsContainer.classList.add('flex');
            if (badgeCount) {
                badgeCount.textContent = `${count} filter aktif`;
                badgeCount.classList.remove('hidden');
            }
            if (btnReset) {
                btnReset.classList.remove('hidden');
                btnReset.classList.add('inline-flex');
            }
        } else {
            chipsContainer.classList.add('hidden');
            chipsContainer.classList.remove('flex');
            if (badgeCount) badgeCount.classList.add('hidden');
            if (btnReset) {
                btnReset.classList.add('hidden');
                btnReset.classList.remove('inline-flex');
            }
        }
    }

    function createChipElement(label, value, onRemove) {
        const chip = document.createElement('div');
        chip.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-blue/10 text-navy border border-brand-blue/20 shadow-2xs transition-all hover:bg-brand-blue/15';
        chip.innerHTML = `<span class="text-slate-500 font-normal text-[11px]">${label}:</span> <strong class="text-navy font-bold truncate max-w-[220px] text-[11.5px]">${value}</strong>
            <button type="button" class="w-4 h-4 rounded-full bg-slate-200/80 hover:bg-rose-100 hover:text-rose-600 flex items-center justify-center ml-0.5 transition-colors cursor-pointer text-[10px]" title="Hapus filter ini">✕</button>`;
        chip.querySelector('button').addEventListener('click', onRemove);
        return chip;
    }

    function resetAllFilters() {
        if (document.getElementById('searchInput')) document.getElementById('searchInput').value = '';
        if (document.getElementById('btnClearSearch')) document.getElementById('btnClearSearch').style.display = 'none';
        if (document.getElementById('filterStatus')) document.getElementById('filterStatus').value = '';
        if (document.getElementById('filterCabang')) document.getElementById('filterCabang').value = '';
        if (document.getElementById('filterTerminal')) document.getElementById('filterTerminal').value = '';
        if (document.getElementById('filterTglDari')) document.getElementById('filterTglDari').value = '';
        if (document.getElementById('filterTglSampai')) document.getElementById('filterTglSampai').value = '';
        populateFilterTerminalData('');
        performClientSideFilter('');
        fetchServerFilteredData();
    }

    // Handler Ganti Jumlah Baris Per Halaman (10, 50, 100)
    function handlePerPageChange(val) {
        const qVal = document.getElementById('searchInput') ? document.getElementById('searchInput').value : '';
        const statusVal = document.getElementById('filterStatus') ? document.getElementById('filterStatus').value : '';
        const cabangVal = document.getElementById('filterCabang') ? document.getElementById('filterCabang').value : '';
        const terminalVal = document.getElementById('filterTerminal') ? document.getElementById('filterTerminal').value : '';
        const tglDariVal = document.getElementById('filterTglDari') ? document.getElementById('filterTglDari').value : '';
        const tglSampaiVal = document.getElementById('filterTglSampai') ? document.getElementById('filterTglSampai').value : '';

        const params = new URLSearchParams();
        if (qVal) params.set('q', qVal);
        if (statusVal) params.set('status', statusVal);
        if (cabangVal) params.set('master_cabang_id', cabangVal);
        if (terminalVal) params.set('terminal_transaksi', terminalVal);
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
        const filterTerminal = document.getElementById('filterTerminal');
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
        [filterStatus, filterCabang, filterTerminal, filterTglDari, filterTglSampai].forEach(el => {
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
        updateActiveFilterChips();
    });

    // State Manajemen Modal
    let currentDetailJurnal = null;
    let currentQuickLogJurnal = null;

    function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[m];
        });
    }

    // Formatting & Modal Detail Functions
    function formatDateIndo(dateStr) {
        if(!dateStr || dateStr === '-' || dateStr === 'null') return '-';
        const d = new Date(dateStr);
        if(isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function showDetailModal(jurnal) {
        currentDetailJurnal = jurnal;
        document.getElementById('modal_nama_nasabah').textContent = jurnal.nama_nasabah || '-';
        document.getElementById('modal_no_rekening').textContent = jurnal.no_rekening || '-';
        document.getElementById('modal_no_resi').textContent = jurnal.no_resi || '-';
        document.getElementById('modal_no_kartu').textContent = jurnal.no_kartu || '-';
        document.getElementById('modal_no_tiket').textContent = jurnal.no_tiket || '-';
        let cabangText = '-';
        if (jurnal.master_cabang) {
            const cName = jurnal.master_cabang.nama_cabang || '-';
            const cCode = jurnal.master_cabang.kode_cabang;
            cabangText = (cCode && cName.toUpperCase().trim() !== 'CALL CENTER') ? `${cCode} - ${cName}` : cName;
        }
        document.getElementById('modal_cabang').textContent = cabangText;
        
        document.getElementById('modal_jenis_transaksi').textContent = (jurnal.master_transaksi ? jurnal.master_transaksi.jenis_transaksi : '-');
        document.getElementById('modal_channel').textContent = (jurnal.master_transaksi ? jurnal.master_transaksi.channel : '-');
        
        const nominal = Number(jurnal.nominal_transaksi) || 0;
        document.getElementById('modal_nominal_transaksi').textContent = 'Rp ' + nominal.toLocaleString('id-ID');

        const fee = Number(jurnal.biaya_admin ?? (jurnal.master_transaksi ? jurnal.master_transaksi.biaya_admin : 0)) || 0;
        document.getElementById('modal_biaya_admin').textContent = fee > 0 ? 'Rp ' + fee.toLocaleString('id-ID') : '- (Rp 0)';
        
        const term = (jurnal.terminal_transaksi || '-').trim();
        const atmInfo = findAtmInfoJs(term);
        let displayNama = (atmInfo && atmInfo.profil) ? atmInfo.profil : term;
        displayNama = displayNama.replace(/^\d+\s*[-_]\s*/, '');
        let idMesin = (atmInfo && atmInfo.id_luno) ? atmInfo.id_luno : '';
        if (!idMesin) {
            const m = term.match(/^(\d+)\s*[-_]/);
            if (m) idMesin = m[1];
        }

        if (term && term !== '-') {
            if (idMesin) {
                document.getElementById('modal_terminal_transaksi').innerHTML = `
                    <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
                        <span style="font-weight: 700; color: var(--bs-navy); font-size: 15px;">${escapeHtml(displayNama)}</span>
                        <span style="display: inline-flex; align-items: center; padding: 2.5px 8px; background: #e0f2fe; color: #0369a1; border-radius: 5px; font-weight: 800; font-size: 12px; font-family: monospace; border: 1.5px solid #bae6fd; letter-spacing: 0.2px;">
                            ID Mesin: ${escapeHtml(idMesin)}
                        </span>
                    </div>
                `;
            } else {
                document.getElementById('modal_terminal_transaksi').innerHTML = `
                    <span style="font-weight: 700; color: var(--bs-navy); font-size: 15px;">${escapeHtml(displayNama)}</span>
                `;
            }
        } else {
            document.getElementById('modal_terminal_transaksi').textContent = '-';
        }
        
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
        
        document.getElementById('modal_permasalahan').textContent = jurnal.permasalahan || '-';

        // Validasi Keterangan Log secara Cerdas
        const hasLog = Boolean(jurnal.keterangan_log && jurnal.keterangan_log.trim() !== '' && jurnal.keterangan_log.trim() !== '-' && jurnal.keterangan_log.trim().toLowerCase() !== 'tidak ada keterangan tambahan.');
        const logContainer = document.getElementById('modal_keterangan_log');

        if (hasLog) {
            logContainer.innerHTML = `
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #a7f3d0; margin-top: 1px;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px;">
                                Catatan Hasil Pemeriksaan Log Transaksi:
                            </div>
                            <div style="color: #1e293b; line-height: 1.55; font-size: 13px; font-weight: 500; white-space: pre-wrap;">${escapeHtml(jurnal.keterangan_log)}</div>
                        </div>
                    </div>
                    <button type="button" onclick="openQuickLogFromDetail(false)" style="background: #ffffff; color: #0284c7; border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 12px; font-size: 11.5px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 1px 2px rgba(0,0,0,0.04);" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#0284c7';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#cbd5e1';">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit Log</span>
                    </button>
                </div>
            `;
        } else {
            logContainer.innerHTML = `
                <div style="background: linear-gradient(135deg, #fffdf7 0%, #fff7ed 100%); border: 1.5px solid #fed7aa; border-radius: 12px; padding: 16px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <!-- Header Card: Icon + Title + Badge -->
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: #ffedd5; color: #ea580c; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #fdba74;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e293b; font-size: 14px; line-height: 1.2;">
                                    Keterangan Log Belum Diisi
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                                    Catatan hasil pemeriksaan switching / mesin ATM wajib dilengkapi
                                </div>
                            </div>
                        </div>
                        <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 700; color: #b45309; background: #fef3c7; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 20px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Wajib untuk Cetak
                        </span>
                    </div>

                    <!-- Divider Halus -->
                    <div style="height: 1px; background: #fed7aa; margin: 12px 0; opacity: 0.7;"></div>

                    <!-- Bottom Action Row -->
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                        <p style="font-size: 12px; color: #475569; margin: 0; line-height: 1.45; flex: 1; min-width: 220px;">
                            Formulir keluhan resmi nasabah belum dapat dicetak sebelum hasil pemeriksaan log dimasukkan.
                        </p>
                        <button type="button" onclick="openQuickLogFromDetail(false)" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); color: #ffffff; border: none; border-radius: 8px; padding: 8px 18px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 5px rgba(234, 88, 12, 0.25); white-space: nowrap; transition: all 0.15s ease;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 10px rgba(234, 88, 12, 0.35)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 5px rgba(234, 88, 12, 0.25)';">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            <span>Isi Keterangan Log</span>
                        </button>
                    </div>
                </div>
            `;
        }

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

        // Set Handler Cetak di Modal Detail
        const cetakBtn = document.getElementById('modalBtnCetak');
        const cetakBtnText = document.getElementById('modalBtnCetakText');
        if (cetakBtn) {
            if (hasLog) {
                cetakBtn.href = '/jurnal/' + jurnal.id + '/download';
                cetakBtn.target = '_blank';
                cetakBtn.onclick = null;
                cetakBtn.style.backgroundColor = '#059669';
                cetakBtn.style.borderColor = '#047857';
                cetakBtn.style.color = '#ffffff';
                cetakBtn.style.cursor = 'pointer';
                cetakBtn.title = 'Preview Cetak Dokumen Jurnal';
                if (cetakBtnText) cetakBtnText.textContent = 'Cetak Dokumen';
            } else {
                cetakBtn.removeAttribute('href');
                cetakBtn.removeAttribute('target');
                cetakBtn.onclick = function(e) {
                    e.preventDefault();
                    openQuickLogFromDetail(true);
                };
                cetakBtn.style.backgroundColor = '#fff7ed';
                cetakBtn.style.borderColor = '#ea580c';
                cetakBtn.style.color = '#c2410c';
                cetakBtn.style.cursor = 'pointer';
                cetakBtn.title = 'Keterangan log masih kosong. Klik untuk mengisi log & langsung mencetak.';
                if (cetakBtnText) cetakBtnText.textContent = 'Cetak (Isi Log Dahulu 🔒)';
            }
        }

        document.getElementById('detailModal').classList.add('show');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.remove('show');
    }

    // Quick Log Modal Handlers
    function openQuickLogModal(jurnal, autoPrint = false) {
        currentQuickLogJurnal = jurnal;
        
        document.getElementById('quickLogNasabah').textContent = jurnal.nama_nasabah || '-';
        document.getElementById('quickLogNoResi').textContent = jurnal.no_resi || '-';
        const nom = Number(jurnal.nominal_transaksi) || 0;
        document.getElementById('quickLogNominal').textContent = 'Rp ' + nom.toLocaleString('id-ID');
        const ch = (jurnal.master_transaksi ? jurnal.master_transaksi.channel : '') || '';
        const tm = (jurnal.terminal_transaksi || '-').trim();
        document.getElementById('quickLogTerminal').textContent = ch ? `${tm} (${ch})` : tm;

        const rawLog = (jurnal.keterangan_log && jurnal.keterangan_log.trim() !== '-' && jurnal.keterangan_log.trim().toLowerCase() !== 'tidak ada keterangan tambahan.') ? jurnal.keterangan_log : '';
        const textarea = document.getElementById('quickLogTextarea');
        textarea.value = rawLog;
        updateQuickLogCharCount();

        const feedback = document.getElementById('quickLogFeedback');
        if (feedback) feedback.style.display = 'none';

        const modal = document.getElementById('quickLogModal');
        if (modal) modal.classList.add('show');
        textarea.focus();
    }

    function closeQuickLogModal() {
        const modal = document.getElementById('quickLogModal');
        if (modal) modal.classList.remove('show');
    }

    function updateQuickLogCharCount() {
        const textarea = document.getElementById('quickLogTextarea');
        const counter = document.getElementById('quickLogCharCount');
        if (textarea && counter) {
            counter.textContent = `${textarea.value.length} karakter`;
        }
    }

    function openQuickLogFromDetail(autoPrint = false) {
        if (!currentDetailJurnal) return;
        openQuickLogModal(currentDetailJurnal, autoPrint);
    }

    function saveQuickLog(autoPrint = false) {
        if (!currentQuickLogJurnal) return;

        const val = document.getElementById('quickLogTextarea').value.trim();
        const feedback = document.getElementById('quickLogFeedback');

        if (!val) {
            if (feedback) {
                feedback.style.display = 'block';
                feedback.style.background = '#fef2f2';
                feedback.style.border = '1px solid #fecdd3';
                feedback.style.color = '#991b1b';
                feedback.innerHTML = '⚠️ Silakan ketik catatan hasil pemeriksaan log terlebih dahulu.';
            }
            return;
        }

        if (feedback) {
            feedback.style.display = 'block';
            feedback.style.background = '#f0f9ff';
            feedback.style.border = '1px solid #bae6fd';
            feedback.style.color = '#0369a1';
            feedback.innerHTML = '⏳ Menyimpan keterangan log ke database...';
        }

        const btnSave = document.getElementById('btnSaveQuickLogOnly');
        const btnSavePrint = document.getElementById('btnSaveQuickLogAndPrint');
        if (btnSave) btnSave.disabled = true;
        if (btnSavePrint) btnSavePrint.disabled = true;

        fetch('/jurnal/' + currentQuickLogJurnal.id + '/update-log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                keterangan_log: val
            })
        })
        .then(res => res.json())
        .then(data => {
            if (btnSave) btnSave.disabled = false;
            if (btnSavePrint) btnSavePrint.disabled = false;

            if (data.status === 'success') {
                currentQuickLogJurnal.keterangan_log = val;

                // Perbarui tombol aksi di baris tabel yang bersangkutan jika ada di DOM
                const tableRowBtn = document.querySelector(`.btn-cetak-action[data-jurnal-id="${currentQuickLogJurnal.id}"]`);
                if (tableRowBtn) {
                    tableRowBtn.outerHTML = `
                        <a href="/jurnal/${currentQuickLogJurnal.id}/download" target="_blank" class="btn btn-sm btn-cetak-action" data-jurnal-id="${currentQuickLogJurnal.id}" style="background-color: #059669; color: white; padding: 6px 12px; font-size: 12.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; border-radius: 4px; margin-left: 5px; border: 1px solid #047857;" title="Preview Cetak Dokumen Jurnal">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                            <span>Cetak</span>
                        </a>
                    `;
                }

                // Perbarui modal detail jika sedang aktif
                if (currentDetailJurnal && currentDetailJurnal.id === currentQuickLogJurnal.id) {
                    currentDetailJurnal.keterangan_log = val;
                    showDetailModal(currentDetailJurnal);
                }

                closeQuickLogModal();

                if (autoPrint) {
                    window.open('/jurnal/' + currentQuickLogJurnal.id + '/download', '_blank');
                }
            } else {
                if (feedback) {
                    feedback.style.display = 'block';
                    feedback.style.background = '#fef2f2';
                    feedback.style.border = '1px solid #fecdd3';
                    feedback.style.color = '#991b1b';
                    feedback.innerHTML = '❌ ' + (data.message || 'Gagal menyimpan keterangan log.');
                }
            }
        })
        .catch(err => {
            console.error(err);
            if (btnSave) btnSave.disabled = false;
            if (btnSavePrint) btnSavePrint.disabled = false;
            if (feedback) {
                feedback.style.display = 'block';
                feedback.style.background = '#fef2f2';
                feedback.style.border = '1px solid #fecdd3';
                feedback.style.color = '#991b1b';
                feedback.innerHTML = '❌ Terjadi kesalahan jaringan saat menyimpan data.';
            }
        });
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
        const quickModal = document.getElementById('quickLogModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        const importModal = document.getElementById('importModal');
        const successModal = document.getElementById('importSuccessModal');
        const errorModal = document.getElementById('importErrorModal');
        const resetModal = document.getElementById('resetAllModal');
        if (e.target === detailModal) closeDetailModal();
        if (e.target === quickModal) closeQuickLogModal();
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
            closeQuickLogModal();
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

    // Cascading Dropdown Filter Terminal / Mesin ATM di Halaman Data Keluhan
    const initialFilterTerminal = @json(request('terminal_transaksi', ''));
    const filterCabangEl = document.getElementById('filterCabang');
    const filterTerminalEl = document.getElementById('filterTerminal');

    function isAtmMatchData(atm, target) {
        if (!target) return false;
        const t = target.toLowerCase().trim();
        const val = (atm.value || '').toLowerCase().trim();
        const prof = (atm.profil || '').toLowerCase().trim();
        const id = (String(atm.id_luno || '')).toLowerCase().trim();
        return t === val || t === prof || t === id || val.includes(t) || t.includes(prof);
    }

    function populateFilterTerminalData(kodeCabang, targetValue = '') {
        if (!filterTerminalEl) return;
        filterTerminalEl.innerHTML = '';

        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- Semua Terminal / Mesin --';
        filterTerminalEl.appendChild(defaultOpt);

        if (kodeCabang) {
            const atms = atmsGrouped[kodeCabang] || [];
            if (atms.length > 0) {
                const optGroup = document.createElement('optgroup');
                const namaCabang = atms[0].cabang || '';
                optGroup.label = (namaCabang && namaCabang.toUpperCase() !== 'CALL CENTER') ? `${kodeCabang} - ${namaCabang}` : (namaCabang || `Cabang ${kodeCabang}`);
                atms.forEach(atm => {
                    const opt = document.createElement('option');
                    opt.value = atm.value;
                    opt.textContent = atm.label;
                    if (isAtmMatchData(atm, targetValue)) {
                        opt.selected = true;
                    }
                    optGroup.appendChild(opt);
                });
                filterTerminalEl.appendChild(optGroup);
            }
        } else {
            // Tampilkan semua mesin terkelompok per cabang
            Object.keys(atmsGrouped).forEach(kode => {
                const atms = atmsGrouped[kode];
                if (atms && atms.length > 0) {
                    const optGroup = document.createElement('optgroup');
                    const namaCabang = atms[0].cabang || '';
                    optGroup.label = (namaCabang && namaCabang.toUpperCase() !== 'CALL CENTER') ? `${kode} - ${namaCabang}` : (namaCabang || `Cabang ${kode}`);
                    atms.forEach(atm => {
                        const opt = document.createElement('option');
                        opt.value = atm.value;
                        opt.textContent = atm.label;
                        if (isAtmMatchData(atm, targetValue)) {
                            opt.selected = true;
                        }
                        optGroup.appendChild(opt);
                    });
                    filterTerminalEl.appendChild(optGroup);
                }
            });

            // Channel non-ATM
            const generalGroup = document.createElement('optgroup');
            generalGroup.label = 'Channel Non-ATM';
            ['BANK LAIN', 'MOBILE BANKING', 'SMS BANKING'].forEach(ch => {
                const opt = document.createElement('option');
                opt.value = ch;
                opt.textContent = ch;
                if (targetValue && targetValue.toUpperCase().trim() === ch) {
                    opt.selected = true;
                }
                generalGroup.appendChild(opt);
            });
            filterTerminalEl.appendChild(generalGroup);
        }

        if (targetValue && filterTerminalEl.selectedIndex <= 0) {
            const customOpt = document.createElement('option');
            customOpt.value = targetValue;
            customOpt.textContent = targetValue;
            customOpt.selected = true;
            filterTerminalEl.appendChild(customOpt);
        }
    }

    if (filterCabangEl) {
        filterCabangEl.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            const kode = selectedOpt ? selectedOpt.getAttribute('data-kode') : '';
            populateFilterTerminalData(kode);
        });
    }

    window.addEventListener('DOMContentLoaded', function() {
        if (filterCabangEl) {
            const selectedOpt = filterCabangEl.options[filterCabangEl.selectedIndex];
            const kode = selectedOpt ? selectedOpt.getAttribute('data-kode') : '';
            populateFilterTerminalData(kode, initialFilterTerminal);
        }
    });
</script>
@endpush
