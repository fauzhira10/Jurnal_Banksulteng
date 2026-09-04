@extends('layouts.app')

@section('title', 'Edit Jurnal Keluhan')
@section('page_title', 'Edit Data Jurnal Keluhan')
@section('page_subtitle', 'Pembaruan dan koreksi data keluhan transaksi nasabah Bank Sulteng')

@section('topbar_action')
    <a href="{{ route('jurnal.index') }}" class="inline-flex items-center gap-2 h-[38px] px-3.5 rounded-lg border border-slate-300 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 text-xs font-semibold transition-colors">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Kembali ke Data Keluhan</span>
    </a>
@endsection

@section('content')
<div class="max-w-[950px] mx-auto">
    @if($jurnal->pengaduan)
        <div class="bg-sky-50 border border-sky-200 text-sky-900 rounded-xl p-4 mb-5 text-[13px] flex items-start gap-3 shadow-xs">
            <span class="text-lg leading-none">📨</span>
            <div class="grow min-w-0">
                <div class="font-bold">Jurnal ini bersumber dari pengaduan CS <span class="font-mono">{{ $jurnal->pengaduan->nomor_pengaduan }}</span> — {{ $jurnal->pengaduan->labelCabang() }}</div>
                <div class="text-[12px] text-sky-800 mt-0.5">Mengubah <strong>Status Keluhan</strong> di sini otomatis memperbarui status yang dilihat CS cabang (Done/Success → Selesai, Rejected → Ditolak).</div>
                @if($jurnal->pengaduan->lampirans->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        @foreach($jurnal->pengaduan->lampirans as $l)
                            <a href="{{ route('pengaduan.lampiran.show', [$jurnal->pengaduan, $l]) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white border border-sky-200 text-[11px] font-semibold text-navy hover:border-brand-blue" title="{{ $l->nama_asli }}">{{ $l->ikon() }} {{ $l->label() }} <span class="text-slate-400 font-normal">{{ $l->labelFormat() }}</span></a>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('admin.pengaduan.show', $jurnal->pengaduan) }}" class="inline-block mt-2 text-[12px] font-bold text-brand-blue hover:underline">Lihat detail pengaduan & kronologi →</a>
            </div>
        </div>
    @endif
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs mb-6 overflow-hidden">
        <div class="px-6 py-4.5 border-b border-slate-200 flex items-center justify-between bg-white flex-wrap gap-2">
            <div class="text-base font-bold text-navy flex items-center gap-2.5">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <span>Edit Rincian Jurnal Keluhan #{{ $jurnal->id }} ({{ $jurnal->nama_nasabah }})</span>
            </div>
            <span class="text-xs text-slate-500">
                Semua Input Bertanda (<span class="text-rose-600 font-bold">*</span>) Wajib Diisi
            </span>
        </div>

        <div class="p-6">
            <form action="{{ route('jurnal.update', $jurnal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- SECTION 1: IDENTITAS NASABAH -->
                    <div class="md:col-span-2 flex items-center gap-2.5 my-2.5 text-brand-blue text-[13px] font-bold uppercase tracking-wider after:content-[''] after:grow after:h-px after:bg-slate-200">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>1. Identitas Nasabah & Rekening</span>
                    </div>

                    <!-- Nama Nasabah -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Nama Nasabah <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="nama_nasabah" value="{{ old('nama_nasabah', $jurnal->nama_nasabah) }}" required placeholder="Contoh: BUDI SANTOSO" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 uppercase transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none" oninput="this.value = this.value.toUpperCase()">
                    </div>

                    <!-- No. Rekening -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">No. Rekening <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="no_rekening" value="{{ old('no_rekening', $jurnal->no_rekening) }}" required placeholder="Contoh: 00900000" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- No. Resi / Trace Number -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">No. Resi / Trace Number <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="no_resi" value="{{ old('no_resi', $jurnal->no_resi) }}" required placeholder="Contoh: 00000000" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Nomor Kartu -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Nomor Kartu ATM/Debit <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="no_kartu" value="{{ old('no_kartu', $jurnal->no_kartu) }}" required placeholder="Contoh: 6019xxxxxxxxxxxx" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Nomor Tiket -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Nomor Tiket CS <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="no_tiket" value="{{ old('no_tiket', $jurnal->no_tiket) }}" required placeholder="Contoh: TKT-2026-0001" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- SECTION 2: DETAIL TRANSAKSI & KANTOR CABANG -->
                    <div class="md:col-span-2 flex items-center gap-2.5 my-2.5 text-brand-blue text-[13px] font-bold uppercase tracking-wider after:content-[''] after:grow after:h-px after:bg-slate-200">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span>2. Detail Transaksi & Lokasi</span>
                    </div>

                    <!-- Cabang Transaksi / Pelapor -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Cabang Transaksi / Pelapor <span class="text-rose-600 font-bold">*</span></label>
                        <select name="master_cabang_id" id="cabang_id" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            <option value="">-- Pilih Kantor Cabang --</option>
                            @foreach($cabangs as $c)
                                <option value="{{ $c->id }}" data-kode="{{ $c->kode_cabang ?? '' }}" data-nama="{{ $c->nama_cabang }}" {{ old('master_cabang_id', $jurnal->master_cabang_id) == $c->id ? 'selected' : '' }}>
                                    {{ !empty($c->kode_cabang) && strtoupper(trim($c->nama_cabang)) !== 'CALL CENTER' ? $c->kode_cabang . ' - ' : '' }}{{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Jenis Transaksi <span class="text-rose-600 font-bold">*</span></label>
                        <select name="master_transaksi_id" id="transaksi_id" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            <option value="">-- Pilih Jenis Transaksi --</option>
                            @foreach($transaksis as $t)
                                <option value="{{ $t->id }}" {{ (old('master_transaksi_id') == $t->id || (!old('master_transaksi_id') && ($jurnal->master_transaksi_id == $t->id || optional($jurnal->masterTransaksi)->jenis_transaksi == $t->jenis_transaksi))) ? 'selected' : '' }}>
                                    {{ $t->jenis_transaksi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Channel Transaksi (Dropdown) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Channel Transaksi <span class="text-rose-600 font-bold">*</span></label>
                        <select name="channel" id="channel" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            <option value="">-- Pilih Channel Transaksi --</option>
                            @php
                                $channelsList = [
                                    'ATM LOKAL',
                                    'ATM BERSAMA',
                                    'ATM LINK',
                                    'FINNET',
                                    'MOBILE BANKING',
                                    'SMS BANKING',
                                    'DEBIT',
                                    'EDC BANK LAIN',
                                    'LAKU PANDAI',
                                    'QRIS',
                                    'CCTV',
                                    'ATM'
                                ];
                                $selectedChannel = old('channel', $jurnal->masterTransaksi->channel ?? '');
                            @endphp
                            @foreach($channelsList as $ch)
                                <option value="{{ $ch }}" {{ strtoupper(trim($selectedChannel)) == $ch ? 'selected' : '' }}>{{ $ch }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Biaya Admin (Combobox Lengkap 7-Fase: Input Bebas + Auto-Format Rupiah + Suggestion + Keyboard Nav + Auto-Flip) -->
                    <div class="flex flex-col gap-1.5 relative" id="admin_fee_combobox_container">
                        <div class="flex items-center justify-between">
                            <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">
                                Biaya Admin (Rp) <span class="text-rose-600 font-bold">*</span>
                            </label>
                            <span id="admin_fee_hint" class="text-[11px] text-slate-400 font-normal">Ketik bebas / pilih preset</span>
                        </div>
                        
                        <div id="admin_fee_input_wrapper" class="flex items-center rounded-xl border border-slate-300 bg-white overflow-hidden focus-within:border-brand-blue focus-within:ring-3 focus-within:ring-brand-blue/15 transition-all duration-200">
                            <!-- Prefix Rp Badge -->
                            <span class="inline-flex items-center px-3.5 py-2.5 bg-slate-50 text-slate-500 font-semibold text-xs border-r border-slate-200 select-none">
                                Rp
                            </span>
                            
                            <!-- Input Tampilan Interaktif (Formatted, No Stepper) -->
                            @php
                                $initialAdminRaw = (float) old('biaya_admin', $jurnal->biaya_admin ?? $jurnal->masterTransaksi->biaya_admin ?? 0);
                                $initialAdminFormatted = number_format($initialAdminRaw, 0, ',', '.');
                            @endphp
                            <input 
                                type="text" 
                                id="biaya_admin_display" 
                                value="{{ $initialAdminFormatted }}" 
                                placeholder="0" 
                                autocomplete="off"
                                role="combobox"
                                aria-expanded="false"
                                aria-haspopup="listbox"
                                aria-autocomplete="list"
                                aria-controls="admin_suggestion_dropdown"
                                class="block w-full px-3.5 py-2.5 text-sm bg-white text-slate-800 placeholder-slate-400 border-0 focus:outline-none focus:ring-0"
                            >

                            <!-- Hidden Input (Nilai numerik integer murni yang dikirim ke backend) -->
                            <input 
                                type="hidden" 
                                name="biaya_admin" 
                                id="biaya_admin" 
                                value="{{ $initialAdminRaw }}"
                            >

                            <!-- Tombol Trigger Dropdown Saran (Affordance) -->
                            <button 
                                type="button" 
                                id="btn_toggle_admin_dropdown" 
                                class="inline-flex items-center px-3 py-2.5 text-slate-400 hover:text-brand-blue transition-colors focus:outline-none cursor-pointer"
                                title="Buka pilihan preset biaya admin"
                                aria-label="Buka pilihan preset biaya admin"
                                tabindex="-1"
                            >
                                <svg id="icon_admin_chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200 pointer-events-none">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                        </div>

                        <!-- Pesan Error Validasi -->
                        <span id="admin_fee_error" class="hidden text-[11.5px] text-rose-600 font-medium mt-0.5">
                            * Biaya admin wajib diisi (minimal Rp 0).
                        </span>

                        <!-- Dropdown Menu Saran Melayang (Floating Popover dengan Auto-Flip) -->
                        <div 
                            id="admin_suggestion_dropdown" 
                            role="listbox"
                            class="hidden absolute left-0 right-0 bg-white border border-slate-200 rounded-xl shadow-xl shadow-slate-200/60 z-50 overflow-hidden py-1 divide-y divide-slate-100 transition-all"
                        >
                            <div class="px-3.5 py-1.5 text-[10.5px] font-bold tracking-wider text-slate-400 uppercase bg-slate-50/80 flex items-center justify-between">
                                <span>Nilai Umum / Preset Standar</span>
                                <span class="text-[10px] text-slate-400 font-normal lowercase">Gunakan ↑↓ Enter</span>
                            </div>
                            <div class="max-h-56 overflow-y-auto py-1" id="admin_suggestion_list">
                                @php
                                    $adminPresets = [
                                        0 => 'Rp 0 (Bebas Biaya / Gratis)',
                                        1000 => 'Rp 1.000',
                                        1500 => 'Rp 1.500',
                                        1750 => 'Rp 1.750',
                                        2000 => 'Rp 2.000',
                                        2500 => 'Rp 2.500',
                                        2750 => 'Rp 2.750',
                                        3000 => 'Rp 3.000',
                                        6500 => 'Rp 6.500',
                                        7500 => 'Rp 7.500',
                                    ];
                                @endphp
                                @foreach($adminPresets as $val => $lbl)
                                    <div 
                                        role="option"
                                        id="admin_opt_{{ $val }}"
                                        aria-selected="false"
                                        class="admin-suggestion-item min-h-[44px] px-3.5 py-2.5 text-sm text-slate-700 hover:bg-sky-50 hover:text-brand-blue flex items-center justify-between cursor-pointer transition-colors"
                                        data-value="{{ $val }}"
                                        data-label="{{ $lbl }}"
                                    >
                                        <span class="font-medium text-[13.5px]">{{ $lbl }}</span>
                                        <span class="admin-check hidden text-brand-blue font-bold text-sm">✓</span>
                                    </div>
                                @endforeach
                                <div id="admin_no_match" class="hidden px-3.5 py-3 text-xs text-slate-500 text-center italic bg-slate-50/50">
                                    Tidak ada preset yang cocok. Tekan Enter atau klik di luar untuk menyimpan nominal kustom.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nominal Transaksi -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Biaya / Nominal Transaksi (Rp) <span class="text-rose-600 font-bold">*</span></label>
                        <div class="flex items-center rounded-xl border border-slate-300 bg-white overflow-hidden focus-within:border-brand-blue focus-within:ring-3 focus-within:ring-brand-blue/15 transition-all duration-200">
                            <span class="inline-flex items-center px-3.5 py-2.5 bg-slate-50 text-slate-500 font-semibold text-xs border-r border-slate-200 select-none">
                                Rp
                            </span>
                            <input type="number" name="nominal_transaksi" value="{{ old('nominal_transaksi', $jurnal->nominal_transaksi) }}" required placeholder="Contoh: 1000000" min="0" step="any" class="block w-full px-3.5 py-2.5 text-sm bg-white text-slate-800 placeholder-slate-400 border-0 focus:outline-none focus:ring-0">
                        </div>
                    </div>

                    <!-- Terminal Transaksi / Mesin ATM -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">
                            Terminal Transaksi / Mesin ATM <span class="text-rose-600 font-bold">*</span>
                        </label>
                        <select name="terminal_transaksi" id="terminal_transaksi" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            <option value="">-- Pilih Mesin ATM / Terminal --</option>
                        </select>
                        <span id="terminalHelperText" class="text-[11.5px] text-slate-500">
                            Pilih mesin ATM langsung atau pilih kantor cabang terlebih dahulu.
                        </span>
                    </div>

                    <!-- SECTION 3: WAKTU, STATUS & KRONOLOGI -->
                    <div class="md:col-span-2 flex items-center gap-2.5 my-2.5 text-brand-blue text-[13px] font-bold uppercase tracking-wider after:content-[''] after:grow after:h-px after:bg-slate-200">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>3. Waktu & Status Penanganan</span>
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Tanggal Transaksi Bermasalah <span class="text-rose-600 font-bold">*</span></label>
                        <input type="date" name="tgl_transaksi" value="{{ old('tgl_transaksi', $jurnal->tgl_transaksi) }}" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Tanggal Terima -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Tanggal Terima Keluhan <span class="text-rose-600 font-bold">*</span></label>
                        <input type="date" name="tgl_terima" value="{{ old('tgl_terima', $jurnal->tgl_terima) }}" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">
                            Tanggal Selesai Penanganan 
                             <span class ="text-rose-600 font-bold">*</span>
                        </label>
                        <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai', $jurnal->tgl_selesai) }}" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Status Keluhan <span class="text-rose-600 font-bold">*</span></label>
                        <select name="status" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            <option value="-" {{ old('status', $jurnal->status) == '-' ? 'selected' : '' }}>- (Belum Ditentukan)</option>
                            <option value="Menunggu" {{ old('status', $jurnal->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Success" {{ old('status', $jurnal->status) == 'Success' ? 'selected' : '' }}>Success</option>
                            <option value="Done" {{ old('status', $jurnal->status) == 'Done' ? 'selected' : '' }}>Done</option>
                            <option value="Rejected" {{ old('status', $jurnal->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Keterangan Keluhan -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700">Keterangan Keluhan</label>
                        <input type="text" name="permasalahan" value="{{ old('permasalahan', $jurnal->permasalahan) }}" placeholder="Contoh: TARIK TUNAI ATM LOKAL GAGAL, SALDO TERDEBET" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 uppercase transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none" oninput="this.value = this.value.toUpperCase()">
                    </div>

                    <!-- Keterangan Log -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700">Keterangan Log / Catatan Kronologi Keluhan</label>
                        <textarea name="keterangan_log" placeholder="Tuliskan catatan, hasil pemeriksaan log, atau tindak lanjut petugas di sini..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none resize-y min-h-[90px]">{{ old('keterangan_log', $jurnal->keterangan_log) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="{{ route('jurnal.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-[13.5px] font-semibold bg-slate-100 text-slate-700 border border-slate-300 hover:bg-slate-200 hover:text-slate-900 transition-colors">
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-[13.5px] font-semibold bg-gradient-to-r from-brand-blue to-navy text-white hover:opacity-95 hover:shadow-lg shadow-brand-blue/25 transition-all cursor-pointer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logic Auto-fill AJAX saat Jenis Transaksi dipilih (Channel)
    let currentChannel = '';
    let currentJenisTransaksiText = '';

    // Inisialisasi Data & Elemen
    const transaksiEl = document.getElementById('transaksi_id');
    const channelSelect = document.getElementById('channel');
    const terminalSelect = document.getElementById('terminal_transaksi');
    const helperText = document.getElementById('terminalHelperText');
    const atmsGrouped = @json($atmsGrouped);
    const initialTerminalVal = @json(old('terminal_transaksi', $jurnal->terminal_transaksi ?? ''));

    // ==========================================
    // BIAYA ADMIN COMBOBOX COMPONENT (7-PHASE)
    // ==========================================
    const adminDisplayInput = document.getElementById('biaya_admin_display');
    const adminHiddenInput = document.getElementById('biaya_admin');
    const adminDropdown = document.getElementById('admin_suggestion_dropdown');
    const adminChevron = document.getElementById('icon_admin_chevron');
    const adminContainer = document.getElementById('admin_fee_combobox_container');
    const adminErrorEl = document.getElementById('admin_fee_error');
    const adminNoMatch = document.getElementById('admin_no_match');
    const adminSuggestionItems = document.querySelectorAll('.admin-suggestion-item');
    const btnToggleAdmin = document.getElementById('btn_toggle_admin_dropdown');

    const numberFormatter = new Intl.NumberFormat('id-ID');
    let currentActiveIndex = -1;

    function formatNumberID(num) {
        if (isNaN(num) || num === null || num === '') return '0';
        return numberFormatter.format(Number(num));
    }

    function parseCleanDigits(str) {
        if (str === null || str === undefined) return 0;
        const cleaned = String(str).replace(/[^\d]/g, '');
        return cleaned === '' ? 0 : parseInt(cleaned, 10);
    }

    function setAdminFeeValue(val, syncDisplay = true) {
        const num = isNaN(val) ? 0 : Number(val);
        if (adminHiddenInput) adminHiddenInput.value = num;
        if (syncDisplay && adminDisplayInput) {
            adminDisplayInput.value = formatNumberID(num);
        }
        hideAdminError();
        highlightActiveAdminPreset();
    }

    function calculateDropdownPosition() {
        if (!adminContainer || !adminDropdown) return;
        const rect = adminContainer.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        const dropdownHeight = 250;

        if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
            // Auto-Flip ke atas jika ruang bawah tidak cukup
            adminDropdown.style.top = 'auto';
            adminDropdown.style.bottom = 'calc(100% + 6px)';
        } else {
            // Normal ke bawah
            adminDropdown.style.bottom = 'auto';
            adminDropdown.style.top = 'calc(100% + 6px)';
        }
    }

    function openAdminDropdown() {
        if (!adminDropdown) return;
        calculateDropdownPosition();
        adminDropdown.classList.remove('hidden');
        if (adminDisplayInput) adminDisplayInput.setAttribute('aria-expanded', 'true');
        if (adminChevron) adminChevron.classList.add('rotate-180');
        filterAdminSuggestions(adminDisplayInput ? adminDisplayInput.value : '');
    }

    function closeAdminDropdown() {
        if (!adminDropdown) return;
        adminDropdown.classList.add('hidden');
        if (adminDisplayInput) adminDisplayInput.setAttribute('aria-expanded', 'false');
        if (adminChevron) adminChevron.classList.remove('rotate-180');
        currentActiveIndex = -1;
        updateItemKeyboardHighlight();
    }

    function toggleAdminDropdown() {
        if (adminDropdown && adminDropdown.classList.contains('hidden')) {
            openAdminDropdown();
            if (adminDisplayInput) adminDisplayInput.focus();
        } else {
            closeAdminDropdown();
        }
    }

    function highlightActiveAdminPreset() {
        const currentVal = adminHiddenInput ? parseInt(adminHiddenInput.value, 10) : 0;
        adminSuggestionItems.forEach(item => {
            const itemVal = parseInt(item.getAttribute('data-value'), 10);
            const check = item.querySelector('.admin-check');
            if (itemVal === currentVal) {
                item.classList.add('bg-sky-50', 'text-brand-blue');
                item.setAttribute('aria-selected', 'true');
                if (check) check.classList.remove('hidden');
            } else {
                item.classList.remove('bg-sky-50', 'text-brand-blue');
                item.setAttribute('aria-selected', 'false');
                if (check) check.classList.add('hidden');
            }
        });
    }

    function filterAdminSuggestions(query) {
        const rawDigits = String(query).replace(/[^\d]/g, '');
        let visibleCount = 0;

        adminSuggestionItems.forEach(item => {
            const itemVal = item.getAttribute('data-value');
            const itemLabel = item.getAttribute('data-label') || '';
            const match = rawDigits === '' || itemVal.includes(rawDigits) || itemLabel.toLowerCase().includes(String(query).toLowerCase());
            
            if (match) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });

        if (adminNoMatch) {
            if (visibleCount === 0) {
                adminNoMatch.classList.remove('hidden');
            } else {
                adminNoMatch.classList.add('hidden');
            }
        }
        highlightActiveAdminPreset();
    }

    function getVisibleItems() {
        return Array.from(adminSuggestionItems).filter(item => !item.classList.contains('hidden'));
    }

    function updateItemKeyboardHighlight() {
        const visibleItems = getVisibleItems();
        visibleItems.forEach((item, idx) => {
            if (idx === currentActiveIndex) {
                item.classList.add('bg-slate-100', 'ring-1', 'ring-brand-blue/30');
                item.scrollIntoView({ block: 'nearest' });
                if (adminDisplayInput) adminDisplayInput.setAttribute('aria-activedescendant', item.id);
            } else {
                item.classList.remove('bg-slate-100', 'ring-1', 'ring-brand-blue/30');
            }
        });
    }

    const adminWrapper = document.getElementById('admin_fee_input_wrapper');

    function showAdminError(msg) {
        if (adminWrapper) {
            adminWrapper.classList.add('border-rose-500', 'ring-2', 'ring-rose-500/20');
            adminWrapper.classList.remove('border-slate-300');
        }
        if (adminErrorEl) {
            if (msg) adminErrorEl.textContent = msg;
            adminErrorEl.classList.remove('hidden');
        }
    }

    function hideAdminError() {
        if (adminWrapper) {
            adminWrapper.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/20');
            adminWrapper.classList.add('border-slate-300');
        }
        if (adminErrorEl) {
            adminErrorEl.classList.add('hidden');
        }
    }

    // Event Listeners Input
    if (adminDisplayInput) {
        adminDisplayInput.addEventListener('focus', function() {
            const num = parseCleanDigits(this.value);
            this.value = num === 0 ? '' : num;
            openAdminDropdown();
        });

        adminDisplayInput.addEventListener('input', function() {
            const digits = this.value.replace(/[^\d]/g, '');
            this.value = digits;
            const parsed = digits === '' ? 0 : parseInt(digits, 10);
            if (adminHiddenInput) adminHiddenInput.value = parsed;
            filterAdminSuggestions(digits);
            hideAdminError();
        });

        adminDisplayInput.addEventListener('blur', function() {
            const parsed = parseCleanDigits(this.value);
            setAdminFeeValue(parsed, true);
            setTimeout(closeAdminDropdown, 180);
        });

        adminDisplayInput.addEventListener('keydown', function(e) {
            const visibleItems = getVisibleItems();

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (adminDropdown && adminDropdown.classList.contains('hidden')) {
                    openAdminDropdown();
                } else if (visibleItems.length > 0) {
                    currentActiveIndex = (currentActiveIndex + 1) % visibleItems.length;
                    updateItemKeyboardHighlight();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (visibleItems.length > 0) {
                    currentActiveIndex = (currentActiveIndex - 1 + visibleItems.length) % visibleItems.length;
                    updateItemKeyboardHighlight();
                }
            } else if (e.key === 'Enter') {
                if (currentActiveIndex >= 0 && visibleItems[currentActiveIndex]) {
                    e.preventDefault();
                    const val = visibleItems[currentActiveIndex].getAttribute('data-value');
                    setAdminFeeValue(val, true);
                    closeAdminDropdown();
                } else {
                    const parsed = parseCleanDigits(this.value);
                    setAdminFeeValue(parsed, true);
                    closeAdminDropdown();
                }
            } else if (e.key === 'Escape') {
                closeAdminDropdown();
            } else if (e.key === 'Tab') {
                closeAdminDropdown();
            }
        });
    }

    // Klik Item Suggestion
    adminSuggestionItems.forEach(item => {
        item.addEventListener('mousedown', function(e) {
            e.preventDefault();
            const val = this.getAttribute('data-value');
            setAdminFeeValue(val, true);
            closeAdminDropdown();
        });
    });

    if (btnToggleAdmin) {
        btnToggleAdmin.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleAdminDropdown();
        });
    }

    // Dismiss saat klik di luar
    document.addEventListener('click', function(e) {
        if (adminContainer && !adminContainer.contains(e.target)) {
            closeAdminDropdown();
        }
    });

    // Validasi Form saat submit
    const formEl = document.querySelector('form');
    if (formEl) {
        formEl.addEventListener('submit', function(e) {
            if (adminHiddenInput && (adminHiddenInput.value === '' || isNaN(adminHiddenInput.value) || Number(adminHiddenInput.value) < 0)) {
                e.preventDefault();
                showAdminError('* Biaya admin wajib diisi (minimal Rp 0).');
                if (adminDisplayInput) adminDisplayInput.focus();
            }
        });
    }

    function loadDetailTransaksi(id) {
        if(id) {
            fetch('/api/transaksi/' + id)
                .then(response => response.json())
                .then(data => {
                    if (channelSelect && data.channel) {
                        channelSelect.value = data.channel.toUpperCase().trim();
                    }
                    if (data && data.biaya_admin !== undefined) {
                        setAdminFeeValue(data.biaya_admin, true);
                    }
                })
                .catch(err => {
                    console.error('Gagal memuat detail transaksi:', err);
                });
        }
    }

    function populateAtmDropdown(targetValue = '') {
        const currentSelected = targetValue || (terminalSelect ? terminalSelect.value : '');
        terminalSelect.innerHTML = '';

        // Default Placeholder
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- Pilih Mesin ATM / Terminal --';
        terminalSelect.appendChild(defaultOpt);

        // Selalu tampilkan seluruh mesin ATM terkelompok per cabang
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
                    opt.setAttribute('data-luno', atm.id_luno || '');
                    opt.setAttribute('data-profil', atm.profil || '');
                    opt.setAttribute('data-lokasi', atm.lokasi || '');
                    opt.setAttribute('data-kode-cabang', kode || '');
                    if (currentSelected && (currentSelected === atm.value || currentSelected.trim() === atm.value.trim() || currentSelected.includes(atm.profil))) {
                        opt.selected = true;
                    }
                    optGroup.appendChild(opt);
                });
                terminalSelect.appendChild(optGroup);
            }
        });

        // Channel non-ATM
        const generalGroup = document.createElement('optgroup');
        generalGroup.label = 'Channel Non-ATM';
        ['MOBILE BANKING', 'ATM BANK LAIN', 'SMS BANKING', 'EDC'].forEach(ch => {
            const opt = document.createElement('option');
            opt.value = ch;
            opt.textContent = ch;
            if (currentSelected && currentSelected.toUpperCase().trim() === ch) {
                opt.selected = true;
            }
            generalGroup.appendChild(opt);
        });
        terminalSelect.appendChild(generalGroup);

        if (helperText) helperText.textContent = 'Daftar seluruh mesin ATM Bank Sulteng lengkap (Terkelompok berdasarkan cabang).';
    }

    if (transaksiEl) {
        transaksiEl.addEventListener('change', function() {
            loadDetailTransaksi(this.value);
        });
    }

    // Jalankan saat load pertama kali
    window.addEventListener('DOMContentLoaded', function() {
        populateAtmDropdown(initialTerminalVal);
        highlightActiveAdminPreset();
        if (transaksiEl && transaksiEl.value && channelSelect && !channelSelect.value) {
            loadDetailTransaksi(transaksiEl.value);
        }
    });
</script>
@endpush

