# 📱 Rencana Komprehensif: Perbaikan Tampilan Responsif Mobile Direktori Restoran

Dokumen ini berisi analisis mendalam, identifikasi akar masalah dari tampilan mobile (berdasarkan screenshot iPhone 16 - 393×852), serta rencana aksi teknis untuk menjadikan halaman direktori restoran sepenuhnya responsif, nyaman, dan estetik di semua ukuran layar smartphone.

---

## 1. Analisis Screenshot & Identifikasi Masalah Utama

Berdasarkan inspeksi langsung pada screenshot iPhone 16 (393px × 852px) yang Anda bagikan:

```
+---------------------------------------------+  <- Layar Mobile (393px)
| [✓ Restoran Terpercaya] [Dine-In QR]        |  <- Trust badges patah tidak simetris
| [Promo & Diskon]                            |  
| ------------------------------------------- |  <- Jarak terlalu mepet dengan Search Bar (-mt-10)
| Lokasi hanya tersedia di HTTPS atau...      |  <- Banner izin lokasi tinggi & padat
| [Q Cari restoran, masakan, atau menu...]    |  
| [Lokasi Saya] [Semua Kategori v] [Filter]   |  <- Tombol berdesakan (450px+)
| [Lihat di Peta]                             |  <- Terdampar sendirian di baris baru!
| ------------------------------------------- |  
| KATEGORI                                    |  
| Jelajahi Berdasarkan Kategori               |  
| [Semua] [Indonesia] [Kopi & Kafe] [Jepang]  |  <- Terpotong di kanan
| ------------------------------------------- |  
| [★ Rekomendasi Spesial]          | [Popu..] |  <- KONTEN KANAN TERPOTONG! (Horizontal Overflow)
| Burger Street                    |          |     Badge 'Populer' terpotong di tepi kanan
| 📖 Cepat Saji                    |          |     Card meluber keluar viewport (>393px)
| [Lihat Detail Restoran ->]                  |  
+---------------------------------------------+
```

### 🚨 Akar Masalah #1: Horizontal Overflow (Halaman Meluber > 393px)
* **Gejala:** Sisi kanan halaman terpotong secara vertikal. Badge hijau `Populer` di pojok kanan atas kartu rekomendasi terpotong, pill kategori terpotong, dan margin kanan container hilang.
* **Penyebab Teknis:**
  1. Kontainer atau elemen anak tidak memiliki pembatas lebar (`max-w-full` / `overflow-x-hidden`).
  2. Elemen tombol mobile di bawah search bar (`search-bar.blade.php`) memiliki total lebar ~450px dengan pembagian item yang memaksakan lebar min-content.
  3. Di CSS Grid (`popular-dishes.blade.php` dan `b2b-banner.blade.php`), track kolom `grid-cols-2` tanpa batas `minmax(0, 1fr)` atau `min-w-0` membuat teks panjang (seperti *"Kitchen Display (KDS)"* atau harga makanan) memaksakan lebar kolom melebihi lebar layar.
  4. Tag `<body>` dan `.directory-shell` tidak memiliki pencegahan `overflow-x: hidden`.

### 🚨 Akar Masalah #2: Tabrakan Vertikal Hero dengan Search Bar (`-mt-10`)
* **Gejala:** Search bar menempel langsung di bawah trust badge hero ("Restoran Terpercaya", "Dine-In QR Meja", "Promo & Diskon") tanpa jarak napas yang proporsional.
* **Penyebab Teknis:**
  - Di desktop, banner kanan hero menampilkan foto makanan tinggi sehingga `-mt-10` membuat kartu search melayang rapi.
  - Namun di mobile, foto makanan di-hide (`hidden lg:block`), sehingga hero section jauh lebih pendek. Akibatnya `-mt-10` (-40px) menarik kartu pencarian ke atas hingga menabrak teks hero.

### 🚨 Akar Masalah #3: Baris Tombol Aksi Mobile Patah Tidak Rapi
* **Gejala:** 
  Baris 1: `[ Lokasi Saya ]` `[ Semua Kategori v ]` `[ Filter ]`
  Baris 2: `[ Lihat di Peta ]` (sendirian di kiri, menyisakan ruang kosong besar di kanan).
* **Penyebab Teknis:**
  - Dropdown `[ Semua Kategori v ]` memiliki `min-w-[9.5rem]` (152px). Ditambah tombol lainnya, lebarnya melebihi 350px.
  - Dropdown ini juga **redundan**, karena tepat di bawahnya sudah ada baris kategori kuliner (Pills *Semua, Indonesia, Kopi & Kafe, Jepang*).
  - Susunan `flex-wrap` tanpa pembagian grid yang jelas membuat tata letak pecah di layar 360–393px.

### 🚨 Akar Masalah #4: Recommended Slider di Mobile (Potensi Tabrakan Tombol)
* **Gejala & Resiko:**
  - Badge `Populer` di posisi `absolute right-5 top-5` terpotong keluar layar.
  - Tombol CTA *"Lihat Detail Restoran"* di kiri bawah dan Kapsul Navigasi slider (*Prev*, *Next*, *Dots*) di kanan bawah saling berebut ruang horizontal di layar sempit.
  - Judul restoran dengan ukuran `text-3xl` terasa terlalu dominan di layar kecil jika tidak diimbangi padding yang fleksibel.

### 🚨 Akar Masalah #5: Header Direktori Tidak Memiliki Menu Navigasi Mobile
* **Gejala:** Di mobile, navigasi ke halaman penting seperti `/daftar-menu`, `Blog`, dan `Tentang Kami` hilang karena navigasi header hanya ditampilkan di desktop (`hidden md:flex`), tanpa adanya hamburger menu atau navigation drawer di mobile.

---

## 2. Rencana Solusi & Desain Perbaikan Mobile-First

### 🎯 Solusi 1: Mengunci Viewport & Menghilangkan Horizontal Overflow
1. **Tambahkan Proteksi Overflow Global:**
   - Berikan `overflow-x-clip` atau `overflow-x-hidden` pada `.directory-shell` dan pembungkus utama di `layouts/directory.blade.php`.
2. **Koreksi CSS Grid Col di Semua Komponen:**
   - Pastikan seluruh container `grid-cols-2` menggunakan formula aman `grid-cols-2` dengan `minmax(0, 1fr)` atau pastikan seluruh anak memiliki kelas `min-w-0` dan `truncate` agar tidak memicu perluasan min-content.

---

### 🎯 Solusi 2: Normalisasi Spacing Hero ke Search Bar
1. Ubah margin negatif kaku `-mt-10` menjadi adaptif:
   ```html
   {{-- Di mobile diberi margin atas yang proporsional, di desktop (lg:) tetap -mt-10 floating card --}}
   <div class="landing-container relative z-30 mt-2 sm:-mt-6 lg:-mt-10 flex-1 pb-16">
   ```
2. Rapikan Trust Badges pada Hero Mobile:
   - Jadikan badge trust mengalir fleksibel dengan `gap-2 sm:gap-4` dan font `text-[11px] sm:text-xs` agar tampil rapi dan simetris di layar sempit.

---

### 🎯 Solusi 3: Redesain Baris Tombol Aksi Mobile (Search Bar)
Ganti susunan `flex-wrap` yang patah dengan **Action Bar 3 Tombol yang Seimbang (Satu Baris Simetris)**:
- **Hapus** dropdown "Semua Kategori" khusus di tampilan mobile karena sudah diwakili oleh Section Kategori di bawahnya (serta ada di modal filter lengkap).
- Gunakan struktur **Grid 3-Kolom Seimbang** (`grid grid-cols-3 gap-2 lg:hidden`):
  1. **Tombol Lokasi**:
     - Status Idle: `📍 Lokasi` (atau `Lokasi Saya`)
     - Status Granted: `✓ Terdekat` (warna hijau emerald)
  2. **Tombol Filter**:
     - `⚙️ Filter` (dengan badge dot/angka jika ada filter aktif) -> Membuka modal `mobile-filter-sheet`.
  3. **Tombol Peta**:
     - `🗺️ Peta` (Icon Map + label) -> Membuka modal peta restoran.
- **Hasil:** Tampilan menjadi 1 baris solid, sangat rapi, mudah ditekan dengan ibu jari (*thumb-friendly*), dan tidak ada tombol yang terdampar sendirian di baris kedua.

---

### 🎯 Solusi 4: Optimalisasi Recommended Slider di Mobile
1. **Perbaiki Penempatan Badge & Padding:**
   - Ubah padding kontainer slide mobile menjadi `p-5 sm:p-8 md:p-12`.
   - Atur posisi badge "Populer": di mobile gunakan `right-4 top-4` (bukan `right-8`), dengan ukuran teks yang pas `text-[10px] sm:text-xs`.
2. **Pisahkan Jalur Tombol CTA dan Navigasi Slider:**
   - Di desktop: Tombol CTA di kiri, Kapsul Navigasi di kanan bawah.
   - Di mobile:
     - Kapsul navigasi (*Prev / Dots / Next*) diposisikan lebih kompak atau dipusatkan di bawah.
     - Tombol CTA *"Lihat Detail Restoran"* diberi `w-full sm:w-auto justify-center` sehingga di mobile menjadi tombol aksi penuh yang nyaman disentuh tanpa berbenturan dengan panah navigasi.
3. **Typography Responsif:**
   - Judul resto: `text-2xl sm:text-4xl md:text-5xl font-bold`.
   - Rating pill: `text-[11px] sm:text-xs` yang ramping dan elegan.

---

### 🎯 Solusi 5: Category Bar & Horizontal Scroll yang Nyaman
1. Berikan indikator scroll visual yang jelas:
   - Tambahkan efek gradient mask halus di tepi kanan (`mask-image: linear-gradient(...)`) atau padding kanan ekstra (`pr-4`) agar pengguna tahu bahwa deretan kategori bisa digeser ke kanan.
2. Tambahkan utility smooth touch scroll:
   - `overflow-x-auto touch-pan-x scrollbar-none`.

---

### 🎯 Solusi 6: Banner B2B Kuliner & Grid Menu Populer di Mobile
1. **B2B Banner:**
   - Fitur 4 poin ("Self-Order QR", "Kitchen Display", dll):
     - Di mobile < 400px, gunakan susunan yang tidak memaksakan teks panjang patah jelek, atau gunakan `grid grid-cols-1 sm:grid-cols-2 gap-2`.
2. **Menu Populer "Yang Lagi Hits":**
   - Pastikan card menu memiliki `min-w-0` pada nama menu dan nama resto.
   - Pada baris harga dan link "Resto ->", pastikan harga tidak bertumpuk dengan ikon panah di layar sempit.

---

### 🎯 Solusi 7: Header Navigasi Mobile (Hamburger Drawer)
1. Tambahkan tombol Hamburger Menu di header untuk pengguna mobile:
   - Berisi menu: **Beranda**, **Daftar Menu**, **Tentang Kami**, **Syarat & Ketentuan**, **Blog**, dan **Daftarkan Restoran / Login**.
   - Memastikan pengguna HP dapat berpindah ke halaman `/daftar-menu` dan menu lainnya dengan mudah.

---

## 3. Matriks Perubahan Komponen (File per File)

| No | File | Bagian yang Diubah | Tujuan & Hasil |
|---|---|---|---|
| 1 | `resources/views/layouts/directory.blade.php` | Body & Wrapper | Menambahkan pencegahan horizontal overflow (`overflow-x-hidden`). |
| 2 | `resources/views/livewire/landing/restaurant-directory.blade.php` | Container Shell & Margin | Mengubah `-mt-10` menjadi responsif `mt-2 sm:-mt-6 lg:-mt-10`, merapikan layout grid. |
| 3 | `resources/views/components/directory/search-bar.blade.php` | Mobile Action Row (L163-206) | Mengganti `flex-wrap` patah dengan Grid 3-Kolom Seimbang (*Lokasi*, *Filter*, *Peta*), merapikan box izin lokasi. |
| 4 | `resources/views/components/directory/recommended-slider.blade.php` | Slider Badge, Padding, CTA & Nav | Padding `p-5 sm:p-10`, badge `right-4 top-4`, tombol CTA full-width di mobile, navigasi slider anti-overlap. |
| 5 | `resources/views/components/directory/category-bar.blade.php` | Pills Scroll Container | Menambahkan `w-full max-w-full overflow-x-auto pr-4`, touch scrolling yang mulus. |
| 6 | `resources/views/components/directory/b2b-banner.blade.php` | Fitur Grid | Menyesuaikan grid fitur menjadi `grid-cols-1 sm:grid-cols-2` agar teks tidak berdesakan di layar HP. |
| 7 | `resources/views/components/directory/interactive-split-banner.blade.php` | Wrapper Map Desktop | Memastikan div wrapper peta desktop memiliki `hidden lg:flex` agar tidak menyisakan ruang kosong di mobile. |
| 8 | `resources/views/components/directory/header.blade.php` | Mobile Navigation | Menambahkan menu drawer/toggle mobile sederhana agar link navigasi utama (terutama Menu `/daftar-menu`) dapat diakses di HP. |

---

## 4. Tahapan Eksekusi (Roadmap Pelaksanaan)

- [ ] **Langkah 1: Perbaikan Overflow & Layout Dasar (Quick Wins)**
  - Terapkan `overflow-x-hidden` pada layout direktori.
  - Sesuaikan margin hero-ke-search bar agar tidak bertabrakan di layar HP.
- [ ] **Langkah 2: Redesain Tombol Aksi Mobile pada Search Bar**
  - Implementasikan Action Bar 3 Tombol (`Lokasi`, `Filter`, `Peta`) yang presisi 1 baris.
  - Uji tampilan pada lebar 360px (Samsung/Android), 390px/393px (iPhone), dan 414px.
- [ ] **Langkah 3: Perbaikan Recommended Slider Mobile**
  - Perbaiki padding, posisi badge `Populer`, dan pisahkan layout tombol CTA agar tidak menabrak panah slider.
- [ ] **Langkah 4: Polish Komponen Pendukung (Category Bar, B2B Banner, Split Banner)**
  - Sesuaikan grid B2B banner, hilangkan div kosong peta desktop di mobile, perhalus horizontal scroll category bar.
- [ ] **Langkah 5: Penambahan Mobile Menu Drawer di Header**
  - Sediakan akses navigasi menu direktori dan halaman terkait di perangkat mobile.
- [ ] **Langkah 6: Pengujian & Validasi Lintas Ukuran Layar**
  - Verifikasi di Chrome DevTools (360px, 375px, 393px, 412px, 768px, 1024px+).
  - Jalankan `php artisan test` untuk memastikan seluruh automated tests tetap lulus 100%.
  - Jalankan `npm run build` dan `graphify update .`.
