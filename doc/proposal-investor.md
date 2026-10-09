# 🚀 PROPOSAL INVESTASI & RENCANA PENGEMBANGAN BISNIS
## WALK-IN RESTO: Platform Operasional Restoran Walk-In, Point of Sale (POS), Kitchen Display System (KDS) & CRM Multi-Tenant

> **Kerahasiaan Dokumen**: Sangat Rahasia (*Strictly Confidential*)  
> **Target Audiens**: Calon Investor Strategis / Angel Investor / Venture Capital  
> **Disusun Oleh**: Tim Manajemen & Pendiri Walk-In Resto  
> **Tanggal Pembaruan**: Oktober 2026  
> **Model Bisnis Inti**: *Performance-Based Revenue Sharing / Biaya Layanan Berbasis Omzet Penjualan*  
> **Versi Dokumen**: 2.0 (Seed Round Funding)

---

## 📑 DAFTAR ISI

1. [EXECUTIVE SUMMARY (RINGKASAN EKSEKUTIF)](#1-executive-summary-ringkasan-eksekutif)
2. [PROBLEM STATEMENT (MASALAH OPERASIONAL INDUSTRI F&B)](#2-problem-statement-masalah-operasional-industri-fb)
3. [THE SOLUTION & VALUE PROPOSITION (SOLUSI WALK-IN RESTO)](#3-the-solution--value-proposition-solusi-walk-in-resto)
4. [SYSTEM FLOW & PRODUCT ARCHITECTURE (ALUR SISTEM LENGKAP)](#4-system-flow--product-architecture-alur-sistem-lengkap)
   - 4.1. Alur Interaksi Tamu (Customer Journey)
   - 4.2. Alur Operasional Kasir & Dapur (FOH & BOH Operations)
   - 4.3. Dua Mode Operasional: Standar KDS vs Simple Mode
   - 4.4. Diagram Arsitektur Cloud Multi-Tenant & Mesin Billing
5. [BUSINESS MODEL: BIAYA LAYANAN BERBASIS OMZET RESTORAN](#5-business-model-biaya-layanan-berbasis-omzet-restoran)
   - 5.1. Mengapa Model Berbasis Omzet (*Revenue-Share*) Jauh Lebih Unggul?
   - 5.2. Struktur & Skema Persentase Biaya Layanan (Commission Tiers)
   - 5.3. Mekanisme Perhitungan Omzet Bersih & Rekonsiliasi Otomatis
   - 5.4. Sumber Pendapatan Tambahan (Value-Added Streams)
6. [UNIT ECONOMICS & ANALISIS MARGIN KEUNTUNGAN](#6-unit-economics--analisis-margin-keuntungan)
   - 6.1. Biaya Pokok Penyediaan Layanan (COGS per Tenant)
   - 6.2. Analisis Gross Profit Margin (> 99%)
   - 6.3. Analisis LTV (Lifetime Value) vs CAC (Customer Acquisition Cost)
   - 6.4. Proyeksi Finansial 3 Tahun (Tahun 1 – Tahun 3)
7. [MARKET SIZE & COMPETITIVE LANDSCAPE (PELUANG PASAR & KOMPETITOR)](#7-market-size--competitive-landscape-peluang-pasar--kompetitor)
   - 7.1. Ukuran Pasar (TAM, SAM, SOM)
   - 7.2. Matriks Perbandingan Kompetitor (Disrupsi Model Berbasis Omzet)
8. [PRODUCT ROADMAP & FUTURE PLANS (RENCANA KEDEPAN)](#8-product-roadmap--future-plans-rencana-kedepan)
   - Fase 1 (Selesai): Pondasi Operasional, Kasir POS, KDS, CRM & Mesin Billing
   - Fase 2 (Q1–Q2 2027): Payment Gateway Dinamis & Centralized Multi-Outlet
   - Fase 3 (Q3–Q4 2027): Manajemen Bahan Baku, Resep Otomatis & Prediksi AI
   - Fase 4 (2028): B2B Supply Chain & Embedded Financial Services
9. [THE ASK: PENGADAAN INVESTASI & ALOKASI DANA (USE OF FUNDS)](#9-the-ask-pengadaan-investasi--alokasi-dana-use-of-funds)
10. [EXIT STRATEGY & RETURN ON INVESTMENT (ROI)](#10-exit-strategy--return-on-investment-roi)

---

## 1. EXECUTIVE SUMMARY (RINGKASAN EKSEKUTIF)

Industri kuliner (*Food & Beverage* - F&B) di Indonesia dan Asia Tenggara tengah mengalami disrupsi besar. Pemilik restoran (*owner*) terjepit di antara dua masalah berat: **tingginya biaya tenaga kerja operasional** dan **potongan komisi aplikasi pesan-antar makanan pihak ketiga yang mencekik (20%–30%)**. Di saat yang sama, penyedia software kasir POS konvensional membebani restoran dengan biaya sewa bulanan flat di depan (*upfront fixed subscription*) yang memberatkan, bahkan saat restoran sedang sepi pengunjung.

**Walk-In Resto** hadir dengan model bisnis revolusioner: **Platform Operasional F&B B2B Berbasis Kinerja (Success-Driven / Revenue-Share Model)**. Kami tidak membebankan biaya sewa software bulanan di depan kepada restoran. Restoran mendapatkan akses penuh ke seluruh ekosistem canggih kami:
* **Self-Order QR Meja Mandiri**: Tamu memesan langsung lewat ponsel pintar tanpa unduh aplikasi.
* **Point of Sale (POS Front of House)**: Antarmuka kasir super cepat, rekonsiliasi shift kasir transparan (*Blind Cash Count*), dan notifikasi suara real-time 0ms.
* **Kitchen Display System (KDS Back of House)**: Layar operasional dapur modern tanpa tiket kertas, pengelompokan stasiun masak, bel lonceng otomatis, dan pita waktu masak (*Aging Timer*).
* **WhatsApp CRM & Program Loyalitas**: Perekaman kontak tamu otomatis, tingkatan member loyalitas (*Tiering*), dan penukaran poin langsung ke diskon tagihan.
* **Website Profil Restoran (CMS)**: Landing page publik gratis instan per restoran dengan 3 tema modern.

### Model Monetisasi: Biaya Layanan Berdasarkan Omzet Kasir
Sebagai imbalan atas peningkatan efisiensi dan perputaran omzet restoran, Walk-In Resto hanya mengenakan **Biaya Layanan Berupa Persentase Kecil dari Omzet Bersih Penjualan (*Take Rate: 5% – 10%*)** yang ditagihkan dan direkonsiliasi secara otomatis pada akhir setiap bulan.

```
┌────────────────────────────────────────────────────────────────────────┐
│             KEUNGGULAN UTAMA MODEL BISNIS BERBASIS OMZET               │
├───────────────────────────────────┬────────────────────────────────────┤
│ 1. Zero Barrier to Entry          │ Resto mendaftar tanpa biaya sewa   │
│    (Akuisisi Merchant Cepat)      │ di depan. Konversi sales naik 8x.  │
├───────────────────────────────────┼────────────────────────────────────┤
│ 2. Uncapped Revenue Upside        │ Omzet resto naik, pendapatan kita  │
│    (Potensi Pendapatan Tanpa Batas)│ ikut naik secara otomatis (10x sewa)│
├───────────────────────────────────┼────────────────────────────────────┤
│ 3. Aligned Incentives             │ Platform dan resto sama-sama ingin │
│    (Satu Visi & Saling Menguntungkan) penjualan meningkat lewat sistem.│
├───────────────────────────────────┼────────────────────────────────────┤
│ 4. Ultra-High Gross Margin (>98%) │ Biaya server per tenant tetap flat │
│    (Skalabilitas Sangat Eksponensial) (~Rp 23k), pendapatan jutaan/bln│
└───────────────────────────────────┴────────────────────────────────────┘
```

---

## 2. PROBLEM STATEMENT (MASALAH OPERASIONAL INDUSTRI F&B)

Restoran konvensional di Indonesia menghadapi 5 masalah kritis harian yang langsung menggerus laba bersih mereka:

```
┌────────────────────────────────────────────────────────────────────────┐
│                   5 MASALAH UTAMA RESTORAN SAAT INI                    │
├───────────────────┬────────────────────────────────────────────────────┤
│ 1. Beban Biaya    │ Software POS lama memungut sewa flat Rp 300k–500k/ │
│    Sewa di Depan  │ bulan per terminal kasir. Resto sepi tetap wajib   │
│                   │ bayar sewa, memicu resistensi adopsi software baru.│
├───────────────────┼────────────────────────────────────────────────────┤
│ 2. Antrean Lambat │ Tamu menunggu pelayan membawakan buku menu fisik.  │
│    di Meja Kasir  │ Jam sibuk makan siang berantakan dan antrean kasir │
│                   │ mengular panjang, menurunkan perputaran meja.      │
├───────────────────┼────────────────────────────────────────────────────┤
│ 3. Biaya Pelayan  │ Resto mempekerjakan banyak pelayan hanya untuk     │
│    & Human Error  │ mencatat pesanan. Tulisan tangan waiter sering tak │
│                   │ terbaca koki, memicu salah saji dan komplain tamu. │
├───────────────────┼────────────────────────────────────────────────────┤
│ 4. Kebocoran Kas  │ Ketiadaan audit trail ketat memungkinkan kasir     │
│    & Manipulasi   │ membatalkan nota tanpa izin owner atau salah       │
│                   │ menghitung kembalian uang tunai di laci kasir.     │
├───────────────────┼────────────────────────────────────────────────────┤
│ 5. Kekacauan Dapur│ Tiket bon kertas sering basah, tersiram minyak,    │
│    (Kertas Kusut) │ atau hilang. Koki tidak tahu pesanan mana yang     │
│                   │ sudah menunggu 30 menit vs pesanan baru masuk.     │
└───────────────────┴────────────────────────────────────────────────────┘
```

---

## 3. THE SOLUTION & VALUE PROPOSITION (SOLUSI WALK-IN RESTO)

Walk-In Resto hadir sebagai jawaban tuntas atas seluruh masalah operasional di atas:

1. **Model Kemitraan Adil (Pay-as-You-Grow)**: Restoran tidak dipungut biaya sewa di depan. Jika resto sepi, mereka tidak terbebani biaya sewa tetap; jika resto ramai, resto dengan senang hati membayar biaya layanan atas omzet yang berhasil dicatat sistem.
2. **Zero-Friction Self-Order QR**: Tamu cukup mengarahkan kamera HP ke stiker meja $\rightarrow$ katalog menu visual terbuka $\rightarrow$ tamu memesan bersama dalam keranjang bersama (*Shared Cart*). Waktu tunggu pangkas hingga 60%.
3. **Penghematan Biaya Staf (*Labor Cost Reduction*)**: Restoran dapat menghemat 1–3 staf pelayan per outlet karena tamu memesan mandiri, menghemat biaya gaji Rp 5.000.000 – Rp 15.000.000 per bulan per outlet.
4. **Audit Trail Anti-Bocor & Blind Cash Count**: Setiap transaksi tercatat permanen di log audit (*append-only*). Kasir diwajibkan menghitung uang fisik laci secara mandiri saat tutup shift tanpa melihat contekan saldo sistem, menjamin integritas pembukuan pemilik 100%.
5. **Kitchen Display System (KDS) Cerdas**: Tablet dapur menampilkan pesanan per stasiun masak (Bar/Kitchen), bel audio berdering otomatis, dan indikator warna waktu masak (*Aging Timer*: Hijau, Kuning, Merah) mencegah makanan dingin diantarkan.
6. **Zero Gateway Fee dengan QRIS Cerdas**: Sistem menyediakan checkout **QRIS Statis dengan 3 Digit Unik Cerdas** (anti-collision) sehingga restoran dapat menerima pembayaran langsung ke rekening bank mereka sendiri tanpa potongan komisi payment gateway pihak ketiga!

---

## 4. SYSTEM FLOW & PRODUCT ARCHITECTURE (ALUR SISTEM LENGKAP)

Arsitektur sistem dibangun di atas basis teknologi teruji (Laravel 11, Livewire 3, Filament v4, Web Audio API, MySQL & Redis Caching) yang dirancang untuk skala multi-tenant berdaya tampung ribuan restoran secara aman dan terisolasi.

### 4.1. Alur Interaksi Tamu (Customer Journey)

```
[ Tamu Duduk di Meja ]
        │
        ▼
[ Scan QR Meja Kriptografis ] ── (Otomatis Kunci Meja & Generate PIN 4-Digit)
        │
        ▼
[ Masukkan No. WhatsApp ] ── (Deteksi Member Loyalty & Saldo Poin Otomatis)
        │
        ▼
[ Pilih Menu & Tambah Varian / Modifier ] ── (Multi-HP Keranjang Bersama / Shared Cart)
        │
        ▼
[ Checkout Pembayaran ]
   ├── Pilihan A: Tunai (Wajib Lolos Geofencing GPS Radius 30 Meter)
   └── Pilihan B: QRIS Statis (+ 3 Digit Angka Unik Verifikasi)
        │
        ▼
[ Notifikasi Bel Berdering di Kasir (Latensi 0ms) ]
        │
        ▼
[ Kasir Menekan 'Terima Pembayaran' (1-Klik Idempoten) ]
        │
        ▼
[ Struk Digital Terkirim ke WhatsApp Tamu + Struk Fisik Termal 80mm Dicetak ]
        │
        ▼
[ Tamu Memantau Live Status Masak di HP (Dimasak ➔ Siap Antar ➔ Selesai) ]
        │
        ▼
[ Selesai Makan: Kirim Ulasan Bintang 1-5 & Akumulasi Poin Reward Otomatis ]
```

### 4.2. Alur Operasional Kasir & Dapur (FOH & BOH Operations)

```
KASIR (FRONT OF HOUSE)                         DAPUR (BACK OF HOUSE)
──────────────────────                         ─────────────────────
[ Buka Shift & Modal Awal ]
         │
[ Pesanan Masuk (Bel 0ms) ]
         │
[ Verifikasi Bayar / Approval ] ─────────────> [ Bel Dapur Berdentang (Chime) ]
         │                                              │
         │                                     [ Tiket Masuk Tab Stasiun: Antri ]
         │                                              │
         │                                     [ Koki Klik: 'Dimasak' (Biru) ]
         │                                              │
         │                                     [ Koki Klik: 'Siap Antar' (Kuning) ]
         │                                              │
[ Runner Jemput Makanan ] <──────────────────── [ Tiket Pindah ke Tab 'Siap Antar' ]
         │
[ Makanan Sampai di Meja (Served) ]
         │
[ Tutup Shift: Blind Cash Count ] ──> Rekonsiliasi Otomatis (PAS / MINUS / PLUS)
```

### 4.3. Dua Mode Operasional Fleksibel: Standar KDS vs Simple Mode
Walk-In Resto memiliki fleksibilitas operasional yang tidak dimiliki kompetitor:
* **Standar Mode (Full KDS Dapur)**: Untuk casual dining & resto keluarga dengan alur masak 4 tahap lengkap (*Queued $\rightarrow$ Preparing $\rightarrow$ Ready $\rightarrow$ Served*).
* **Simple Mode (Prasmanan & Kedai Kopi Cepat)**: Untuk gerai fast-food, kedai kopi, dan booth boba di mana kasir merangkap peracik. Begitu kasir menerima pembayaran, status pesanan otomatis langsung berstatus selesai & disajikan (`served`), layar KDS di-bypass, dan meja langsung dibebaskan dalam 5 menit.

### 4.4. Diagram Arsitektur Cloud Multi-Tenant & Mesin Billing

```
┌────────────────────────────────────────────────────────────────────────┐
│              ARSITEKTUR MULTI-TENANT & AUTOMATED BILLING               │
├────────────────────────────────────────────────────────────────────────┤
│                                                                        │
│  [ Restoran Tenant A ]      [ Restoran Tenant B ]      [ Tenant C ]   │
│  (Database Terisolasi)      (Database Terisolasi)      (...)           │
│           │                            │                  │            │
│           └────────────────────────────┼──────────────────┘            │
│                                        ▼                               │
│                         ┌─────────────────────────────┐                │
│                         │   DailyOmzetService Engine  │                │
│                         │ (Filter Order Paid Non-Void)│                │
│                         └──────────────┬──────────────┘                │
│                                        ▼                               │
│                     ┌──────────────────────────────────────┐           │
│                     │ CashierCommissionBillingService      │           │
│                     │ - Hitung Net Omzet Bersih Menu       │           │
│                     │ - Exclude PB1 & Service Charge       │           │
│                     │ - Apply Trial Period Cut-Off         │           │
│                     │ - Apply Commission Rate (5% - 10%)   │           │
│                     └──────────────────┬───────────────────┘           │
│                                        ▼                               │
│                     ┌──────────────────────────────────────┐           │
│                     │ Automated Monthly Commission Invoice │           │
│                     │ (Rekonsiliasi & Penagihan Akhir Bulan)│          │
│                     └──────────────────────────────────────┘           │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 5. BUSINESS MODEL: BIAYA LAYANAN BERBASIS OMZET RESTORAN

### 5.1. Mengapa Model Berbasis Omzet (*Revenue-Share*) Jauh Lebih Unggul?

Perbandingan antara model sewa software flat konvensional vs model biaya layanan berbasis omzet Walk-In Resto:

| Sudut Pandang | Model Sewa Flat POS Konvensional (Moka/Majoo/Pawoon) | **Model Biaya Layanan Berbasis Omzet WALK-IN RESTO** |
|---|---|---|
| **Biaya di Depan (*Upfront Cost*)** | Bayar sewa Rp 300k–500k/bulan di awal. | **Rp 0 (Gratis Pendaftaran & Gratis Masa Uji Coba Trial).** |
| **Beban Mental Pemilik Resto** | Takut rugi jika toko sepi; ragu mencoba. | **Nol Risiko. "Hanya bayar ketika resto Anda menghasilkan omzet."** |
| **Kecepatan Akuisisi Klien (*Sales Friction*)** | Sulit closing; butuh negosiasi lama. | **Sangat Cepat. Tingkat konversi closing tim sales naik drastis.** |
| **Potensi Pendapatan Platform (*Upside*)** | Terkunci mentok di Rp 300k–500k per resto/bulan. | **Tanpa Batas (*Uncapped*): Menghasilkan Rp 2,5 Juta – Rp 15 Juta/bln per resto!** |
| **Penyelarasan Insentif (*Incentive Alignment*)** | Penyedia POS tidak peduli resto laku atau bangkrut. | **Platform termotivasi penuh membantu resto meningkatkan omzet.** |

### 5.2. Struktur & Skema Persentase Biaya Layanan (Commission Tiers)

Platform menerapkan skema persentase biaya layanan yang transparan, berkeadilan, dan disesuaikan dengan skala omzet restoran:

```
┌────────────────────────────────────────────────────────────────────────┐
│          STRUKTUR PERSENTASE BIAYA LAYANAN BERBASIS OMZET              │
├─────────────────────┬───────────────────┬──────────────┬───────────────┤
│ Skala Restoran      │ Omzet Bersih/Bln  │ Rate Komisi  │ Est. Fee/Bln  │
├─────────────────────┼───────────────────┼──────────────┼───────────────┤
│ ☕ Gerai Kecil/Cafe  │ Rp 25.000.000     │    10.0%     │ Rp 2.500.000  │
│ 🍳 Restoran Menengah│ Rp 60.000.000     │     8.0%     │ Rp 4.800.000  │
│ 🍽️ Resto Ramai/Besar │ Rp 150.000.000    │     6.0%     │ Rp 9.000.000  │
│ 🏢 Flagship / Chain │ Rp 300.000.000+   │     5.0%     │ Rp 15.000.000 │
└─────────────────────┴───────────────────┴──────────────┴───────────────┘
```

* **Masa Uji Coba Gratis (*Free Trial Period*)**: Setiap restoran baru mendapatkan masa uji coba operasional gratis selama **14 hingga 30 hari** pertama dengan komisi 0%. Seluruh fitur kasir, KDS, dan QR aktif tanpa batasan.
* **Toleransi Omzet Minimum**: Jika pada bulan tertentu restoran mengalami renovasi atau tutup sementara sehingga omzet bersihnya Rp 0, maka tagihan biaya layanan bulan tersebut otomatis menjadi **Rp 0 (Nihil)**.

### 5.3. Mekanisme Perhitungan Omzet Bersih & Rekonsiliasi Otomatis
Sistem `CashierCommissionBillingService` dan `CommissionReconciliationService` yang tertanam di basis kode kami bekerja secara adil dan matematis:
1. **Dasar Pengenaan Komisi (Eligible Net Omzet)**:
   * Hanya dihitung dari penjualan menu bersih (*Net Menu Sales*) yang telah berstatus lunas (`paid`).
   * **Tidak memotong Pajak Daerah (PB1 10%)**: Pajak pemerintah milik daerah tidak dikenakan komisi.
   * **Tidak memotong Biaya Layanan Resto (*Service Charge*)**: Hak staf resto tidak disentuh.
   * **Mengecualikan Pesanan Batal (*Void*)**: Makanan yang dibatalkan kasir otomatis memotong dasar pengenaan komisi.
2. **Penerbitan Invoice Otomatis di Akhir Bulan**:
   * Setiap tanggal 1 dini hari, sistem otomatis merekapitulasi omzet bersih bulan sebelumnya, menerbitkan invoice resmi di panel admin resto, dan mengirimkan notifikasi rincian pembayaran ke WhatsApp pemilik resto.
   * Pemilik resto dapat memeriksa transparansi laporan di menu **Laporan $\rightarrow$ Rekonsiliasi komisi**.

### 5.4. Sumber Pendapatan Tambahan (Value-Added Streams)
Selain biaya layanan omzet, platform memiliki sumber pendapatan sekunder:
1. **Paket Hardware Starter Kit (One-Time Sale)**: Penjualan paket bundling printer termal 80mm, stand akrilik QR meja anti-air, dan tablet KDS dapur dengan margin keuntungan **30% – 35%**.
2. **Kustom Domain Website Restoran (*White-Label Domain*)**: Biaya penautan domain eksklusif (`menu.namaresto.com`) senilai **Rp 199.000 / tahun**.
3. **Kredit Kuota WhatsApp Pemasaran (*Broadcast CRM*)**: Penjualan paket blast promo WhatsApp resmi ke ribuan database pelanggan yang tersimpan di CRM resto.

---

## 6. UNIT ECONOMICS & ANALISIS MARGIN KEUNTUNGAN

Kombinasi antara **biaya server cloud yang sangat murah per tenant** dan **pendapatan berbasis omzet yang tinggi** menciptakan metrik unit economics terbaik di kelas industri teknologi.

### 6.1. Biaya Pokok Penyediaan Layanan (COGS per Tenant per Bulan)
Biaya langsung infrastruktur (*Cost of Goods Sold*) untuk melayani 1 tenant restoran aktif:

| Komponen Biaya Langsung | Biaya per Tenant / Bulan | Keterangan & Rincian Teknis |
|---|---|---|
| Cloud Compute & Database (AWS / DigitalOcean) | Rp 10.000 | Resource terisolasi cluster multi-tenant. |
| Cloud Object Storage (Foto Menu WebP) | Rp 3.000 | Penyimpanan gambar menu WebP terkompresi. |
| CDN & Network Bandwidth (Cloudflare) | Rp 2.000 | Pengiriman data statis aset cepat ke smartphone tamu. |
| Queue Worker & Routing WhatsApp | Rp 5.000 | Pemeliharaan antrean pengiriman struk digital. |
| Backup & Monitoring Server | Rp 3.000 | Cadangan database harian otomatis & error logs. |
| **TOTAL COGS PER TENANT** | **Rp 23.000** | **Biaya server per restoran per bulan** |

### 6.2. Analisis Gross Profit Margin (> 99%)

Mari kita bedah profitabilitas pada restoran skala menengah rata-rata:
* Rata-rata Omzet Bersih Restoran Mitra: **Rp 50.000.000 / bulan**.
* Rata-rata Take Rate Komisi Biaya Layanan (8%): **Rp 4.000.000 / bulan**.

$$\text{Gross Profit} = \text{Pendapatan Komisi} - \text{COGS}$$
$$\text{Gross Profit} = \text{Rp } 4.000.000 - \text{Rp } 23.000 = \mathbf{Rp\ 3.977.000}$$

$$\text{Gross Profit Margin} = \left( \frac{\text{Rp } 3.977.000}{\text{Rp } 4.000.000} \right) \times 100\% = \mathbf{99.4\%}$$

```
┌────────────────────────────────────────────────────────────────────────┐
│             STRUKTUR MARGIN KEUNTUNGAN BERBASIS OMZET                  │
├────────────────────────────────────────────────────────────────────────┤
│ [████████████████████████████████████████████████████] 99.4% MARGIN    │
│                                                                        │
│ ■ Laba Kotor (Gross Profit): Rp 3.977.000 (99.4%)                      │
│ ░ Biaya Server (COGS):       Rp    23.000 ( 0.6%)                      │
│ ───────────────────────────────────────────────────────────────────────│
│ Total Pendapatan Komisi:     Rp 4.000.000 (100%)                       │
└────────────────────────────────────────────────────────────────────────┘
```

> [!IMPORTANT]
> **Margin Laba Kotor 99.4%** membuktikan bahwa model bisnis berbasis omzet memiliki *Operating Leverage* yang luar biasa. Biaya komputasi server hampir tidak bergerak naik saat omzet restoran mitra melonjak, sehingga hampir seluruh rupiah komisi yang masuk langsung menjadi laba kotor perusahaan!

### 6.3. Analisis LTV (Lifetime Value) vs CAC (Customer Acquisition Cost)
* **Customer Acquisition Cost (CAC)**:
  * Karena model kemitraan ini menawarkan *Zero Upfront Fee* (tanpa biaya sewa di depan), pemilik restoran sangat mudah diakuisisi (*Frictionless Sales Closing*).
  * Biaya akuisisi tim sales langsung per restoran: **Rp 450.000**.
* **Customer Lifetime (Masa Berlangganan)**:
  * Begitu QR akrilik meja tertempel, staf terbiasa dengan KDS, dan database ribuan pelanggan tersimpan di sistem, tingkat retensi (*Stickiness*) menjadi sangat tinggi.
  * Estimasi churn rate konservatif bulanan: **2.5%**.
  * Rata-rata masa hidup kemitraan restoran (*Average Lifetime*): **30 bulan**.
* **Customer Lifetime Value (LTV)**:
  $$\text{LTV} = \text{Rata-rata Komisi Bulanan} \times \text{Gross Margin} \times \text{Masa Hidup}$$
  $$\text{LTV} = \text{Rp } 3.500.000 \times 99.4\% \times 30 \text{ bulan} = \mathbf{Rp\ 104.370.000}$$
* **Rasio LTV : CAC**:
  $$\text{Rasio LTV : CAC} = \frac{\text{Rp } 104.370.000}{\text{Rp } 450.000} = \mathbf{231.9\text{x}}$$
  *(Standar industri modal ventura global menganggap rasio di atas 3x sudah sangat luar biasa).*

### 6.4. Proyeksi Finansial 3 Tahun (Tahun 1 – Tahun 3)
Simulasi pertumbuhan finansial berbasis proyeksi omzet dan penambahan restoran mitra:

| Metrik Finansial (IDR) | Tahun 1 (Traksi Awal) | Tahun 2 (Ekspansi Kota Besar) | Tahun 3 (Skala Nasional) |
|---|---|---|---|
| **Jumlah Restoran Mitra Aktif** | **120 Resto** | **600 Resto** | **2.000 Resto** |
| Rata-rata Omzet Bersih/Resto/Bln | Rp 40.000.000 | Rp 45.000.000 | Rp 50.000.000 |
| Total Transaksi Diproses (GTV/Bulan)| Rp 4,8 Miliar | Rp 27,0 Miliar | Rp 100,0 Miliar |
| Rata-rata Take Rate Komisi Layanan | 8.5% | 7.5% | 7.0% |
| **Monthly Recurring Revenue (MRR)** | **Rp 408.000.000** | **Rp 2.025.000.000** | **Rp 7.000.000.000** |
| **Annual Recurring Revenue (ARR)** | **Rp 4.896.000.000**| **Rp 24.300.000.000**| **Rp 84.000.000.000**|
| Pendapatan Tambahan (Hardware & Add-on)| Rp 120.000.000 | Rp 650.000.000 | Rp 2.100.000.000 |
| **Total Pendapatan Platform (Revenue)**| **Rp 5.016.000.000**| **Rp 24.950.000.000**| **Rp 86.100.000.000**|
| Biaya Infrastruktur Server (COGS) | (Rp 33.120.000) | (Rp 165.600.000) | (Rp 552.000.000) |
| **Laba Kotor (Gross Profit)** | **Rp 4.982.880.000**| **Rp 24.784.400.000**| **Rp 85.548.000.000**|
| *Gross Profit Margin (%)* | *99.3%* | *99.3%* | *99.4%* |
| Beban Operasional & Tim (OpEx) | (Rp 840.000.000) | (Rp 2.800.000.000) | (Rp 7.500.000.000) |
| Beban Penjualan & Pemasaran (Sales) | (Rp 600.000.000) | (Rp 2.400.000.000) | (Rp 6.800.000.000) |
| **Laba Bersih Operasional (EBITDA)** | **Rp 3.542.880.000**| **Rp 19.584.400.000**| **Rp 71.248.000.000**|
| *EBITDA Margin (%)* | *70.6%* | *78.5%* | *82.7%* |

---

## 7. MARKET SIZE & COMPETITIVE LANDSCAPE (PELUANG PASAR & KOMPETITOR)

### 7.1. Ukuran Pasar (Market Sizing)
* **Total Addressable Market (TAM)**:
  * Lebih dari **4,2 juta pelaku usaha kuliner di Indonesia** dengan perputaran transaksi F&B melebihi Rp 800 Triliun per tahun.
* **Serviceable Addressable Market (SAM)**:
  * **250.000 restoran, cafe, coffee shop, bistro, dan warung modern** di kota-kota besar Indonesia yang melayani makan di tempat (*dine-in*) dan belum mengadopsi sistem self-order mandiri terintegrasi KDS.
* **Serviceable Obtainable Market (SOM)**:
  * Target 3 tahun Walk-In Resto: **2.000 restoran mitra aktif** (mengambil ~0.8% dari pasar SAM restoran modern).

```
┌────────────────────────────────────────────────────────────────────────┐
│                        UKURAN PASAR KULINER DINE-IN                    │
├────────────────────────────────────────────────────────────────────────┤
│ ┌────────────────────────────────────────────────────────────────────┐ │
│ │ TAM: 4.200.000+ Pelaku Usaha Kuliner Indonesia                     │ │
│ │ ┌────────────────────────────────────────────────────────────────┐ │ │
│ │ │ SAM: 250.000 Restoran & Cafe Modern Dine-In Terkoneksi Internet│ │ │
│ │ │ ┌────────────────────────────────────────────────────────────┐ │ │ │
│ │ │ │ SOM Target 3 Tahun: 2.000 Restoran Mitra (ARR Rp 84 Miliar) │ │ │ │
│ │ │ └────────────────────────────────────────────────────────────┘ │ │ │
│ │ └────────────────────────────────────────────────────────────────┘ │ │
│ └────────────────────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────────────────────┘
```

### 7.2. Matriks Perbandingan Kompetitor (Disrupsi Model Berbasis Omzet)

| Fitur / Parameter | POS Konvensional (Moka / Majoo / Pawoon) | POS Enterprise Mahal (ESB / Olsera) | **WALK-IN RESTO (Sistem Kami)** |
|---|---|---|---|
| **Model Skema Biaya** | Biaya sewa bulanan flat di depan (Rp 300k–500k/bln) | Biaya lisensi tahunan jutaan rupiah + add-on | **Biaya Layanan Berbasis Omzet (Pay-as-you-grow, Rp 0 di depan)** |
| **Resiko Finansial Resto**| Tinggi (Resto sepi tetap wajib bayar sewa) | Sangat tinggi (Investasi awal puluhan juta) | **Nol Resiko (Hanya bayar ketika resto berhasil cetak omzet)** |
| **Model Pemesanan Meja** | Pelayan catat manual / tamu antre di kasir | Add-on QR rumit atau butuh aplikasi | **Self-Order QR PWA (Tanpa unduh app, instan di browser)** |
| **Keranjang Bersama Multi-HP**| Tidak ada | Jarang / biaya modul tambahan | **Ada (PIN Meja 4-Digit + Shared Cart)** |
| **Kitchen Display System (KDS)**| Harus beli lisensi terpisah | Ada tapi butuh hardware khusus mahal | **Terintegrasi Penuh (Multi-stasiun + Bel Audio Chime)** |
| **Dua Mode Operasional** | Kaku (Hanya 1 alur standar) | Rumit & butuh pelatihan berminggu-minggu | **Dua Mode Fleksibel: Standar KDS vs Simple Mode** |
| **Biaya Gateway Pembayaran** | Potongan MDR 0.7%–2% per nota | Potongan gateway pihak ketiga | **Zero Fee Gateway: QRIS Statis + 3 Digit Unik Cerdas** |
| **Keamanan Pesanan Tunai** | Rawan nota prank / kasir fiktif | Bergantung pengawasan manual | **Proteksi Geofencing GPS Satelit Radius 30 Meter** |
| **Audit Tutup Shift Kasir** | Tutup buku biasa (rawan intip saldo) | Kadang ada rekonsiliasi | **Blind Cash Count (Audit integritas kasir tanpa contekan)** |

---

## 8. PRODUCT ROADMAP & FUTURE PLANS (RENCANA KEDEPAN)

Kami memiliki peta jalan pengembangan produk (*Product Roadmap*) yang terstruktur untuk mengunci retensi pengguna dan memperluas skala bisnis:

```
FASE 1 (SELESAI)         FASE 2 (Q1-Q2 2027)      FASE 3 (Q3-Q4 2027)      FASE 4 (2028)
─────────────────        ───────────────────      ───────────────────      ─────────────
• Walk-In Self-Order     • Payment Gateway        • Smart Recipe &         • B2B Marketplace
• Kasir POS FOH          • Dynamic QRIS           • Auto HPP Food Cost       Bahan Baku Resto
• Multi-Stasiun KDS      • Central Multi-Outlet   • Waste Loss Analytics   • Revenue-Based
• WhatsApp CRM & Loyalty • Runner Mobile App      • Supplier Auto-PO         Financing Resto
• Mesin Billing Komisi   • Custom Domain DNS      • Multi-Warehouse        • AI Demand Forecast
```

### Fase 1: Pondasi Operasional, Kasir POS, KDS, CRM & Mesin Billing (STATUS: LIVE / SELESAI)
* Sistem operasional inti telah selesai 100% dan teruji di lapangan: Pemesanan QR mandiri, POS kasir, KDS dapur, WhatsApp CRM, audit log, denah meja, dan mesin kalkulasi tagihan komisi bulanan otomatis.

### Fase 2: Payment Gateway Dinamis & Centralized Multi-Outlet (Q1 – Q2 2027)
* **Integrasi Payment Gateway Otomatis (Midtrans / Xendit)**: Opsi pembayaran otomatis bagi restoran yang menginginkan verifikasi instan tanpa kasir mencocokkan mutasi m-banking.
* **Manajemen Rantai Multi-Cabang (*Centralized Multi-Outlet Dashboard*)**: Owner dapat memantau 10–50 cabang dari satu layar dasbor eksekutif, menyinkronkan master harga menu antar cabang, dan transfer stok meja.
* **Aplikasi Khusus Runner / Waiter (Mobile Native Android & iOS)**: Aplikasi ringan untuk pelayan menerima notifikasi getar di saku saat masakan dapur siap diantar.

### Fase 3: Manajemen Resep, HPP Bahan Baku & Prediksi AI (Q3 – Q4 2027)
* **Smart Recipe Management & Auto-Deduction Inventory**:
  * Setiap porsi nasi goreng yang terjual otomatis memotong stok beras 150 gram, telur 1 butir, dan minyak 20 ml di gudang secara real-time.
  * Perhitungan **Harga Pokok Penjualan (HPP / Food Cost)** otomatis untuk melihat laba kotor per porsi hidangan secara akurat.
* **Pemesanan Otomatis ke Supplier (*Supplier Auto Purchase Order*)**:
  * Sistem otomatis mengirimkan draf pesanan pembelian (PO) via WhatsApp ke supplier sayur/daging saat stok bahan baku mendekati batas aman (*Reorder Point*).

### Fase 4: Ekosistem Suplai B2B & Layanan Keuangan Restoran (2028)
* **B2B Marketplace Bahan Baku Restoran**: Menghubungkan restoran mitra langsung ke distributor bahan baku pertanian/peternakan skala besar untuk mendapatkan harga bahan baku lebih murah, di mana platform mengambil komisi transaksi B2B.
* **Fasilitas Pembiayaan Berbasis Kinerja Resto (*Revenue-Based Financing*)**:
  * Bekerja sama dengan institusi perbankan / fintech berizin OJK untuk menyalurkan pinjaman modal kerja ekspansi cabang bagi restoran yang memiliki riwayat omzet sehat di sistem Walk-In Resto.

---

## 9. THE ASK: PENGADAAN INVESTASI & ALOKASI DANA (USE OF FUNDS)

Untuk mempercepat pertumbuhan jaringan restoran mitra (*merchant acquisition*) dan mendominasi pasar kuliner di kota-kota besar Indonesia, kami membuka putaran pendanaan tahap awal (*Seed Funding Round*):

### Rincian Penawaran Investasi
* **Target Pendanaan yang Dicari**: **Rp 1.500.000.000** *(Satu Miliar Lima Ratus Juta Rupiah)*.
* **Instrumen Investasi**: Saham Ekuitas (*Equity Shares*) atau *Convertible Note* (SAFE).
* **Valuasi Pra-Investasi (*Pre-Money Valuation*)**: **Rp 8.500.000.000**.
* **Porsi Saham yang Dilepas**: **15.0%** kepemilikan saham ekuitas.

### Alokasi Penggunaan Dana (Use of Funds)

```
┌────────────────────────────────────────────────────────────────────────┐
│                   ALOKASI PENGGUNAAN DANA INVESTASI                    │
├────────────────────────────────────────────────────────────────────────┤
│ [██████████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░] 40%    │
│ 1. Penjualan & Pemasaran B2B (Sales & Marketing):    Rp 600.000.000    │
│    (Tim sales lapangan B2B di 5 kota besar, komisi agen, pameran F&B)  │
├────────────────────────────────────────────────────────────────────────┤
│ [████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░] 25%    │
│ 2. Pengembangan Produk & Engineering:                Rp 375.000.000    │
│    (Modul multi-outlet enterprise, smart inventory, mobile app runner) │
├────────────────────────────────────────────────────────────────────────┤
│ [█████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░] 20%    │
│ 3. Operasional & Customer Onboarding:                Rp 300.000.000    │
│    (Tim edukasi resto, setup stiker akrilik meja, support kasir 24/7)  │
├────────────────────────────────────────────────────────────────────────┤
│ [███████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░] 15%    │
│ 4. Modal Kerja & Legalitas / Cadangan Kas:           Rp 225.000.000    │
│    (Perizinan HKI paten brand, server cloud reserve 18 bulan ke depan) │
├────────────────────────────────────────────────────────────────────────┤
│ TOTAL DANA:                                          Rp 1.500.000.000  │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 10. EXIT STRATEGY & RETURN ON INVESTMENT (ROI)

Kami merancang skenario pengembalian investasi yang realistis dan sangat menguntungkan bagi investor dalam kurun waktu 3 hingga 5 tahun:

1. **Opsi 1: Akuisisi Strategis (*Trade Sale / M&A*) oleh Konglomerat Teknologi / FinTech**:
   * Perusahaan SaaS restoran dengan volume pemrosesan transaksi (GTV) ratusan miliar rupiah merupakan target akuisisi bernilai premium bagi:
     * Perusahaan penyedia Payment Gateway (Xendit, Midtrans, DOKU) untuk mengunci transaksi merchant offline.
     * Raksasa Super-App (Grab, GoTo, Shopee) untuk memperkuat penetrasi F&B dine-in.
     * Bank digital yang membidik data perputaran kas dan penyaluran kredit UMKM kuliner.
   * Valuasi akuisisi SaaS F&B berbasis komisi omzet umumnya diperdagangkan pada kelipatan **4x – 8x ARR**. Pada Tahun ke-3 dengan target ARR Rp 84 Miliar, estimasi nilai akuisisi mencapai **Rp 336 Miliar – Rp 672 Miliar** (memberikan potensi pengembalian modal investor **30x – 60x ROI**).
2. **Opsi 2: Dividen Arus Kas Berkelanjutan (*Dividend Payout*)**:
   * Margin kotor yang mencapai >99% menghasilkan laba operasional (EBITDA) yang sangat tebal (EBITDA margin >70% sejak Tahun ke-1). Perusahaan dapat membagikan dividen tunai rutin tahunan yang sangat menggiurkan kepada para pemegang saham ekuitas.
3. **Opsi 3: Pembelian Kembali Saham oleh Pendiri (*Share Buyback*)**:
   * Pendiri dapat menawarkan opsi pembelian kembali saham investor (*Buyback Clause*) pada tahun ke-4 dengan imbal hasil modal yang disepakati (*IRR minimal 30% per tahun*).

---

## 📞 KONTAK & TAHAP SELANJUTNYA (NEXT STEPS)

Kami menyambut baik kesempatan untuk mendiskusikan peluang investasi ini secara lebih mendalam, melakukan demonstrasi langsung (*Live Product Demo*), serta mengundang Anda mengunjungi restoran mitra yang telah aktif menggunakan sistem Walk-In Resto.

* **Perwakilan Tim Pendiri**: Tim Manajemen Walk-In Resto
* **Email Resmi**: `investor@walkinresto.com` / `management@walkinresto.com`
* **Dokumentasi Produk & Kode**: Tersedia di direktori `doc/manual-book.md` dan `doc/feature.md`
* **Alamat Kantor Operasional**: Jakarta / Bandung, Indonesia

---
*Dokumen ini merupakan ringkasan informasi bisnis dan proyeksi finansial. Informasi disajikan dengan itikad baik berdasarkan data riil pengembangan produk dan kondisi pasar terkini.*
