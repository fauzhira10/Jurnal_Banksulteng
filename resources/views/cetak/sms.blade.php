<div>
    <!-- It is never too late to be what you might have been. - George Eliot -->
</div>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Keluhan & Slip Jurnal SMS Banking - {{ $jurnal->no_tiket }}</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        body {
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #000;
        }

        .form-wrapper {
            background: #ffffff;
            width: 210mm;
            margin: 0 auto 30px auto;
            padding: 20px;
            border: 2px solid #000;
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

        /* SLIP JURNAL / NOTA DEBET STYLING */
        .page-break {
            page-break-before: always;
        }

        .slip-header-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            margin-bottom: -1px;
        }

        .slip-header-table td {
            border: 1px solid #000;
            padding: 6px 10px;
        }

        .slip-title-main {
            font-weight: bold;
            font-size: 13px;
            text-align: center;
        }

        .slip-title-sub {
            font-weight: bold;
            font-size: 11px;
            text-align: center;
        }

        .table-slip {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
        }

        .table-slip td, .table-slip th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .slip-col-header {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            background-color: #f8fafc;
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

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: none; }
            .form-wrapper { border: 1.5px solid #000; width: 100%; padding: 15px; margin-bottom: 0; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>

    <!-- Tombol Navigasi Layar -->
    <div class="no-print">
        <a href="javascript:history.back()" class="btn btn-back">⬅ Kembali</a>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Form & Slip Jurnal</button>
    </div>

    <!-- BAGIAN 1: FORM PENYELESAIAN KELUHAN NASABAH -->
    <div class="form-wrapper">
        <table class="table-form">
            <tr>
                <td class="logo-cell">
                    <img src="{{ asset('images/logo_bank_sulteng.png') }}" alt="Bank Sulteng" class="logo-img" onerror="this.outerHTML='<strong style=\'font-size:16px;color:#0284c7;\'>Bank Sulteng</strong>'">
                </td>
                <td class="title-cell">
                    FORM PENYELESAIAN KELUHAN NASABAH
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
                        : {{ $jurnal->masterTransaksi->jenis_transaksi ?? 'TRANSAKSI SMS BANKING GAGAL, SALDO TERDEBET' }}
                    </div>

                    <table style="width: 80%; border-collapse: collapse; margin-left: 30px;">
                        <tr>
                            <td style="width: 25%; border: none; padding: 3px 0;">Nama</td>
                            <td style="width: 2%; border: none; padding: 3px 0;">:</td>
                            <td style="border: none; padding: 3px 0; font-weight: bold;">{{ $jurnal->nama_nasabah }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 3px 0;">No Rekening</td>
                            <td style="border: none; padding: 3px 0;">:</td>
                            <td style="border: none; padding: 3px 0; font-weight: bold;">{{ $jurnal->no_rekening }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 3px 0;">No Kartu ATM/No HP</td>
                            <td style="border: none; padding: 3px 0;">:</td>
                            <td style="border: none; padding: 3px 0;">{{ $jurnal->no_kartu }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 3px 0;">Trace & Resi</td>
                            <td style="border: none; padding: 3px 0;">:</td>
                            <td style="border: none; padding: 3px 0;"><span class="highlight-yellow">{{ $jurnal->no_resi }}</span></td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 3px 0;">Nominal Transaksi Keluhan</td>
                            <td style="border: none; padding: 3px 0;">:</td>
                            <td style="border: none; padding: 3px 0; font-weight: bold;">Rp {{ number_format($jurnal->nominal_transaksi, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 3px 0;">Biaya Admin</td>
                            <td style="border: none; padding: 3px 0;">:</td>
                            <td style="border: none; padding: 3px 0;">Rp {{ number_format($jurnal->masterTransaksi->biaya_admin ?? 0, 0, ',', '.') ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 3px 0;">Tanggal Transaksi</td>
                            <td style="border: none; padding: 3px 0;">:</td>
                            <td style="border: none; padding: 3px 0; font-weight: bold;">{{ \Carbon\Carbon::parse($jurnal->tgl_transaksi)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 3px 0;">Cabang Transaksi</td>
                            <td style="border: none; padding: 3px 0;">:</td>
                            <td style="border: none; padding: 3px 0;">{{ $jurnal->masterCabang->kode_cabang ?? '-' }} - {{ $jurnal->masterCabang->nama_cabang ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 3px 0;">Terminal Lokasi Transaksi</td>
                            <td style="border: none; padding: 3px 0;">:</td>
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
                            <td style="width: 18%; border: none; padding: 4px 0; font-weight: bold;">STATUS KLAIM</td>
                            <td style="width: 2%; border: none; padding: 4px 0; font-weight: bold;">:</td>
                            <td style="border: none; padding: 4px 0;" class="text-red">
                                {{ strtolower($jurnal->status) == 'rejected' ? 'KLAIM DITOLAK' : 'KLAIM DITERIMA' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 4px 0; font-weight: bold; vertical-align: top;">KETERANGAN</td>
                            <td style="border: none; padding: 4px 0; font-weight: bold; vertical-align: top;">:</td>
                            <td style="border: none; padding: 4px 0; line-height: 1.5; white-space: pre-wrap;">{{ $jurnal->keterangan_log ?? 'Berdasarkan hasil pemeriksaan log MBBS transaksi tersebut gagal, Status time out RCODE (SETT 68). Claim di terima' }}</td>
                        </tr>
                    </table>

                    @if(strtolower($jurnal->status) != 'rejected')
                    <div style="margin-top: 15px; font-size: 10.5px; color: #333;">
                        Maka akan dikreditkan kerekening Nasabah Bank Sulteng sebagai berikut:
                        <table style="width: 60%; border-collapse: collapse; margin-top: 4px; margin-left: 20px;">
                            <tr>
                                <td style="width: 30%; border: none; padding: 2px 0;">Nama</td>
                                <td style="width: 4%; border: none; padding: 2px 0;">:</td>
                                <td style="border: none; padding: 2px 0; font-weight: bold;">{{ $jurnal->nama_nasabah }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 0;">No Rekening</td>
                                <td style="border: none; padding: 2px 0;">:</td>
                                <td style="border: none; padding: 2px 0; font-weight: bold;">{{ $jurnal->no_rekening }}</td>
                            </tr>
                        </table>
                    </div>
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 6px 10px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 18%; border: none; padding: 0;">Tanggal selesai</td>
                            <td style="width: 2%; border: none; padding: 0;">:</td>
                            <td style="border: none; padding: 0; font-weight: bold;">{{ \Carbon\Carbon::parse($jurnal->tgl_selesai)->translatedFormat('d F Y') }}</td>
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
                                <div class="sig-name">NUR SANTI HASYIM</div>
                                <div class="sig-title">Supervisi layanan keluhan dan Monitoring Kartu</div>
                            </td>
                            <td class="sig-body" style="width: 25%; border-right: 1px solid #000;">
                                <div class="sig-name">MOHAMAD NUR</div>
                                <div class="sig-title">PINBAG E- CHANEL</div>
                            </td>
                            <td class="sig-body" style="width: 25%;">
                                <div class="sig-name">DIANA ST</div>
                                <div class="sig-title">Pemimpin Divisi IT</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- PAGE BREAK UNTUK CETAK SLIP JURNAL -->
    <div class="page-break"></div>

    <!-- BAGIAN 2: NOTA DEBET / SLIP JURNAL DIVISI IT (SMS BANKING) -->
    <div class="form-wrapper">
        <table class="slip-header-table">
            <tr>
                <td style="width: 30%; text-align: center;">
                    <img src="{{ asset('images/logo_bank_sulteng.png') }}" alt="Bank Sulteng" class="logo-img" onerror="this.outerHTML='<strong style=\'font-size:16px;color:#0284c7;\'>Bank Sulteng</strong>'">
                </td>
                <td style="width: 40%;" class="slip-title-main">
                    NOTA DEBET
                </td>
                <td style="width: 30%;" class="slip-title-sub">
                    SLIP JURNAL<br>DIVISI IT
                </td>
            </tr>
        </table>

        <table class="table-slip">
            <thead>
                <tr>
                    <th style="width: 42%;" class="slip-col-header">D E B E T</th>
                    <th style="width: 16%;" class="slip-col-header">Jumlah dalam Rp.</th>
                    <th style="width: 42%;" class="slip-col-header">K R E D I T</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <!-- DEBET UTAMA: SMS BANKING -->
                    <td style="padding: 10px;">
                        <div style="font-weight: bold; text-align: center; margin-bottom: 4px;">PENAMPUNGAN SELISIH ATM BERSAMA</div>
                        <div style="font-weight: bold; text-align: center; margin-bottom: 12px;">000.00.2310527.003.360</div>

                        <div style="margin-left: 10px; line-height: 1.6;">
                            <div>Rev. Transaksi SMS Banking</div>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr><td style="width: 38%; border: none; padding: 1px 0;">No HP terdaftar</td><td style="width: 3%; border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0; font-weight: bold;">{{ $jurnal->no_kartu }}</td></tr>
                                <tr><td style="border: none; padding: 1px 0;">No Resi</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0; font-weight: bold;">{{ $jurnal->no_resi }}</td></tr>
                                <tr><td style="border: none; padding: 1px 0;">No Tiket</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0; font-weight: bold;">{{ $jurnal->no_tiket }}</td></tr>
                                <tr><td style="border: none; padding: 1px 0;">Tgl Transaksi</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0; font-weight: bold;">{{ \Carbon\Carbon::parse($jurnal->tgl_transaksi)->translatedFormat('d F Y') }}</td></tr>
                                <tr><td style="border: none; padding: 1px 0;">Jumlah Transaksi</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;"></td></tr>
                            </table>
                        </div>
                    </td>

                    <!-- NOMINAL DEBET -->
                    <td style="vertical-align: bottom; text-align: right; padding: 10px 6px;">
                        <div style="font-weight: bold; display: flex; justify-content: space-between;">
                            <span>Rp</span>
                            <span>{{ number_format($jurnal->nominal_transaksi, 0, ',', '.') }}</span>
                        </div>
                    </td>

                    <!-- KREDIT NASABAH -->
                    <td style="padding: 10px;">
                        <div style="font-weight: bold; text-align: center; margin-bottom: 4px; text-transform: uppercase;">{{ $jurnal->nama_nasabah }}</div>
                        <div style="font-weight: bold; text-align: center; margin-bottom: 12px;">{{ $jurnal->no_rekening }}</div>

                        <div style="margin-left: 10px; line-height: 1.6;">
                            <div>Rev. Transaksi SMS Banking</div>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr><td style="width: 38%; border: none; padding: 1px 0;">No HP terdaftar</td><td style="width: 3%; border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0; font-weight: bold;">{{ $jurnal->no_kartu }}</td></tr>
                                <tr><td style="border: none; padding: 1px 0;">No Resi</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0; font-weight: bold;">{{ $jurnal->no_resi }}</td></tr>
                                <tr><td style="border: none; padding: 1px 0;">No Tiket</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0; font-weight: bold;">{{ $jurnal->no_tiket }}</td></tr>
                                <tr><td style="border: none; padding: 1px 0;">Tgl Transaksi</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0; font-weight: bold;">{{ \Carbon\Carbon::parse($jurnal->tgl_transaksi)->translatedFormat('d F Y') }}</td></tr>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- SUBSIDIARY ROW: BIAYA ADMIN SMS BANKING -->
                <tr>
                    <td style="padding: 10px;">
                        <div style="font-weight: bold; text-align: center; margin-bottom: 4px;">PENAMPUNGAN SELISIH ATM BERSAMA</div>
                        <div style="font-weight: bold; text-align: center; margin-bottom: 8px;">000.00.2310527.003.360</div>

                        <div style="margin-left: 10px; line-height: 1.6;">
                            <div>REV. Fee Adm SMS Banking</div>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr><td style="width: 35%; border: none; padding: 1px 0;">Jumlah Fee</td><td style="width: 3%; border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;"></td></tr>
                            </table>
                        </div>
                    </td>

                    <!-- NOMINAL FEE -->
                    <td style="vertical-align: top; text-align: right; padding: 10px 6px;">
                        <div style="display: flex; justify-content: space-between; margin-top: 28px;">
                            <span>Rp</span>
                            <span>{{ number_format($jurnal->masterTransaksi->biaya_admin ?? 0, 0, ',', '.') ?: '-' }}</span>
                        </div>
                    </td>

                    <!-- KREDIT NOMINAL -->
                    <td style="vertical-align: bottom; text-align: right; padding: 10px 6px;">
                        <div style="font-weight: bold; display: flex; justify-content: space-between;">
                            <span>Rp</span>
                            <span>{{ number_format($jurnal->nominal_transaksi, 0, ',', '.') }}</span>
                        </div>
                    </td>
                </tr>

                <!-- FOOTER PEMBUKUAN & OLEH -->
                <tr>
                    <td colspan="2" style="padding: 0;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td colspan="2" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000;">Pembukuan</td>
                                <td colspan="2" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000;">Dibukukan</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; text-align: center; border-right: 1px solid #000; padding: 4px;">Fiat</td>
                                <td style="width: 25%; text-align: center; border-right: 1px solid #000; padding: 4px;">Kontr</td>
                                <td style="width: 25%; text-align: center; border-right: 1px solid #000; padding: 4px;">Oleh</td>
                                <td style="width: 25%; text-align: center; padding: 4px;">Tgl</td>
                            </tr>
                            <tr style="height: 60px;">
                                <td style="border-right: 1px solid #000;"></td>
                                <td style="border-right: 1px solid #000;"></td>
                                <td style="border-right: 1px solid #000;"></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                    <td style="padding: 0;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 60%; text-align: center; font-weight: bold; border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 4px;">Dibuat/Ditugaskan Oleh</td>
                                <td style="width: 40%; text-align: center; font-weight: bold; border-bottom: 1px solid #000; padding: 4px;">Paraf & Tanggal</td>
                            </tr>
                            <tr>
                                <td style="border-right: 1px solid #000; padding: 4px;">pemimpin Divisi</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="border-right: 1px solid #000; padding: 4px;">Pemimpin Bagian</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="border-right: 1px solid #000; padding: 4px;">Pemimpin Unit</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="border-right: 1px solid #000; padding: 4px;">Staf</td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>