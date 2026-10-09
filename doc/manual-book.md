# 📖 BUKU PANDUAN PENGGUNAAN PANEL ADMIN RESTORAN
## Walk-In Resto: Platform Operasional Kasir POS, Kitchen Display System (KDS), & Manajemen Restoran

> **Dokumen Resmi Panduan Operasional Klien Restoran**  
> **Versi Panduan**: 2.4.0  
> **Sasaran Pengguna**: Pemilik Restoran (*Owner*), Manajer Operasional, Staf Kasir (*Front of House*), Staf Dapur & Barista (*Back of House*), dan Tim Finansial/Admin.  
> **Platform**: Web Based Application (Filament v4 Engine - Cloud Hosted)

---

## 📑 DAFTAR ISI LENGKAP

1. [BAGIAN I: PENDAHULUAN & AKSES SISTEM](#bagian-i-pendahuluan--akses-sistem)
   - [Bab 1: Pengenalan Ekosistem & Memulai Aplikasi](#bab-1-pengenalan-ekosistem--memulai-aplikasi)
     - 1.1. Konsep Dasar Walk-In Resto
     - 1.2. Kebutuhan Perangkat Keras & Jaringan
     - 1.3. Prosedur Masuk ke Panel Admin (Login)
     - 1.4. Navigasi Antarmuka & Indikator Status Langganan
     - 1.5. Sistem Notifikasi Suara Real-Time (Audio Alert)
2. [BAGIAN II: MONITORING BISNIS & RINGKASAN HARIAN](#bagian-ii-monitoring-bisnis--ringkasan-harian)
   - [Bab 2: Dasbor Analitik & Kesiapan Restoran](#bab-2-dasbor-analitik--kesiapan-restoran)
     - 2.1. Membaca Kartu Kesiapan Restoran (Readiness Checklist)
     - 2.2. Filter Rentang Waktu Analitik
     - 2.3. Memahami Kartu Indikator Kinerja Utama (KPI Metrics)
     - 2.4. Grafik Tren Penjualan & Bauran Pembayaran
     - 2.5. Peringkat Menu Terlaris (Top Menu Analytics)
     - 2.6. Widget Pembayaran Tertunda (Pending Approvals)
3. [BAGIAN III: OPERASIONAL KASIR DEPAN (FRONT OF HOUSE / POS)](#bagian-iii-operasional-kasir-depan-front-of-house--pos)
   - [Bab 3: Manajemen Shift Kasir & Rekonsiliasi Kas](#bab-3-manajemen-shift-kasir--rekonsiliasi-kas)
     - 3.1. Prosedur Buka Shift Kasir Pagi (Starting Cash)
     - 3.2. Pencatatan Mutasi Kas Operasional (Petty Cash In / Out)
     - 3.3. Prosedur Tutup Shift dengan Standar Audit Blind Cash Count
     - 3.4. Membaca Laporan Rekonsiliasi Kas & Cetak Slip Shift (X/Z Report)
   - [Bab 4: Antarmuka Order Kasir POS (Pemesanan Cepat)](#bab-4-antarmuka-order-kasir-pos-pemesanan-cepat)
     - 4.1. Memilih Meja atau Mode Bawa Pulang (Takeaway)
     - 4.2. Identifikasi Tamu & Integrasi Member Loyalitas WhatsApp
     - 4.3. Input Menu, Kustomisasi Varian, Modifier, & Catatan Khusus
     - 4.4. Otomatisasi Perhitungan Pajak PB1, Service Charge, & Diskon Poin
     - 4.5. Penyelesaian Pembayaran: Tunai, QRIS Statis Cerdas, & Tukar Poin
     - 4.6. Cetak Struk Fisik Termal 80mm & Struk WhatsApp Otomatis
   - [Bab 5: Manajemen Pesanan & Transaksi Aktif](#bab-5-manajemen-pesanan--transaksi-aktif)
     - 5.1. Memantau Status Siklus Nota Pesanan
     - 5.2. Persetujuan Pembayaran Idempoten (Approval Bayar 1-Klik)
     - 5.3. Penanganan Khusus: Override GPS Geofence Pembayaran Tunai
     - 5.4. Penolakan Pesanan dengan Alasan Wajib (Reject Order)
     - 5.5. Prosedur Pembatalan Menu (Void Item & Void Order dengan Pelacakan Limbah)
     - 5.6. Cetak Ulang Struk & Kirim Ulang Struk WhatsApp
   - [Bab 6: Manajemen Meja & Denah Interaktif](#bab-6-manajemen-meja--denah-interaktif)
     - 6.1. Membuat & Mengedit Master Meja Restoran
     - 6.2. Menggunakan Visual Floor Plan Editor (Drag-and-Drop)
     - 6.3. Memahami 5 Siklus Warna Status Meja
     - 6.4. Mengunduh & Mencetak Stiker QR Meja (PNG & Vektor PDF)
     - 6.5. Regenerasi Token QR Meja Bertanda Tangan Kriptografis
   - [Bab 7: Manajemen Kunjungan Tamu (Visit Sesi Meja)](#bab-7-manajemen-kunjungan-tamu-visit-sesi-meja)
     - 7.1. Memantau Sesi Tamu Aktif & Batas Waktu Klaim (Claim TTL)
     - 7.2. Solusi Tamu Lupa PIN: Prosedur Reset 4-Digit PIN Meja
     - 7.3. Koreksi Nomor WhatsApp Tamu
     - 7.4. Prosedur Pindah Meja Tamu Dinamis
     - 7.5. Penutupan Sesi Meja (Close Visit & Status Cleaning)
4. [BAGIAN IV: OPERASIONAL DAPUR & BAR (BACK OF HOUSE)](#bagian-iv-operasional-dapur--bar-back-of-house)
   - [Bab 8: Kitchen Display System (Layar Operasional KDS)](#bab-8-kitchen-display-system-layar-operasional-kds)
     - 8.1. Konfigurasi Layar Monitor/Tablet per Stasiun Kerja
     - 8.2. Respon Bel Audio Dapur Otomatis (Kitchen Service Chime)
     - 8.3. Siklus Produksi 4 Tahap (Queued -> Preparing -> Ready -> Served)
     - 8.4. Indikator Waktu Tunggu Visual (Aging Timer Band Warna)
     - 8.5. Membaca Modifikasi Item, Varian, & Catatan Khusus
     - 8.6. Tab Khusus "Siap Antar" untuk Staf Runner / Waiter
5. [BAGIAN V: MANAJEMEN MENU & PRODUK](#bagian-v-manajemen-menu--produk)
   - [Bab 9: Manajemen Menu, Varian, Modifier & Stasiun](#bab-9-manajemen-menu-varian-modifier--stasiun)
     - 9.1. Mengelola Kategori Menu
     - 9.2. Menambah & Mengedit Item Menu Master
     - 9.3. Pengaturan Foto Menu Berkualitas (Standar Rasio 4:3)
     - 9.4. Manajemen Ketersediaan: Toggle Aktif vs Toggle Stok Habis (Out of Stock)
     - 9.5. Konfigurasi Varian Menu (Porsi / Ukuran dengan Selisih Harga)
     - 9.6. Konfigurasi Grup Modifier (Topping & Add-ons dengan Aturan Min/Max)
     - 9.7. Mengelola Stasiun Kerja KDS
6. [BAGIAN VI: CRM, LOYALITAS & PEMASARAN](#bagian-vi-crm-loyalitas--pemasaran)
   - [Bab 10: CRM Pelanggan, Sistem Poin Loyalitas & Ulasan Tamu](#bab-10-crm-pelanggan-sistem-poin-loyalitas--ulasan-tamu)
     - 10.1. Mengakses Database Pelanggan Otomatis via WhatsApp
     - 10.2. Pengaturan Program Poin & Tingkatan Member (Reguler, Silver, Gold, VIP)
     - 10.3. Pengaturan Kurs Penukaran Poin ke Diskon Tagihan (Point-to-Discount)
     - 10.4. Moderasi Ulasan & Rating Bintang Tamu (Analisis Sentimen Otomatis)
     - 10.5. Memantau Metrik Kepuasan Tamu (Customer Satisfaction / CSAT)
   - [Bab 11: Pengelolaan Website Profil Restoran (CMS)](#bab-11-pengelolaan-website-profil-restoran-cms)
     - 11.1. Profil Restoran, Logo, Favicon, & Warna Brand
     - 11.2. Memilih Tema Desain Publik: Classic, Foodie, atau Glassmorphism
     - 11.3. Jam Operasional Mingguan & Fasilitas Restoran
     - 11.4. Integrasi WhatsApp Restoran Resmi (Fonnte API Token)
     - 11.5. Tata Letak Seksi Halaman Depan dengan Antarmuka Drag-and-Drop
     - 11.6. Banner Promosi Berjadwal Otomatis
     - 11.7. Galeri Foto Restoran & Tanya Jawab Pelanggan (FAQ)
7. [BAGIAN VII: LAPORAN FINANSIAL & PUSAT DATA](#bagian-vii-laporan-finansial--pusat-data)
   - [Bab 12: Pusat Ekspor Laporan Finansial & Operasional](#bab-12-pusat-ekspor-laporan-finansial--operasional)
     - 12.1. Membuat Laporan Finansial & Operasional (Excel & PDF)
     - 12.2. Mengunduh Dokumen di Menu Riwayat Ekspor
     - 12.3. Rekonsiliasi Penjualan & Komisi Platform
8. [BAGIAN VIII: PENGATURAN CABANG, KEAMANAN & KARYAWAN](#bagian-viii-pengaturan-cabang-keamanan--karyawan)
   - [Bab 13: Konfigurasi Outlet & Mode Operasional](#bab-13-konfigurasi-outlet--mode-operasional)
     - 13.1. Pengaturan Pajak PB1 & Service Charge
     - 13.2. Penentuan Titik Koordinat GPS & Radius Geofencing Tunai
     - 13.3. Mengunggah Kode QRIS Statis Outlet
     - 13.4. Batas Waktu Operasional (Claim TTL & Awaiting Cashier TTL)
     - 13.5. Pemilihan Mode Operasional: Simple Mode vs Standar Mode KDS
     - 13.6. Otomatisasi Dialog Cetak Struk
   - [Bab 14: Manajemen Karyawan, Hak Akses & Keamanan](#bab-14-manajemen-karyawan-hak-akses--keamanan)
     - 14.1. Menambah Karyawan Baru & Pengaturan Avatar
     - 14.2. Pembagian Hak Akses Berdasarkan Peran (Owner, Kasir, Dapur)
     - 14.3. Menonaktifkan Akses Staf yang Sudah Berhenti
     - 14.4. Membaca Log Aktivitas (Immutable Audit Trail)
     - 14.5. Memantau Log Pengiriman Struk WhatsApp
     - 14.6. Memulihkan Data Terhapus dari Tempat Sampah (Trash Recovery)
   - [Bab 15: Manajemen Langganan Restoran & Profil Akun](#bab-15-manajemen-langganan-restoran--profil-akun)
     - 15.1. Memeriksa Status Paket Langganan & Masa Aktif
     - 15.2. Perpanjangan Langganan, Penerbitan Invoice, & Upload Bukti Transfer
     - 15.3. Pemahaman Mode Masa Tenggang (Grace Period) & Read-Only Mode
     - 15.4. Pengaturan Profil Akun Pengguna Pribadi
9. [BAGIAN IX: STANDAR OPERASIONAL PROSEDUR (SOP RINGKAS HARIAN)](#bagian-ix-standar-operasional-prosedur-sop-ringkas-harian)
   - [Bab 16: Checklist Standar Operasional Harian Restoran](#bab-16-checklist-standar-operasional-harian-restoran)
     - 16.1. SOP Pembukaan Resto (Pagi Hari)
     - 16.2. SOP Jam Operasional Sibuk (Rush Hour)
     - 16.3. SOP Penutupan Resto (Malam Hari / Tutup Shift)

---

# BAGIAN I: PENDAHULUAN & AKSES SISTEM

## Bab 1: Pengenalan Ekosistem & Memulai Aplikasi

### 1.1. Konsep Dasar Walk-In Resto
Walk-In Resto adalah sistem manajemen restoran modern berbasis *Cloud* yang menggabungkan tiga pilar operasional F&B dalam satu kesatuan data yang sinkron secara real-time:
1. **Self-Order Tamu via QR Meja**: Tamu duduk, memindai QR meja bertanda tangan kriptografis, membuka katalog visual responsif, memesan bersama teman satu meja (*Shared Cart*), dan membayar mandiri.
2. **Point of Sale (POS Front of House)**: Antarmuka kasir cepat untuk melayani transaksi manual/takeaway, verifikasi pembayaran tamu, kelola laci kasir, dan cetak struk termal/WhatsApp.
3. **Kitchen Display System (KDS Back of House)**: Layar pemantau pesanan dapur dan bar tanpa tiket kertas kusut, dilengkapi pembagian per stasiun, bel audio otomatis, dan indikator waktu masak.

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│   Tamu di Meja  │ ────> │   Kasir Depan   │ ────> │   Dapur & Bar   │
│ (Scan QR & HP)  │       │   (Panel POS)   │       │   (Layar KDS)   │
└─────────────────┘       └─────────────────┘       └─────────────────┘
         │                         │                         │
         └─────────────────────────┼─────────────────────────┘
                                   ▼
                    ┌─────────────────────────────┐
                    │  Panel Admin & Owner Resto  │
                    │   (Laporan, Menu, CRM)      │
                    └─────────────────────────────┘
```

### 1.2. Kebutuhan Perangkat Keras & Jaringan
Sistem ini berbasis web modern (*Single Page Application*), sehingga tidak memerlukan instalasi aplikasi khusus. Spesifikasi perangkat yang disarankan:
* **Perangkat Kasir**: Komputer PC, Laptop, atau Tablet Touchscreen (layar minimal 10 inci, RAM minimal 4 GB) dengan browser Google Chrome atau Microsoft Edge versi terbaru.
* **Printer Kasir**: Printer termal standar 80mm (USB, Bluetooth, atau Network LAN) yang mendukung *ESC/POS*.
* **Perangkat Dapur (KDS)**: Tablet Android / iPad / Monitor Smart TV dengan browser web modern. Disarankan menghubungkan speaker eksternal agar bel notifikasi terdengar jelas di tengah bising dapur.
* **Koneksi Internet**: Kecepatan minimal 10 Mbps stabil dengan jangkauan Wi-Fi yang baik di seluruh area meja makan, meja kasir, dan dapur.

### 1.3. Prosedur Masuk ke Panel Admin (Login)
1. Buka browser pada komputer kasir atau tablet Anda.
2. Akses alamat web panel admin restoran Anda, contoh: `https://namadomain.com/admin` atau alamat spesifik tenant `https://namadomain.com/admin/slug-resto-anda`.
3. Masukkan **Email** atau **Username** dan **Kata Sandi (Password)** Anda.
4. *(Opsional)* Centang **Ingat Saya (Remember Me)** jika Anda menggunakan komputer kasir pribadi milik resto.
5. Klik tombol **Masuk (Sign In)**.

> [!NOTE]
> Jika Anda lupa kata sandi, hubungi staf dengan peran **Owner** restoran Anda untuk melakukan reset kata sandi melalui menu **Pengaturan $\rightarrow$ Staf**, atau klik tautan *Lupa Password* pada layar login jika email server restoran Anda sudah terkonfigurasi.

### 1.4. Navigasi Antarmuka & Indikator Status Langganan
Setelah berhasil masuk, Anda akan melihat antarmuka terintegrasi:
* **Bilah Samping Kiri (Sidebar Navigation)**: Menampilkan menu navigasi yang dikelompokkan rapi (Dashboard, Operasional, Menu, CRM & Pelanggan, CMS, Laporan, Pengaturan). Bilah ini dapat disembunyikan/diciutkan pada desktop agar area kerja lebih lega.
* **Bilah Atas (Header Bar)**:
  * Logo & Nama Restoran Anda.
  * **Notifikasi Tab**: Menampilkan lonceng notifikasi aktivitas pesanan masuk, pembatalan, dan peringatan sistem.
  * **Profil Pengguna**: Menampilkan nama akun aktif, menu *Edit Profil*, dan tombol *Keluar (Logout)*.
* **Spanduk Masa Tenggang (Grace Period Banner)**:
  * Jika langganan restoran Anda memasuki masa tenggang pembayaran invoice, spanduk kuning/merah akan muncul di bagian paling atas layar yang memberitahukan sisa hari sebelum akses beralih ke mode *Read-Only*. Segera lakukan perpanjangan di menu **Pengaturan $\rightarrow$ Langganan**.

### 1.5. Sistem Notifikasi Suara Real-Time (Audio Alert)
Panel admin dilengkapi dengan mesin suara ganda (*Dual-Engine Sound Notification*) yang memutar nada bel instan berlatensi 0ms saat ada pesanan baru masuk dari meja tamu:
1. Pastikan Anda telah mengklik sembarang area di layar browser setidaknya 1 kali setelah login (kebijakan keamanan browser modern mewajibkan interaksi awal pengguna agar suara diizinkan berbunyi otomatis).
2. Di pojok kanan bawah layar, terdapat tombol kontrol suara:
   * **Ikon Speaker Aktif (Unmuted)**: Notifikasi suara menyala.
   * **Ikon Speaker Coret (Muted)**: Notifikasi suara dibisukan.
   * **Tombol Uji Suara (Test Sound)**: Klik untuk memastikan speaker kasir berbunyi nyaring.

---

# BAGIAN II: MONITORING BISNIS & RINGKASAN HARIAN

## Bab 2: Dasbor Analitik & Kesiapan Restoran

> **Akses Navigasi**: Menu Utama $\rightarrow$ **Dashboard**  
> **Hak Akses**: Owner, Manajer, dan Kasir (tergantung perizinan peran).

```
┌────────────────────────────────────────────────────────────────────────┐
│ [Banner Sambutan Resto]       [Kartu Kesiapan Resto: 5/5 Siap]         │
├────────────────────────────────────────────────────────────────────────┤
│ Filter: [ Hari Ini ▾ ]   Dari: [ 07 Okt 2026 ]   Sampai: [ 07 Okt 2026 ]│
├─────────────┬─────────────┬──────────────┬──────────────┬──────────────┤
│ Omzet Harian│ Total Order │ Rata-rata AOV│ Makanan Void │ Selisih Kemarin│
│ Rp 4.250.000│  86 Nota    │  Rp 49.418   │   Rp 35.000  │    +18.4% ↗   │
├─────────────┴─────────────┴──────────────┴──────────────┴──────────────┤
│ [ Grafik Tren Omzet Per Jam & Bauran Pembayaran Tunai vs QRIS ]       │
├──────────────────────────────────────────┬─────────────────────────────┤
│ 🏆 Menu Terlaris (Kuantitas & Rupiah)    │ ⚠️ Pembayaran Butuh Tindakan│
│ 1. Nasi Goreng Spesial (34 porsi)        │ Pesanan #ORD-089 (Meja 04)  │
│ 2. Es Kopi Susu Aren   (52 cup)          │ Menunggu verifikasi kasir   │
└──────────────────────────────────────────┴─────────────────────────────┘
```

### 2.1. Membaca Kartu Kesiapan Restoran (Readiness Checklist)
Bagi restoran baru atau saat persiapan buka toko di pagi hari, widget **Kesiapan Restoran (Restaurant Readiness)** akan menampilkan indikator kelengkapan operasional:
* Apakah profil cabang dan jam buka sudah terisi?
* Apakah QRIS statis outlet sudah diunggah?
* Apakah meja makan sudah terdaftar minimal 1 meja?
* Apakah menu makanan dan stasiun dapur sudah aktif?
Jika seluruh ceklis sudah bertanda centang hijau, restoran Anda dinyatakan 100% siap melayani pelanggan.

### 2.2. Filter Rentang Waktu Analitik
Di bagian atas dasbor terdapat bilah filter waktu interaktif. Anda dapat memilih preset waktu cepat:
* **Hari Ini**: Memantau perputaran omzet saat toko sedang buka hari ini.
* **Kemarin**: Mengevaluasi pencapaian operasional hari kemarin.
* **7 Hari Terakhir**: Menganalisa tren performa mingguan.
* **30 Hari Terakhir / Bulan Ini**: Laporan performa bulanan.
* **Kustom Tanggal**: Tentukan tanggal awal (*Date From*) dan tanggal akhir (*Date To*) secara bebas.

### 2.3. Memahami Kartu Indikator Kinerja Utama (KPI Metrics)
1. **Omzet Bersih (Net Revenue)**: Total rupiah dari seluruh pesanan yang telah lunas diverifikasi kasir, dikurangi pesanan yang dibatalkan saat belum dimasak.
2. **Total Pesanan (Order Count)**: Jumlah nota transaksi yang berhasil diselesaikan pada periode yang dipilih.
3. **Average Order Value (AOV)**: Rata-rata nilai belanja per nota/meja (dihitung dari: *Total Omzet dibagi Total Pesanan*). Semakin tinggi angka AOV, semakin baik keberhasilan kasir dalam menawarkan varian dan add-ons (*upselling*).
4. **Kerugian Void / Limbah Makanan (Waste Loss Amount)**: Total nilai rupiah dari makanan yang dibatalkan setelah dapur terlanjur memasak hidangan. Angka ini harus ditekan sekecil mungkin oleh tim dapur dan pelayan.
5. **Perbandingan dengan Periode Sebelumnya**: Lencana persentase hijau (naik) atau merah (turun) yang membandingkan performa periode aktif dengan periode sebelumnya.

### 2.4. Grafik Tren Penjualan & Bauran Pembayaran
* **Grafik Batang Penjualan**: Menampilkan pergerakan transaksi per jam (pada filter harian) atau per hari (pada filter bulanan). Anda dapat melihat pada jam berapa restoran mengalami jam sibuk (*peak hours*) sehingga jadwal kerja staf dapat disesuaikan.
* **Bauran Pembayaran (Payment Mix)**: Menampilkan proporsi transaksi antara **Pembayaran Tunai (Cash)** dan **Pembayaran Non-Tunai (QRIS)**.

### 2.5. Peringkat Menu Terlaris (Top Menu Analytics)
Tabel ini membagi menu menjadi dua sudut pandang:
* **Top by Volume**: Menu yang paling banyak dipesan dalam satuan porsi/cup. Berguna untuk persiapan stok bahan baku di dapur.
* **Top by Revenue**: Menu yang menyumbangkan pendapatan rupiah terbesar bagi restoran. Menu ini adalah tambang emas bisnis Anda yang patut dipertahankan kualitasnya.

### 2.6. Widget Pembayaran Tertunda (Pending Approvals)
Widget ini menampilkan daftar pesanan tamu yang sedang menunggu persetujuan kasir di meja kasir. Mengklik baris transaksi ini akan langsung membawa Anda ke halaman detail nota untuk melakukan persetujuan.

---

# BAGIAN III: OPERASIONAL KASIR DEPAN (FRONT OF HOUSE / POS)

## Bab 3: Manajemen Shift Kasir & Rekonsiliasi Kas

> **Akses Navigasi**: Menu **Operasional** $\rightarrow$ **Order kasir** & **Shift kasir**  
> **Hak Akses**: Kasir, Manajer Operasional, Owner.

```
┌────────────────────────────────────────────────────────────────────────┐
│ [STATUS SHIFT: AKTIF]  Kasir: Rina  |  Modal: Rp 100.000  |  Est: Rp 850.000│
│ [ + / - Kas Masuk & Keluar ]                     [ Tombol Tutup Shift ]│
└────────────────────────────────────────────────────────────────────────┘
```

### 3.1. Prosedur Buka Shift Kasir Pagi (Starting Cash)
Sebelum melayani transaksi pertama, kasir yang bertugas **wajib** membuka shift kerja:
1. Buka menu **Operasional $\rightarrow$ Order kasir**.
2. Jika shift belum dibuka, sistem menampilkan lencana kuning bertuliskan **Shift Belum Dibuka**.
3. Klik tombol tersebut untuk membuka jendela pop-up **Buka Shift Kasir Baru**.
4. Masukkan nominal **Modal Awal (Starting Cash)**, yaitu uang tunai fisik pecahan kecil yang disiapkan di dalam laci kasir untuk uang kembalian pelanggan. Anda dapat mengetikkan nominalnya atau menekan tombol nominal cepat (**Rp 50.000**, **Rp 100.000**, **Rp 200.000**).
5. Tuliskan keterangan di kolom **Catatan** jika perlu (contoh: *"Pecahan 2 ribuan 20 lembar, 5 ribuan 12 lembar"*).
6. Klik **Mulai Shift Kasir**. Lencana berubah menjadi hijau aktif.

### 3.2. Pencatatan Mutasi Kas Operasional (Petty Cash In / Out)
Jika selama jam operasional terjadi pengeluaran atau pemasukan uang tunai mendadak di meja kasir:
1. Klik tombol **Kas Masuk / Keluar (+/-)** di bilah atas layar Order Kasir.
2. Pilih jenis transaksi:
   * **Kas Keluar**: Untuk biaya operasional mendadak (contoh: membeli es batu kristal darurat, air galon, parkir kurir logistik).
   * **Kas Masuk**: Untuk penambahan uang tunai modal dari brankas pemilik resto.
3. Pilih kategori pengeluaran dan masukkan nominal uang.
4. Tuliskan alasan/keterangan yang jelas di kolom catatan.
5. Klik **Simpan Mutasi Kas**. Saldo estimasi di laci kasir otomatis terbarui.

### 3.3. Prosedur Tutup Shift dengan Standar Audit Blind Cash Count
Saat jam kerja kasir berakhir atau terjadi pergantian staf kasir:
1. Klik tombol **Tutup Shift** di pojok kanan atas layar Order Kasir.
2. **Pahami Metode Blind Cash Count**: Sistem sengaja **tidak menampilkan** estimasi saldo uang yang seharusnya ada di laci. Hal ini bertujuan mencegah manipulasi dan menjaga integritas audit kasir.
3. Kasir wajib mengeluarkan seluruh uang kertas dan koin dari laci kasir, lalu menghitungnya secara fisik.
4. Masukkan total uang tunai riil yang telah dihitung ke kolom **Uang Tunai Fisik di Laci**.
5. Jika kasir menyadari ada kekurangan atau kelebihan uang (misal uang kembalian tertinggal), tuliskan alasannya pada kolom **Catatan Selisih**.
6. Klik **Hitung & Tutup Shift**.

### 3.4. Membaca Laporan Rekonsiliasi Kas & Cetak Slip Shift (X/Z Report)
Sistem akan memvalidasi input kasir dan langsung menampilkan jendela rekapitulasi:
* **Modal Awal**: Nominal kas saat shift dibuka.
* **Penjualan Tunai**: Total omzet tunai yang diterima kasir selama shift.
* **Total Kas Masuk & Kas Keluar**: Rekapitulasi mutasi kas kecil operasional.
* **Estimasi Kas Sistem (Teoritis)**: Hasil kalkulasi sistem (*Modal Awal + Penjualan Tunai + Kas Masuk - Kas Keluar*).
* **Kas Fisik Aktual**: Jumlah uang yang dihitung kasir.
* **Status Selisih**:
  * 🟢 **PAS (Rp 0)**: Saldo fisik cocok sempurna dengan data sistem.
  * 🔴 **MINUS (-Rp ...)**: Uang di laci kurang dari perhitungan sistem.
  * 🔵 **PLUS (+Rp ...)**: Uang di laci lebih banyak dari perhitungan sistem.
* Klik tombol **Cetak Slip Shift** untuk mencetak laporan ke printer termal atau mengunduh dokumen PDF resmi sebagai bukti serah terima kasir. Riwayat seluruh shift dapat dilihat kembali pada menu **Operasional $\rightarrow$ Shift kasir**.

---

## Bab 4: Antarmuka Order Kasir POS (Pemesanan Cepat)

> **Akses Navigasi**: Menu **Operasional** $\rightarrow$ **Order kasir**  
> **Fungsi Utama**: Melayani pesanan tamu walk-in manual di kasir (tamu tanpa smartphone atau pesanan dibungkus/takeaway).

```
┌──────────────────────────────────────┬─────────────────────────────────┐
│ KATALOG MENU (Cari: [ Nasi Goreng ])  │ RINGKASAN ORDER                 │
├──────────────────────────────────────┤ Meja: [ Meja 02 ▾ ]             │
│ [Semua] [Makanan] [Minuman] [Snack]  │ Nama: [ Budi ]  WA: [0812..]   │
├───────────────────┬──────────────────┤ ─────────────────────────────── │
│ [Foto] Nasi Goreng│ [Foto] Es Teh    │ 1x Nasi Goreng Spesial  35.000  │
│ Rp 35.000         │ Rp 8.000         │    + Pedas Sedang, Telur Ceplok │
│ [ Tambah / Opsi ] │ [ Tambah ]       │ 1x Es Teh Manis          8.000  │
├───────────────────┴──────────────────┤ ─────────────────────────────── │
│                                      │ Subtotal:             Rp 43.000 │
│                                      │ PB1 (10%):            Rp  4.300 │
│                                      │ Total Bayar:          Rp 47.300 │
│                                      ├─────────────────────────────────┤
│                                      │ Bayar: [● Tunai] [○ QRIS]       │
│                                      │ Uang Diterima: [ Rp 50.000 ]    │
│                                      │ Kembalian:     Rp  2.700        │
│                                      │ [ Tombol Buat Pesanan & Bayar ] │
└──────────────────────────────────────┴─────────────────────────────────┘
```

### 4.1. Memilih Meja atau Mode Bawa Pulang (Takeaway)
Pada panel formulir sebelah kanan:
* **Makan di Tempat (Dine-In)**: Klik menu pilihan **Pilih Meja**, pilih nomor meja tempat tamu duduk (misal: *Meja 01*, *A-2*, *VIP*). Meja yang sudah terisi tamu lain akan memiliki penanda khusus.
* **Bawa Pulang (Takeaway)**: Pilih opsi meja *Takeaway / Bungkus* jika tamu tidak menggunakan meja makan di restoran.

### 4.2. Identifikasi Tamu & Integrasi Member Loyalitas WhatsApp
1. Masukkan **Nama Tamu** dan **Nomor WhatsApp**.
2. Format nomor WhatsApp otomatis dinormalisasi oleh sistem (contoh input: `08123456789` otomatis diubah menjadi `628123456789`).
3. **Pengecekan Member Otomatis**: Jika nomor WhatsApp tamu sudah terdaftar sebelumnya di database resto, sistem otomatis menampilkan lencana **Member Loyalty** lengkap dengan **Tier Member (Reguler/Silver/Gold/VIP)** dan saldo poin yang mereka miliki!

### 4.3. Input Menu, Kustomisasi Varian, Modifier, & Catatan Khusus
1. Di panel sebelah kiri, cari menu menggunakan kolom pencarian atau klik tab kategori (Makanan, Minuman, Dessert).
2. **Menu Standar (Tanpa Opsi)**: Klik tombol **Tambah (+)**. Jumlah item langsung bertambah di keranjang belanja.
3. **Menu dengan Opsi (Varian & Modifier)**: Klik tombol **Pilih Opsi**. Jendela kustomisasi akan terbuka:
   * Pilih **Varian** (misal: *Ukuran Regular* atau *Large*).
   * Centang **Modifier / Ekstra Topping** (misal: *Tambah Keju Mozzarella (+Rp 5.000)*, *Level Pedas 3*).
   * Ketikkan instruksi khusus di kolom **Catatan Khusus** (misal: *"Tanpa daun bawang"*, *"Es batu sedikit"*, *"Kuah dipisah"*).
4. Klik **Simpan**. Menu berpilihan masuk ke keranjang belanja dengan rincian yang rapi.

### 4.4. Otomatisasi Perhitungan Pajak PB1, Service Charge, & Diskon Poin
Keranjang belanja di sisi kanan secara otomatis mengkalkulasi nominal:
* **Subtotal**: Total harga menu dan ekstra topping.
* **Diskon Poin Member**: Potongan langsung jika tamu menukarkan poin reward.
* **Biaya Layanan (Service Charge %)**: Sesuai persentase yang diatur di outlet Anda.
* **Pajak Restoran (PB1 %)**: Pajak daerah restoran (default 10%).
* **Total Akhir (Grand Total)**: Nominal bersih yang wajib dibayar tamu.

### 4.5. Penyelesaian Pembayaran
Kasir dapat memilih salah satu metode pembayaran:
* **Metode 1: Pembayaran Tunai (Cash)**
  1. Klik tab **Tunai**.
  2. Masukkan nominal uang yang diserahkan pelanggan pada kolom **Uang Diterima**.
  3. Gunakan tombol bantuan **Uang Pas** jika pelanggan membayar pas.
  4. Sistem menampilkan nominal **Kembalian** dengan angka tebal. Jika uang yang dimasukkan kurang, sistem menampilkan peringatan merah dan tombol bayar dinonaktifkan.
* **Metode 2: Pembayaran Non-Tunai QRIS**
  1. Klik tab **QRIS**.
  2. Gambar barcode QRIS resmi outlet akan muncul di layar kasir, lengkap dengan nominal yang telah ditambahkan **3 digit angka unik anti-bentrok** (misal: tagihan Rp 45.000 menjadi Rp 45.128).
  3. Arahkan tamu untuk memindai QRIS tersebut menggunakan aplikasi m-Banking atau e-Wallet mereka.
* **Metode 3: Penukaran Poin Loyalitas (Point Redemption)**
  * Jika tamu memiliki poin dan ingin menggunakannya, ketikkan jumlah poin yang ditukar atau klik tombol **Maksimal**. Subtotal tagihan akan langsung terpotong sesuai nilai kurs poin.

### 4.6. Cetak Struk Fisik Termal 80mm & Struk WhatsApp Otomatis
1. Centang kotak **Kirim Struk WhatsApp** jika ingin nota digital dikirimkan ke nomor ponsel tamu secara otomatis melalui integrasi Fonnte.
2. Klik tombol **Buat Pesanan & Bayar**.
3. Dalam hitungan detik:
   * Status pesanan berubah menjadi lunas (`paid`).
   * **Jika Mode Standar KDS**: Tiket pesanan langsung terkirim secara otomatis ke layar Kitchen Display System (KDS) dapur untuk dimasak.
   * **Jika Mode Sederhana (Simple Mode)**: Pesanan otomatis langsung ditandai berstatus selesai & disajikan (`served`), layar KDS di-bypass, dan pop-up sukses pesanan langsung muncul beserta kembalian dan opsi cetak struk.
   * Dialog cetak printer termal 80mm terbuka otomatis (jika fitur *Auto-Print* diaktifkan di outlet).
4. Kasir dapat menekan tombol **Order Baru** untuk langsung melayani antrean pelanggan berikutnya.

---

## Bab 5: Manajemen Pesanan & Transaksi Aktif

> **Akses Navigasi**: Menu **Operasional** $\rightarrow$ **Pesanan**  
> **Hak Akses**: Kasir, Manajer Operasional, Owner.

```
┌────────────────────────────────────────────────────────────────────────┐
│ DAFTAR PESANAN                                                         │
├───────────┬─────────┬──────────────┬─────────────┬───────────┬─────────┤
│ Nomor Nota│ Meja    │ Tamu / WA    │ Total Bayar │ Metode    │ Status  │
├───────────┼─────────┼──────────────┼─────────────┼───────────┼─────────┤
│ #ORD-089  │ Meja 04 │ Rian (0812..)│ Rp 84.120   │ QRIS Statis│ Menunggu│
│ #ORD-088  │ Meja 01 │ Sinta(0857..)│ Rp 120.000  │ Tunai     │ Dimasak │
│ #ORD-087  │ Meja 02 │ Doni (0821..)│ Rp 45.000   │ Tunai     │ Selesai │
└───────────┴─────────┴──────────────┴─────────────┴───────────┴─────────┘
```

### 5.1. Memantau Status Siklus Nota Pesanan
Setiap transaksi di restoran memiliki siklus hidup yang transparan:
1. `pending_payment` / `awaiting_cashier`: Tamu telah checkout di HP mereka dan sedang menunggu kasir memverifikasi pembayaran.
2. `paid`: Pembayaran sudah diverifikasi kasir, tiket masuk antrean dapur.
3. `preparing`: Dapur mulai memasak pesanan.
4. `ready`: Makanan selesai dimasak dan siap diantar oleh pelayan/runner.
5. `served` / `completed`: Makanan sudah mendarat di meja tamu dan sesi selesai.
6. `rejected`: Pembayaran ditolak oleh kasir.
7. `voided`: Pesanan dibatalkan secara resmi dengan pencatatan audit.

### 5.2. Persetujuan Pembayaran Idempoten (Approval Bayar 1-Klik)
Ketika tamu melakukan self-order dari HP mereka dengan memilih metode QRIS atau Tunai, notifikasi bel kasir akan berbunyi:
1. Buka menu **Pesanan**, klik tombol **Lihat (View)** pada pesanan bertanda kuning (*Menunggu Kasir*).
2. Periksa bukti transfer m-banking (cocokkan nominal transfer dengan 3 digit unik QRIS) atau terima uang tunai fisik dari tamu di meja kasir.
3. Klik tombol hijau **Terima Pembayaran (Approve)** di bagian atas halaman.
4. Jika metode bayar adalah tunai, masukkan jumlah uang tunai yang diterima untuk menghitung kembalian.
5. Klik **Konfirmasi**. Transaksi otomatis berstatus `paid` dan tiket pesanan langsung terkirim ke KDS dapur dalam 0 milidetik. Sistem ini dilindungi mekanisme *Idempotency* sehingga tidak akan terjadi dobel data meski tombol ditekan berulang kali.

### 5.3. Penanganan Khusus: Override GPS Geofence Pembayaran Tunai
Untuk mencegah pesanan fiktif dari luar restoran, sistem mengharuskan tamu yang memilih metode pembayaran tunai berada dalam radius fisik resto (misal 30 meter).
* Jika GPS HP tamu mengalami kendala sinyal atau akurasi rendah, sistem akan menandai pesanan dengan peringatan: *"Butuh Override GPS"*.
* Kasir cukup memastikan secara visual bahwa tamu memang benar-benar duduk di meja restoran.
* Saat menekan tombol *Terima Pembayaran*, kasir wajib mengisi kolom **Alasan Override GPS** (contoh: *"Tamu sudah terkonfirmasi duduk di Meja 04"*). Alasan ini tercatat permanen di log audit sistem.

### 5.4. Penolakan Pesanan dengan Alasan Wajib (Reject Order)
Jika tamu mengunggah bukti bayar QRIS palsu, nominal transfer tidak sesuai, atau tamu membatalkan pesanan sebelum bayar:
1. Buka pesanan terkait, klik tombol merah **Tolak Pesanan (Reject)**.
2. Kasir **wajib** mengisi formulir **Alasan Penolakan** (contoh: *"Bukti transfer QRIS tidak masuk ke mutasi bank"*, *"Tamu membatalkan sepihak"*).
3. Klik **Konfirmasi Tolak**.
4. Sistem otomatis membatalkan pesanan, melepas digit unik QRIS kembali ke pool, dan mencatat nama kasir serta alasan penolakan ke log audit restoran. Dapur tidak akan memproses pesanan ini.

### 5.5. Prosedur Pembatalan Menu (Void Item & Void Order dengan Pelacakan Limbah)
Jika pesanan sudah lunas namun ada perubahan mendadak dari pelanggan:
* **Void Item (Batal Sebagian Menu)**:
  1. Klik tombol kuning **Void Item**.
  2. Pilih menu yang ingin dibatalkan dan ketikkan alasan pembatalan.
  3. **Evaluasi Dampak Limbah (Waste Tracking)**:
     * Jika item dibatalkan saat statusnya masih di antrean dapur (`queued`), sistem akan mengembalikan saldo omzet bersih secara otomatis.
     * Jika item dibatalkan saat makanan sudah mulai dimasak (`preparing`, `ready`, atau `served`), sistem **tidak memotong omzet** dan otomatis mencatat nilai menu tersebut sebagai **Biaya Kerugian Limbah Bahan (Waste Loss)**, karena bahan baku sudah terlanjur diolah di wajan dapur.
* **Void Order (Batal Seluruh Transaksi)**:
  * Klik tombol merah **Void Pesanan**, masukkan alasan resmi pembatalan. Seluruh tiket dapur otomatis ditarik dan sesi meja dibatalkan.

### 5.6. Cetak Ulang Struk & Kirim Ulang Struk WhatsApp
Di dalam halaman detail pesanan, kasir memiliki tombol aksi cepat:
* **Cetak Struk**: Mencetak ulang struk 80mm ke printer termal kasir.
* **Unduh Struk PDF**: Mengunduh salinan dokumen nota berformat PDF resmi.
* **Kirim Ulang Struk WA**: Mengirimkan ulang pesan WhatsApp nota pembayaran ke ponsel pelanggan jika pengiriman sebelumnya terkendala sinyal.

---

## Bab 6: Manajemen Meja & Denah Interaktif

> **Akses Navigasi**: Menu **Operasional** $\rightarrow$ **Meja**  
> **Hak Akses**: Kasir, Manajer Operasional, Owner.

```
┌────────────────────────────────────────────────────────────────────────┐
│ DENAH MEJA INTERAKTIF (Tabs: [ Area Indoor (12) ] [ Area Outdoor (8) ])│
│ [ Tombol: Edit Denah ]                 [ Tombol: Atur Otomatis (Grid) ]│
├────────────────────────────────────────────────────────────────────────┤
│ ┌──────────────┐     ┌──────────────┐     ┌──────────────┐             │
│ │   Meja 01    │     │   Meja 02    │     │   Meja 03    │             │
│ │  (4 Kursi)   │     │  (2 Kursi)   │     │  (6 Kursi)   │             │
│ │ 🟢 Tersedia  │     │ 🔵 Terisi    │     │ 🟠 Kotor     │             │
│ └──────────────┘     └──────────────┘     └──────────────┘             │
│                                                                        │
│ ┌──────────────┐     ┌──────────────┐     ┌──────────────┐             │
│ │   Meja 04    │     │   Meja 05    │     │   Meja 06    │             │
│ │  (2 Kursi)   │     │  (4 Kursi)   │     │  (4 Kursi)   │             │
│ │ 🟡 Pesan HP  │     │ 🟢 Tersedia  │     │ ⚫ Rusak     │             │
│ └──────────────┘     └──────────────┘     └──────────────┘             │
└────────────────────────────────────────────────────────────────────────┘
```

### 6.1. Membuat & Mengedit Master Meja Restoran
1. Buka menu **Operasional $\rightarrow$ Meja**.
2. Klik tombol **Buat (New Meja)**.
3. Isi formulir identitas meja:
   * **Nomor / Kode Meja**: Nama pengenal meja (contoh: `01`, `A1`, `VIP-1`, `Outdoor-3`). Kode ini harus unik per outlet.
   * **Kapasitas Kursi**: Jumlah kursi maksimal (misal: 2, 4, 8 orang).
   * **Area / Zona**: Pengelompokan ruangan (contoh: *Indoor*, *Outdoor*, *Lantai 2*, *VIP*).
4. Klik **Simpan**.

### 6.2. Menggunakan Visual Floor Plan Editor (Drag-and-Drop)
Anda dapat menyusun tata letak meja di layar agar persis seperti ruangan fisik restoran:
1. Klik tab area yang ingin diatur (misal: tab *Indoor*).
2. Klik tombol **Edit Denah** di bilah atas. Kanvas denah akan beralih ke mode edit interaktif.
3. Klik dan tahan kartu meja, lalu geser (*drag-and-drop*) ke posisi koordinat ruangan yang diinginkan.
4. Lepaskan mouse, sistem otomatis menyimpan posisi koordinat X dan Y meja tersebut secara instan.
5. **Fitur Atur Otomatis (Auto-Layout Grid)**: Jika posisi meja berantakan, klik tombol **Atur Otomatis**. Sistem akan merapikan seluruh meja ke dalam baris dan kolom kisi-kisi yang presisi dalam 1 detik.
6. Setelah selesai, klik tombol **Selesai Edit**.

### 6.3. Memahami 5 Siklus Warna Status Meja
Setiap kartu meja di denah memiliki indikator warna status cerdas yang bergerak secara otomatis:
* 🟢 **Available (Tersedia)**: Meja kosong, bersih, dan siap ditempati tamu baru.
* 🟡 **Claiming / Ordering (Memilih Menu)**: Tamu sudah duduk dan sedang memilih makanan di HP mereka.
* 🔵 **Occupied (Terisi / Makan)**: Pesanan tamu sudah dibayar dan hidangan sedang disiapkan atau dinikmati.
* 🟠 **Cleaning (Pembersihan)**: Tamu sudah selesai makan dan meninggalkan meja. Meja butuh dibersihkan oleh pelayan sebelum bisa ditempati kembali.
* ⚫ **Out of Service (Nonaktif)**: Meja sedang rusak atau ditutup sementara. Tamu tidak bisa memindai QR meja ini.

> [!TIP]
> Setelah pelayan selesai membersihkan meja yang kotor, pelayan/kasir cukup mengklik meja berstatus 🟠 *Cleaning* dan menekan tombol **Tandai Siap / Bersih**. Meja seketika berubah hijau 🟢 *Available*.

### 6.4. Mengunduh & Mencetak Stiker QR Meja (PNG & Vektor PDF)
Untuk mencetak stiker QR meja berkualitas tinggi:
1. Buka menu daftar meja.
2. Di setiap baris meja, klik tombol **Cetak QR**. Anda dapat memilih:
   * **Unduh PNG**: Format gambar satuan beresolusi tinggi untuk dicetak di percetakan akrilik.
   * **Unduh Vektor PDF**: Dokumen siap cetak yang memuat nomor meja tebal, instruksi scan, dan tanda batas potong (*crop marks*).
3. Anda juga dapat memilih seluruh meja (*Bulk Action*) dan mengklik **Cetak Seluruh Stiker Meja (Batch PDF)** untuk mencetak nomor meja 1 s/d 50 sekaligus dalam satu file PDF.

### 6.5. Regenerasi Token QR Meja Bertanda Tangan Kriptografis
Setiap QR meja dilindungi tanda tangan kriptografis HMAC untuk mencegah pengunjung iseng memalsukan nomor meja di URL browser.
* Jika ada stiker meja yang rusak, hilang, atau dicurigai disalahgunakan:
  1. Klik baris meja terkait di daftar meja.
  2. Klik aksi **Regenerasi Token QR**.
  3. Konfirmasi tindakan. Sistem seketika menerbitkan kode token baru.
  4. Unduh stiker QR baru dan tempelkan di meja fisik. Token lama otomatis tidak berlaku lagi tanpa memutus tamu yang sedang makan saat itu.

---

## Bab 7: Manajemen Kunjungan Tamu (Visit Sesi Meja)

> **Akses Navigasi**: Menu **Operasional** $\rightarrow$ **Visit**  
> **Hak Akses**: Kasir, Manajer Operasional.

### 7.1. Memantau Sesi Tamu Aktif & Batas Waktu Klaim (Claim TTL)
Menu Visit mencatat setiap sesi interaksi tamu di meja restoran:
* **Status Open**: Sesi tamu sedang berlangsung.
* **Waktu Klaim & Expired**: Waktu tamu mulai scan meja. Jika tamu memindai meja namun tidak kunjung membuat pesanan hingga melewati batas toleransi (*Claim TTL*, default 10 menit), sistem otomatis menutup sesi dan meja kembali kosong.

### 7.2. Solusi Tamu Lupa PIN: Prosedur Reset 4-Digit PIN Meja
Tamu pertama yang memindai QR meja akan menerima 4-digit PIN acak untuk dibagikan ke teman semeja agar bisa bergabung dalam satu keranjang belanja (*Shared Cart*).
* Jika tamu lupa PIN atau teman semeja salah memasukkan PIN berulang kali:
  1. Buka menu **Operasional $\rightarrow$ Visit**.
  2. Cari nomor meja terkait yang berstatus *Open*.
  3. Klik aksi **Reset PIN**.
  4. Sistem akan menampilkan nomor PIN baru (contoh: `8491`). Beritahukan nomor PIN ini kepada tamu di meja tersebut.

### 7.3. Koreksi Nomor WhatsApp Tamu
Jika tamu salah mengetikkan nomor ponselnya saat scan awal (sehingga struk WhatsApp tidak masuk):
1. Buka baris visit meja terkait di menu Visit.
2. Klik aksi **Ganti WA**.
3. Masukkan nomor WhatsApp tamu yang benar, lalu klik **Simpan**.

### 7.4. Prosedur Pindah Meja Tamu Dinamis
Jika tamu yang sedang makan meminta pindah meja (contoh: dari Meja 02 Indoor pindah ke Meja 08 Outdoor karena ingin merokok):
1. Buka baris visit meja lama di menu Visit.
2. Klik aksi **Pindah Meja**.
3. Pada pilihan **Meja Tujuan**, sistem hanya akan menampilkan daftar meja yang sedang kosong (*Available*).
4. Pilih meja baru, lalu klik **Konfirmasi Pindah**.
5. Seketika itu juga:
   * Seluruh tagihan pesanan, keranjang bersama, dan tiket KDS dapur berpindah ke meja baru.
   * Denah meja kasir langsung terbarui secara otomatis.
   * Meja lama otomatis kosong atau masuk ke status pembersihan (*Cleaning*).

### 7.5. Penutupan Sesi Meja (Close Visit & Status Cleaning)
Ketika tamu selesai bersantap dan meninggalkan restoran:
1. Klik aksi **Tutup Visit** pada meja terkait.
2. Sesi makan dinyatakan selesai (`closed`), keranjang belanja bersama ditutup, dan status meja fisik beralih ke warna oranye 🟠 *Cleaning* agar staf segera membersihkan meja.

---

# BAGIAN IV: OPERASIONAL DAPUR & BAR (BACK OF HOUSE)

## Bab 8: Kitchen Display System (Layar Operasional KDS)

> **Akses Navigasi**: Menu **Operasional** $\rightarrow$ **Dapur** (atau URL langsung: `/admin/slug-resto/kds`)  
> **Hak Akses**: Staf Dapur, Barista, Runner, Manajer Operasional, Owner.

```
┌────────────────────────────────────────────────────────────────────────┐
│ LAYAR DAPUR KDS (Tab: [ Hot Kitchen (8) ] [ Bar (3) ] [ Siap Antar (2) ])│
├────────────────────────────────────────────────────────────────────────┤
│ ┌──────────────────────┐  ┌──────────────────────┐  ┌─────────────────┐ │
│ │ MEJA 04   •  #ORD-089│  │ MEJA 01   •  #ORD-088│  │ MEJA 02  #ORD-87│ │
│ │ Tunggu: 4 Menit 🟢   │  │ Tunggu: 16 Menit 🟡  │  │ Tunggu: 24 Mnt 🔴│ │
│ ├──────────────────────┤  ├──────────────────────┤  ├─────────────────┤ │
│ │ 2x Nasi Goreng Pedas │  │ 1x Ayam Bakar Madu   │  │ 2x Sirloin Steak│ │
│ │    *Tanpa Acar       │  │    *Sambal Dipisah   │  │    *Well Done   │ │
│ │ 1x Gurame Bakar      │  │                      │  │                 │ │
│ ├──────────────────────┤  ├──────────────────────┤  ├─────────────────┤ │
│ │ [ Tombol: DIMASAK ]  │  │ [ Tombol: SIAP ANTAR]│  │ [TOMB: SELESAI] │ │
│ └──────────────────────┘  └──────────────────────┘  └─────────────────┘ │
└────────────────────────────────────────────────────────────────────────┘
```

### 8.1. Konfigurasi Layar Monitor/Tablet per Stasiun Kerja
Sistem KDS mendukung pembagian tampilan stasiun fisik kerja dapur:
* **Stasiun Kitchen (Dapur Panas)**: Khusus menampilkan hidangan makanan utama, tumisan, dan gorengan.
* **Stasiun Bar / Barista**: Khusus menampilkan hidangan kopi, jus, teh, dan minuman segar.
* **Stasiun Grill / Panggangan**: Khusus hidangan steak, sate, dan bakaran.
* Staf dapur cukup mengklik tab nama stasiun mereka di bagian atas layar KDS. Pesanan satu nota pelanggan yang berisi makanan dan minuman otomatis dipecah secara cerdas ke layar masing-masing bagian tanpa saling mengganggu.

### 8.2. Respon Bel Audio Dapur Otomatis (Kitchen Service Chime)
Setiap kali ada pesanan baru yang dibayar di kasir atau diverifikasi masuk ke stasiun dapur:
* Tablet dapur akan membunyikan nada lonceng (*Service Bell Chime*) secara otomatis.
* Polling sistem berjalan ringan setiap 5 detik di latar belakang.
* Koki tidak perlu terus-menerus menatap layar monitor. Koki cukup fokus memasak dan langsung sigap melihat layar ketika bel berdering.

### 8.3. Siklus Produksi 4 Tahap (Queued $\rightarrow$ Preparing $\rightarrow$ Ready $\rightarrow$ Served)
Staf dapur mengelola antrean memasak hanya dengan satu sentuhan jari di layar sentuh tablet:
1. **Antri (Queued - Abu-abu)**: Pesanan baru masuk dari kasir. Belum ada koki yang memegang wajan.
2. **Dimasak (Preparing - Biru)**: Koki mulai mengambil bahan dan menyalakan kompor. Klik tombol **Dimasak**. Status berubah biru, mengabarkan ke HP pelanggan bahwa makanannya sedang dimasak.
3. **Siap Antar (Ready - Kuning Emas)**: Makanan sudah matang di piring saji pasing dapur. Klik tombol **Siap Antar**. Kartu hidangan otomatis berpindah ke tab *Siap Antar*.
4. **Sudah Diantar (Served - Hijau)**: Pelayan atau runner telah mengambil piring dan mengantarkannya ke meja pelanggan. Klik tombol **Selesai / Sudah Diantar**. Kartu menghilang dari antrean dapur.

### 8.4. Indikator Waktu Tunggu Visual (Aging Timer Band Warna)
Setiap kartu pesanan di KDS dilengkapi timer waktu tunggu yang dihitung sejak pembayaran diterima di server:
* 🟢 **Hijau (&lt; 10 Menit)**: Waktu tunggu aman sesuai standar operasional.
* 🟡 **Kuning (10–20 Menit)**: Waktu tunggu mulai lama. Perlu perhatian koki.
* 🔴 **Merah (&gt; 20 Menit)**: Waktu tunggu kritis! Kartu berkedip menjadi alarm visual bagi kepala koki untuk segera memprioritaskan hidangan tersebut sebelum tamu komplain.

### 8.5. Membaca Modifikasi Item, Varian, & Catatan Khusus
Setiap kartu di layar KDS menampilkan informasi terstruktur:
* **Jumlah Porsi & Nama Menu**: Tercetak dengan huruf tebal berukuran besar (contoh: `2× Kwetiau Goreng Sapi`).
* **Varian**: Ditampilkan di bawah nama menu (contoh: `Porsi Jumbo`).
* **Modifier / Add-ons**: Ditampilkan dengan tanda tambah (contoh: `+ Extra Telur Dadar`).
* **Catatan Khusus Tamu**: Ditampilkan dengan warna kontras (contoh: `Catatan: Jangan pakai tauge dan daun bawang, anak kecil yang makan`). Hal ini menghilangkan kesalahan saji akibat koki lupa membaca instruksi pelayan.

### 8.6. Tab Khusus "Siap Antar" untuk Staf Runner / Waiter
Pelayan atau staf runner FOH memiliki tab khusus bertuliskan **Siap Antar (Ready Tab)**:
* Begitu koki menekan tombol *Siap Antar*, hidangan muncul di tab ini lengkap dengan nomor meja pelanggan.
* Pelayan langsung menuju meja pasing dapur, mencocokkan nomor meja, membawa makanan ke meja tamu, lalu menekan tombol **Sudah Diantar**. Dapur menjadi tenang tanpa perlu koki berteriak memanggil pelayan.

---

# BAGIAN V: MANAJEMEN MENU & PRODUK

## Bab 9: Manajemen Menu, Varian, Modifier & Stasiun

> **Akses Navigasi**: Menu **Menu** $\rightarrow$ **Kategori menu**, **Item menu**, **Grup modifier**, & **Stasiun dapur**  
> **Hak Akses**: Owner, Manajer Operasional.

### 9.1. Mengelola Kategori Menu
Kategori digunakan untuk mengelompokkan makanan pada katalog digital pelanggan dan layar kasir POS:
1. Buka menu **Menu $\rightarrow$ Kategori menu**.
2. Klik **Buat (New Category)**.
3. Masukkan **Nama Kategori** (contoh: *Makanan Utama*, *Minuman Dingin*, *Camilan*, *Paket Hemat*).
4. Tentukan **Urutan (Sort Order)**: Angka lebih kecil akan tampil paling depan di menu.
5. Pastikan toggle **Aktif** menyala. Klik **Simpan**.

### 9.2. Menambah & Mengedit Item Menu Master
1. Buka menu **Menu $\rightarrow$ Item menu**. Klik **Buat (New Menu)**.
2. **Identitas Menu**:
   * **Nama**: Nama hidangan (contoh: `Nasi Goreng Kampung`).
   * **Kategori**: Pilih kategori menu.
   * **Stasiun KDS**: Tentukan pesanan ini lari ke layar dapur mana (misal: *Hot Kitchen* atau *Bar*).
   * **Deskripsi**: Penjelasan bahan dan rasa hidangan yang menggugah selera pelanggan.
3. **Harga & Ketersediaan**:
   * **Harga**: Masukkan harga jual standar (Rp).
   * **Potongan Harga (%)**: Masukkan persentase diskon promosi jika sedang diskon.
   * **Urutan**: Posisi susunan menu di daftar.
   * **Toggle Best Seller**: Aktifkan untuk memberikan badge bintang *Favorit / Best Seller* di katalog digital tamu.

### 9.3. Pengaturan Foto Menu Berkualitas (Standar Rasio 4:3)
Foto yang menarik dapat meningkatkan penjualan hidangan hingga 40%:
* Unggah **Foto Utama**: Sistem mewajibkan foto berasio **4:3**. Fitur pemotong otomatis (*integrated image cropper*) akan membantu Anda menyesuaikan posisi foto secara sempurna. Ukuran file maksimal 15 MB (otomatis dikompresi menjadi format modern WEBP yang sangat ringan).
* Unggah **Foto Tambahan**: Anda dapat menambahkan hingga 3 foto galeri tambahan untuk memperlihatkan sudut hidangan lain.

### 9.4. Manajemen Ketersediaan: Toggle Aktif vs Toggle Stok Habis (Out of Stock)
Jika bahan baku hidangan habis mendadak di tengah jam operasional:
* Buka menu **Item menu**, cari menu yang bersangkutan.
* Aktifkan toggle **Stok Habis (Out of Stock)**:
  * **Efek pada Pelanggan**: Menu tetap terlihat di katalog digital, namun diberi label merah *"Stok Habis"* dan tombol pesan dinonaktifkan. Tamu tetap tahu restoran memiliki menu tersebut tanpa merasa kecewa pesanan mereka dibatalkan belakangan.
* Jika menu memang ditarik permanen dari peredaran, matikan toggle **Aktif di Menu**.

### 9.5. Konfigurasi Varian Menu (Porsi / Ukuran dengan Selisih Harga)
Varian digunakan jika hidangan memiliki pilihan ukuran dasar:
* Di formulir menu, gulir ke seksi **Varian**.
* Klik **Tambah Varian**:
  * Contoh Varian 1: Nama `Regular`, Selisih Harga: `Rp 0`.
  * Contoh Varian 2: Nama `Large (Jumbo)`, Selisih Harga: `Rp 10.000`.
* Saat tamu memilih varian *Large*, sistem otomatis menambahkan Rp 10.000 ke harga dasar menu.

### 9.6. Konfigurasi Grup Modifier (Topping & Add-ons dengan Aturan Min/Max)
Modifier digunakan untuk opsi tambahan atau preferensi selera:
1. Buka menu **Menu $\rightarrow$ Grup modifier**. Klik **Buat (New Modifier Group)**.
2. Tentukan **Aturan Grup**:
   * **Nama Grup**: Contoh `Level Pedas` atau `Pilihan Topping`.
   * **Minimal Pilih**: Isi `1` jika wajib dipilih, isi `0` jika opsional.
   * **Maksimal Pilih**: Isi `1` untuk pilihan tunggal (radio), atau isi `3` jika tamu boleh memilih hingga 3 jenis topping.
   * **Toggle Wajib Dipilih**: Aktifkan jika tamu tidak boleh melewati opsi ini sebelum masuk keranjang.
3. Masukkan **Pilihan Extra (Daftar Modifier)**:
   * Contoh 1: Nama `Pedas Sedang`, Harga Tambahan: `Rp 0`.
   * Contoh 2: Nama `Extra Keju Mozzarella`, Harga Tambahan: `Rp 6.000`.
   * Contoh 3: Nama `Extra Telur Ceplok`, Harga Tambahan: `Rp 5.000`.
4. Hubungkan grup modifier ini ke item menu yang sesuai pada halaman master menu.

### 9.7. Mengelola Stasiun Kerja KDS
Di menu **Menu $\rightarrow$ Stasiun dapur**, Anda dapat menambah stasiun baru (misal: *Stasiun Bakery / Dessert*). Stasiun ini yang nantinya dipilih pada setiap item menu agar pembagian alur masak terdistribusi otomatis.

---

# BAGIAN VI: CRM, LOYALITAS & PEMASARAN

## Bab 10: CRM Pelanggan, Sistem Poin Loyalitas & Ulasan Tamu

> **Akses Navigasi**: Menu **CRM & Pelanggan** $\rightarrow$ **Pelanggan**, **Ulasan & Rating**, & **Analitik CRM**  
> **Hak Akses**: Owner, Manajer Operasional.

### 10.1. Mengakses Database Pelanggan Otomatis via WhatsApp
Setiap kali tamu bertransaksi melalui scan QR atau kasir POS dan memasukkan nomor ponsel, sistem secara otomatis merekam profil mereka ke database CRM restoran tanpa perlu formulir pendaftaran fisik:
* **Nama & Nomor WhatsApp**: Dilengkapi tombol salin kontak satu klik.
* **Tier Member**: Lencana tingkatan loyalitas tamu (Reguler, Silver, Gold, VIP).
* **Total Kunjungan**: Frekuensi kedatangan tamu ke restoran.
* **Total Akumulasi Belanja (LTV / Customer Lifetime Value)**: Total rupiah yang telah dibelanjakan tamu sepanjang masa.
* **Saldo Poin Aktif**: Sisa poin loyalitas yang siap ditukarkan.
* **Kunjungan Terakhir**: Indikator kapan terakhir kali tamu bersantap di resto Anda.

### 10.2. Pengaturan Program Poin & Tingkatan Member (Reguler, Silver, Gold, VIP)
Untuk mengatur aturan loyalitas, buka menu **CRM & Pelanggan $\rightarrow$ Analitik CRM**, lalu klik tombol **Pengaturan Poin & Tier**:
1. Nyalakan toggle **Aktifkan Program Poin & Tier Member**.
2. **Ketentuan Perolehan Poin**:
   * **Kelipatan Belanja**: Masukkan nominal belanja dasar (contoh: `Rp 10.000`).
   * **Poin yang Didapat**: Masukkan jumlah poin (contoh: `1 Poin`).
   * *Artinya: Tamu belanja Rp 100.000 akan otomatis memperoleh 10 poin loyalitas.*
3. **Ambang Batas Tingkatan Member (Tiering)**:
   * **Silver**: Minimal total belanja kumulatif (contoh: `Rp 500.000`).
   * **Gold**: Minimal total belanja kumulatif (contoh: `Rp 1.500.000`).
   * **VIP**: Minimal total belanja kumulatif (contoh: `Rp 5.000.000`).

### 10.3. Pengaturan Kurs Penukaran Poin ke Diskon Tagihan (Point-to-Discount)
Tamu dapat menukarkan poin reward menjadi potongan harga langsung saat memesan di kasir atau di HP meja:
* **Nilai 1 Poin (Rp)**: Tentukan kurs rupiah per 1 poin (contoh: `1 Poin = Rp 1.000`).
* **Minimal Poin per Redeem**: Ambang minimal saldo poin agar bisa ditukarkan (contoh: minimal `10 Poin`).
* **Batas Maksimal Diskon (%)**: Batasi maksimal pemotongan dari subtotal tagihan (contoh: maksimal `50%`) agar restoran tidak merugi.

### 10.4. Moderasi Ulasan & Rating Bintang Tamu (Analisis Sentimen Otomatis)
Di menu **CRM & Pelanggan $\rightarrow$ Ulasan & Rating**:
* Tamu yang menyelesaikan sesi makan dapat memberikan rating bintang (1–5) dan komentar masukan langsung di HP mereka.
* **Analisis Sentimen Otomatis**: Sistem melabeli ulasan dengan warna:
  * 🟢 **Positif**: Bintang 4 dan 5.
  * 🟡 **Netral**: Bintang 3.
  * 🔴 **Negatif**: Bintang 1 dan 2.
* **Tampilkan di Landing Page**: Anda dapat mengaktifkan toggle **Tampilkan** pada ulasan-ulasan berbintang 5 terbaik agar otomatis terpampang di website publik restoran Anda untuk membangun kepercayaan calon pelanggan baru.

### 10.5. Memantau Metrik Kepuasan Tamu (Customer Satisfaction / CSAT)
Di menu **Analitik Kepuasan**, pantau skor kepuasan rata-rata restoran Anda (*Customer Satisfaction Score / CSAT*), total ulasan masuk bulan ini, dan grafik distribusi bintang untuk mengevaluasi kinerja staf dapur dan pelayanan resto Anda.

---

## Bab 11: Pengelolaan Website Profil Restoran (CMS)

> **Akses Navigasi**: Menu **CMS** $\rightarrow$ **Profil restoran**, **Layout landing**, **Banner promo**, **Galeri foto**, & **FAQ**  
> **Hak Akses**: Owner, Manajer Operasional.

### 11.1. Profil Restoran, Logo, Favicon, & Warna Brand
Buka menu **CMS $\rightarrow$ Profil restoran**:
* **Identitas**: Nama restoran, slug URL website publik (contoh: `namadomain.com/resto-nusantara`), slogan/tagline, dan bio deskripsi singkat restoran.
* **Logo & Favicon**: Unggah logo resmi berlatar belakang transparan (PNG). Logo ini otomatis tampil di header website publik, favicon browser, dan header struk kasir termal.
* **Warna Brand Kustom**: Pilih warna **Primer** dan **Aksen** sesuai identitas restoran Anda (menggunakan pemilih warna visual / kode HEX).

### 11.2. Memilih Tema Desain Publik: Classic, Foodie, atau Glassmorphism
Sistem menyediakan 3 pilihan arsitektur visual modern untuk website profil restoran Anda:
1. **Classic Template**: Tampilan bersih, elegan, dan profesional. Sangat cocok untuk restoran keluarga, bistro, dan fine dining.
2. **Foodie Template**: Tampilan berani dengan warna-warna cerah dan fokus visual besar pada foto hidangan lezat. Sempurna untuk cafe kekinian dan resto cepat saji.
3. **Glassmorphism Template**: Tampilan ultra-modern mewah dengan aksen kaca tembus pandang (*frosted glass*). Sangat cocok untuk bar, lounge, dan coffee shop estetik.

### 11.3. Jam Operasional Mingguan & Fasilitas Restoran
* **Jam Buka / Tutup**: Tentukan jam operasional per hari dari Senin hingga Minggu. Centang opsi *Libur* jika restoran tutup pada hari tertentu. Di website publik, sistem otomatis menampilkan lencana cerdas: **Buka Sekarang (Open Now)** atau **Tutup Hari Ini (Closed Today)**.
* **Fasilitas Restoran**: Centang fasilitas yang tersedia di resto Anda (Wi-Fi Cepat, AC Dingin, Parkir Luas, Musholla, Smoking Area, Kursi Bayi, Colokan Listrik). Ikon fasilitas ini akan tampil rapi di website publik.

### 11.4. Integrasi WhatsApp Restoran Resmi (Fonnte API Token)
Agar pengiriman struk belanja digital dan notifikasi promo berasal langsung dari nomor WhatsApp resmi restoran Anda:
1. Daftarkan nomor WhatsApp restoran Anda di penyedia gateway Fonnte (`fonnte.com`).
2. Masukkan **API Token Fonnte** pada seksi *WhatsApp (Fonnte)* di menu Profil Restoran.
3. Klik **Simpan**. Seluruh struk nota pelanggan akan otomatis dikirim menggunakan nomor WhatsApp restoran Anda sendiri.

### 11.5. Tata Letak Seksi Halaman Depan dengan Antarmuka Drag-and-Drop
Buka menu **CMS $\rightarrow$ Layout landing**:
* Anda dapat mengatur susunan urutan seksi di website profil publik resto semudah menggeser kartu:
  * Seksi Hero Banner Utama
  * Seksi Menu Unggulan & Katalog
  * Seksi Galeri Foto Suasana
  * Seksi Ulasan & Testimoni Pelanggan
  * Seksi Petunjuk Cara Pesan
  * Seksi Lokasi Maps & Jam Buka
  * Seksi Tanya Jawab (FAQ)
* Anda juga dapat menonaktifkan seksi tertentu yang belum ingin ditampilkan ke publik hanya dengan mematikan toggle aktifnya.

### 11.6. Banner Promosi Berjadwal Otomatis
Buka menu **CMS $\rightarrow$ Banner promo**:
* Unggah poster promosi (contoh: *Promo Diskon Akhir Pekan 20%*, *Menu Spesial Ramadhan*).
* Tentukan **Tanggal Mulai Tayang** dan **Tanggal Berakhir Tayang**.
* **Keunggulan**: Sistem otomatis menayangkan banner saat tanggal mulai tiba, dan otomatis menyembunyikan banner saat tanggal promo berakhir. Anda tidak perlu khawatir lupa menurunkan banner promo yang sudah basi!

### 11.7. Galeri Foto Restoran & Tanya Jawab Pelanggan (FAQ)
* **Galeri Foto**: Unggah foto sudut interior estetik, area VIP room, dan kehangatan pelayanan resto Anda.
* **FAQ**: Tuliskan jawaban atas pertanyaan yang sering diajukan pelanggan (misal: *"Apakah makanan di sini halal?"*, *"Apakah bisa reservasi untuk acara ulang tahun?"*, *"Apakah menerima pembayaran kartu kredit?"*).

---

# BAGIAN VII: LAPORAN FINANSIAL & PUSAT DATA

## Bab 12: Pusat Ekspor Laporan Finansial & Operasional

> **Akses Navigasi**: Menu **Laporan** $\rightarrow$ **Buat laporan**, **Riwayat ekspor**, & **Rekonsiliasi komisi**  
> **Hak Akses**: Owner, Manajer Operasional, Tim Akuntansi.

### 12.1. Membuat Laporan Finansial & Operasional (Excel & PDF)
Buka menu **Laporan $\rightarrow$ Buat laporan**. Pilih salah satu dari 5 modul laporan resmi:
1. **Ringkasan Omzet Harian (Daily Turnover Summary)**:
   * Berisi rekapitulasi penjualan harian, pembagian omzet tunai vs non-tunai (QRIS), total pajak PB1 terkumpul, dan total service charge.
2. **Rincian Penjualan Item (Item Sales Report)**:
   * Berisi detail setiap porsi makanan yang terjual, jumlah kuantitas, varian yang dipilih, ekstra modifier, dan kontribusi pendapatan kotor per menu.
3. **Rincian Penjualan Order (Order Sales Transaction Report)**:
   * Berisi buku register seluruh transaksi kasir: nomor nota pesanan, nama tamu, nomor meja, kasir penanggung jawab, waktu transaksi, metode bayar, dan total tagihan.
4. **Laporan Audit Void & Limbah Makanan (Void & Waste Audit Report)**:
   * Berisi rekapitulasi makanan yang dibatalkan, nama staf yang membatalkan, alasan pembatalan, status masakan saat dibatalkan, dan estimasi nominal rupiah bahan makanan yang terbuang (*waste loss*).
5. **Katalog Master Menu**:
   * Berisi daftar seluruh menu aktif, harga jual, kategori, dan status stok.

**Pengaturan Format & Periode**:
* Pilih Format: **Excel (.xlsx)** untuk keperluan olah data akuntansi/pajak, atau **PDF** untuk dokumen laporan resmi pimpinan.
* Tentukan rentang tanggal laporan, lalu klik tombol **Proses Ekspor Laporan**.

### 12.2. Mengunduh Dokumen di Menu Riwayat Ekspor
Pemrosesan laporan berjalan di latar belakang (*Queue Worker*) sehingga Anda tidak perlu menunggu layar memuat jika data transaksi sangat besar:
1. Buka menu **Laporan $\rightarrow$ Riwayat ekspor**.
2. Anda akan melihat daftar file laporan yang pernah diminta.
3. Jika status file sudah bertanda hijau **Selesai (Completed)**, klik tombol **Unduh File (Download)** untuk mengunduh dokumen langsung ke komputer Anda.

### 12.3. Rekonsiliasi Penjualan & Komisi Platform
Bagi restoran yang menggunakan skema komisi platform atau kemitraan bagi hasil:
* Buka menu **Laporan $\rightarrow$ Rekonsiliasi komisi**.
* Pilih bulan buku yang ingin diperiksa.
* Layar menampilkan rekapitulasi: Total Omzet Penjualan Kotor, Penjualan Bersih, Potongan Komisi Platform (jika ada), dan Saldo Bersih Pembayaran.
* Dokumen rekonsiliasi dapat diekspor ke Excel untuk ditandatangani bersama.

---

# BAGIAN VIII: PENGATURAN CABANG, KEAMANAN & KARYAWAN

## Bab 13: Konfigurasi Outlet & Mode Operasional

> **Akses Navigasi**: Menu **Pengaturan** $\rightarrow$ **Outlet**  
> **Hak Akses**: Owner, Manajer Operasional.

### 13.1. Pengaturan Pajak PB1 & Service Charge
Di seksi *Pajak & antrian kasir*:
* **PB1 (%)**: Pajak restoran daerah resmi (standar 10%). Masukkan angka `0` jika restoran Anda belum memungut pajak PB1.
* **Service Charge (%)**: Biaya layanan restoran untuk staf (contoh: `5%`).
* Seluruh perhitungan dihitung otomatis secara *exclusive* pada keranjang belanja kasir dan HP pelanggan.

### 13.2. Penentuan Titik Koordinat GPS & Radius Geofencing Tunai
Di seksi *Lokasi*:
* Masukkan **Latitude** dan **Longitude** titik lokasi bangunan restoran Anda (dapat disalin dari Google Maps).
* **Radius Geofence (Meter)**: Batas jarak toleransi smartphone tamu untuk diizinkan memilih metode pembayaran tunai (rekomendasi: `30` hingga `50` meter).
* **Akurasi GPS Maksimal**: Batas toleransi sinyal satelit GPS (rekomendasi: `50` meter). Fitur ini menjamin pesanan tunai hanya bisa dipesan oleh tamu yang benar-benar berada di dalam ruangan resto Anda.

### 13.3. Mengunggah Kode QRIS Statis Outlet
Di seksi *QRIS statis*:
* Unggah gambar barcode QRIS resmi outlet Anda (dari BCA, Mandiri, BRI, ShopeePay, GoPay, Nobu, dll.).
* Gunakan rasio gambar kotak **1:1** dengan barcode berada tepat di tengah.
* Gambar QRIS ini yang akan ditampilkan di layar HP tamu saat checkout QRIS mandiri dan di layar kasir POS.

### 13.4. Batas Waktu Operasional (Claim TTL & Awaiting Cashier TTL)
* **TTL Klaim Meja (Claim TTL)**: Batas waktu menit setelah tamu memindai QR meja. Jika tamu tidak melakukan pemesanan hingga waktu habis (default: `10` menit), meja otomatis dibebaskan kembali ke status *Available*.
* **TTL Antrian Kasir (Awaiting Cashier TTL)**: Batas waktu menit bagi kasir untuk merespons pesanan tamu. Jika kasir mengabaikan pesanan hingga waktu habis (default: `20` menit), pesanan otomatis dibatalkan sistem demi keamanan dan digit unik dilepas.

### 13.5. Pemilihan Mode Operasional: Simple Mode vs Standar Mode KDS
Restoran Anda memiliki karakteristik unik. Sistem ini menyediakan fleksibilitas penuh melalui tombol toggle **Mode Kasir Sederhana (Simple Mode / Prasmanan)** di menu Pengaturan Outlet:

| Aspek Operasional | 🍳 Standar Mode (Full KDS Dapur) | ⚡ Simple Mode (Prasmanan / Cepat) |
|---|---|---|
| **Karakter Usaha** | Restoran keluarga, casual dining, cafe besar, steakhouse. | Coffee shop kilat, booth boba, fast food, bakery, prasmanan, food court. |
| **Keberadaan Koki/Barista** | Ada tim dapur/bar terpisah yang memantau pesanan. | Tanpa staf dapur khusus (kasir merangkap peracik / makanan siap saji). |
| **Layar Dapur (KDS)** | Wajib dibuka di tablet dapur per stasiun masak. | Tidak wajib dibuka (dapur di-bypass otomatis). |
| **Status Pesanan Setelah Bayar** | Masuk antrean dapur berstatus Antri (`queued`) $\rightarrow$ Dimasak $\rightarrow$ Siap Antar $\rightarrow$ Disajikan. | Otomatis berstatus Selesai & Disajikan (`served`) seketika pembayaran diterima kasir. |
| **Dampak pada Meja & Denah** | Meja berstatus Terisi (`Occupied`) hingga tamu selesai makan dan pelayan menutup visit meja. | Meja langsung dibebaskan atau visit meja tamu otomatis ditutup sistem 5 menit setelah selesai. |
| **Perilaku di Layar Kasir POS** | Pesanan masuk antrean operasional; kasir memantau status masak. | Pop-up pesanan selesai langsung muncul, siap melayani antrean berikutnya tanpa hambatan. |
| **Perilaku Self-Order Tamu via QR** | Tamu memantau live status masak di HP (Menunggu $\rightarrow$ Dimasak $\rightarrow$ Diantar). | Tamu langsung melihat status pesanan selesai dan struk pembayaran. |

> [!TIP]
> **Cara Beralih Mode**: Cukup buka menu **Pengaturan $\rightarrow$ Outlet**, cari seksi *Pajak & antrian kasir*, lalu nyalakan atau matikan toggle **Mode Kasir Sederhana (Simple Mode / Prasmanan)**, kemudian klik **Simpan Perubahan**. Perubahan berlaku seketika tanpa perlu restart perangkat atau migrasi data!

### 13.6. Otomatisasi Dialog Cetak Struk
* **Cetak Struk Otomatis (Auto-Print)**: Jika toggle ini diaktifkan, setiap kali kasir menekan tombol *Terima Pembayaran*, dialog cetak printer termal 80mm akan otomatis terbuka tanpa kasir perlu mengklik tombol cetak manual lagi. Sangat mempercepat kerja kasir saat jam sibuk.

---

## Bab 14: Manajemen Karyawan, Hak Akses & Keamanan

> **Akses Navigasi**: Menu **Pengaturan** $\rightarrow$ **Staf**, **Log aktivitas**, & **Struk WhatsApp**  
> **Hak Akses**: Owner.

### 14.1. Menambah Karyawan Baru & Pengaturan Avatar
1. Buka menu **Pengaturan $\rightarrow$ Staf**. Klik **Buat (New Staf)**.
2. Masukkan **Nama Lengkap**, **Username unik**, dan **Alamat Email**.
3. Buat **Kata Sandi (Password)** awal untuk karyawan tersebut.
4. Unggah foto avatar profil staf (rasio 1:1 bulat).

### 14.2. Pembagian Hak Akses Berdasarkan Peran (Owner, Kasir, Dapur)
Pilih salah satu peran pada kolom **Peran (Role)**:
* **Kasir**: Memiliki hak akses ke menu *Order Kasir*, *Pesanan*, *Shift Kasir*, *Meja*, dan *Visit*. Tidak bisa melihat laporan keuangan omzet pemilik atau mengubah master harga menu.
* **Dapur**: Khusus memiliki hak akses ke layar *Kitchen Display System (KDS)* dan pengubahan status masakan. Tidak bisa membuka kasir atau melihat data pelanggan.
* **Owner**: Pemilik restoran dengan kendali penuh atas seluruh modul, pengaturan keuangan, master menu, laporan omzet, dan manajemen staf.

### 14.3. Menonaktifkan Akses Staf yang Sudah Berhenti
Jika ada karyawan yang mengundurkan diri atau diberhentikan:
* Buka akun staf yang bersangkutan di menu Staf.
* Matikan toggle **Aktif**. Klik **Simpan**.
* Sesi login karyawan tersebut seketika dicabut oleh sistem dan mereka tidak akan bisa masuk ke panel admin lagi. Data riwayat transaksi lama yang pernah mereka layani tetap tersimpan utuh di laporan.

### 14.4. Membaca Log Aktivitas (Immutable Audit Trail)
Buka menu **Pengaturan $\rightarrow$ Log aktivitas**:
* Sistem mencatat seluruh tindakan sensitif staf secara otomatis ke dalam buku catatan audit yang **tidak bisa diedit dan tidak bisa dihapus** oleh siapa pun (*Append-Only*).
* Setiap baris log memuat:
  * Nama staf penanggung jawab.
  * Waktu kejadian hingga satuan milidetik.
  * Alamat IP perangkat yang digunakan.
  * Deskripsi aksi (contoh: `order.approve`, `order.reject`, `order.void_item`, `cashier_shift.open`, `cashier_shift.close`, `table.move`).
  * Nilai data sebelum diubah (*old values*) dan nilai data sesudah diubah (*new values*).
* Pemilik resto dapat memeriksa menu ini jika terjadi selisih kas mencurigakan atau pembatalan menu yang janggal.

### 14.5. Memantau Log Pengiriman Struk WhatsApp
Buka menu **Pengaturan $\rightarrow$ Struk WhatsApp**:
* Memantau seluruh antrean pengiriman pesan nota digital ke ponsel tamu.
* Kolom status menampilkan apakah pesan dalam antrean (`queued`), berhasil terkirim (`sent`), atau gagal (`failed` jika nomor tamu tidak aktif).
* Tersedia tombol aksi **Kirim Ulang** untuk mencoba mengirimkan kembali struk yang gagal.

### 14.6. Memulihkan Data Terhapus dari Tempat Sampah (Trash Recovery)
Sistem dilengkapi proteksi penghapusan lunak (*Soft Delete*):
* Jika staf tidak sengaja menghapus item menu, meja makan, modifier, foto galeri, atau akun karyawan:
  * Buka halaman master terkait, lalu klik tombol **Tempat Sampah (Trash)** di pojok kanan atas.
  * Cari data yang terhapus, lalu klik tombol **Pulihkan (Restore)**.
  * Data seketika kembali aktif seperti sedia kala tanpa merusak relasi histori transaksi sebelumnya!
  * Hanya gunakan tombol *Hapus Permanen (Force Delete)* jika Anda benar-benar yakin ingin memusnahkan data tersebut selamanya.

---

## Bab 15: Manajemen Langganan Restoran & Profil Akun

> **Akses Navigasi**: Menu **Pengaturan** $\rightarrow$ **Langganan** & **Profil saya**  
> **Hak Akses**: Owner (untuk langganan) & Seluruh Pengguna (untuk profil pribadi).

### 15.1. Memeriksa Status Paket Langganan & Masa Aktif
Buka menu **Pengaturan $\rightarrow$ Langganan**:
* Menampilkan informasi paket langganan Anda saat ini (contoh: *Paket Full Operasional POS & KDS*).
* Menampilkan tanggal jatuh tempo masa aktif langganan (*Subscription Renewal Date*).
* Menampilkan daftar kuota fitur yang aktif (Akses POS Kasir, Akses Layar Dapur KDS, Akses CMS Website Publik, Akses CRM Loyalitas).

### 15.2. Perpanjangan Langganan, Penerbitan Invoice, & Upload Bukti Transfer
Untuk memperpanjang masa aktif langganan:
1. Klik tombol **Perpanjang Langganan** atau **Upgrade Paket**.
2. Pilih durasi langganan (Bulanan atau Tahunan).
3. Sistem secara otomatis menerbitkan **Nomor Invoice Tagihan Resmi**.
4. Lakukan transfer bank ke nomor rekening resmi platform pengembang yang tertera di layar.
5. Klik aksi **Unggah Bukti Pembayaran**, lampirkan foto struk transfer m-banking Anda.
6. Admin platform akan memverifikasi pembayaran dan masa aktif paket Anda seketika bertambah.

### 15.3. Pemahaman Mode Masa Tenggang (Grace Period) & Read-Only Mode
* Jika jatuh tempo terlewati dan pembayaran belum diselesaikan, sistem memberikan toleransi masa tenggang (*Grace Period* selama beberapa hari). Operasional restoran tetap berjalan normal dengan spanduk peringatan di layar.
* Jika masa tenggang habis, panel admin beralih ke **Mode Hanya Lihat (Read-Only)**. Staf masih bisa melihat riwayat laporan dan data menu, namun pembuatan pesanan baru dinonaktifkan sementara hingga tagihan diselesaikan. Data restoran Anda dijamin 100% aman dan tidak akan dihapus.

### 15.4. Pengaturan Profil Akun Pengguna Pribadi
Setiap staf yang sedang login dapat mengklik nama mereka di pojok kanan atas layar dan memilih menu **Profil Saya**:
* Memperbarui nama lengkap dan alamat email.
* Mengubah kata sandi login pribadi (masukkan kata sandi lama, lalu ketikkan kata sandi baru).

---

# BAGIAN IX: STANDAR OPERASIONAL PROSEDUR (SOP RINGKAS HARIAN)

## Bab 16: Checklist Standar Operasional Harian Restoran

Gunakan lembar panduan ringkas ini sebagai panduan operasional harian bagi seluruh staf Anda:

```
┌────────────────────────────────────────────────────────────────────────┐
│ CHECKLIST OPERASIONAL HARIAN RESTORAN                                  │
├────────────────────────────────────────────────────────────────────────┤
│ [PAGI] BUKA RESTORAN (09:00 - 10:00)                                   │
│ [ ] 1. Nyalakan komputer kasir, hubungkan printer termal 80mm & Wi-Fi. │
│ [ ] 2. Login ke Panel Admin, pastikan uji suara notifikasi berbunyi.   │
│ [ ] 3. Kasir buka menu Order Kasir: Buka Shift & input Modal Awal laci.│
│ [ ] 4. Dapur nyalakan tablet KDS, pilih tab stasiun kerja (Bar/Kitchen)│
│ [ ] 5. Periksa stok menu: Aktifkan "Stok Habis" jika ada bahan kosong. │
│ [ ] 6. Pastikan stiker akrilik QR Code meja bersih & terpasang rapi.   │
├────────────────────────────────────────────────────────────────────────┤
│ [SIANG & MALAM] JAM OPERASIONAL SIBUK (RUSH HOUR)                      │
│ [ ] 1. Sambut tamu, arahkan duduk di meja & scan QR di smartphone.     │
│ [ ] 2. Kasir sigap saat bel berdering: Verifikasi pembayaran tamu.     │
│ [ ] 3. Dapur pantau KDS: Ubah status ke Dimasak -> Siap Antar.         │
│ [ ] 4. Runner ambil makanan di pasing dapur & antar ke meja tamu.      │
│ [ ] 5. Kasir layani tamu manual/takeaway lewat panel Order Kasir POS.  │
│ [ ] 6. Pantau denah meja: Bersihkan meja kotor & ubah status ke Siap.  │
├────────────────────────────────────────────────────────────────────────┤
│ [MALAM] TUTUP RESTORAN & TUTUP BUKU                                    │
│ [ ] 1. Pastikan seluruh transaksi meja sudah selesai & visit ditutup.  │
│ [ ] 2. Kasir lakukan Blind Cash Count: Hitung uang fisik dalam laci.   │
│ [ ] 3. Kasir klik Tutup Shift, input uang fisik, cetak slip rekap X/Z. │
│ [ ] 4. Serahkan uang fisik & slip rekap shift kepada Manajer/Owner.    │
│ [ ] 5. Owner/Manajer cek dasbor omzet harian & ekspor laporan Excel.   │
│ [ ] 6. Matikan tablet KDS, printer kasir, dan logout dari sistem.      │
└────────────────────────────────────────────────────────────────────────┘
```

---
*Dokumentasi Buku Panduan ini disusun secara komprehensif berdasarkan basis arsitektur riil Walk-In Resto Panel Admin. Dokumen ini dapat dicetak (*Print to PDF*) atau dibagikan langsung sebagai modul pelatihan resmi bagi staf dan mitra klien restoran.*
