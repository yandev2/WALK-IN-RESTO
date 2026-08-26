# Bahan Proposal — Sistem Self-Order Restoran Walk-In (SaaS)

Dokumen ini merangkum **rumusan masalah, tujuan, manfaat,** dan **katalog fitur** berdasarkan sistem yang sudah diimplementasi. Produk tahap ini adalah **walk-in self-order**: tamu datang, duduk di meja pilihannya, scan QR, memesan dari HP, kasir menerima pembayaran, dapur memasak. **Reservasi / booking / DP / e-ticket belum termasuk.**

Sistem bersifat **multi-tenant SaaS**: setiap restoran adalah tenant tersendiri, dengan halaman publik, panel staf, dan (opsional) API tamu.

---

## 1. Latar belakang

Restoran walk-in masih banyak mengandalkan pemesanan lisan ke pelayan, nota kertas, dan koordinasi dapur lewat teriakan atau kertas order. Pola itu menimbulkan antrean di kasir, salah catat item/varian, tagihan tidak seragam (pajak dan service), serta dapur yang tidak tahu urutan dan umur pesanan.

Sisi tamu juga terfragmentasi: informasi jam buka, menu, lokasi, dan promo tersebar di Instagram atau WhatsApp, sementara memesan di tempat tetap bergantung pada staf. Sisi pemilik butuh jejak omzet harian, void, dan aksi sensitif (terima bayar, pindah meja) yang bisa diaudit—tanpa harus membangun sistem sendiri.

Kebutuhan itu semakin nyata untuk restoran yang ingin tamu **memesan sendiri dari HP di meja**, kasir hanya **memverifikasi pembayaran**, dan dapur mendapat **tiket digital per item**. Pembayaran tahap ini masih **manual** (QRIS statis + tunai), bukan payment gateway.

---

## 2. Rumusan masalah

Bagaimana merancang dan membangun sistem informasi restoran walk-in berbasis cloud (SaaS) yang:

1. memungkinkan tamu **memesan sendiri dari HP** setelah scan stiker QR di meja, tanpa membuat akun;
2. mengunci **satu sesi makan (visit) per meja**, termasuk HP kedua yang gabung lewat PIN;
3. menghitung tagihan secara konsisten (subtotal, service charge, PB1, nominal unik QRIS);
4. menempatkan kasir sebagai **penentu lunas** (QRIS maupun tunai), dengan proteksi **geofence GPS** khusus pembayaran tunai dari HP tamu;
5. meneruskan item ke **layar dapur / bar (KDS)** hanya setelah order berstatus lunas;
6. menyediakan **halaman publik (CMS + direktori)** untuk profil, menu, jam buka, dan promo; serta
7. memisahkan akses antar restoran (tenant), peran staf, dan paket langganan.

### Rumusan masalah khusus

1. Bagaimana alur klaim meja (scan QR bertoken, TTL, PIN gabung, status denah) agar tidak terjadi dua visit terbuka di meja yang sama?
2. Bagaimana checkout QRIS memakai **kode unik 1–999** pada nominal agar kasir bisa mencocokkan mutasi, tanpa memasukkan digit itu ke omzet?
3. Bagaimana checkout tunai dari HP tamu menolak pesanan di luar radius resto, tetapi tetap memberi jalur **override GPS** bagi kasir jika akurasi buruk?
4. Bagaimana kasir membuat order untuk tamu tanpa HP, termasuk kalkulasi uang diterima dan kembalian tunai?
5. Bagaimana KDS menampilkan status per item (bukan per meja), timer SLA, dan pemisahan stasiun dapur/bar?
6. Bagaimana struk PDF dan pengiriman WhatsApp (Fonnte) tidak menggagalkan pesanan jika pengiriman gagal?
7. Bagaimana paket langganan membatasi fitur (hanya landing vs operasional penuh) tanpa mencampur data antar tenant?

---

## 3. Tujuan

### 3.1 Tujuan umum

Membangun sistem SaaS restoran walk-in yang menghubungkan **tamu (self-order di meja)**, **kasir (verifikasi bayar & operasional meja)**, **dapur (KDS)**, dan **halaman publik restoran**, dengan perhitungan tagihan, jejak audit, dan laporan omzet yang konsisten.

### 3.2 Tujuan khusus

1. Menyediakan alur tamu: scan QR meja → isi data visit → katalog & keranjang → checkout QRIS/tunai → pantau status item → (opsional) ulasan.
2. Menyediakan panel kasir: antrian pembayaran, terima/tolak, order manual, void, pindah meja, tutup visit, cetak/unduh/kirim ulang struk.
3. Menyediakan KDS per stasiun produksi dengan status item `queued → preparing → ready → served` dan timer sejak order lunas.
4. Menyediakan CMS landing per restoran (profil, banner berjangka, galeri, FAQ, layout seksi, katalog menu publik) plus direktori restoran di beranda platform.
5. Menyediakan pengaturan outlet: jam buka, GPS/geofence, foto QRIS, PB1, service charge, TTL klaim dan antrian kasir, cetak struk otomatis.
6. Menyediakan analitik harian (omzet tunai vs QRIS, jumlah order, void/waste, menu terlaris) dan ekspor laporan (Excel/PDF) lewat antrean.
7. Menyediakan model langganan dua paket, registrasi restoran mandiri, invoice & verifikasi bayar langganan, serta panel operator platform (founder).
8. Menyediakan API publik dan API tamu (sesi, klaim/gabung meja, keranjang, checkout, status order, ulasan) untuk klien web atau aplikasi HP.

---

## 4. Manfaat

### 4.1 Bagi restoran (owner, kasir, dapur)

- Mengurangi salah catat pesanan karena tamu memilih sendiri item, varian, extra, dan catatan (alergi, level pedas).
- Kasir fokus **mencocokkan pembayaran dan meja**, bukan mengetik seluruh order (kecuali tamu tanpa HP).
- Dapur mendapat antrean digital per stasiun, timer warna (hijau / kuning / merah), dan status per hidangan.
- Tagihan seragam: service dan PB1 dihitung otomatis; omzet tidak tercampur dengan digit unik QRIS.
- Jejak aksi sensitif (approve, reject, void, override GPS, pindah meja, kirim ulang struk) tersimpan di log audit, tanpa tombol hapus log.
- Laporan omzet, penjualan item/order, void, dan katalog menu bisa diekspor tanpa mengganggu operasional (diproses di antrean).
- Halaman publik menjadi etalase resmi (jam buka, lokasi, promo, menu, ulasan) tanpa form reservasi yang menyesatkan tamu.

### 4.2 Bagi tamu

- Tidak perlu unduh aplikasi atau daftar akun: cukup scan stiker di meja.
- Bisa pesan add-on di visit yang sama; teman di meja lain HP-nya gabung dengan PIN 4 digit.
- Melihat status masakan di HP; mendapat struk PDF, dan (jika resto mengaktifkan Fonnte) salinan ke WhatsApp.
- Pembayaran tunai dari HP hanya lolos jika berada di sekitar resto (anti-ghost); QRIS tidak memaksa GPS.
- Setelah makan, bisa memberi rating dan komentar yang tampil di landing restoran.

### 4.3 Bagi pengelola platform

- Onboarding restoran lewat `/daftar` (akun owner, slug, pilihan paket, masa uji coba).
- Dua paket: **Landing Page Only** (CMS/direktori) dan **Management KDS** (operasional penuh).
- Panel founder: tenant, paket, invoice langganan, fasilitas & kategori restoran, tampilan beranda platform, rekening billing.
- Isolasi data per tenant; staf restoran tidak melihat data resto lain.

### 4.4 Manfaat operasional / tata kelola

- Satu kamus status untuk denah, visit, order, dan item KDS—menghindari “meja dan order dijadikan satu status”.
- Order ke dapur **hanya setelah lunas**, sehingga dapur tidak memasak pesanan yang belum dibayar.
- Soft delete + halaman trash untuk master data (menu, meja, staf, CMS) agar salah hapus bisa dipulihkan.

---

## 5. Aktor dan perangkat

| Aktor | Perangkat | Peran di sistem |
|---|---|---|
| Tamu host | HP sendiri, tanpa akun | Scan QR, klaim meja, pesan, bayar, lihat status, tampilkan PIN ke teman |
| Tamu gabungan | HP kedua | Sama, setelah memasukkan PIN visit |
| Kasir | Tablet/HP/laptop staf | Antrian bayar, order manual, meja, void, struk |
| Dapur / bar | Tablet KDS | Ubah status item di stasiunnya |
| Owner | Laptop/HP | CMS, menu, setting, staf, laporan, audit, langganan |
| Operator platform | Panel founder | Tenant, paket, invoice, branding beranda |
| Sistem (job) | Server | TTL klaim & antrian kasir, nonaktifkan banner kadaluarsa, kirim WA, ekspor laporan |

---

## 6. Katalog fitur

Fitur dikelompokkan sesuai yang **benar-benar ada di sistem**. Harga paket di bawah mengikuti seed default (dapat diubah di panel founder).

### 6.1 Direktori & onboarding platform

- **Beranda direktori restoran** (`/`): pencarian nama, filter kategori dan fasilitas, urutan (terbaru, dll.), tampilan grid, filter jarak berdasarkan lokasi pengunjung (GPS browser) dengan radius kilometer.
- **Halaman landing per restoran** (`/{slug}`): profil publik sesuai CMS; tidak ada form booking.
- **Katalog menu publik** (`/{slug}/menu`): menu bisa dilihat tanpa scan meja.
- **Registrasi restoran** (`/daftar`): owner membuat akun (email, password), nama resto, slug URL, dan paket langganan; sistem mem-provision tenant, outlet default, dan peran owner.
- **Paket langganan**
  - *Landing Page Only* — CMS, profil, direktori; tanpa pemesanan/KDS/kasir/analitik. Pengaturan outlet terbatas.
  - *Management KDS* — seluruh fitur operasional, menu, KDS, kasir, meja, laporan.
- **Masa uji coba** sesuai pengaturan platform; setelah itu akses panel mengikuti status langganan (aktif / hampir habis / kedaluwarsa / hanya baca).

### 6.2 CMS restoran (halaman publik tenant)

- **Profil restoran**: nama, slug, nama legal, logo, zona waktu, mata uang, deskripsi, warna tema, kategori, fasilitas, tautan sosial.
- **Layout landing**: urutan dan nyala/mati seksi (hero, promo, menu, ulasan, cara pesan, tentang, galeri, jam, lokasi, FAQ, CTA penutup) plus salinan teks per seksi.
- **Banner promo berjangka**: judul, isi, gambar, tanggal mulai–selesai; banner lewat tanggal otomatis tidak tayang.
- **Galeri foto**.
- **FAQ** (tanya–jawab).
- **Pratinjau halaman publik** dari panel admin.
- Master **kategori restoran** dan **fasilitas** dikelola di panel founder, lalu dipilih owner di profil.

### 6.3 Pengaturan outlet & restoran

- **Profil outlet**: nama, telepon, alamat, toggle buka/tutup, aktif/nonaktif.
- **Jam operasional per hari** (jam buka–tutup atau libur).
- **Koordinat GPS** untuk peta landing dan geofence tunai.
- **Radius geofence** (default 30 m) dan **ambang akurasi GPS** (default 50 m).
- **Foto QRIS statis** untuk instruksi bayar non-tunai.
- **PB1** dan **service charge** (mode pajak saat ini *exclusive*: pajak dihitung di atas subtotal + service).
- **TTL klaim meja** (default 10 menit tanpa checkout) dan **TTL antrian kasir** (default 20 menit tanpa keputusan → order dibatalkan otomatis).
- **Cetak struk otomatis** setelah kasir menekan Terima pembayaran (membuka PDF 80 mm di dialog cetak browser).
- **API key Fonnte** per restoran (terenkripsi): jika kosong, kirim struk WhatsApp mati; jika terisi, checkout menampilkan opsi kirim struk.
- Saat resto **Closed**: klaim/checkout baru ditolak; kasir tetap boleh menyelesaikan antrian yang sudah ada.

### 6.4 Menu & stasiun produksi

- **Kategori menu** (urutan, aktif/nonaktif).
- **Item menu**: nama, harga, deskripsi, foto (lebih dari satu), kategori, stasiun KDS, aktif/nonaktif, **habis stok (OOS)** tanpa modul inventaris bahan.
- **Varian** per item (contoh ukuran) dengan selisih harga.
- **Grup extra / modifier**: aturan min–maks pilih, wajib atau opsional, daftar extra berbayar; tamu tidak bisa menambah item jika extra wajib belum dipilih.
- **Catatan per item** di keranjang (contoh: tanpa cabai) ikut ke tiket KDS.
- **Stasiun KDS**: nama, slug URL, aktif/nonaktif (contoh dapur dan bar); item hanya muncul di layar stasiunnya.
- Snapshot harga saat checkout: perubahan harga menu kemudian tidak mengubah order yang sudah masuk.

### 6.5 Meja, QR, dan visit

- **CRUD meja**: kode unik per outlet, kapasitas (informasi, tidak memblokir jumlah orang), area teks bebas, out of service.
- **QR bertoken tertandatangan** (bukan `table_id` polos). Unduh stiker **PNG** atau **PDF**. Regenerasi token mematikan stiker lama; visit yang sedang berjalan tidak putus.
- **Klaim meja**: scan → form nama (opsional) + nomor WhatsApp (wajib untuk klaim mandiri) → visit `open`, PIN 4 digit, TTL klaim.
- **Satu visit terbuka per meja** (kendala unik); scan bersamaan, satu yang menang.
- **Gabung meja**: HP kedua scan meja occupied + PIN benar → perangkat tercatat di visit, **keranjang bersama**. PIN salah berulang (5 kali) mengunci gabung 10 menit; kasir bisa reset PIN.
- **Status denah (diturunkan, bukan diisi bebas)**: out of service → cleaning → occupied → ordering/claiming → available.
- **Pindah visit** ke meja tujuan yang available (audit). Ditolak jika tujuan masih occupied.
- **Tutup visit** hanya jika tidak ada order menggantung (`awaiting_cashier` / `pending_payment`); meja masuk **cleaning**, kasir menandai meja siap → available. Boleh tutup meski masih ada item KDS berjalan, dengan konfirmasi.
- **Ubah nomor WA visit** oleh kasir (struk yang sudah terkirim tetap ke snapshot nomor lama).
- **Buka meja manual** untuk tamu tanpa HP (lewat order kasir): visit dibuat kasir; nomor WA **opsional** pada jalur kasir (berbeda dari klaim scan tamu yang wajib WA).

### 6.6 Self-order tamu (web HP)

Alur: `/order/t/{token}` → menu → keranjang → checkout → bayar → status → ulasan.

- **Sesi tamu** tanpa registrasi (cookie/token perangkat).
- **Katalog** hanya item aktif dan tidak OOS; item OOS di keranjang menghalangi checkout item itu.
- **Keranjang visit**: tambah/ubah/hapus qty, varian, extra, catatan; dua HP di visit yang sama melihat keranjang yang sama.
- **Checkout QRIS**: order `awaiting_cashier`, tampil gambar QRIS + nominal `grand_qris` (total + digit unik 0–999 yang unik di antara order hidup di outlet), salin nominal, unggah bukti transfer **opsional**.
- **Checkout tunai dari HP**: wajib GPS. Jarak ≤ radius dan akurasi cukup → antrian kasir. Di luar radius → ditolak (minta QRIS atau mendekat). Izin ditolak / akurasi buruk → antrian bertanda **butuh override GPS**.
- **Opsi kirim struk WA** hanya jika resto punya key Fonnte.
- **Add-on**: checkout lagi di visit occupied menghasilkan **order baru** (antrian kasir dan tiket KDS terpisah), bukan menggabung ke tiket lama.
- **Pantau status** item (queued / preparing / ready / served) dan ringkasan timer.
- **Halaman bayar** tetap menampilkan order yang sama jika HP di-refresh.
- **Ulasan**: satu ulasan per visit (rating + komentar), tampil di landing dan API publik.
- **Tidak didukung**: split bill, voucher, akun tamu, reservasi.

### 6.7 Pembayaran order (operasional, bukan gateway)

Rumus tagihan:

```
subtotal     = Σ (harga item + varian + extra) × qty
service      = round(subtotal × service_pct)
pb1          = round((subtotal + service) × pb1_pct)
grand_before = subtotal + service + pb1
grand_qris   = grand_before + unique_add (0–999, hanya QRIS)
grand_tunai  = grand_before
```

- Digit unik **tidak** masuk omzet. Omzet = `grand_before` order `paid` (mengikuti aturan void di bawah).
- Kasir **Terima pembayaran** → order `paid` → item masuk KDS. **Tolak** wajib alasan (nominal salah, bukti palsu, tamu batal, dll.) → order `rejected`; nominal unik dilepas.
- Approve **idempotent** (ketukan ganda tidak mendobel).
- **Override GPS** wajib alasan + audit; baru kemudian approve tunai.
- **Uang diterima & kembalian tunai**:
  - Di **order kasir**: input uang diterima, tombol uang pas, kembalian live = uang diterima − total bayar; disimpan di pembayaran (`cash_received`, `change_amount`).
  - Di **approve** order tamu tunai: jika tender belum diisi, kasir mengisi uang diterima; jika sudah diisi saat buat order, modal hanya menampilkan ringkasan bayar/kembalian (tidak ditanya ulang).
  - Uang diterima tidak boleh kurang dari total.
- **Bukti QRIS** opsional; kasir tetap mencocokkan mutasi secara visual.
- Tidak ada tombol “unapprove”; koreksi lewat **void**.

### 6.8 Panel kasir & pesanan

- **Daftar pesanan** dengan filter status; tidak ada edit bebas order `paid`.
- **Widget antrian pembayaran** di dasbor.
- **Terima / tolak pembayaran**, unduh PDF struk, **cetak struk 80 mm**, **kirim ulang struk WhatsApp** (izin `receipt.resend` / `receipt.print`).
- **Void item atau order** wajib alasan. Item masih `queued`: nilai dipotong dari omzet. Item sudah diproses (`preparing`/`ready`/`served`): omzet **tidak** berkurang, ditandai waste.
- **Order kasir (FOH)**: pilih meja available, baris menu (qty, varian, extra, catatan), metode cash/QRIS, preview subtotal–pajak–total, kirim struk opsional, kalkulator tunai. Order tunai kasir **tidak** wajib GPS tamu.

### 6.9 Kitchen Display System (KDS)

- Layar per stasiun (tab), hanya item stasiun itu.
- Status per item: `queued` → `preparing` → `ready` → `served` (bukan status per meja).
- Timer mulai saat order `paid`; indikasi waktu (hijau &lt; 10 menit, kuning 10–20, merah &gt; 20).
- Suara/pengumuman pesanan baru per order lunas yang menyentuh stasiun.
- Item `ready` siap diantar; kasir atau dapur menandai `served`. Mundur `served` → `ready` hanya dalam jendela singkat, ber-audit.
- Tampilan batch (agregat nama+varian+extra) adalah filter tampilan; menyelesaikan tetap per kartu item.
- Item KDS **hanya dibuat setelah paid**. Listrik/tablet mati tidak menghapus antrean di server; timer tidak direset.

### 6.10 Struk digital & WhatsApp

- PDF struk per order `paid` (bukan faktur pajak resmi / NPWP).
- Unduh (tautan bertanda tangan) dan cetak (staf login).
- Antrian `whatsapp_messages`: worker kirim lewat Fonnte (teks ringkas + PDF) memakai key **restoran itu**. Sukses = `sent`, gagal = `failed`; **order tetap paid**.
- Retry otomatis terbatas; kasir boleh kirim ulang jika key masih terisi.
- Struk tunai menampilkan uang diterima dan kembalian **hanya jika** tender tercatat.
- Gagal Fonnte tidak membatalkan dapur atau pembayaran.

### 6.11 Analitik, laporan, dan ekspor

Dasbor (filter periode, zona waktu resto):

- KPI hari ini vs kemarin: omzet, jumlah order, AOV, bauran tunai vs QRIS, void, waste.
- Grafik tren omzet/jumlah order.
- Ringkasan periode dan **menu terlaris** (qty dan pendapatan).
- Ekspor cepat omzet harian dari dasbor.

Halaman **Buat laporan** (antrean, unduh dari riwayat):

| Modul | Isi |
|---|---|
| Ringkasan omzet harian | Omzet paid per hari, tunai vs QRIS |
| Daftar penjualan (item) | Baris item terjual |
| Daftar penjualan (order) | Per order |
| Laporan void item | Item yang divoid |
| Laporan void pesanan | Order void |
| Katalog item menu | Master menu; filter kategori, aktif, OOS |

Format **Excel** atau **PDF**. File hasil di **Riwayat ekspor** (unduh oleh staf yang login).

### 6.12 Staf, peran, audit, dan keamanan data

- Login staf email/username + password.
- Peran & izin (Spatie / Filament Shield) **per restoran (team)**. Izin bisnis antara lain: `order.create`, `order.verify_payment`, `order.reject_payment`, `order.void`, `table.manage`, `kds.view`, `kds.update_status`, `menu.manage`, `cms.manage`, `settings.manage`, `analytics.view`, `audit.view`, `receipt.resend`, `receipt.print`.
- Seed peran khas: owner, kasir, dapur (plus super admin / operator platform).
- CRUD **staf** (avatar, peran); owner terlindungi dari hapus/edit sembarangan; tidak bisa menghapus diri sendiri.
- **Log aktivitas** append-only: approve/reject, override GPS, void, pindah meja, tutup visit, reset PIN, ubah CMS tayang, kirim ulang struk, dll. Tidak ada UI edit/hapus log.
- **Soft delete + trash** untuk master (menu, modifier, stasiun, meja, banner, galeri, FAQ, staf): pulihkan atau hapus permanen.
- Isolasi query per `restaurant_id` (dan `outlet_id` pada data operasional).

### 6.13 Langganan tenant (billing restoran)

- Halaman **Langganan**: status paket, masa berlaku, panduan billing, **buat invoice**, unggah bukti transfer ke rekening platform.
- Invoice: dikirim → menunggu verifikasi → disetujui operator (memperpanjang `subscribed_until`) atau ditolak.
- Invoice otomatis mendekati tanggal habis jika belum ada invoice terbuka.
- Langganan kedaluwarsa mematikan fitur sesuai gate; operator platform tetap bisa masuk.

### 6.14 Panel founder (operator platform)

- **Tenant**: daftar restoran, paket, status langganan, aktif/nonaktif, masa berlaku.
- **Paket langganan**: nama, harga bulanan, matriks fitur (`cms`, `menu`, `operations`, `analytics`, `settings`).
- **Invoice langganan**: verifikasi bukti bayar.
- **Fasilitas** restoran (untuk filter direktori dan profil CMS).
- **Tampilan home**: nama situs, logo, warna, hero, CTA daftar, placeholder pencarian, footer (kontak, sosial, tautan kebijakan).
- **Rekening billing** platform (tujuan transfer langganan).
- Statistik ringkas tenant/invoice.

### 6.15 API (untuk klien eksternal / aplikasi HP)

**Publik** (tanpa sesi meja):

- Daftar dan detail restoran (slug), menu publik, daftar ulasan.

**Tamu** (sesi perangkat + visit):

- Buat sesi; lihat token meja; **klaim** dan **gabung** (PIN); lihat visit; katalog; keranjang CRUD; checkout; daftar/detail order; unggah bukti bayar; lihat/kirim ulasan.

Rate limit dan middleware operasional (resto harus buka + paket mengizinkan `operations`) diterapkan pada klaim/checkout.

### 6.16 Perilaku sistem otomatis

- Visit tanpa checkout melewati TTL klaim → ditutup, meja available, keranjang hilang (kecuali sudah ada order menunggu kasir).
- Order `awaiting_cashier` melewati TTL → `cancelled`, digit unik dilepas; visit tidak otomatis ditutup jika masih relevan.
- Banner CMS di luar tanggal tayang tidak ditampilkan.
- Worker: Fonnte, builder ekspor laporan, invoice langganan.

---

## 7. Yang sengaja tidak termasuk (non-goal tahap ini)

Agar proposal tidak mengklaim fitur yang belum ada:

- Reservasi meja, DP, OTP booking, e-ticket, no-show.
- Payment gateway (Midway/Xendit/Midtrans); kolom provider disiapkan untuk nanti.
- Split bill, gabung/pecah meja, voucher, loyalty, inventaris/resep/HPP, P&L lengkap.
- Denah drag-and-drop; aplikasi runner khusus.
- Pajak *inclusive*; UI multi-cabang (skema outlet sudah ada, UI tahap ini satu outlet default).
- Faktur pajak resmi (NPWP).

---

## 8. Ringkasan nilai produk

Sistem ini menempatkan **meja + visit + order + item KDS** sebagai model terpisah, bukan satu status “meja/order”. Tamu memesan di tempat lewat QR; uang dikonfirmasi manusia (kasir) dengan bantuan nominal unik QRIS dan geofence tunai; dapur hanya memasak yang sudah lunas; owner mendapat etalase publik, omzet yang bisa diaudit, dan langganan berjenjang.

Itu yang membedakannya dari POS tradisional (input hanya di kasir) dan dari aplikasi booking (slot meja di masa depan): produk ini adalah **operasi walk-in hari ini**, dengan fondasi tenant SaaS untuk skala banyak restoran.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
