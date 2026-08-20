# Dokumentasi Projek: Sistem Jurnal Keluhan Nasabah (Bank Sulteng)

## 📌 1. Deskripsi Projek
Projek **Jurnal_Banksulteng** adalah aplikasi web berbasis **Laravel 12** yang dirancang khusus untuk memfasilitasi pencatatan, validasi, dan pengelolaan **Jurnal Keluhan Transaksi Nasabah** di lingkungan Bank Sulteng.

### Tujuan & Manfaat Utama:
- **Pencatatan Terpusat**: Menggantikan pencatatan manual keluhan nasabah ke dalam sistem web yang terstruktur.
- **Hard Anti-Duplikat**: Mencegah klaim ganda atas transaksi keluhan nasabah yang sama (berdasarkan kombinasi Nama Nasabah + No. Resi + Tanggal Transaksi).
- **Otomatisasi Channel & Biaya Admin**: Mempercepat pengisian form dengan mekanisme *auto-fill* berbasis AJAX saat jenis transaksi dipilih.
- **Keamanan & Kepatuhan**: Menyiapkan rekam jejak audit (*audit trail*) berbasis hash chaining untuk integritas data perbankan.

---

## 📊 2. Status & Progres Pengerjaan Projek

### Ringkasan Pencapaian (Milestone Progress)

```text
[████████████████████] 100% - Fase 1: Basis Data & Master Data
[████████████████████] 100% - Fase 2: Formulir Frontend & Auto-Fill AJAX
[████████████████████] 100% - Fase 3: Backend Controller & Validasi Hard Anti-Duplikat
[████████████████████] 100% - Fase 4: Seeding 33 Jenis Transaksi & 10 Channel Resmi
[████████████████████] 100% - Fase 5: Modul Autentikasi & Skema Audit Trail
[░░░░░░░░░░░░░░░░░░░░]   0% - Fase 6 (Rencana): Dashboard Rekapitulasi & Export Laporan
```

### Tabel Status Pengerjaan Modul:

| No | Modul / Fitur | Target Pekerjaan | Status | Progres |
|:---|:---|:---|:---:|:---:|
| 1 | **Skema Database & Migrasi** | Tabel `users`, `master_cabangs`, `master_transaksis`, `jurnals`, `audit_trails` | **Selesai** | **100%** |
| 2 | **Master Data Transaksi** | 33 jenis transaksi dan 10 channel resmi diinput ke database | **Selesai** | **100%** |
| 3 | **Master Data Cabang** | Input data awal kantor cabang (KCP Tinombo, KCP Toili, Cabang Buol) | **Selesai** | **100%** |
| 4 | **Formulir Keluhan (UI/UX)** | Tampilan web 2-kolom responsif dengan 11 field input lengkap | **Selesai** | **100%** |
| 5 | **Fitur Auto-Fill AJAX** | Auto-fill biaya admin dan channel saat memilih jenis transaksi | **Selesai** | **100%** |
| 6 | **Validasi Hard Anti-Duplikat** | Logika penolakan klaim ganda (Nama + No. Resi + Tanggal) | **Selesai** | **100%** |
| 7 | **Model Eloquent & Relasi** | Mass assignment `$guarded = ['id']` dan fungsi relasi antar tabel | **Selesai** | **100%** |
| 8 | **Autentikasi Pengguna** | Controller Login & Logout petugas perbankan | **Selesai** | **100%** |
| 9 | **Tabel Daftar Keluhan & Export** | Menampilkan daftar seluruh jurnal, filter status, export Excel/PDF | *Rencana Lanjutan* | *0%* |

---

## 🕒 3. Log Riwayat Pengerjaan (Activity Changelog)

* **v1.2 (Pembaruan Terkini)**:
  - ✅ Memasukkan **33 Jenis Transaksi** resmi Bank Sulteng ke dalam seeder database.
  - ✅ Menstandarkan **10 Master Channel**: `ATM LOKAL`, `ATM BERSAMA`, `ATM LINK`, `FINNET`, `SMS BANKING`, `MOBILE BANKING`, `DEBIT`, `EDC BANK LAIN`, `LAKU PANDAI`, dan `CCTV`.
  - ✅ Mengubah logika seeder menjadi `updateOrInsert` agar idempoten dan aman dieksekusi berulang kali.
  - ✅ Mengeksekusi seeder ke database (33 data terverifikasi aktif di MySQL).

* **v1.1**:
  - ✅ Menambahkan seluruh field input yang diminta: Tanggal Terima (`tgl_terima`), Tanggal Selesai (`tgl_selesai`), Nomor Tiket (`no_tiket`), Nomor Kartu (`no_kartu`), Terminal Transaksi (`terminal_transaksi`), Keterangan Log (`keterangan_log`), dan Status (`status`: Menunggu, Success, Done, Rejected).
  - ✅ Memperbarui layout form menjadi grid 2-kolom responsif modern dengan pesan error/sukses terintegrasi.
  - ✅ Memperbarui validasi backend pada `JurnalController::store()`.
  - ✅ Menambahkan `$guarded = ['id']` dan relasi Eloquent pada `Jurnal.php`.

* **v1.0 (Inisiasi Awal)**:
  - ✅ Struktur pondasi Laravel 12.
  - ✅ Skema awal migrasi database tabel master, jurnal, dan audit trail.
  - ✅ Controller autentikasi dasar (`AuthController.php`).

---

## 📝 4. Rincian Field Input Formulir Jurnal

Tampilan formulir pada [jurnal_form.blade.php](file:///c:/bank-sulteng/projek/projek1/Jurnal_Banksulteng/resources/views/jurnal_form.blade.php) mencakup field-field berikut:

| No | Label Form | Nama Input | Tipe Input | Sifat | Keterangan |
|:---|:---|:---|:---|:---|:---|
| 1 | **Nama Nasabah** | `nama_nasabah` | Text | Wajib (`*`) | Nama lengkap nasabah pelapor. |
| 2 | **No. Resi / Trace Number** | `no_resi` | Text | Wajib (`*`) | Nomor resi/trace transaksi ATM/EDC/Mobile. |
| 3 | **No. Rekening** | `no_rekening` | Text | Wajib (`*`) | Nomor rekening nasabah yang didebet. |
| 4 | **Nomor Kartu** | `no_kartu` | Text | Opsional | Nomor kartu ATM/Debit nasabah. |
| 5 | **Nomor Tiket** | `no_tiket` | Text | Opsional | Nomor tiket referensi customer service. |
| 6 | **Tanggal Transaksi** | `tgl_transaksi` | Date | Wajib (`*`) | Tanggal saat nasabah melakukan transaksi yang bermasalah. |
| 7 | **Tanggal Terima** | `tgl_terima` | Date | Wajib (`*`) | Tanggal saat cabang/petugas menerima pengaduan nasabah. |
| 8 | **Tanggal Selesai** | `tgl_selesai` | Date | Opsional | Tanggal saat keluhan selesai diproses/diselesaikan. |
| 9 | **Cabang Transaksi / Pelapor** | `master_cabang_id` | Select | Wajib (`*`) | Dropdown daftar kantor cabang Bank Sulteng. |
| 10 | **Jenis Transaksi** | `master_transaksi_id` | Select | Wajib (`*`) | Dropdown 33 jenis transaksi (memicu auto-fill). |
| 11 | **Biaya Admin (Otomatis)** | `biaya_admin` | Text (Readonly) | Otomatis | Terisi otomatis dari database via AJAX saat jenis transaksi dipilih. |
| 12 | **Channel Transaksi (Otomatis)** | `channel` | Text (Readonly) | Otomatis | Terisi otomatis sesuai channel jenis transaksi. |
| 13 | **Terminal Transaksi** | `terminal_transaksi` | Text | Opsional | ID Mesin ATM / Terminal EDC. |
| 14 | **Biaya / Nominal Transaksi** | `nominal_transaksi` | Number | Wajib (`*`) | Jumlah nominal uang yang dikeluhkan nasabah (Rp). |
| 15 | **Status** | `status` | Select | Wajib (`*`) | Pilihan: **Menunggu**, **Success**, **Done**, **Rejected**. |
| 16 | **Keterangan Log** | `keterangan_log` | Textarea | Opsional | Catatan kronologi keluhan atau detail tindak lanjut. |

---

## 🏛️ 5. Master Data 10 Channel & 33 Jenis Transaksi

### Daftar 10 Master Channel Resmi:
1. `ATM LOKAL`
2. `ATM BERSAMA`
3. `ATM LINK`
4. `FINNET`
5. `SMS BANKING`
6. `MOBILE BANKING`
7. `DEBIT`
8. `EDC BANK LAIN`
9. `LAKU PANDAI`
10. `CCTV`

### Pemetaan 33 Jenis Transaksi ke Channel:
| No | Jenis Transaksi | Channel |
|:---|:---|:---|
| 1 | `ATM_TARIK TUNAI ATM BANK SULTENG` | **ATM LOKAL** |
| 2 | `ATM_TARIK TUNAI DI BANK LAIN` | **ATM BERSAMA** |
| 3 | `CRM_SETOR TUNAI` | **ATM LOKAL** |
| 4 | `ATM_TRANSFER MESIN BANK SULTENG` | **ATM LOKAL** |
| 5 | `ATM_TRANSFER MESIN BANK LAIN` | **ATM BERSAMA** |
| 6 | `ATM_TELKOM` | **FINNET** |
| 7 | `ATM_PULSA TSEL` | **FINNET** |
| 8 | `ATM_PULSA XL` | **FINNET** |
| 9 | `ATM_PLN PREPAID` | **FINNET** |
| 10 | `ATM_DANA` | **FINNET** |
| 11 | `ATM_GOPAY` | **FINNET** |
| 12 | `ATM_PEMBAYARAN HALO` | **FINNET** |
| 13 | `ATM_BPJS` | **FINNET** |
| 14 | `SMS BANKING (TRANSFER INTERNAL)` | **SMS BANKING** |
| 15 | `SMS BANKING (TRANSFER KE BANK LAIN)` | **SMS BANKING** |
| 16 | `SMS BANKING (PULSA TSEL)` | **SMS BANKING** |
| 17 | `SMS BANKING (PLN PREPAID)` | **SMS BANKING** |
| 18 | `MBANKING_TRANSFER` | **MOBILE BANKING** |
| 19 | `MBANKING_TELKOM` | **MOBILE BANKING** |
| 20 | `MBANKING_PULSA TSEL` | **MOBILE BANKING** |
| 21 | `MBANKING_PULSA XL` | **MOBILE BANKING** |
| 22 | `MBANKING_PLN PREPAID` | **MOBILE BANKING** |
| 23 | `MBANKING_DANA` | **MOBILE BANKING** |
| 24 | `MBANKING_PEMBAYARAN` | **MOBILE BANKING** |
| 25 | `MBANKING_PEMBAYARAN HALO` | **MOBILE BANKING** |
| 26 | `MBANKING_BPJS` | **MOBILE BANKING** |
| 27 | `EDC` | **DEBIT** |
| 28 | `EDC BANK LAIN` | **EDC BANK LAIN** |
| 29 | `QRIS` | **MOBILE BANKING** |
| 30 | `LAKU PANDAI` | **LAKU PANDAI** |
| 31 | `LAINNYA_PERMINTAAN CCTV` | **CCTV** |
| 32 | `PEMBAYARAN` | **FINNET** |
| 33 | `PEMBELIAN` | **FINNET** |

---

## 📂 6. Struktur Berkas & Kode Program

```text
Jurnal_Banksulteng/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          <-- Logika Login & Logout petugas
│   │   ├── Controller.php            <-- Base Controller Laravel
│   │   └── JurnalController.php        <-- Simpan Jurnal (Validasi anti-duplikat) & API auto-fill
│   └── Models/
│       ├── AuditTrail.php              <-- Model Audit Trail (hash chaining)
│       ├── Jurnal.php                  <-- Model Jurnal Keluhan (mass-assignment protected)
│       ├── MasterCabang.php            <-- Model Master Kantor Cabang
│       ├── MasterTransaksi.php         <-- Model Master Jenis Transaksi & Channel
│       └── User.php                    <-- Model Pengguna / Petugas
├── database/
│   ├── migrations/                     <-- Berkas Migrasi Skema Database
│   │   ├── 2026_08_14_024901_create_master_cabangs_table.php
│   │   ├── 2026_08_14_024911_create_master_transaksis_table.php
│   │   ├── 2026_08_14_024926_create_jurnals_table.php
│   │   └── 2026_08_14_024938_create_audit_trails_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php          <-- Seeder Utama pemanggil MasterSeeder
│       └── MasterSeeder.php            <-- Seeder 33 Jenis Transaksi & Data Cabang
├── resources/
│   └── views/
│       └── jurnal_form.blade.php       <-- Tampilan Form Jurnal (Grid responsif + AJAX)
├── routes/
│   └── web.php                         <-- Rute Web: '/', '/jurnal/simpan', '/api/transaksi/{id}'
├── .env                                <-- Konfigurasi Database (MySQL) & App Key
└── DOCUMENTATION.md                    <-- Dokumen Panduan & Catatan Projek Ini
```

---

## 🚀 7. Panduan Menjalankan & Memperbarui Sistem

1. Masuk ke direktori projek:
   ```powershell
   cd C:\bank-sulteng\projek\projek1\Jurnal_Banksulteng
   ```
2. Menjalankan migrasi & data master:
   ```powershell
   php artisan db:seed
   ```
   *(Atau `php artisan migrate:fresh --seed` jika ingin membuat ulang seluruh database dari awal)*

3. Menjalankan server lokal:
   ```powershell
   php artisan serve
   ```
4. Akses sistem melalui browser di: **`http://127.0.0.1:8000`**
