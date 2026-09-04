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
- **Pencarian Bebas Huruf Besar/Kecil (*Case-Insensitive*)**: Pencarian otomatis mendeteksi kata kunci baik ditulis huruf besar, kecil, maupun campuran (`budi`, `BUDI`, `Budi`) tanpa peduli kapitalisasi huruf.
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
| 12 | **Tabel Data Keluhan Ultra Rapi** | Tabel daftar jurnal 9 kolom (termasuk kolom Channel terpisah) dengan single button "Detail" di kolom aksi | **Selesai** | **100%** |
| 13 | **Pusat Aksi di Modal Detail** | Pop-up modal rincian 16 field lengkap dengan tombol Edit Data dan Hapus Data berdampingan | **Selesai** | **100%** |
| 14 | **Laporan Rekapitulasi Keluhan Bulanan** | Matriks 12 bulan (Jan-Des), tab drilldown bulanan, 8 kelompok klaim standar, dan export Excel identik | **Selesai** | **100%** |
| 15 | **Monitoring Mesin ATM Bermasalah** | Peringkat terminal ATM berdasarkan jumlah keluhan terbanyak, KPI cards, filter cabang/periode, modal drill-down nasabah, dan export Excel | **Selesai** | **100%** |

---

## 🔑 3. Kredensial & Peran Pengguna

| Peran | Username | Password | Hak Akses |
|:---|:---|:---|:---|
| **Admin Pusat** (Divisi IT) | `admin` | `admin123` | Kelola Jurnal Keluhan, verifikasi pengaduan cabang, laporan, monitoring ATM, manajemen pengguna. |
| **CS Cabang** (contoh, Cabang Utama `001`) | `cs.palu` | `cs12345` | Kirim pengaduan nasabah + lampiran, pantau status pengaduan cabangnya sendiri. |

URL login sama untuk kedua peran: `http://127.0.0.1:8000/login`. Setelah login, Admin diarahkan ke `/` dan CS ke `/cs`. Akun CS lain dibuat Admin lewat menu **Manajemen Pengguna** (`/pengguna`) dan wajib terikat ke satu kantor cabang. Akun tidak dihapus, hanya **dinonaktifkan**.

---

## 📨 3a. Modul Pengaduan CS Cabang → Admin Pusat

### Alur & Siklus Status

```text
CS kirim pengaduan ──► Terkirim ──► Diterima ──► Diproses ──► Selesai
   (boleh edit/hapus)      │           │   (masuk jurnal)  (jurnal Done/Success)
                           └───────────┴──► Ditolak (catatan pusat wajib / jurnal Rejected)
```

| Status | Label di CS | Pemicu |
|:---|:---|:---|
| `Terkirim` | Menunggu Verifikasi Pusat | CS mengirim formulir. CS masih boleh mengedit/menghapus. |
| `Diterima` | Diterima Pusat | Admin klik **Terima**. Data terkunci untuk CS. |
| `Diproses` | Dalam Proses Jurnal | Admin klik **Input ke Jurnal** → form jurnal terisi otomatis → disimpan (`jurnal_id` terisi). |
| `Selesai` | Selesai | Status jurnal diubah menjadi `Done` / `Success`. |
| `Ditolak` | Ditolak | Admin klik **Tolak** (wajib alasan) atau status jurnal `Rejected`. |

Sinkronisasi status jurnal → pengaduan berjalan otomatis (observer). Menghapus jurnal mengembalikan pengaduan ke `Diterima`.

### Skala Tampilan Adaptif
Ukuran tampilan sistem **menyesuaikan lebar layar secara otomatis**. Pada laptop 14 inci ukurannya tetap seperti biasa, lalu membesar bertahap pada monitor yang lebih besar sehingga teks tidak terasa mengecil dan pengguna tidak perlu melakukan zoom.

| Lebar layar | Contoh perangkat | Ukuran teks isi | Teks terkecil |
|:---|:---|:---:|:---:|
| 1366 px | Laptop 14 inci | 14,0 px | 11,5 px |
| 1920 px | Monitor 22–24 inci | 15,0 px | 12,3 px |
| 2560 px | Monitor 27 inci QHD | 16,2 px | 13,3 px |
| 3840 px | Monitor 4K | 16,6 px | 13,7 px |

Seluruh elemen ikut berskala: tinggi tombol, lebar sidebar, lebar kolom tabel, dan jarak antarelemen, sehingga proporsi tampilan tetap terjaga. Pengaturan ukuran huruf bawaan browser pengguna juga tetap dihormati, jadi pengguna yang memperbesar teks lewat pengaturan browser tetap terlayani. Dokumen cetak A4 pada menu Cetak sengaja dikecualikan agar hasil cetaknya tetap presisi.

Ukuran teks terkecil dinaikkan menjadi 11,5 piksel dan warna teks keterangan dipertegas agar memenuhi standar keterbacaan WCAG AA.

### Dialog Konfirmasi
Tindakan penting seperti **Terima Pengaduan**, **Tolak Pengaduan**, menghapus lampiran, dan menonaktifkan akun pengguna memakai **modal konfirmasi bergaya sistem**, bukan dialog bawaan browser. Setiap dialog menampilkan ikon berwarna, judul, rincian data yang terdampak, serta tombol Batal dan tombol lanjut yang warnanya menyesuaikan tingkat risiko tindakan. Modal dapat ditutup dengan tombol Batal, tombol Escape, atau mengklik area di luar kotak.

### Field Formulir Pengaduan CS (`/cs/pengaduan/input`)

| Bagian | Field | Keterangan |
|:---|:---|:---|
| Data Pelapor | `nama_pelapor`*, `master_cabang_id`* (asal cabang, dipilih manual; default cabang akun CS), `kategori`*, `sub_kategori`, `sub_kategori_2` | Kategori berupa teks bebas. Pengaduan terlihat oleh CS pengirim, CS lain di cabang akun yang sama, dan CS di cabang asal yang dipilih. |
| Data Nasabah | `nama_nasabah`*, `no_hp`*, `no_ktp`* (16 digit), `no_rekening`*, `no_kartu` | |
| Data Transaksi | `master_transaksi_id`*, `channel`* (prinsipal), `no_resi`*, `terminal_transaksi`, `nominal_transaksi`* (Rp), `tgl_transaksi`* | Channel terisi otomatis dari jenis transaksi. |
| Lampiran | `foto_ktp`* , `buku_tabungan`, `kartu_atm`, `form_keluhan`, `lainnya` | Maks 5 berkas / jenis, 5 MB / berkas (JPG/PNG/WEBP/PDF). **Format berkas selalu sama dengan kiriman CS** dan tidak pernah dikonversi ke PDF. Foto beresolusi besar diperkecil otomatis agar hemat penyimpanan. |
| Keterangan | `kronologi`* (min. 20 karakter) | |

Nomor pengaduan dibuat otomatis: `PGD-{kode_cabang}-{YYYYMMDD}-{urut}`.

### Keamanan & Penyimpanan Lampiran
Lampiran (berisi KTP) disimpan di disk **privat** `storage/app/private/pengaduan/{id}/` dan hanya dapat dibuka lewat `/pengaduan/{id}/lampiran/{lampiranId}` oleh Admin Pusat atau CS yang berwenang. Tidak diperlukan `storage:link`.

### Format & Optimalisasi Berkas
**Format berkas selalu dipertahankan**: JPG tetap JPG, PNG tetap PNG, WEBP tetap WEBP, dan PDF tidak pernah disentuh sama sekali. Yang dioptimalkan hanya gambar, yaitu koreksi orientasi foto ponsel (EXIF) dan pengecilan resolusi bila sisi terpanjang melebihi `lebar_maks_gambar` (bawaan 2000 piksel), lalu disimpan ulang dengan format yang sama.

| Berkas | Sebelum | Sesudah |
|:---|:---|:---|
| Foto ponsel 4000 × 3000 piksel | 4,3 MB | 0,5 MB (hemat 88%) |
| Foto/scan ≤ 2000 piksel | tidak diubah | tidak diubah |
| Berkas PDF | tidak diubah | tidak diubah |

Tiga pengaman agar berkas tidak pernah menjadi lebih buruk:
1. Gambar yang sudah kecil dan orientasinya benar disimpan apa adanya tanpa dikodekan ulang.
2. Bila hasil olahan ternyata lebih besar dari berkas asli, berkas asli yang dipakai (kasus PNG berblok warna datar).
3. Kanal transparansi hanya dipertahankan bila berkas aslinya memang punya transparansi.

Pengaturan ada di `config/pengaduan.php`: `lebar_maks_gambar`, `kualitas_gambar`, `kompresi_png`, `max_ukuran_file_kb`, `max_file_per_jenis`.

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
| 5 | **Nomor Tiket Keluhan** | `no_tiket` | Text | **Wajib (`*`)** | Diketik petugas sesuai berkas. Terkunci otomatis hanya bila jurnal berasal dari pengaduan CS. Lihat bagian penomoran tiket di bawah. |
| 6 | **Cabang Transaksi / Pelapor** | `master_cabang_id` | Select | **Wajib (`*`)** | Dropdown 41 daftar kantor cabang Bank Sulteng. |
| 7 | **Jenis Transaksi** | `master_transaksi_id` | Select | **Wajib (`*`)** | Dropdown 33 jenis transaksi (memicu auto-fill). |
| 8 | **Channel Transaksi (Otomatis)** | `channel` | Text (Readonly) | Otomatis | Terisi otomatis sesuai channel jenis transaksi. |
| 9 | **Biaya Admin (Pilihan)** | `biaya_admin` | Select | **Wajib (`*`)** | Dropdown pilihan biaya admin: `- (Rp 0)`, `Rp 1.000`, `Rp 1.500`, `Rp 1.750`, `Rp 2.000`, `Rp 2.500`, `Rp 2.750`, `Rp 3.000`, `Rp 6.500`, `Rp 7.500` / sesuai Excel saat import. |
| 10 | **Biaya / Nominal Transaksi** | `nominal_transaksi` | Number | **Wajib (`*`)** | Jumlah nominal uang yang dikeluhkan nasabah (Rp). |
| 11 | **Terminal Transaksi / Mesin** | `terminal_transaksi` | Text | **Wajib (`*`)** | ID Mesin ATM / Terminal EDC. |
| 12 | **Tanggal Transaksi Bermasalah** | `tgl_transaksi` | Date | **Wajib (`*`)** | Tanggal saat nasabah melakukan transaksi yang bermasalah. |
| 13 | **Tanggal Terima Keluhan** | `tgl_terima` | Date | **Wajib (`*`)** | Tanggal saat cabang/petugas menerima pengaduan nasabah. |
| 14 | **Tanggal Selesai Penanganan** | `tgl_selesai` | Date | Opsional | Tanggal saat keluhan selesai diproses (boleh kosong / `-` jika belum selesai). |
| 15 | **Status Keluhan** | `status` | Teks + daftar pilihan | **Wajib (`*`)** | **Dapat diketik manual** atau dipilih dari daftar standar: **`-` (Belum Ditentukan)**, **Menunggu**, **Success**, **Done**, **Rejected** (otomatis `-` jika kosong di Excel). Lihat catatan status kustom di bawah. |
| 16 | **Keterangan Log / Kronologi** | `keterangan_log` | Textarea | Opsional | Catatan kronologi keluhan atau detail tindak lanjut (default `-`). |

### Penomoran Tiket Keluhan
Setiap keluhan memiliki **satu nomor tiket** yang dipakai dari awal sampai selesai. Nomor dibuat otomatis oleh sistem dan tidak dapat diketik petugas.

```text
BS-2026090412345
│   │        └── 5 angka acak yang belum terpakai pada tanggal tersebut
│   └─────────── Tanggal (YYYYMMDD)
└─────────────── Awalan tetap Bank Sulteng
```

Ada dua jalur masuk keluhan, dan penomorannya berbeda.

**Jalur 1 — Keluhan dari CS cabang (nomor dibuat sistem).** Nomor lahir saat CS mengirim keluhan, lalu dibawa terus ke Jurnal Keluhan tanpa dibuat ulang, sehingga CS dan Admin Pusat selalu merujuk nomor yang sama.

```text
CS kirim keluhan ──► Nomor tiket dibuat ──► Admin verifikasi ──► Masuk jurnal
                     BS-2026090412345         (nomor sama)        (nomor sama)
```

**Jalur 2 — Berkas fisik yang sampai ke Divisi IT (nomor diketik petugas).** Berkas menempuh beberapa tahap sebelum tiba di Divisi IT:

```text
CS cabang ──► Penyelia cabang ──► Divisi Literasi ──► Admin Divisi IT
```

Karena itu **Tanggal Terima Keluhan** pada form jurnal adalah tanggal berkas selesai diperiksa Divisi Literasi, **bukan** tanggal CS menerima keluhan dari nasabah. Nomor tiket tidak boleh dibuat dari tanggal tersebut, sehingga petugas mengetiknya sendiri sesuai berkas yang diterima.

| Hal | Ketentuan |
|:---|:---|
| Jalur pengaduan CS | Nomor dibuat sistem saat pengaduan dikirim, memakai tanggal pengiriman. Pada form jurnal nomor tampil **terkunci** dan tidak dapat diubah, agar rujukan CS cabang tetap sama. |
| Jalur jurnal langsung | Nomor **diketik petugas** sesuai berkas, wajib diisi, dan bebas format karena bisa saja mengikuti penomoran lama. |
| Angka acak (jalur CS) | Diambil dengan pembangkit acak kriptografis, lalu dipastikan belum terpakai pada tanggal yang sama, dicek ke data pengaduan maupun jurnal. |
| Kapasitas | 100.000 nomor untuk setiap tanggal. |
| Bila bentrok | Sistem mencoba angka lain hingga 25 kali, lalu beralih mencari angka bebas secara berurutan sehingga pengiriman tidak pernah gagal. |
| Bersamaan | Pembuatan nomor dikunci di tingkat database, jadi dua CS yang mengirim pada detik yang sama tidak mendapat nomor kembar. |
| Saat diedit | Jurnal jalur langsung boleh dikoreksi nomornya. Jurnal jalur pengaduan CS nomornya tetap. |
| Data lama | Nomor tiket lama hasil impor Excel dibiarkan apa adanya. |

### Status Keluhan yang Diketik Manual
Kolom **Status Keluhan** pada form Input Jurnal maupun Edit Jurnal berupa isian teks yang menyatu dengan daftar pilihan. Petugas dapat mengetik status apa pun sesuai kondisi penanganan, misalnya `Menunggu Konfirmasi Bank Lain` atau `Sedang Investigasi Vendor ATM`, atau memilih salah satu status standar dari daftar.

| Perilaku | Keterangan |
|:---|:---|
| Penyeragaman penulisan | Ketikan yang sama dengan status standar diseragamkan otomatis, `done` menjadi `Done`, sehingga perhitungan statistik tidak terpecah. |
| Perapian spasi | Spasi berlebih dirapikan, `Menunggu   Dokumen` menjadi `Menunggu Dokumen`. |
| Batas panjang | Maksimal 50 karakter. |
| Filter data | Status kustom otomatis muncul pada dropdown filter halaman Data Keluhan di grup **Status Kustom (diketik manual)**. |
| Kartu statistik | Kartu ringkasan hanya menghitung status standar. Status kustom masuk hitungan **Total Keluhan** tetapi tidak pada kartu Menunggu / Success / Done / Rejected. |
| Lencana tabel | Status kustom tampil dengan lencana abu-abu netral. |
| Status pengaduan CS | Jurnal berstatus kustom membuat pengaduan cabang berstatus **Dalam Proses Jurnal**. Status **Selesai** hanya tercapai bila status jurnal `Done` atau `Success`, dan **Ditolak** bila `Rejected`. |

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
