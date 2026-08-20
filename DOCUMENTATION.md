# Dokumentasi Projek: Sistem Jurnal Keluhan Nasabah (Bank Sulteng)

## 📌 1. Deskripsi Projek
Projek **Jurnal_Banksulteng** adalah aplikasi web berbasis **Laravel 12/13** yang dirancang khusus untuk memfasilitasi pencatatan, validasi, monitoring, dan pengelolaan **Jurnal Keluhan Transaksi Nasabah** di lingkungan Bank Sulteng.

### Tujuan & Manfaat Utama:
- **Autentikasi Username Petugas**: Halaman login admin berbasis **Username** murni (`username: admin`) tanpa perlu registrasi mandiri.
- **Tabel Data Keluhan Ultra Bersih (Single Action "Detail")**:
  - Kolom **Aksi** di tabel utama hanya memiliki 1 tombol: **Detail**, membuat tata letak tabel sangat bersih, rapi, dan memberikan ruang maksimum bagi kolom data nasabah, cabang, transaksi, dan nominal.
- **Pusat Aksi Terintegrasi di Modal Rincian**:
  - Tombol **Edit Data Jurnal** (kuning/amber) dan tombol **Hapus Data** (merah) ditempatkan berdampingan secara proporsional di bagian bawah (*footer*) pop-up modal rincian.
  - Mengklik tombol Hapus Data pada modal rincian akan membuka dialog konfirmasi hapus data permanen dengan double-confirmation.
- **Modal Konfirmasi Hapus Data Ekstra Aman**: Dialog konfirmasi interaktif dengan rincian data nasabah dan peringatan permanen sebelum eksekusi penghapusan data.
- **Standar Seluruh Input Wajib Diisi**: Menjamin kelengkapan data perbankan dengan mewajibkan seluruh 16 field pengaduan jurnal keluhan nasabah.
- **Pencarian Bebas Huruf Besar/Kecil (*Case-Insensitive*)**: Pencarian instan (*live detect*) otomatis mendeteksi kata kunci baik ditulis huruf besar, kecil, maupun campuran (`budi`, `BUDI`, `Budi`) tanpa peduli kapitalisasi huruf.
- **Penanda Teks Latar Kuning (*Yellow Highlight*)**: Kata yang cocok otomatis disorot dengan latar belakang kuning stabilo dengan tetap mempertahankan huruf besar/kecil asli data nasabah.
- **Master Data 41 Kantor Cabang**: Mendukung seluruh jaringan kantor cabang, KCP, dan Bank Lain di seluruh wilayah Sulawesi Tengah & Jakarta.
- **Pencatatan Terpusat**: Menggantikan pencatatan manual keluhan nasabah ke dalam sistem web yang terstruktur.
- **Sidebar Navigasi Modern**: Memudahkan transisi antar menu "Input Jurnal Keluhan" dan "Data Keluhan", lengkap dengan info username aktif dan tombol logout.
- **Hard Anti-Duplikat**: Mencegah klaim ganda atas transaksi keluhan nasabah yang sama (berdasarkan kombinasi Nama Nasabah + No. Resi + Tanggal Transaksi).
- **Otomatisasi Channel & Biaya Admin**: Mempercepat pengisian form dengan mekanisme *auto-fill* berbasis AJAX saat jenis transaksi dipilih.
- **Keamanan & Kepatuhan**: Menyiapkan rekam jejak audit (*audit trail*) berbasis hash chaining untuk integritas data perbankan.

---

## 📊 2. Status & Progres Pengerjaan Projek

### Ringkasan Pencapaian (Milestone Progress)

```text
[████████████████████] 100% - Fase 1: Basis Data & Master Data (41 Cabang & 33 Transaksi)
[████████████████████] 100% - Fase 2: Formulir Frontend (Seluruh Input Wajib Diisi) & Auto-Fill AJAX
[████████████████████] 100% - Fase 3: Backend Controller & Validasi Hard Anti-Duplikat
[████████████████████] 100% - Fase 4: Seeding 33 Jenis Transaksi & 10 Channel Resmi
[████████████████████] 100% - Fase 5: Modul Autentikasi Admin (Login Berbasis Username & Logout)
[████████████████████] 100% - Fase 6: Layout Sidebar & Modul Data Keluhan (Aksi Terpusat di Modal Detail, Live Search)
```

### Tabel Status Pengerjaan Modul:

| No | Modul / Fitur | Target Pekerjaan | Status | Progres |
|:---|:---|:---|:---:|:---:|
| 1 | **Skema Database & Migrasi** | Tabel `users` (+ kolom `username`), `master_cabangs`, `master_transaksis`, `jurnals`, `audit_trails` | **Selesai** | **100%** |
| 2 | **Master Data Transaksi** | 33 jenis transaksi dan 10 channel resmi diinput ke database | **Selesai** | **100%** |
| 3 | **Master Data 41 Cabang** | Input lengkap 41 kantor cabang, KCP, dan Bank Lain resmi Bank Sulteng | **Selesai** | **100%** |
| 4 | **Autentikasi Username Admin** | Halaman login admin bersih berbasis username, akun default siap pakai, proteksi auth middleware | **Selesai** | **100%** |
| 5 | **Sidebar Navigasi Bank Sulteng** | Navigasi responsif (Input Jurnal & Data Keluhan), jam real-time WITA, mobile drawer | **Selesai** | **100%** |
| 6 | **Formulir Keluhan (Semua Wajib)** | Tampilan web 2-kolom responsif di mana seluruh field berstatus Wajib Diisi (`*`) | **Selesai** | **100%** |
| 7 | **Fitur Edit Data Jurnal** | Formulir edit data keluhan dengan pre-fill data, auto-fill AJAX, dan tombol edit via modal Detail | **Selesai** | **100%** |
| 8 | **Modal Konfirmasi Hapus Data** | Dialog konfirmasi interaktif dengan rincian data nasabah sebelum penghapusan permanen | **Selesai** | **100%** |
| 9 | **Fitur Auto-Fill AJAX** | Auto-fill biaya admin dan channel saat memilih jenis transaksi | **Selesai** | **100%** |
| 10 | **Validasi Hard Anti-Duplikat** | Logika penolakan klaim ganda (Nama + No. Resi + Tanggal) | **Selesai** | **100%** |
| 11 | **Case-Insensitive Live Search & Highlight** | Pencarian instan otomatis tanpa peduli huruf besar/kecil dengan highlight kuning | **Selesai** | **100%** |
| 12 | **Tabel Data Keluhan Ultra Rapi** | Tabel daftar jurnal 8 kolom dengan single button "Detail" di kolom aksi | **Selesai** | **100%** |
| 13 | **Pusat Aksi di Modal Detail** | Pop-up modal rincian 16 field lengkap dengan tombol Edit Data dan Hapus Data berdampingan | **Selesai** | **100%** |

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

## 📝 5. Rincian Field Input Formulir Jurnal (Semua Wajib Diisi)

| No | Label Form | Nama Input | Tipe Input | Sifat | Keterangan |
|:---|:---|:---|:---|:---|:---|
| 1 | **Nama Nasabah** | `nama_nasabah` | Text | **Wajib (`*`)** | Nama lengkap nasabah pelapor. |
| 2 | **No. Rekening** | `no_rekening` | Text | **Wajib (`*`)** | Nomor rekening nasabah yang didebet. |
| 3 | **No. Resi / Trace Number** | `no_resi` | Text | **Wajib (`*`)** | Nomor resi/trace transaksi ATM/EDC/Mobile. |
| 4 | **Nomor Kartu ATM/Debit** | `no_kartu` | Text | **Wajib (`*`)** | Nomor kartu ATM/Debit nasabah. |
| 5 | **Nomor Tiket CS** | `no_tiket` | Text | **Wajib (`*`)** | Nomor tiket referensi customer service. |
| 6 | **Cabang Transaksi / Pelapor** | `master_cabang_id` | Select | **Wajib (`*`)** | Dropdown 41 daftar kantor cabang Bank Sulteng. |
| 7 | **Jenis Transaksi** | `master_transaksi_id` | Select | **Wajib (`*`)** | Dropdown 33 jenis transaksi (memicu auto-fill). |
| 8 | **Channel Transaksi (Otomatis)** | `channel` | Text (Readonly) | Otomatis | Terisi otomatis sesuai channel jenis transaksi. |
| 9 | **Biaya Admin (Otomatis)** | `biaya_admin` | Text (Readonly) | Otomatis | Terisi otomatis dari database via AJAX saat jenis transaksi dipilih. |
| 10 | **Biaya / Nominal Transaksi** | `nominal_transaksi` | Number | **Wajib (`*`)** | Jumlah nominal uang yang dikeluhkan nasabah (Rp). |
| 11 | **Terminal Transaksi / Mesin** | `terminal_transaksi` | Text | **Wajib (`*`)** | ID Mesin ATM / Terminal EDC. |
| 12 | **Tanggal Transaksi Bermasalah** | `tgl_transaksi` | Date | **Wajib (`*`)** | Tanggal saat nasabah melakukan transaksi yang bermasalah. |
| 13 | **Tanggal Terima Keluhan** | `tgl_terima` | Date | **Wajib (`*`)** | Tanggal saat cabang/petugas menerima pengaduan nasabah. |
| 14 | **Tanggal Selesai Penanganan** | `tgl_selesai` | Date | **Wajib (`*`)** | Tanggal saat keluhan selesai diproses/diselesaikan. |
| 15 | **Status Keluhan** | `status` | Select | **Wajib (`*`)** | Pilihan: **Menunggu**, **Success**, **Done**, **Rejected**. |
| 16 | **Keterangan Log / Kronologi** | `keterangan_log` | Textarea | **Wajib (`*`)** | Catatan kronologi keluhan atau detail tindak lanjut. |

---

## 📂 6. Struktur Berkas & Kode Program

```text
Jurnal_Banksulteng/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          <-- Logika Login berbasis username, Logout, dan autentikasi admin
│   │   ├── Controller.php            <-- Base Controller Laravel
│   │   └── JurnalController.php        <-- Simpan, Edit, Update, Hapus, Live Search & API AJAX
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
│       ├── jurnal_form.blade.php       <-- Tampilan Form Input Jurnal (Semua Input Wajib Diisi *)
│       ├── jurnal_edit.blade.php       <-- Tampilan Form Edit Jurnal Keluhan (Pre-filled + AJAX)
│       └── jurnal_data.blade.php       <-- Tampilan Data Keluhan (Tabel Single Button Detail, Modal Edit & Hapus)
├── routes/
│   └── web.php                         <-- Rute Web: '/login', '/logout', '/', '/jurnal/data', GET/PUT '/jurnal/{id}/edit', DELETE '/jurnal/{id}'
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
