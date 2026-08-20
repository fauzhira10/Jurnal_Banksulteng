<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Jurnal Keluhan Nasabah - Bank Sulteng</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h2 { color: #0056b3; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background-color: #218838; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Form Jurnal Keluhan Nasabah</h2>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <!-- Notifikasi Error / Gagal Validasi -->
    @if ($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/jurnal/simpan" method="POST">
        @csrf
        
        <div class="form-group">
            <label>Nama Nasabah:</label>
            <input type="text" name="nama_nasabah" required placeholder="Contoh: Budi Santoso">
        </div>

        <div class="form-group">
            <label>No. Resi / Trace Number:</label>
            <input type="text" name="no_resi" required placeholder="Contoh: 00000000">
        </div>

        <div class="form-group">
            <label>No. Rekening:</label>
            <input type="text" name="no_rekening" required placeholder="Contoh: 00900000">
        </div>

        <div class="form-group">
            <label>Tanggal Transaksi:</label>
            <input type="date" name="tgl_transaksi" required>
        </div>

        <div class="form-group">
            <label>Cabang Pelapor:</label>
            <select name="master_cabang_id" required>
                <option value="">-- Pilih Cabang --</option>
                @foreach($cabangs as $c)
                    <option value="{{ $c->id }}">{{ $c->kode_cabang }} - {{ $c->nama_cabang }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Transaksi:</label>
            <select name="master_transaksi_id" id="transaksi_id" required>
                <option value="">-- Pilih Jenis Transaksi --</option>
                @foreach($transaksis as $t)
                    <option value="{{ $t->id }}">{{ $t->jenis_transaksi }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Biaya Admin (Otomatis):</label>
            <input type="text" id="biaya_admin" readonly placeholder="Akan terisi otomatis...">
        </div>

        <div class="form-group">
            <label>Channel (Otomatis):</label>
            <input type="text" id="channel" readonly placeholder="Akan terisi otomatis...">
        </div>

        <div class="form-group">
            <label>Nominal Transaksi (Rp):</label>
            <input type="number" name="nominal_transaksi" required placeholder="Contoh: 1000000">
        </div>

        <button type="submit">Simpan Jurnal Keluhan</button>
    </form>
</div>

<script>
    // Logic Auto-fill dengan AJAX saat Jenis Transaksi dipilih
    document.getElementById('transaksi_id').addEventListener('change', function() {
        let id = this.value;
        if(id) {
            fetch('/api/transaksi/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('biaya_admin').value = data.biaya_admin;
                    document.getElementById('channel').value = data.channel;
                });
        } else {
            document.getElementById('biaya_admin').value = '';
            document.getElementById('channel').value = '';
        }
    });
</script>

</body>
</html>