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

                    <!-- Biaya Admin (Dropdown) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Biaya Admin (Rp) <span class="text-rose-600 font-bold">*</span></label>
                        @php
                            $adminFeeOptions = [0, 1000, 1500, 1750, 2000, 2500, 2750, 3000, 6500, 7500];
                            $selectedAdmin = (float) old('biaya_admin', $jurnal->biaya_admin ?? $jurnal->masterTransaksi->biaya_admin ?? 0);
                            if (!in_array($selectedAdmin, $adminFeeOptions)) {
                                $adminFeeOptions[] = $selectedAdmin;
                                sort($adminFeeOptions);
                            }
                        @endphp
                        <select name="biaya_admin" id="biaya_admin" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
                            @foreach($adminFeeOptions as $fee)
                                <option value="{{ $fee }}" {{ (float)$selectedAdmin == (float)$fee ? 'selected' : '' }}>
                                    {{ $fee == 0 ? '- (Rp 0)' : 'Rp ' . number_format($fee, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nominal Transaksi -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700 flex items-center gap-1">Biaya / Nominal Transaksi (Rp) <span class="text-rose-600 font-bold">*</span></label>
                        <input type="number" name="nominal_transaksi" value="{{ old('nominal_transaksi', $jurnal->nominal_transaksi) }}" required placeholder="Contoh: 1000000" min="0" step="any" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none">
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

                    <!-- Permasalahan -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700">Permasalahan (Detail Isian Form Excel)</label>
                        <input type="text" name="permasalahan" value="{{ old('permasalahan', $jurnal->permasalahan) }}" placeholder="Contoh: TARIK TUNAI ATM LOKAL GAGAL, SALDO TERDEBET" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 uppercase transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none" oninput="this.value = this.value.toUpperCase()">
                    </div>

                    <!-- Keterangan Log -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-semibold text-[13px] text-slate-700">Keterangan Log / Catatan Kronologi Keluhan</label>
                        <textarea name="keterangan_log" placeholder="TULISKAN CATATAN, KRONOLOGI MASALAH, ATAU TINDAK LANJUT PETUGAS DI SINI..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 uppercase transition-all duration-200 focus:border-brand-blue focus:ring-3 focus:ring-brand-blue/15 focus:outline-none resize-y min-h-[90px]" oninput="this.value = this.value.toUpperCase()">{{ old('keterangan_log', $jurnal->keterangan_log) }}</textarea>
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

    function loadDetailTransaksi(id) {
        if(id) {
            fetch('/api/transaksi/' + id)
                .then(response => response.json())
                .then(data => {
                    if (channelSelect && data.channel) {
                        channelSelect.value = data.channel.toUpperCase().trim();
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
        if (transaksiEl && transaksiEl.value && channelSelect && !channelSelect.value) {
            loadDetailTransaksi(transaksiEl.value);
        }
    });
</script>
@endpush

