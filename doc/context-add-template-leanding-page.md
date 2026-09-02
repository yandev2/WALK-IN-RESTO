# Panduan & Standar Pembuatan Template Landing Page Baru

Dokumen ini berisi standar, aturan wajib, arsitektur, dan langkah kerja (*step-by-step*) untuk menambahkan template landing page restoran baru di sistem **Walk-in Resto**.

---

## 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules)

1. **100% Data Asli Database (Dilarang Keras Teks/Data Dummy Hardcode):**
   - Tidak boleh ada teks keunggulan buatan statis, nama makanan fiktif, harga fiktif, rating bintang buatan statis, atau nomor telepon statis.
   - Semua teks judul, label, langkah, ulasan, jam operasional, menu, dan alamat harus diambil dari database (`LandingPageDataService`, `CmsProfile`, `LandingLayout`, `MenuItem`, `RestaurantReview`, `Outlet`, `CmsFaq`, `CmsBanner`, `CmsGalleryImage`).

2. **Gunakan Warna Brand Restoran (Primary & Accent/Secondary):**
   - **Dilarang** menggunakan warna statis seperti `amber-500`, `orange-500`, atau `blue-600` untuk elemen brand utama.
   - **Wajib** menggunakan token tema Tailwind / CSS variables restoran:
     - Background tombol / aksen: `bg-primary`, `bg-primary-dark`, `hover:bg-primary-dark`
     - Gradien brand: `bg-linear-to-r from-primary to-accent`
     - Teks highlight: `text-primary`, `text-accent`
     - Tint & Border: `bg-primary/10`, `border-primary/20`, `ring-primary/20`
     - Shadow: `shadow-primary/25`

3. **Wajib Terintegrasi Penuh dengan Mode Gelap (Dark Mode):**
   - Selalu extend layout induk `@extends('layouts.landing')`.
   - Sertakan tombol pengalih tema `<x-customer.theme-toggle />` di header.
   - Semua kartu, border, background, dan teks harus memiliki class `dark:`:
     - Background halaman: `bg-surface-base dark:bg-zinc-950`
     - Kartu & Kontainer: `bg-surface-raised dark:bg-zinc-900/90`
     - Border: `border-border-subtle dark:border-zinc-800`
     - Teks utama: `text-body dark:text-zinc-100`
     - Teks sekunder/muted: `text-muted dark:text-zinc-400`
     - Footer: `bg-zinc-950 dark:bg-black text-zinc-300 dark:text-zinc-400`

4. **Kesesuaian Desain Kartu Menu (`dish-card`):**
   - Kartu menu di landing page dan di halaman semua menu (`/resto/menu` via `restaurant-menu-catalog.blade.php`) harus menggunakan komponen seragam `<x-customer.dish-card :template="$template" ... />`.

---

## 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping)

Di CMS Resto (`ManageLandingLayout.php`), pemilik resto dapat mengatur urutan (*drag-and-drop*) dan visibilitas dari **10 Core Section**. Template baru **wajib menyediakan file Blade untuk masing-masing 10 section** di dalam subfolder `sections/`:

| No | File Section (`sections/`) | ID Section | Sumber Database | Keterangan |
|:--:|:---|:---|:---|:---|
| 1 | `banners.blade.php` | `banners` | `cms_banners` | Carousel banner promo via `<x-customer.promo-banner-carousel>` |
| 2 | `menu.blade.php` | `menu` | `menu_items`, `menu_categories` | Grid hidangan populer berdiskon via `<x-customer.dish-card>` |
| 3 | `reviews.blade.php` | `reviews` | `restaurant_reviews`, `$ratingSummary` | Testimoni tamu asli + skor kepuasan rating |
| 4 | `how_to.blade.php` | `how_to` | `cms_profiles.landing_copy['how_to']['steps']` | 4 langkah cara pesan walk-in |
| 5 | `about.blade.php` | `about` | `cms_profiles.about_html` & `landing_copy['about']` | Cerita tentang restoran dari editor CMS |
| 6 | `gallery.blade.php` | `gallery` | `cms_gallery_images` | Foto suasana resto + Alpine zoom lightbox modal |
| 7 | `hours.blade.php` | `hours` | `outlet_operating_hours`, `$isOpenNow`, `$todayHours` | Jam buka harian (Senin–Minggu) + status buka/tutup |
| 8 | `location.blade.php` | `location` | `outlets.address`, `$mapsUrl`, `$mapEmbedUrl` | Alamat lengkap + iframe peta Google Maps |
| 9 | `faq.blade.php` | `faq` | `cms_faqs` | Accordion tanya jawab seputar resto |
| 10 | `cta.blade.php` | `cta` | `$whatsappUrl`, `$mapsUrl`, `landing_copy['cta']` | Banner penutup ajakan berkunjung / kontak WhatsApp |

Selain 10 section di atas, sediakan 3 file pendukung:
- `header.blade.php`: Header sticky dengan logo, nama resto, navigasi link dinamis, `<x-customer.theme-toggle />`, dan tombol CTA.
- `hero.blade.php`: Bagian utama atas dengan headline dinamis (`$profile?->headline ?: $restaurant->name`), status buka, pill promo, dan foto hero.
- `footer.blade.php`: Footer multi-kolom dengan navigasi dinamis, info kontak, dan status jam buka.

---

## 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)

### Langkah 1: Buat Direktori Template
Buat folder baru dengan slug template (misalnya `bistro`):
```
resources/views/landing/templates/bistro/
├── show.blade.php
└── sections/
    ├── header.blade.php
    ├── hero.blade.php
    ├── banners.blade.php
    ├── menu.blade.php
    ├── reviews.blade.php
    ├── how_to.blade.php
    ├── about.blade.php
    ├── gallery.blade.php
    ├── hours.blade.php
    ├── location.blade.php
    ├── faq.blade.php
    ├── cta.blade.php
    └── footer.blade.php
```

### Langkah 2: Buat File Utama `show.blade.php`
Gunakan pola standar iterasi `$visibleSections` agar dinamis terhadap pengaturan CMS:
```blade
@extends('layouts.landing')

@section('title', ($profile?->headline ?: $restaurant->name).' · Walk-in')
@section('description', $layout->copyFor('hero')['subtitle'] ?? 'Datang, duduk di meja pilihan Anda, lalu scan QR untuk memesan.')

@section('body')
    <div class="min-h-screen bg-surface-base text-body transition-colors duration-300">
        {{-- Header Navigation --}}
        @include('landing.templates.{slug}.sections.header')

        <main id="atas">
            {{-- Hero Section --}}
            @if (in_array('hero', $visibleSections, true))
                @include('landing.templates.{slug}.sections.hero')
            @endif

            {{-- Core Sections in Dynamic Order --}}
            @foreach ($visibleSections as $sectionId)
                @if ($sectionId !== 'hero')
                    @if (view()->exists('landing.templates.{slug}.sections.'.$sectionId))
                        @include('landing.templates.{slug}.sections.'.$sectionId)
                    @elseif (view()->exists('landing.sections.'.$sectionId))
                        @include('landing.sections.'.$sectionId)
                    @endif
                @endif
            @endforeach
        </main>

        {{-- Footer --}}
        @include('landing.templates.{slug}.sections.footer')
    </div>
@endsection
```

### Langkah 3: Tambahkan Dukungan Varian di `dish-card.blade.php`
Buka `resources/views/components/customer/dish-card.blade.php` dan tambahkan blok tampilan kartu jika template memiliki gaya visual khusus.

### Langkah 4: Registrasi Template ke Database
Template dapat didaftarkan melalui 2 cara:

1. **Melalui Panel Founder (`/founder/landing-templates`):**
   - Klik **Buat Template Landing**.
   - **Nama:** e.g. *Bistro Modern*
   - **Slug:** `bistro`
   - **Badge:** e.g. *Baru*
   - **Thumbnail:** Upload screenshot thumbnail preview (rasio 16:9 / 4:3).
   - **View Path:** `landing.templates.bistro.show`
   - **Status:** Aktif (Checked).

2. **Melalui Seeder (`LandingTemplateSeeder.php`):**
```php
LandingTemplate::query()->updateOrCreate(
    ['slug' => 'bistro'],
    [
        'name' => 'Bistro Modern',
        'description' => 'Desain bistro modern bertema minimalis dan elegan.',
        'badge' => 'Baru',
        'thumbnail_path' => 'images/landing/templates/bistro.webp',
        'view_path' => 'landing.templates.bistro.show',
        'is_active' => true,
        'sort_order' => 3,
    ]
);
```

### Langkah 5: Jalankan Pengujian Otomatis
Jalankan test suite untuk memastikan template baru lulus seluruh pengujian integritas data:
```bash
php artisan test --filter=LandingMultiTemplateTest
php artisan test --filter=Landing
```

---

## 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`

Setiap view template secara otomatis menerima variabel terstandarisasi berikut:

```php
$restaurant       // Model Restaurant (name, logo_path, currency, timezone)
$outlet           // Model Outlet default (name, address, phone, latitude, longitude)
$profile          // Model CmsProfile (headline, primary_color, accent_color, about_html)
$menuItems        // Koleksi MenuItem aktif (terlaris & berdiskon)
$menuCategories   // Koleksi MenuCategory aktif
$ratingSummary    // Array ['average' => float|null, 'count' => int]
$layout           // Objek LandingLayout (metode copyFor($sectionId), isVisible($sectionId))
$visibleSections  // Array ID section yang aktif dan diurutkan sesuai pengaturan CMS
$isOpenNow        // Boolean: status outlet buka/tutup saat ini
$todayHours       // Model OutletOperatingHour hari ini
$dayNames         // Array nama hari 0 => Minggu s/d 6 => Sabtu
$mapsUrl          // String URL pencarian Google Maps
$mapEmbedUrl      // String URL embed iframe peta
$whatsappUrl      // String URL WhatsApp direct chat
$heroUrl          // String URL gambar Hero yang diunggah
$aboutImageUrl    // String URL gambar Tentang yang diunggah
$howToImageUrl    // String URL gambar Cara Pesan yang diunggah
$logoUrl          // String URL logo restoran
$theme            // Array token warna tema ['primary', 'primary_dark', 'accent']
$template         // Model LandingTemplate yang aktif
```

---

## 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*)

Berdasarkan iterasi dan feedback pembuatan template `glassmorphism`, `foodie`, dan `classic`, berikut prinsip desain penting yang **wajib diterapkan** untuk setiap template masa depan:

### A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons)
- **Hindari emoji:** Dilarang menggunakan emoji seperti `🪑`, `📱`, `🍲`, `💳`, `🍽️`, `🚀`, `💬`, `📍`, `⏰`, `★` pada badge, kartu, atau langkah alur karena memberi kesan *"murahan / AI-generated"*.
- **Gunakan SVG Heroicons:** Selalu gunakan SVG Heroicons outline/solid yang presisi dengan ukuran terstandarisasi (`h-3.5 w-3.5`, `h-4 w-4`, `h-5 w-5`) dan warna yang mengikuti tema (`text-primary`, `text-muted`).
- **Rating Bintang Vektor:** Ganti string teks `★★★★★` dengan loop SVG bintang `<svg class="fill-current ...">` yang tajam di layar resolusi tinggi.

### B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*)
Agar efek latar belakang (seperti bola kaca 3D, mesh neon, gelembung cair) tembus pandang ke seluruh section tanpa tertutup:
1. **Layer `-z-20` (Paling Bawah - Kanvas Dasar):**
   - Letakkan warna dasar solid di layer ini: `bg-slate-100 dark:bg-[#060913]`.
   - **Dilarang** menaruh warna solid `bg-zinc-950` atau `bg-surface-base` pada pembungkus konten utama (`<main>` atau parent `<div>`) karena akan menutupi layer dekorasi di belakangnya.
2. **Layer `-z-10` (Tengah - Ambient Decorative Mesh & 3D Spheres):**
   - Tempatkan bola-bola kaca 3D organik dan fluid mesh glow di layer ini dengan `fixed inset-0 pointer-events-none`.
3. **Layer `z-10` (Atas - Konten & Kartu):**
   - Seluruh section, header, dan footer berada di layer ini dengan background kartu transparan/frosted (`backdrop-blur-2xl`).

### C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*)
1. **Transparansi Tepat:**
   - Gunakan `bg-white/25 dark:bg-white/[0.06]` dipadukan dengan `backdrop-blur-2xl`.
   - **Jangan** membungkus komponen yang memiliki background solid sendiri (seperti `bg-surface-section`).
2. **Kilau Tepi (Specular Highlight) & Inner Glow:**
   - Tepi luar: `border border-white/50 dark:border-white/20`
   - Kilau dalam: `shadow-[0_12px_40px_rgba(0,0,0,0.06),inset_0_1px_1px_rgba(255,255,255,0.7)]`
3. **Visibilitas di Mode Gelap (*Dark Mode Contrast*):**
   - Agar bola kaca tetap terlihat jelas pada latar gelap (*darkened cosmic tone*), berikan **pantulan cahaya sudut atas-kiri** yang terang (`dark:from-white/40`) berpadu dengan gradasi neon brand resto (`dark:via-primary/45 dark:to-purple-600/50`) serta kilau dalam (`dark:shadow-[inset_0_3px_14px_rgba(255,255,255,0.5)]`).
   - Tingkatkan intensitas ambient glow di dark mode (`dark:opacity-85`) agar bola kaca kontras dan berdimensi 3D.

### D. Konsistensi Antar-Halaman (Ekosistem Menu & Kartu)
- Setiap variasi desain kartu pada template baru wajib didaftarkan juga ke komponen `<x-customer.dish-card>` agar halaman katalog menu lengkap (`/resto/menu` via `restaurant-menu-catalog.blade.php`) memiliki keseragaman visual 100% dengan landing page utama.
