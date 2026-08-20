# Dokumentasi Projek: Sistem Jurnal Keluhan Nasabah (Bank Sulteng)

## 📌 1. Deskripsi Projek
Projek **Jurnal_Banksulteng** adalah aplikasi web berbasis **Laravel 12/13** yang dirancang khusus untuk memfasilitasi pencatatan, validasi, monitoring, dan pengelolaan **Jurnal Keluhan Transaksi Nasabah** di lingkungan Bank Sulteng.

### Tujuan & Manfaat Utama:
- **Autentikasi Username Petugas**: Halaman login admin berbasis **Username** murni (`username: admin`) tanpa perlu registrasi mandiri.
- **Pencarian Bebas Huruf Besar/Kecil (*Case-Insensitive*)**: Pencarian instan (*live detect*) otomatis mendeteksi kata kunci baik ditulis huruf besar, kecil, maupun campuran (`budi`, `BUDI`, `Budi`) tanpa peduli kapitalisasi huruf.
- **Penanda Teks Latar Kuning (*Yellow Highlight*)**: Kata yang cocok otomatis disorot dengan latar belakang kuning stabilo dengan tetap mempertahankan huruf besar/kecil asli data nasabah.
- **Master Data 41 Kantor Cabang**: Mendukung seluruh jaringan kantor cabang, KCP, dan Bank Lain di seluruh wilayah Sulawesi Tengah & Jakarta.
- **Pencatatan Terpusat**: Menggantikan pencatatan manual keluhan nasabah ke dalam sistem web yang terstruktur.
- **Sidebar Navigasi Modern**: Memudahkan transisi antar menu "Input Jurnal Keluhan" dan "Data Keluhan", lengkap dengan info username aktif dan tombol logout.
- **Pencarian, Rekapitulasi & Hapus Jurnal**: Menampilkan data keluhan tersimpan dengan fitur pencarian multi-field, filter status, filter cabang, filter rentang tanggal, serta tombol **Hapus Data** dengan dialog konfirmasi.
- **Modal Rincian Interaktif**: Pop-up modal yang menampilkan 16 atribut lengkap dari setiap keluhan beserta opsi cetak ringkasan.
- **Hard Anti-Duplikat**: Mencegah klaim ganda atas transaksi keluhan nasabah yang sama (berdasarkan kombinasi Nama Nasabah + No. Resi + Tanggal Transaksi).
- **Otomatisasi Channel & Biaya Admin**: Mempercepat pengisian form dengan mekanisme *auto-fill* berbasis AJAX saat jenis transaksi dipilih.
- **Keamanan & Kepatuhan**: Menyiapkan rekam jejak audit (*audit trail*) berbasis hash chaining untuk integritas data perbankan.

---

## 📊 2. Status & Progres Pengerjaan Projek

### Ringkasan Pencapaian (Milestone Progress)

```text
[████████████████████] 100% - Fase 1: Basis Data & Master Data (41 Cabang & 33 Transaksi)
[████████████████████] 100% - Fase 2: Formulir Frontend & Auto-Fill AJAX
[████████████████████] 100% - Fase 3: Backend Controller & Validasi Hard Anti-Duplikat
[████████████████████] 100% - Fase 4: Seeding 33 Jenis Transaksi & 10 Channel Resmi
[████████████████████] 100% - Fase 5: Modul Autentikasi Admin (Login Berbasis Username & Logout)
[████████████████████] 100% - Fase 6: Layout Sidebar & Modul Data Keluhan (Case-Insensitive Live Search, Highlight & Hapus)
```

### Tabel Status Pengerjaan Modul:

| No | Modul / Fitur | Target Pekerjaan | Status | Progres |
|:---|:---|:---|:---:|:---:|
| 1 | **Skema Database & Migrasi** | Tabel `users` (+ kolom `username`), `master_cabangs`, `master_transaksis`, `jurnals`, `audit_trails` | **Selesai** | **100%** |
| 2 | **Master Data Transaksi** | 33 jenis transaksi dan 10 channel resmi diinput ke database | **Selesai** | **100%** |
| 3 | **Master Data 41 Cabang** | Input lengkap 41 kantor cabang, KCP, dan Bank Lain resmi Bank Sulteng | **Selesai** | **100%** |
| 4 | **Autentikasi Username Admin** | Halaman login admin bersih berbasis username, akun default siap pakai, proteksi auth middleware | **Selesai** | **100%** |
| 5 | **Sidebar Navigasi Bank Sulteng** | Navigasi responsif (Input Jurnal & Data Keluhan), jam real-time WITA, mobile drawer | **Selesai** | **100%** |
| 6 | **Formulir Keluhan (UI/UX)** | Tampilan web 2-kolom responsif terintegrasi layout induk dengan 16 field input | **Selesai** | **100%** |
| 7 | **Fitur Auto-Fill AJAX** | Auto-fill biaya admin dan channel saat memilih jenis transaksi | **Selesai** | **100%** |
| 8 | **Validasi Hard Anti-Duplikat** | Logika penolakan klaim ganda (Nama + No. Resi + Tanggal) | **Selesai** | **100%** |
| 9 | **Case-Insensitive Live Search & Highlight** | Pencarian instan otomatis tanpa peduli huruf besar/kecil dengan highlight kuning | **Selesai** | **100%** |
| 10 | **Tabel Data Keluhan & Filter** | Tabel daftar jurnal tersimpan, filter status/cabang/tanggal, multi-term keyword search | **Selesai** | **100%** |
| 11 | **Fitur Hapus Data Keluhan** | Tombol hapus data per baris di tabel dengan dialog konfirmasi aman | **Selesai** | **100%** |
| 12 | **Modal Detail Keluhan & Print** | Pop-up modal rincian 16 field lengkap & fungsi cetak ringkasan | **Selesai** | **100%** |

---

## 🔑 3. Kredensial Administrator

| Parameter | Kredensial |
|:---|:---|
| **URL Login** | `http://127.0.0.1:8000/login` |
| **Username** | `admin` |
| **Password** | `admin123` |
| **Hak Akses** | Administrator Penuh (Kelola Jurnal Keluhan & Data Rekapitulasi) |

---

## 🏛️ 4. Master Data 41 Kantor Cabang & KCP Bank Sulteng

| No | Kode | Nama Kantor Cabang / KCP | No | Kode | Nama Kantor Cabang / KCP |
|:---|:---:|:---|:---|:---:|:---|
| 1 | `000` | BANK LAIN | 22 | `302` | KCP WAKAI |
| 2 | `001` | CABANG UTAMA | 23 | `303` | KCP TENTENA |
| 3 | `002` | CABANG TOLI TOLI | 24 | `304` | KCP PENDOLO |
| 4 | `003` | CABANG POSO | 25 | `305` | KCP NAPU |
| 5 | `004` | CABANG LUWUK | 26 | `401` | CABANG KOLONODALE |
| 6 | `005` | CABANG BUNGKU | 27 | `402` | CABANG BANGGAI LAUT |
| 7 | `006` | CABANG SALAKAN | 28 | `403` | KCP BETELEME |
| 8 | `007` | CABANG SIGI | 29 | `404` | KCP BATUI |
| 9 | `008` | CABANG PALU BARAT | 30 | `405` | KCP TOILI |
| 10 | `009` | CABANG JAKARTA | 31 | `411` | KCP MAMOSALATO |
| 11 | `101` | CABANG DONGGALA | 32 | `412` | KCP TOMATA |
| 12 | `102` | CABANG PARIGI | 33 | `413` | KCP BATURUBE |
| 13 | `103` | KCP LAMBUNU | 34 | `501` | KCP BAHOMOTEFE |
| 14 | `104` | KCP LABEAN | 35 | `502` | KCP BAHODOPI |
| 15 | `105` | KCP TOLAI | 36 | `701` | KCP KULAWI |
| 16 | `106` | KCP TINOMBO | 37 | `801` | KCP TAWAELI |
| 17 | `107` | KCP TINOMBALA | 38 | `406` | KCP MASAMA |
| 18 | `201` | CABANG BUOL | 39 | `306` | KCP TAMBARANA |
| 19 | `202` | KCP SONI | 40 | `407` | KCP BUNTA |
| 20 | `211` | KCP PALELEH | 41 | `108` | KCP KOTARAYA |
| 21 | `301` | CABANG AMPANA | | | |

---

## 📝 5. Rincian Field Input Formulir Jurnal

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
| 9 | **Cabang Transaksi / Pelapor** | `master_cabang_id` | Select | Wajib (`*`) | Dropdown 41 daftar kantor cabang Bank Sulteng. |
| 10 | **Jenis Transaksi** | `master_transaksi_id` | Select | Wajib (`*`) | Dropdown 33 jenis transaksi (memicu auto-fill). |
| 11 | **Biaya Admin (Otomatis)** | `biaya_admin` | Text (Readonly) | Otomatis | Terisi otomatis dari database via AJAX saat jenis transaksi dipilih. |
| 12 | **Channel Transaksi (Otomatis)** | `channel` | Text (Readonly) | Otomatis | Terisi otomatis sesuai channel jenis transaksi. |
| 13 | **Terminal Transaksi** | `terminal_transaksi` | Text | Opsional | ID Mesin ATM / Terminal EDC. |
| 14 | **Biaya / Nominal Transaksi** | `nominal_transaksi` | Number | Wajib (`*`) | Jumlah nominal uang yang dikeluhkan nasabah (Rp). |
| 15 | **Status** | `status` | Select | Wajib (`*`) | Pilihan: **Menunggu**, **Success**, **Done**, **Rejected**. |
| 16 | **Keterangan Log** | `keterangan_log` | Textarea | Opsional | Catatan kronologi keluhan atau detail tindak lanjut. |

---

## 📂 6. Struktur Berkas & Kode Program

```text
Jurnal_Banksulteng/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          <-- Logika Login berbasis username, Logout, dan autentikasi admin
│   │   ├── Controller.php            <-- Base Controller Laravel
│   │   └── JurnalController.php        <-- Simpan, Pencarian Case-Insensitive, Hapus jurnal & API AJAX
│   └── Models/
│       ├── AuditTrail.php              <-- Model Audit Trail (hash chaining)
│       ├── Jurnal.php                  <-- Model Jurnal Keluhan (mass-assignment protected)
│       ├── MasterCabang.php            <-- Model Master Kantor Cabang (41 Cabang & KCP)
│       ├── MasterTransaksi.php         <-- Model Master Jenis Transaksi & Channel
│       └── User.php                    <-- Model Pengguna / Petugas (fillable: name, username, email, password)
├── database/
│   ├── migrations/                     <-- Berkas Migrasi Skema Database (+ username)
│   └── seeders/
│       ├── DatabaseSeeder.php          <-- Seeder Utama pemanggil MasterSeeder & UserSeeder
│       ├── MasterSeeder.php            <-- Seeder 33 Jenis Transaksi & 41 Data Cabang
│       └── UserSeeder.php              <-- Seeder Akun Admin Default (username: admin, pass: admin123)
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php         <-- Tampilan Login Bersih (Username & Eye Icon)
│       ├── layouts/
│       │   └── app.blade.php           <-- Master Layout Blade (Sidebar & Logout Bank Sulteng)
│       ├── jurnal_form.blade.php       <-- Tampilan Form Input Jurnal (Grid responsif + AJAX)
│       └── jurnal_data.blade.php       <-- Tampilan Data Keluhan (Case-Insensitive Live Search, Highlights & Modal)
├── routes/
│   └── web.php                         <-- Rute Web: '/login', '/logout', '/', '/jurnal/data', DELETE '/jurnal/{id}', API endpoints
├── .env                                <-- Konfigurasi Database (MySQL) & App Key
└── DOCUMENTATION.md                    <-- Dokumen Panduan & Catatan Projek Ini
```

---

## 🚀 7. Panduan Menjalankan Sistem

1. Masuk ke direktori projek:
   ```powershell
   cd C:\bank-sulteng\projek\projek1\Jurnal_Banksulteng
   ```
2. Menjalankan server lokal:
   ```powershell
   php artisan serve
   ```
   *(Atau menggunakan path PHP Laragon: `& "C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe" artisan serve`)*

3. Buka browser di: **`http://127.0.0.1:8000/login`**
4. Masuk dengan kredensial:
   - **Username**: **`admin`**
   - **Password**: **`admin123`**
