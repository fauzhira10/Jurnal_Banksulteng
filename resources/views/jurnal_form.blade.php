@extends('layouts.app')

@section('title', 'Form Jurnal Keluhan')
@section('page_title', 'Input Jurnal Keluhan Nasabah')
@section('page_subtitle', 'Pencatatan, validasi anti-duplikat, dan penanganan keluhan transaksi nasabah Bank Sulteng')

@section('topbar_action')
    <a href="{{ route('jurnal.index') }}" class="inline-flex items-center gap-2 h-[38px] px-3.5 rounded-lg border border-slate-300 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 text-xs font-semibold transition-colors">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="3" y1="9" x2="21" y2="9"></line>
            <line x1="9" y1="21" x2="9" y2="9"></line>
        </svg>
        <span>Lihat Data Keluhan</span>
    </a>
@endsection

@section('content')
<div class="max-w-[950px] mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs mb-6 overflow-hidden">
        <div class="px-6 py-4.5 border-b border-slate-200 flex items-center justify-between bg-white flex-wrap gap-2">
            <div class="text-base font-bold text-navy flex items-center gap-2.5">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                <span>Formulir Pengaduan & Jurnal Transaksi</span>
            </div>
            <span class="text-xs text-slate-500">
                Semua Input Bertanda (<span class="text-rose-600 font-bold">*</span>) Wajib Diisi
            </span>
        </div>

        <div class="p-6">
            <form action="{{ route('jurnal.store') }}" method="POST">
                @csrf

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
                        <input type="text" name="nama_nasabah" value="{{ old('nama_nasabah') }}" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- No. Rekening -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">No. Rekening <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="no_rekening" value="{{ old('no_rekening') }}" required placeholder="Contoh: 00900000" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- No. Resi / Trace Number -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">No. Resi / Trace Number <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="no_resi" value="{{ old('no_resi') }}" required placeholder="Contoh: 00000000" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Nomor Kartu -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Nomor Kartu ATM/Debit <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="no_kartu" value="{{ old('no_kartu') }}" required placeholder="Contoh: 6019xxxxxxxxxxxx" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Nomor Tiket -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Nomor Tiket CS <span class="text-rose-600 font-bold">*</span></label>
                        <input type="text" name="no_tiket" value="{{ old('no_tiket') }}" required placeholder="Contoh: TKT-2026-0001" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
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
                                <option value="{{ $c->id }}" data-kode="{{ $c->kode_cabang ?? '' }}" data-nama="{{ $c->nama_cabang }}" {{ old('master_cabang_id') == $c->id ? 'selected' : '' }}>
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
                                <option value="{{ $t->id }}" {{ old('master_transaksi_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->jenis_transaksi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Channel Transaksi (Auto-fill) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700">Channel Transaksi (Otomatis)</label>
                        <input type="text" id="channel" readonly placeholder="Akan terisi otomatis..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-600 font-semibold cursor-not-allowed placeholder-slate-400">
                    </div>

                    <!-- Biaya Admin (Dropdown) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Biaya Admin (Rp) <span class="text-rose-600 font-bold">*</span></label>
                        @php
                            $adminFeeOptions = [0, 1000, 1500, 1750, 2000, 2500, 2750, 3000, 6500, 7500];
                            $selectedAdmin = (float) old('biaya_admin', 0);
                        @endphp
                        <select name="biaya_admin" id="biaya_admin" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            @foreach($adminFeeOptions as $fee)
                                <option value="{{ $fee }}" {{ $selectedAdmin == $fee ? 'selected' : '' }}>
                                    {{ $fee == 0 ? '- (Rp 0)' : 'Rp ' . number_format($fee, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nominal Transaksi -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Biaya / Nominal Transaksi (Rp) <span class="text-rose-600 font-bold">*</span></label>
                        <input type="number" name="nominal_transaksi" value="{{ old('nominal_transaksi') }}" required placeholder="Contoh: 1000000" min="0" step="any" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Terminal Transaksi / Mesin ATM -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">
                            Terminal Transaksi / Mesin ATM <span class="text-rose-600 font-bold">*</span>
                        </label>
                        <select name="terminal_transaksi" id="terminal_transaksi" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            <option value="">-- Pilih Kantor Cabang Dahulu --</option>
                        </select>
                        <div id="atmCodeBadge" class="hidden text-[13px] font-semibold text-sky-900 bg-sky-50 border border-sky-200 rounded-xl px-3.5 py-2 items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-sky-600 shrink-0">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span id="atmCodeBadgeText"></span>
                        </div>
                        <span id="terminalHelperText" class="text-[11.5px] text-slate-500">
                            Pilih kantor cabang di atas untuk memuat daftar mesin ATM.
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
                        <input type="date" name="tgl_transaksi" value="{{ old('tgl_transaksi') }}" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Tanggal Terima -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Tanggal Terima Keluhan <span class="text-rose-600 font-bold">*</span></label>
                        <input type="date" name="tgl_terima" value="{{ old('tgl_terima', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">
                            Tanggal Selesai Penanganan 
                            <span class="text-[11.5px] text-slate-500 font-normal">(Opsional jika belum selesai)</span>
                        </label>
                        <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Status Keluhan <span class="text-rose-600 font-bold">*</span></label>
                        <select name="status" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            <option value="-" {{ old('status') == '-' ? 'selected' : '' }}>- (Belum Ditentukan)</option>
                            <option value="Menunggu" {{ old('status', 'Menunggu') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Success" {{ old('status') == 'Success' ? 'selected' : '' }}>Success</option>
                            <option value="Done" {{ old('status') == 'Done' ? 'selected' : '' }}>Done</option>
                            <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Keterangan Log -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700">Keterangan Log / Catatan Kronologi Keluhan</label>
                        <textarea name="keterangan_log" placeholder="Tuliskan catatan, kronologi masalah, atau tindak lanjut petugas di sini..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none resize-y min-h-[90px]">{{ old('keterangan_log') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button type="reset" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-[13.5px] font-semibold bg-slate-100 text-slate-700 border border-slate-300 hover:bg-slate-200 hover:text-slate-900 transition-colors cursor-pointer">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                            <path d="M3 3v5h5"></path>
                        </svg>
                        <span>Reset Form</span>
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-[13.5px] font-semibold bg-gradient-to-r from-brand-blue to-navy text-white hover:opacity-95 hover:shadow-lg shadow-brand-blue/25 transition-all cursor-pointer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        <span>Simpan Jurnal Keluhan</span>
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

    const transaksiEl = document.getElementById('transaksi_id');
    const cabangSelect = document.getElementById('cabang_id');
    const terminalSelect = document.getElementById('terminal_transaksi');
    const atmCodeBadge = document.getElementById('atmCodeBadge');
    const atmCodeBadgeText = document.getElementById('atmCodeBadgeText');
    const helperText = document.getElementById('terminalHelperText');
    const atmsGrouped = @json($atmsGrouped);
    const initialTerminalVal = @json(old('terminal_transaksi', ''));

    function updateAtmCodeBadge() {
        if (!atmCodeBadge || !atmCodeBadgeText || !terminalSelect) return;
        const val = terminalSelect.value;
        if (!val || val === 'MOBILE BANKING' || val === 'ATM BANK LAIN' || val === '-') {
            atmCodeBadge.classList.add('hidden');
            atmCodeBadge.classList.remove('flex');
            return;
        }

        const selectedOpt = terminalSelect.options[terminalSelect.selectedIndex];
        const luno = selectedOpt ? selectedOpt.getAttribute('data-luno') : '';

        if (luno) {
            atmCodeBadgeText.innerHTML = `<span class="text-slate-600">Kode Mesin:</span> <strong class="font-mono text-[14.5px] font-extrabold text-navy px-2.5 py-0.5 rounded-md bg-white border border-sky-300 shadow-2xs tracking-wide">${luno}</strong>`;
            atmCodeBadge.classList.remove('hidden');
            atmCodeBadge.classList.add('flex');
        } else {
            atmCodeBadge.classList.add('hidden');
            atmCodeBadge.classList.remove('flex');
        }
    }

    function handleChannelAtmAdaptation() {
        const isMobileBanking = currentChannel.includes('MOBILE') || currentChannel.includes('SMS') || currentJenisTransaksiText.includes('MOBILE') || currentJenisTransaksiText.includes('M-BANKING') || currentJenisTransaksiText.includes('SMS BANKING');
        const isBankLain = currentChannel.includes('BANK LAIN') || currentJenisTransaksiText.includes('BANK LAIN') || (cabangSelect && cabangSelect.options[cabangSelect.selectedIndex]?.getAttribute('data-kode') === '000');

        if (isMobileBanking) {
            terminalSelect.innerHTML = '<option value="MOBILE BANKING" selected>MOBILE BANKING</option>';
            if (atmCodeBadge) {
                atmCodeBadge.classList.add('hidden');
                atmCodeBadge.classList.remove('flex');
            }
            if (helperText) helperText.textContent = 'Transaksi Mobile Banking (Tanpa mesin fisik). Cabang adalah unit pelapor/asal rekening nasabah.';
        } else if (isBankLain) {
            terminalSelect.innerHTML = '<option value="ATM BANK LAIN" selected>ATM BANK LAIN</option>';
            if (atmCodeBadge) {
                atmCodeBadge.classList.add('hidden');
                atmCodeBadge.classList.remove('flex');
            }
            if (helperText) helperText.textContent = 'Transaksi Off-Us ATM Bank Lain. Cabang adalah unit pelapor atau kantor terdekat.';
        } else {
            const selectedOpt = cabangSelect ? cabangSelect.options[cabangSelect.selectedIndex] : null;
            const kode = selectedOpt ? selectedOpt.getAttribute('data-kode') : '';
            populateAtmDropdown(kode, initialTerminalVal);
        }
    }

    function loadDetailTransaksi(id) {
        if(id) {
            fetch('/api/transaksi/' + id)
                .then(response => response.json())
                .then(data => {
                    const chInput = document.getElementById('channel');
                    if (chInput) {
                        chInput.value = data.channel || '-';
                    }
                    currentChannel = (data.channel || '').toUpperCase();
                    if (transaksiEl && transaksiEl.selectedIndex >= 0) {
                        currentJenisTransaksiText = (transaksiEl.options[transaksiEl.selectedIndex].text || '').toUpperCase();
                    }
                    handleChannelAtmAdaptation();
                })
                .catch(err => {
                    console.error('Gagal memuat detail transaksi:', err);
                });
        } else {
            const chInput = document.getElementById('channel');
            if (chInput) {
                chInput.value = '';
            }
            currentChannel = '';
            currentJenisTransaksiText = '';
            handleChannelAtmAdaptation();
        }
    }

    function populateAtmDropdown(kodeCabang, targetValue = '') {
        terminalSelect.innerHTML = '';

        if (!kodeCabang) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = '-- Pilih Kantor Cabang Dahulu --';
            terminalSelect.appendChild(opt);
            if (helperText) helperText.textContent = 'Pilih kantor cabang di atas untuk memuat daftar mesin ATM.';
            updateAtmCodeBadge();
            return;
        }

        const atms = atmsGrouped[kodeCabang] || [];

        if (atms.length === 0) {
            const opt = document.createElement('option');
            opt.value = '-';
            opt.textContent = '- (Tidak ada mesin ATM terdaftar di cabang ini)';
            terminalSelect.appendChild(opt);
            if (helperText) helperText.textContent = 'Tidak ada mesin ATM terdaftar pada cabang ini.';
            updateAtmCodeBadge();
            return;
        }

        // Default placeholder option
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = `-- Pilih Mesin ATM (${atms.length} Unit Tersedia) --`;
        terminalSelect.appendChild(defaultOpt);

        atms.forEach(atm => {
            const opt = document.createElement('option');
            opt.value = atm.value; // e.g. "180 - CRM.PALUBARAT"
            opt.textContent = atm.label; // e.g. "180 - CRM.PALUBARAT (KANTOR PALU BARAT)"
            opt.setAttribute('data-luno', atm.id_luno || '');
            opt.setAttribute('data-profil', atm.profil || '');
            opt.setAttribute('data-lokasi', atm.lokasi || '');
            if (targetValue && (targetValue === atm.value || targetValue.trim() === atm.value.trim() || targetValue.includes(atm.profil))) {
                opt.selected = true;
            }
            terminalSelect.appendChild(opt);
        });

        if (helperText) helperText.textContent = `Tersedia ${atms.length} mesin ATM terdaftar untuk cabang ini.`;
        updateAtmCodeBadge();
    }

    if (transaksiEl) {
        transaksiEl.addEventListener('change', function() {
            loadDetailTransaksi(this.value);
        });
    }

    if (cabangSelect) {
        cabangSelect.addEventListener('change', function() {
            handleChannelAtmAdaptation();
        });
    }

    if (terminalSelect) {
        terminalSelect.addEventListener('change', updateAtmCodeBadge);
    }

    // Jalankan saat load pertama kali
    window.addEventListener('DOMContentLoaded', function() {
        if (transaksiEl && transaksiEl.value) {
            loadDetailTransaksi(transaksiEl.value);
        } else if (cabangSelect && cabangSelect.value) {
            const selectedOpt = cabangSelect.options[cabangSelect.selectedIndex];
            const kode = selectedOpt ? selectedOpt.getAttribute('data-kode') : '';
            populateAtmDropdown(kode, initialTerminalVal);
        }
    });
</script>
@endpush