<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Keluhan & Hasil Pemeriksaan CCTV - {{ $jurnal->no_tiket }}</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        body {
            background-color: #f1f5f9;
            margin: 0;
            padding: 30px 20px;
            color: #000;
        }

        .form-wrapper {
            background: #ffffff;
            width: 210mm;
            margin: 0 auto 35px auto;
            padding: 28px 30px;
            border: 2px solid #000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table-form {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
        }

        .table-form td, .table-form th {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: middle;
        }

        .logo-cell {
            width: 25%;
            text-align: center;
            padding: 8px !important;
        }

        .logo-img {
            max-width: 130px;
            height: auto;
        }

        .title-cell {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-weight: bold;
            background-color: #ffffff;
            text-transform: uppercase;
        }

        .highlight-yellow {
            background-color: #ffff00 !important;
            font-weight: bold;
        }

        .text-red {
            color: #dc2626;
            font-weight: bold;
        }

        /* Tanda Tangan Form */
        .sig-header {
            text-align: center;
            font-weight: bold;
            padding: 4px !important;
        }

        .sig-body {
            height: 70px;
            text-align: center;
            vertical-align: bottom !important;
            padding-bottom: 6px !important;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .sig-title {
            font-size: 9px;
            color: #333;
        }

        /* Tombol Aksi */
        .no-print {
            width: 210mm;
            margin: 0 auto 15px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
        }

        .btn-print { background-color: #0284c7; color: #ffffff; border: none; }
        .btn-back { background-color: #64748b; color: #ffffff; border: none; }

        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }

        @media print {
            .no-print { display: none !important; }
            html, body { padding: 0 !important; margin: 0 !important; background: none !important; }
            .form-wrapper {
                border: 1.5px solid #000 !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 18px 20px !important;
                margin: 0 auto !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Navigasi Layar -->
    <div class="no-print">
        <button type="button" onclick="handleKembali()" class="btn btn-back">⬅ Kembali</button>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Form CCTV</button>
    </div>

    <div class="form-wrapper">
        <table class="table-form">
            <tr>
                <td class="logo-cell">
                    <img src="{{ asset('images/logo_bank_sulteng.png') }}" alt="Bank Sulteng" class="logo-img" onerror="this.outerHTML='<strong style=\'font-size:16px;color:#0284c7;\'>Bank Sulteng</strong>'">
                </td>
                <td class="title-cell">
                    FORM PENYELESAIAN KELUHAN NASABAH (CCTV)
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 15%; border: none; padding: 2px 0;">Deskripsi</td>
                            <td style="width: 2%; border: none; padding: 2px 0;">:</td>
                            <td style="border: none; padding: 2px 0; font-weight: bold;">Permintaan</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 2px 0;">Permintaan</td>
                            <td style="border: none; padding: 2px 0;">:</td>
                            <td style="border: none; padding: 2px 0; font-style: italic;">Terlampir Keluhan Nasabah</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="section-title">LEMBAR HELPDESK:</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 15%; border-right: 1px solid #000; border-bottom: 1px solid #000;">No. Tiket</td>
                            <td style="width: 35%; border-right: 1px solid #000; border-bottom: 1px solid #000; font-weight: bold;">: {{ $jurnal->no_tiket }}</td>
                            <td style="width: 15%; border-right: 1px solid #000; border-bottom: 1px solid #000;">Tanggal Terima</td>
                            <td style="width: 35%; border-bottom: 1px solid #000;">: {{ \Carbon\Carbon::parse($jurnal->tgl_terima)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000;">Nama Penerima</td>
                            <td style="border-right: 1px solid #000;">: {{ auth()->user()->name ?? 'Mujadid' }}</td>
                            <td style="border-right: 1px solid #000;">Status</td>
                            <td style="font-weight: bold; text-transform: uppercase;">: {{ $jurnal->status == 'Done' ? 'SELESAI' : $jurnal->status }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="section-title">PERMASALAHAN:</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 10px;">
                    <div style="font-weight: bold; margin-bottom: 10px; text-transform: uppercase;">
                        : {{ $jurnal->masterTransaksi->jenis_transaksi ?? 'PEMERIKSAAN REKAMAN CCTV ATM' }}
                    </div>

                    <table style="width: 100%; border-collapse: collapse; margin-left: 0;">
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">Nama</td>
                            <td style="width: 2%; border: none; padding: 3px 0; text-align: center;">:</td>
                            <td style="border: none; padding: 3px 0; font-weight: bold;">{{ $jurnal->nama_nasabah }}</td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">No Rekening</td>
                            <td style="width: 2%; border: none; padding: 3px 0; text-align: center;">:</td>
                            <td style="border: none; padding: 3px 0; font-weight: bold;">{{ $jurnal->no_rekening }}</td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">No Kartu ATM/No HP</td>
                            <td style="width: 2%; border: none; padding: 3px 0; text-align: center;">:</td>
                            <td style="border: none; padding: 3px 0;">{{ $jurnal->no_kartu }}</td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">Trace & Resi</td>
                            <td style="width: 2%; border: none; padding: 3px 0; text-align: center;">:</td>
                            <td style="border: none; padding: 3px 0;"><span class="highlight-yellow">{{ $jurnal->no_resi }}</span></td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">Nominal Transaksi Keluhan</td>
                            <td style="width: 2%; border: none; padding: 3px 0; text-align: center;">:</td>
                            <td style="border: none; padding: 3px 0; font-weight: bold;">Rp {{ number_format($jurnal->nominal_transaksi, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">Tanggal Transaksi</td>
                            <td style="width: 2%; border: none; padding: 3px 0; text-align: center;">:</td>
                            <td style="border: none; padding: 3px 0; font-weight: bold;">{{ \Carbon\Carbon::parse($jurnal->tgl_transaksi)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">Cabang Transaksi</td>
                            <td style="width: 2%; border: none; padding: 3px 0; text-align: center;">:</td>
                            <td style="border: none; padding: 3px 0;">{{ $jurnal->masterCabang->kode_cabang ?? '-' }} - {{ $jurnal->masterCabang->nama_cabang ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">Terminal Lokasi Transaksi</td>
                            <td style="width: 2%; border: none; padding: 3px 0; text-align: center;">:</td>
                            <td style="border: none; padding: 3px 0;"><span class="highlight-yellow">{{ $jurnal->terminal_transaksi }}</span></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="section-title" style="text-align: center;">TINDAK LANJUT PENYELESAIAN</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 10px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25%; border: none; padding: 4px 0; font-weight: bold;">STATUS KLAIM</td>
                            <td style="width: 2%; border: none; padding: 4px 0; font-weight: bold; text-align: center;">:</td>
                            <td style="border: none; padding: 4px 0;" class="text-red">
                                {{ strtolower($jurnal->status) == 'rejected' ? 'KLAIM DITOLAK' : 'KLAIM DITERIMA' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border: none; padding: 4px 0; font-weight: bold; vertical-align: top;">KETERANGAN</td>
                            <td style="width: 2%; border: none; padding: 4px 0; font-weight: bold; vertical-align: top; text-align: center;">:</td>
                            <td style="border: none; padding: 4px 0; line-height: 1.5; white-space: pre-wrap;">{{ $jurnal->keterangan_log ?? 'Hasil penyelesaian CCTV ATM terlampir pada berita acara penyelesaian keluhan nasabah. Berita acara keluhan dan hasil data CCTV tidak untuk di serahkan ke nasabah' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 6px 10px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25%; border: none; padding: 0;">Tanggal selesai</td>
                            <td style="width: 2%; border: none; padding: 0; text-align: center;">:</td>
                            <td style="border: none; padding: 0; font-weight: bold;">{{ $jurnal->tgl_selesai ? \Carbon\Carbon::parse($jurnal->tgl_selesai)->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td colspan="3" class="sig-header" style="width: 75%; border-right: 1px solid #000;">Di Selesaikan Oleh,</td>
                            <td class="sig-header" style="width: 25%;">Di ketahui Oleh,</td>
                        </tr>
                        <tr>
                            <td class="sig-body" style="width: 25%; border-right: 1px solid #000;">
                                <div class="sig-name">MUJADID</div>
                                <div class="sig-title">Staf Layanan Keluhan dan Monitoring Transaksi Kartu</div>
                            </td>
                            <td class="sig-body" style="width: 25%; border-right: 1px solid #000;">
                                <div class="sig-name">AYU FEBRIANTI</div>
                                <div class="sig-title">Pemimpin Unit Layanan Keluhan dan Monitoring Transaksi Kartu</div>
                            </td>
                            <td class="sig-body" style="width: 25%; border-right: 1px solid #000;">
                                <div class="sig-name">WACHYUNI MADARAYU</div>
                                <div class="sig-title">PINBAG E- CHANEL</div>
                            </td>
                            <td class="sig-body" style="width: 25%;">
                                <div class="sig-name">DIANA, ST</div>
                                <div class="sig-title">Pemimpin Divisi IT</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <script>
        function handleKembali() {
            window.close();
            setTimeout(function() {
                if (!window.closed) {
                    window.location.href = "{{ route('jurnal.index') }}";
                }
            }, 150);
        }
    </script>
</body>
</html>