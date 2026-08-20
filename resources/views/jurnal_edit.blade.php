@extends('layouts.app')

@section('title', 'Edit Jurnal Keluhan')
@section('page_title', 'Edit Data Jurnal Keluhan')
@section('page_subtitle', 'Pembaruan dan koreksi data keluhan transaksi nasabah Bank Sulteng')

@section('topbar_action')
    <a href="{{ route('jurnal.index') }}" class="btn btn-secondary btn-sm" style="height: 38px; padding: 0 14px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Kembali ke Data Keluhan</span>
    </a>
@endsection

@push('styles')
<style>
    .form-container {
        max-width: 950px;
        margin: 0 auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .full-width {
        grid-column: span 2;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 13px;
        color: var(--bs-gray-700);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-group label .required {
        color: var(--bs-danger);
    }

    .form-group input, 
    .form-group select, 
    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--bs-gray-300);
        border-radius: var(--radius-md);
        font-size: 14px;
        background-color: #ffffff;
        color: var(--bs-gray-800);
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-group input:focus, 
    .form-group select:focus, 
    .form-group textarea:focus {
        border-color: var(--bs-blue);
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.15);
    }

    .form-group input[readonly] {
        background-color: #f8fafc;
        border-color: var(--bs-gray-200);
        color: var(--bs-gray-600);
        cursor: not-allowed;
        font-weight: 600;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 90px;
    }

    .form-actions {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--bs-gray-200);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .section-divider {
        grid-column: span 2;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0 4px;
        color: var(--bs-blue);
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-divider::after {
        content: '';
        flex-grow: 1;
        height: 1px;
        background: var(--bs-gray-200);
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .full-width, .section-divider {
            grid-column: span 1;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <span>Edit Rincian Jurnal Keluhan #{{ $jurnal->id }} ({{ $jurnal->nama_nasabah }})</span>
            </div>
            <span style="font-size: 12px; color: var(--bs-gray-500);">
                Semua Input Bertanda (<span style="color: var(--bs-danger); font-weight: bold;">*</span>) Wajib Diisi
            </span>
        </div>

        <div class="card-body">
            <form action="{{ route('jurnal.update', $jurnal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <!-- SECTION 1: IDENTITAS NASABAH -->
                    <div class="section-divider">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>1. Identitas Nasabah & Rekening</span>
                    </div>

                    <!-- Nama Nasabah -->
                    <div class="form-group">
                        <label>Nama Nasabah <span class="required">*</span></label>
                        <input type="text" name="nama_nasabah" value="{{ old('nama_nasabah', $jurnal->nama_nasabah) }}" required placeholder="Contoh: Budi Santoso">
                    </div>

                    <!-- No. Rekening -->
                    <div class="form-group">
                        <label>No. Rekening <span class="required">*</span></label>
                        <input type="text" name="no_rekening" value="{{ old('no_rekening', $jurnal->no_rekening) }}" required placeholder="Contoh: 00900000">
                    </div>

                    <!-- No. Resi / Trace Number -->
                    <div class="form-group">
                        <label>No. Resi / Trace Number <span class="required">*</span></label>
                        <input type="text" name="no_resi" value="{{ old('no_resi', $jurnal->no_resi) }}" required placeholder="Contoh: 00000000">
                    </div>

                    <!-- Nomor Kartu -->
                    <div class="form-group">
                        <label>Nomor Kartu ATM/Debit <span class="required">*</span></label>
                        <input type="text" name="no_kartu" value="{{ old('no_kartu', $jurnal->no_kartu) }}" required placeholder="Contoh: 6019xxxxxxxxxxxx">
                    </div>

                    <!-- Nomor Tiket -->
                    <div class="form-group full-width">
                        <label>Nomor Tiket CS <span class="required">*</span></label>
                        <input type="text" name="no_tiket" value="{{ old('no_tiket', $jurnal->no_tiket) }}" required placeholder="Contoh: TKT-2026-0001">
                    </div>

                    <!-- SECTION 2: DETAIL TRANSAKSI & KANTOR CABANG -->
                    <div class="section-divider">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span>2. Detail Transaksi & Lokasi</span>
                    </div>

                    <!-- Cabang Transaksi / Pelapor -->
                    <div class="form-group">
                        <label>Cabang Transaksi / Pelapor <span class="required">*</span></label>
                        <select name="master_cabang_id" required>
                            <option value="">-- Pilih Kantor Cabang --</option>
                            @foreach($cabangs as $c)
                                <option value="{{ $c->id }}" {{ old('master_cabang_id', $jurnal->master_cabang_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->kode_cabang }} - {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div class="form-group">
                        <label>Jenis Transaksi <span class="required">*</span></label>
                        <select name="master_transaksi_id" id="transaksi_id" required>
                            <option value="">-- Pilih Jenis Transaksi --</option>
                            @foreach($transaksis as $t)
                                <option value="{{ $t->id }}" {{ old('master_transaksi_id', $jurnal->master_transaksi_id) == $t->id ? 'selected' : '' }}>
                                    {{ $t->jenis_transaksi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Channel Transaksi (Auto-fill) -->
                    <div class="form-group">
                        <label>Channel Transaksi (Otomatis)</label>
                        <input type="text" id="channel" readonly value="{{ $jurnal->masterTransaksi->channel ?? '' }}" placeholder="Akan terisi otomatis...">
                    </div>

                    <!-- Biaya Admin (Auto-fill) -->
                    <div class="form-group">
                        <label>Biaya Admin (Otomatis)</label>
                        <input type="text" id="biaya_admin" readonly value="Rp {{ number_format($jurnal->masterTransaksi->biaya_admin ?? 0, 0, ',', '.') }}" placeholder="Akan terisi otomatis...">
                    </div>

                    <!-- Nominal Transaksi -->
                    <div class="form-group">
                        <label>Biaya / Nominal Transaksi (Rp) <span class="required">*</span></label>
                        <input type="number" name="nominal_transaksi" value="{{ old('nominal_transaksi', $jurnal->nominal_transaksi) }}" required placeholder="Contoh: 1000000" min="0" step="any">
                    </div>

                    <!-- Terminal Transaksi -->
                    <div class="form-group">
                        <label>Terminal Transaksi / Mesin <span class="required">*</span></label>
                        <input type="text" name="terminal_transaksi" value="{{ old('terminal_transaksi', $jurnal->terminal_transaksi) }}" required placeholder="Contoh: ATM-001 / EDC-PL">
                    </div>

                    <!-- SECTION 3: WAKTU, STATUS & KRONOLOGI -->
                    <div class="section-divider">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>3. Waktu & Status Penanganan</span>
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div class="form-group">
                        <label>Tanggal Transaksi Bermasalah <span class="required">*</span></label>
                        <input type="date" name="tgl_transaksi" value="{{ old('tgl_transaksi', $jurnal->tgl_transaksi) }}" required>
                    </div>

                    <!-- Tanggal Terima -->
                    <div class="form-group">
                        <label>Tanggal Terima Keluhan <span class="required">*</span></label>
                        <input type="date" name="tgl_terima" value="{{ old('tgl_terima', $jurnal->tgl_terima) }}" required>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="form-group">
                        <label>Tanggal Selesai Penanganan <span class="required">*</span></label>
                        <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai', $jurnal->tgl_selesai) }}" required>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label>Status Keluhan <span class="required">*</span></label>
                        <select name="status" required>
                            <option value="Menunggu" {{ old('status', $jurnal->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Success" {{ old('status', $jurnal->status) == 'Success' ? 'selected' : '' }}>Success</option>
                            <option value="Done" {{ old('status', $jurnal->status) == 'Done' ? 'selected' : '' }}>Done</option>
                            <option value="Rejected" {{ old('status', $jurnal->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Keterangan Log -->
                    <div class="form-group full-width">
                        <label>Keterangan Log / Catatan Kronologi Keluhan <span class="required">*</span></label>
                        <textarea name="keterangan_log" required placeholder="Tuliskan catatan, kronologi masalah, atau tindak lanjut petugas di sini...">{{ old('keterangan_log', $jurnal->keterangan_log) }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="btn btn-primary">
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
    // Logic Auto-fill AJAX saat Jenis Transaksi dipilih
    function loadDetailTransaksi(id) {
        if(id) {
            fetch('/api/transaksi/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('biaya_admin').value = data.biaya_admin ? 'Rp ' + Number(data.biaya_admin).toLocaleString('id-ID') : 'Rp 0';
                    document.getElementById('channel').value = data.channel || '-';
                })
                .catch(err => {
                    console.error('Gagal memuat detail transaksi:', err);
                });
        } else {
            document.getElementById('biaya_admin').value = '';
            document.getElementById('channel').value = '';
        }
    }

    document.getElementById('transaksi_id').addEventListener('change', function() {
        loadDetailTransaksi(this.value);
    });
</script>
@endpush
