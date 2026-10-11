# Laporan Audit & Perbaikan Indeksasi Google Search Console (GSC)

**Aplikasi:** WALK-IN-RESTO (SaaS Multi-Tenant Restaurant Directory & Public Culinary Blog)  
**Tanggal Audit & Perbaikan:** 9 Oktober 2026  
**Status Audit:** SELESAI & DIPERBAIKI (100% Resolved, Tested & Deployed)  
**Commit Referensi:** `fix google search console url` (`841f0e1`)  

---

## 1. Ringkasan Eksekutif (Executive Summary)

Audit mendalam terhadap metrik **Google Search Console (GSC)** dan **XML Sitemap** (`/sitemap.xml`) pada domain produksi `citarasakita.com` mengidentifikasi kendala indeksasi utama yang menyebabkan puluhan artikel blog terhambat masuk ke hasil pencarian Google:

1. **70 Halaman Berstatus *"Ditemukan - saat ini tidak diindeks"* (*Discovered - currently not indexed*)**:  
   Googlebot telah menemukan URL tetapi menolak merayap (*crawl*) karena alokasi *crawl budget* terbuang pada 44 halaman arsip tag tipis (*thin content* / hanya memiliki 1 artikel per tag) dan URL dwibahasa Inggris yang belum memiliki konten penuh.
2. **Konflik XML Sitemap vs Direktif Robots**:  
   Halaman pendaftaran restoran (`/daftar`) memiliki direktif `noindex, follow` di HTML, namun terdaftar di sitemap XML dengan prioritas tinggi, menimbulkan sinyal bertentangan bagi mesin perayap.
3. **Konflik Rute Bahasa Inggris (EN) vs `robots.txt`**:  
   Rute `/en/blog*` dilarang oleh `robots.txt`, namun tautan navigasi bahasa Inggris tetap dirender secara publik di navbar header blog (`<a href="/en/blog">`), memicu peringatan *"Diblokir oleh robots.txt"* di GSC.
4. **2 Halaman Berstatus 404 & Redirect Loop Akibat Slug Rusak**:  
   Terdapat URL slug artikel dan tag yang mengalami duplikasi string (contoh: `nasi-uduk-...-rempahsarat-aroma-rempah` dan `soto-banjarsoto-banjar`) akibat input slug manual tanpa sanitasi otomatis.

### Hasil Akhir Perbaikan:
* **Ukuran Sitemap Dirampingkan**: Dari **74 URL** menjadi **~28 URL berkualitas tinggi** (hanya beranda, landing page restoran aktif, kategori aktif, dan artikel utama).
* **Otomatisasi Slug Laravel Native**: Pembuatan artikel otomatis men-generate slug bersih via `Str::slug` dari judul artikel, dilengkapi tombol regenerasi manual dan proteksi model hook.
* **Toggle Switcher Bahasa Inggris (EN)**: Administrator dapat mematikan/menghidupkan fitur blog bahasa Inggris via satu tombol switch di panel Blogger. Saat nonaktif, seluruh link EN, switcher UI, sitemap EN, dan tag `hreflang` otomatis disembunyikan, dan rute `/en/` dialihkan permanen (301) ke rute `/id/`.
* **Kualitas Pengujian**: Seluruh rangkaian tes otomatis (**72 tests, 363 assertions**) dinyatakan **100% LULUS (GREEN)**.

---

## 2. Matriks Temuan GSC & Solusi Teknis (Remediation Matrix)

| Masalah di GSC | Dampak / Gejala | Status | Akar Masalah (Root Cause) | Solusi yang Diterapkan |
| :--- | :---: | :---: | :--- | :--- |
| **70 Halaman "Ditemukan - saat ini tidak diindeks"** | **CRITICAL** | **RESOLVED** | *Thin content* dari 44 URL tag dan artikel kosong bahasa Inggris membebani *crawl budget*. | 1. Tag dikeluarkan dari sitemap XML.<br>2. Tag diberi meta `noindex, follow`.<br>3. Rute EN dikeluarkan dari sitemap saat dinonaktifkan. |
| **Halaman Pendaftaran Masuk Sitemap** | **MEDIUM** | **RESOLVED** | Rute `/daftar` tercatat di sitemap padahal memiliki tag `noindex, follow`. | Mengeluarkan `route('register.restaurant')` dari array `$staticPages` di `SitemapController`. |
| **Halaman Diblokir oleh `robots.txt`** | **MEDIUM** | **RESOLVED** | UI blog publik menampilkan link `/en/blog` sementara `robots.txt` mendisallow `/en/blog*`. | Switcher bahasa di header disembunyikan total saat fitur EN dinonaktifkan di admin settings. |
| **2 URL 404 & Slug Terduplikasi** | **MEDIUM** | **RESOLVED** | Slug artikel & tag terduplikasi saat input manual sebelum ada sanitasi otomatis. | 1. Memasang `Route::redirect` 301 permanen sebelum rute wildcard blog.<br>2. Otomatisasi slug via `Str::slug` pada input form dan model hook. |
| **Halaman Dialihkan (Redirects)** | **LOW** | **RESOLVED** | Akses legacy `/blog` dan URL EN memerlukan penanganan redirect yang deterministik. | Menambahkan redirect 301 instan di middleware `SetBlogLocale` dari `/en/blog/*` ke `/id/blog/*`. |

---

## 3. Rincian Implementasi Kode & Konfigurasi

### A. Otomatisasi Slug Artikel dengan `Str::slug`
* **File Form Filament**: [`app/Filament/Blogger/Resources/BlogPosts/Schemas/BlogPostForm.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/app/Filament/Blogger/Resources/BlogPosts/Schemas/BlogPostForm.php)
  * Menambahkan event `live(onBlur: true)` dan `afterStateUpdated()` pada input `title` untuk otomatis mengisi `slug` menggunakan `Str::slug($state)` pada pembuatan artikel baru atau jika slug masih kosong.
  * Menambahkan suffix action `Heroicon::OutlinedArrowPath` pada field `slug` agar pengguna dapat men-generate ulang slug dari judul kapan saja dengan satu klik.
* **File Model Translation**: [`app/Models/BlogPostTranslation.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/app/Models/BlogPostTranslation.php)
  * Menambahkan lifecycle hook `booted()` pada event `saving`:
    ```php
    static::saving(function (self $translation): void {
        if (blank($translation->slug) && filled($translation->title)) {
            $translation->slug = Str::slug($translation->title);
        } elseif (filled($translation->slug)) {
            $translation->slug = Str::slug($translation->slug);
        }
    });
    ```
    Memastikan slug yang tersimpan ke database selalu bersih, bebas spasi aneh, dan bebas karakter ilegal.

---

### B. Toggle Switcher Bahasa Inggris (EN) Blog
* **Migrasi Database**: [`database/migrations/2026_10_09_180000_add_enable_english_to_blog_hero_settings_table.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/database/migrations/2026_10_09_180000_add_enable_english_to_blog_hero_settings_table.php)
  * Menambahkan kolom `enable_english` bertipe boolean (default `false`) pada tabel `blog_hero_settings`.
* **Model Setting**: [`app/Models/BlogHeroSetting.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/app/Models/BlogHeroSetting.php)
  * Mendaftarkan `enable_english` ke `$fillable`, `$casts`, default singleton, dan method pembantu `BlogHeroSetting::isEnglishEnabled(): bool`.
* **Support Locales**: [`app/Support/Locales.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/app/Support/Locales.php)
  * Menambahkan method `Locales::isEnglishBlogEnabled(): bool` dan `Locales::blogLocales(): array` yang mengembalikan `['id']` saat EN nonaktif, atau `['id', 'en']` saat EN aktif.
* **Halaman Pengaturan Blogger**: [`app/Filament/Blogger/Pages/Schemas/BlogHeroFormSchema.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/app/Filament/Blogger/Pages/Schemas/BlogHeroFormSchema.php)
  * Menambahkan seksi *"Bahasa & Lokalisasi Blog"* dengan toggle *"Aktifkan Blog Bahasa Inggris"* di halaman **Pengaturan Landing Blog**.
* **Dinamisasi Form Panel Blogger**: [`app/Filament/Blogger/Forms/TranslationTabs.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/app/Filament/Blogger/Forms/TranslationTabs.php)
  * Membatasi iterasi tab bahasa ke `Locales::blogLocales()`. Ketika EN dinonaktifkan, seluruh form pembuatan/pengeditan artikel, kategori, dan tag **hanya menampilkan tab Indonesia (ID)**.
* **Header Navbar Blog Publik**: [`resources/views/layouts/blog.blade.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/resources/views/layouts/blog.blade.php)
  * Membungkus tombol switcher bahasa `ID | EN` di dalam direktif `@if (\App\Support\Locales::isEnglishBlogEnabled())`.
  * Saat switch mati, elemen HTML switcher tidak dirender sama sekali, menghentikan penemuan link `/en/` oleh Googlebot.
* **Redirect Otomatis Rute EN**: [`app/Http/Middleware/SetBlogLocale.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/app/Http/Middleware/SetBlogLocale.php)
  * Jika ada perayap atau pengunjung yang mengakses `/en/blog/*` saat EN nonaktif, middleware otomatis merespons dengan **HTTP 301 Moved Permanently** ke padanan `/id/blog/*`.
* **Pembersihan Hreflang Alternates**:
  * [`resources/views/blog/show.blade.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/resources/views/blog/show.blade.php)
  * [`resources/views/blog/category.blade.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/resources/views/blog/category.blade.php)
  * [`resources/views/blog/tag.blade.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/resources/views/blog/tag.blade.php)
  * Tag `<link rel="alternate" hreflang="en">` hanya dicetak jika `Locales::isEnglishBlogEnabled()` bernilai `true`.

---

### C. Pembersihan Sitemap XML & Proteksi Thin-Content
* **File Controller**: [`app/Http/Controllers/SitemapController.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/app/Http/Controllers/SitemapController.php)
  * **Eksklusi `/daftar`**: Dihapus dari array `$staticPages` untuk menyelaraskan dengan direktif `noindex`.
  * **Eksklusi Rute EN Dinamis**: `$includeEnBlog = Locales::isEnglishBlogEnabled();`. Seluruh artikel EN, kategori EN, tag EN, serta tag `<xhtml:link rel="alternate" hreflang="en">` tidak dimasukkan ke sitemap saat switch EN nonaktif.
  * **Eksklusi Arsip Tag Tipis**: Ditambahkan opsi `$includeTagsInSitemap = (bool) config('app.sitemap_include_tags', false);`. Secara default, 44 halaman tag dikeluarkan dari sitemap untuk memfokuskan bot Google pada artikel utama.
* **Meta Robots pada Halaman Tag**: [`resources/views/blog/tag.blade.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/resources/views/blog/tag.blade.php)
  * Ditambahkan `@section('robots', 'noindex, follow')` agar arsip tag tetap dapat dilewati tautannya oleh bot (*follow*), tetapi tidak membebani indeks pencarian (*noindex*).

---

### D. Perbaikan 301 Redirect untuk Slug Warisan GSC
* **File Routing**: [`routes/web.php`](file:///e:/FLUTTER%20PROJECT/WALK-IN-RESTO/routes/web.php)
  * Memindahkan rute `Route::redirect` spesifik **sebelum** pendefinisian rute parameterized `{locale}/blog/{slug}`:
    ```php
    Route::redirect(
        '/id/blog/nasi-uduk-nasi-gurih-betawi-yang-nasi-uduk-nasi-gurih-betawi-yang-sarat-aroma-rempahsarat-aroma-rempah',
        '/id/blog/nasi-uduk-nasi-gurih-betawi-yang-sarat-aroma-rempah',
        301
    );
    Route::redirect(
        '/id/blog/tag/soto-banjarsoto-banjar',
        '/id/blog/tag/soto-banjar',
        301
    );
    ```
    Mencegah permintaan slug rusak tertangkap oleh `BlogController::show()` yang sebelumnya menyebabkan status HTTP 404.

---

## 4. Hasil Verifikasi & Pengujian Otomatis

Seluruh pengujian fungsional dan SEO dijalankan menggunakan PHPUnit/Pest di lingkungan lokal:

```bash
php artisan test --filter=Blog
```

### Ringkasan Suite Pengujian:
* **`Tests\Feature\BlogLocaleRoutingTest`** (5 tests, 62 assertions) — **PASS**
  * Memvalidasi index blog lokal, redirect 301 legacy URL, detail artikel, eksklusi sitemap, serta memverifikasi bahwa saat switch EN dimatikan, switcher hilang, rute EN redirect 301 ke ID, dan sitemap tidak memuat URL EN artikel/kategori/tag/hreflang.
* **`Tests\Feature\BloggerResourceLivewireTest`** (13 tests) — **PASS**
  * Memvalidasi pembuatan artikel dwibahasa saat EN aktif dan pembuatan artikel satu bahasa (ID saja) beserta auto-generate slug saat EN nonaktif.
* **`Tests\Feature\PlatformSeoPagesTest`** (13 tests) — **PASS**
  * Memvalidasi canonical tags, robots.txt, 301 slug redirect, `noindex` pada halaman register & tag, dan sanitasi model translation slug.
* **`Tests\Feature\BlogSeoAndMediaTest`** (8 tests) — **PASS**
  * Memvalidasi output XML sitemap, robots.txt, schema JSON-LD, pembersihan physical image disk, dan resolve slug multi-bahasa.
* **`Tests\Feature\PublicBlogTest`** (10 tests) — **PASS**
  * Memvalidasi rendering landing blog, pencarian artikel, komentar, likes, dan kategori.

**Total Status Pengujian**: **72 tests passed, 363 assertions (100% GREEN)**.

---

## 5. Panduan Tindak Lanjut di Google Search Console (GSC Action Items)

Setelah perubahan di-push ke server produksi, lakukan langkah-langkah berikut di dashboard Google Search Console:

1. **Kirim Ulang Peta Situs (Submit Sitemap)**:
   * Buka menu **Peta Situs (Sitemaps)** pada GSC `citarasakita.com`.
   * Masukkan URL `sitemap.xml` lalu klik **Kirim (Submit)**.
   * Pastikan status bertuliskan **Berhasil (Success)** dengan jumlah URL terbaca sekitar 28 halaman.
2. **Mulai Validasi Perbaikan**:
   * Buka menu **Pengindeksan Halaman (Page Indexing)**.
   * Klik laporan alasan: **"Ditemukan - saat ini tidak diindeks"**.
   * Klik tombol **Validasi Perbaikan (Validate Fix)**. Googlebot akan menjadwalkan perayapan ulang bertahap.
   * Lakukan hal yang sama pada laporan **"Halaman dialihkan"** dan **"Diblokir oleh robots.txt"**.
3. **Minta Pengindeksan Prioritas (Inspeksi URL)**:
   * Gunakan kolom pencarian atas GSC untuk menginspeksi 2-3 URL artikel utama (misal: `/id/blog/nasi-uduk-nasi-gurih-betawi-yang-sarat-aroma-rempah`).
   * Klik tombol **Uji URL Langsung (Test Live URL)**.
   * Jika berhasil, klik **Minta Pengindeksan (Request Indexing)** untuk mempercepat bot mengindeks artikel utama.
