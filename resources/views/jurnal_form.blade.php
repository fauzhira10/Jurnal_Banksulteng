<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Jurnal Keluhan Nasabah - Bank Sulteng</title>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 30px 15px; 
            background-color: #f0f4f8; 
            color: #333;
        }
        .container { 
            max-width: 850px; 
            margin: 0 auto; 
            background: #ffffff; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
        }
        .header { 
            border-bottom: 2px solid #0056b3; 
            padding-bottom: 12px; 
            margin-bottom: 25px; 
        }
        .header h2 { 
            margin: 0; 
            color: #0056b3; 
            font-size: 24px;
        }
        .header p { 
            margin: 5px 0 0; 
            color: #666; 
            font-size: 14px; 
        }
        .form-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 18px; 
        }
        .full-width { 
            grid-column: span 2; 
        }
        .form-group { 
            display: flex; 
            flex-direction: column; 
        }
        label { 
            font-weight: 600; 
            margin-bottom: 6px; 
            font-size: 13px; 
            color: #444; 
        }
        label .required { 
            color: #dc3545; 
        }
        input, select, textarea { 
            width: 100%; 
            padding: 9px 12px; 
            border: 1px solid #ced4da; 
            border-radius: 6px; 
            font-size: 14px; 
            transition: border-color 0.2s; 
        }
        input:focus, select:focus, textarea:focus { 
            border-color: #0056b3; 
            outline: none; 
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.15); 
        }
        input[readonly] { 
            background-color: #e9ecef; 
            cursor: not-allowed; 
        }
        textarea { 
            resize: vertical; 
            min-height: 80px; 
        }
        .btn-submit { 
            background-color: #0056b3; 
            color: white; 
            padding: 12px 20px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: 600; 
            width: 100%; 
            transition: background-color 0.2s; 
            margin-top: 10px; 
        }
        .btn-submit:hover { 
            background-color: #004085; 
        }
        .alert { 
            padding: 12px 16px; 
            margin-bottom: 20px; 
            border-radius: 6px; 
            font-size: 14px; 
        }
        .alert-success { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .alert-danger { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        .alert-danger ul { 
            margin: 0; 
            padding-left: 20px; 
        }
        @media (max-width: 650px) {
            .form-grid { 
                grid-template-columns: 1fr; 
            }
            .full-width { 
                grid-column: span 1; 
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Form Jurnal Keluhan Nasabah</h2>
        <p>Bank Sulteng - Pencatatan dan Manajemen Keluhan Transaksi</p>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Notifikasi Error / Gagal Validasi -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/jurnal/simpan" method="POST">
        @csrf
        
        <div class="form-grid">
            <!-- Nama Nasabah -->
            <div class="form-group">
                <label>Nama Nasabah <span class="required">*</span></label>
                <input type="text" name="nama_nasabah" value="{{ old('nama_nasabah') }}" required placeholder="Contoh: Budi Santoso">
            </div>

            <!-- No. Resi / Trace Number -->
            <div class="form-group">
                <label>No. Resi / Trace Number <span class="required">*</span></label>
                <input type="text" name="no_resi" value="{{ old('no_resi') }}" required placeholder="Contoh: 00000000">
            </div>

            <!-- No. Rekening -->
            <div class="form-group">
                <label>No. Rekening <span class="required">*</span></label>
                <input type="text" name="no_rekening" value="{{ old('no_rekening') }}" required placeholder="Contoh: 00900000">
            </div>

            <!-- Nomor Kartu -->
            <div class="form-group">
                <label>Nomor Kartu</label>
                <input type="text" name="no_kartu" value="{{ old('no_kartu') }}" placeholder="Contoh: 6019xxxxxxxxxxxx">
            </div>

            <!-- Nomor Tiket -->
            <div class="form-group">
                <label>Nomor Tiket</label>
                <input type="text" name="no_tiket" value="{{ old('no_tiket') }}" placeholder="Contoh: TKT-2026-0001">
            </div>

            <!-- Tanggal Transaksi -->
            <div class="form-group">
                <label>Tanggal Transaksi <span class="required">*</span></label>
                <input type="date" name="tgl_transaksi" value="{{ old('tgl_transaksi') }}" required>
            </div>

            <!-- Tanggal Terima -->
            <div class="form-group">
                <label>Tanggal Terima <span class="required">*</span></label>
                <input type="date" name="tgl_terima" value="{{ old('tgl_terima', date('Y-m-d')) }}" required>
            </div>

            <!-- Tanggal Selesai -->
            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}">
            </div>

            <!-- Cabang Transaksi / Pelapor -->
            <div class="form-group">
                <label>Cabang Transaksi / Pelapor <span class="required">*</span></label>
                <select name="master_cabang_id" required>
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($cabangs as $c)
                        <option value="{{ $c->id }}" {{ old('master_cabang_id') == $c->id ? 'selected' : '' }}>
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
                        <option value="{{ $t->id }}" {{ old('master_transaksi_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->jenis_transaksi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Biaya Admin (Auto-fill) -->
            <div class="form-group">
                <label>Biaya Admin (Otomatis)</label>
                <input type="text" id="biaya_admin" readonly placeholder="Akan terisi otomatis...">
            </div>

            <!-- Channel Transaksi (Auto-fill) -->
            <div class="form-group">
                <label>Channel Transaksi (Otomatis)</label>
                <input type="text" id="channel" readonly placeholder="Akan terisi otomatis...">
            </div>

            <!-- Terminal Transaksi -->
            <div class="form-group">
                <label>Terminal Transaksi</label>
                <input type="text" name="terminal_transaksi" value="{{ old('terminal_transaksi') }}" placeholder="Contoh: ATM-001 / EDC-PL">
            </div>

            <!-- Biaya / Nominal Transaksi -->
            <div class="form-group">
                <label>Biaya / Nominal Transaksi (Rp) <span class="required">*</span></label>
                <input type="number" name="nominal_transaksi" value="{{ old('nominal_transaksi') }}" required placeholder="Contoh: 1000000">
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" required>
                    <option value="Menunggu" {{ old('status', 'Menunggu') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Success" {{ old('status') == 'Success' ? 'selected' : '' }}>Success</option>
                    <option value="Done" {{ old('status') == 'Done' ? 'selected' : '' }}>Done</option>
                    <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="form-group full-width">
                <label>Keterangan Log / Catatan Keluhan</label>
                <textarea name="keterangan_log" placeholder="Tuliskan keterangan atau kronologi keluhan transaksi nasabah di sini...">{{ old('keterangan_log') }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn-submit">Simpan Jurnal Keluhan</button>
    </form>
</div>

<script>
    // Logic Auto-fill dengan AJAX saat Jenis Transaksi dipilih
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

    // Jalankan saat load pertama kali jika jenis transaksi sudah terpilih sebelumnya
    window.addEventListener('DOMContentLoaded', function() {
        let initialId = document.getElementById('transaksi_id').value;
        if(initialId) {
            loadDetailTransaksi(initialId);
        }
    });
</script>

</body>
</html>