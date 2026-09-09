# 🌌 Vision UI Adaptive Widget Design System Prompt

Gunakan prompt dan panduan di bawah ini untuk diberikan kepada AI saat ingin membuat atau memperbarui widget baru agar memiliki gaya, estetika, dan kualitas yang identik dengan dashboard **Blog Analytics (Vision UI Adaptive Glassmorphism)**.

---

## 📋 Prompt Siap Pakai (Tinggal Copy-Paste ke AI)

```markdown
Tolong buatkan widget [Nama Widget / Deskripsi Fitur] untuk dashboard Filament admin dengan mengikuti standar desain **Vision UI Adaptive Glassmorphism** yang sudah diterapkan pada proyek ini:

1. **Struktur Kontainer & Tinggi**:
   - Gunakan wrapper root `<x-filament-widgets::widget class="h-full">`.
   - Gunakan kartu `.vision-card` dengan class `class="vision-card h-full flex flex-col justify-between"`.
   - Pastikan tinggi widget sejajar (*equal height*) dengan widget di sampingnya jika berada dalam satu baris grid.

2. **Dukungan Dual-Mode (Dark & Light Mode Wajib Sempurna)**:
   - **Dark Mode**: Latar belakang deep midnight navy glass (`linear-gradient(127deg, #060b28, #0a0e27)`), teks putih tajam, border kaca tipis `rgba(255, 255, 255, 0.08)`, dan aksen neon cyan (`#2cd9ff`), electric blue (`#0075ff`), atau neon emerald (`#01b574`).
   - **Light Mode**: Latar belakang putih bersih (`#ffffff`), border slate lembut (`#e2e8f0`), bayangan halus (`shadow-[0_4px_20px_-2px_rgba(0,0,0,0.05)]`), dan teks slate gelap tajam (`text-slate-900` untuk judul/angka, `text-slate-500` untuk subteks/deskripsi).
   - Jangan gunakan hardcoded `text-white` tanpa varian `dark:`. Gunakan utility adaptif Tailwind seperti `text-slate-900 dark:text-white` dan `border-slate-100 dark:border-white/5`.

3. **Komponen Visual Khusus**:
   - **Icon Box**: Jika ada ikon metrik, gunakan kotak gradien bercahaya (`vision-icon-box vision-icon-box-blue`, `green`, `purple`, atau `orange`).
   - **Pill Sub-Widget**: Untuk kotak metrik kecil atau badge status, gunakan `.vision-pill-card` (di Light Mode berlatar `#f1f5f9` dengan border `#e2e8f0`, di Dark Mode berlatar kaca transparan).
   - **Chart (ApexCharts)**:
     - Area/Line Chart: Kurva halus (`curve: 'smooth'`), hilangkan bug tumpukan tanggal sumbu-X dengan `tickAmount: 6`, `hideOverlappingLabels: true`, dan `rotate: 0`.
     - Radial/Speedometer Gauge: Sudut `-125°` sampai `125°`, warna track non-aktif wajib `#e2e8f0` di Light Mode agar tampak utuh dan tidak hilang di latar putih, serta gradien neon di Dark Mode.
     - Donut Chart: `size: '72%'`, warna stroke adaptif (`#ffffff` di Light Mode, `#060b26` di Dark Mode).
   - **Filter & Dropdown**: Jika ada form filter, jangan gunakan double card. Gunakan `.vision-filter-card` dengan `overflow: visible !important;` agar dropdown popover tidak terpotong saat dibuka, dan pastikan field input seleksi 100% full width.
   - **Tabel Data**: Gunakan `.vision-table-card` agar baris data, search bar, dan header kolom serasi dengan tema kaca tanpa strip putih belang.
   - **Form Section & Input**: Gunakan `Section::make()->extraAttributes(['class' => 'vision-card'])` agar section form otomatis berlatar kaca Vision UI, bebas double card, input semi-transparan dengan glow fokus biru electric, dan TranslationTabs bergradien elegan.
   - **Kartu Statistik (`Stat::make`)**: Gunakan `Stat::make()` dengan warna `primary`, `info`, `warning`, `success` yang otomatis memetakan ke kotak ikon gradien neon bersinar (`.vision-icon-box`).

Setelah selesai membuat/mengedit kode, jalankan `npm run build` untuk mengompilasi CSS dan `graphify update .` untuk memperbarui graph.
```

---

## 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`

Seluruh class styling berikut sudah terdaftar di `resources/css/filament/admin/theme.css` dan siap digunakan langsung pada file Blade:

### 1. Kartu Kontainer Utama (`.vision-card`)
- **Light Mode**: Background `#ffffff`, border `#e2e8f0`, text `#0f172a`, shadow lembut.
- **Dark Mode**: Background `linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.82) 76.65%)`, border `rgba(255, 255, 255, 0.08)`, backdrop-blur `20px`, text `#ffffff`.
- **Top border glow** otomatis aktif di Dark Mode.

### 2. Kotak Ikon Gradien Neon (`.vision-icon-box`)
Ukuran `2.75rem x 2.75rem` (`44px`), `rounded-xl`, flex center, drop shadow neon:
- `.vision-icon-box-blue`: Gradien `#0075ff` ke `#2cd9ff` (shadow cyan/blue glow).
- `.vision-icon-box-green`: Gradien `#01b574` ke `#05cd99` (shadow emerald glow).
- `.vision-icon-box-purple`: Gradien `#7551ff` ke `#3965ff` (shadow purple glow).
- `.vision-icon-box-orange`: Gradien `#ff9a56` ke `#ff6b35` (shadow orange glow).

### 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`)
- **Light Mode**: Background `#f1f5f9`, border `#e2e8f0`, shadow tipis `0 1px 2px rgba(0,0,0,0.04)`.
- **Dark Mode**: Background `rgba(255, 255, 255, 0.04)`, border `rgba(255, 255, 255, 0.07)`.
- **Hover**: Transisi halus saat diarahkan kursor.

### 4. Kartu Filter (`.vision-filter-card`)
- Memiliki `overflow: visible !important;` dan `z-index: 30` sehingga panel popover dropdown menu tidak akan terpotong batas kartu.
- Input wrapper di dalamnya otomatis membentang `width: 100% !important;`.

### 5. Tabel Data Kaca (`.vision-table-card`)
Diterapkan via `$table->extraAttributes(['class' => 'vision-table-card'])`:
- **Light Mode**: Card putih, header kolom `#f8fafc`, teks `#0f172a`, search bar `#f8fafc`.
- **Dark Mode**: Card midnight navy, thead transparan, search bar semi-transparan `bg-white/5`.
- **Badge Numerik**: Otomatis berformat kapsul rounded-full dengan outline warna neon (`#2cd9ff`, `#01b574`, `#ffb547`).

### 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`)
Diterapkan langsung pada skema form:
```php
Section::make('Nama Section')
    ->extraAttributes(['class' => 'vision-card'])
    ->description('Deskripsi section...')
    ->icon(Heroicon::OutlinedDocumentText)
    ->schema([...])
```
- **Mekanisme Reset Otomatis (Bebas Double Card)**:
  Filament menempatkan `extraAttributes` pada wrapper `<div class="fi-sc-section vision-card">`. CSS `theme.css` otomatis mereset elemen `<section class="fi-section">` di dalamnya menjadi transparan tanpa background abu-abu, tanpa ring/border ganda, dan tanpa celah padding ekstra.
- **Header Section**:
  - Judul bold kontras tinggi (`text-white` di Dark, `text-slate-900` di Light).
  - Deskripsi halus dengan garis pemisah border tipis.
  - Ikon section bercahaya neon cyan (`#2cd9ff`) di Dark Mode dan electric blue (`#0284c7`) di Light Mode.
- **Field & Input (`.fi-input-wrp`)**:
  - Input, Textarea, dan Select semi-transparan (translucent glass) dengan border 1px halus.
  - Efek **Electric Blue Glow** (`#0075ff`) saat field dalam keadaan fokus (`:focus-within`).
- **Translation Tabs (`TranslationTabs` / `.fi-tabs`)**:
  - Tombol tab berbentuk pill bar modern.
  - Tab aktif otomatis menggunakan gradien *electric blue* (`#0075ff` → `#2cd9ff`) dengan teks putih dan bayangan lembut, menggantikan tombol kotak hitam kaku bawaan Filament.
- **Fieldset & Legend (`.fi-fieldset`)**:
  - Wadah fieldset dengan border tipis dan latar kaca tembus pandang.
  - Teks legend huruf kapital (uppercase) berwarna cyan (`#2cd9ff`) / electric blue (`#0284c7`).
- **Rich Editor & File Upload**:
  - Toolbar dan area penulisan menyatu mulus dengan kartu tanpa batas warna abu-abu default.

### 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`)
Diterapkan otomatis pada semua widget turunan `StatsOverviewWidget` (seperti `BackupStatsOverviewWidget`):
```php
Stat::make('Label Metrik', 'Nilai')
    ->description('Keterangan status...')
    ->icon(Heroicon::OutlinedArchiveBox)
    ->color('primary') // Otomatis map ke vision-icon-box
```
- **Pemetaan Warna Ikon (`.vision-icon-box`)**:
  - `primary` / `gray` → **Electric Blue** (`vision-icon-box-blue`)
  - `info` → **Vibrant Purple** (`vision-icon-box-purple`)
  - `warning` / `danger` → **Warm Orange** (`vision-icon-box-orange`)
  - `success` → **Emerald Green** (`vision-icon-box-green`)
- Menghilangkan pola gelombang gradien jadul dan menggantinya dengan kartu kaca `.vision-card` yang serasi di Dark Mode dan Light Mode.

---

## 📐 Template Kerangka Widget Blade

Berikut adalah template standar Blade untuk widget baru:

```blade
<x-filament-widgets::widget class="h-full">
    <div class="vision-card h-full flex flex-col justify-between">
        <!-- Header Widget -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-white/5">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                    Judul Widget
                </h3>
                <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                    Deskripsi singkat metrik
                </p>
            </div>
            <!-- Opsional: Badge atau Kotak Ikon -->
            <div class="vision-icon-box vision-icon-box-blue">
                <x-filament::icon icon="heroicon-o-chart-bar" class="h-5 w-5 text-white" />
            </div>
        </div>

        <!-- Body / Konten / Chart -->
        <div class="flex-1 flex flex-col justify-center my-3">
            <!-- Isi konten metrik atau chart di sini -->
        </div>

        <!-- Footer Info -->
        <div class="border-t border-slate-100 dark:border-white/5 pt-2.5 flex items-center justify-between text-xs text-slate-500 dark:text-gray-400">
            <span>Status / Keterangan</span>
            <strong class="text-slate-800 dark:text-white">Nilai Ringkasan</strong>
        </div>
    </div>
</x-filament-widgets::widget>
```

---

## ⚙️ Aturan Wajib untuk AI Developer
1. **Jangan membuat CSS baru jika class di atas sudah ada**. Gunakan token yang sudah terdaftar di `theme.css`.
2. **Hindari Double Card**:
   - Untuk filter: gunakan `.vision-filter-card` langsung tanpa membungkusnya dengan `Section::make()` ganda.
   - Untuk form section: cukup tambahkan `->extraAttributes(['class' => 'vision-card'])` pada `Section::make(...)`.
3. **Selalu Uji Light Mode**: Pastikan teks tidak pernah berwarna putih di atas background putih, dan track gauge yang belum tercapai selalu terlihat dengan kontras slate `#e2e8f0`.
4. **Compile & Sync**: Setelah kode diubah, selalu jalankan `npm run build` dan `graphify update .`.
