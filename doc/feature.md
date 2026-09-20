# 📖 DOKUMEN KATALOG FITUR LENGKAP & PANDUAN PROMOSI
## WALK-IN-RESTO: Platform Operasional Restoran Walk-In, Self-Order QR & Manajemen Dapur Multi-Tenant

> **Tujuan Dokumen**: Dokumen ini merangkum seluruh arsitektur fitur yang ada di dalam sistem **Walk-In Resto** secara mendalam, lengkap, dan tanpa ada yang terlewat. Setiap fitur dilengkapi dengan penjelasan fungsi, manfaat bisnis, keunggulan teknis, perbandingan *Before vs After*, serta sudut pandang promosi (*copywriting angle*) yang siap digunakan sebagai materi promosi, presentasi penjualan (*sales pitch*), maupun konten media sosial.

---

## 📑 DAFTAR ISI
1. [Ekosistem Pemesanan Mandiri Tamu (Guest Self-Order & Dining Experience)](#1-ekosistem-pemesanan-mandiri-tamu)
2. [Operasional Kasir & Point of Sale (POS Front of House)](#2-operasional-kasir--point-of-sale-pos)
3. [Kitchen Display System (KDS - Operasional Dapur & Bar)](#3-kitchen-display-system-kds)
4. [Manajemen Meja & Denah Interaktif (Interactive Table Floor Plan)](#4-manajemen-meja--denah-interaktif)
5. [Dua Mode Operasional: Simple Mode vs Standar KDS](#5-dua-mode-operasional-simple-mode-vs-standar-kds)
6. [CMS Restoran & Multi-Template Website Publik](#6-cms-restoran--multi-template-website-publik)
7. [CRM, Program Loyalitas & Retensi Pelanggan](#7-crm-program-loyalitas--retensi-pelanggan)
8. [Blog, SEO & Mesin Pemasaran Konten Terintegrasi](#8-blog-seo--mesin-pemasaran-konten)
9. [Analitik Bisnis, Laporan Finansial & Rekonsiliasi](#9-analitik-bisnis-laporan-finansial--rekonsiliasi)
10. [SaaS Multi-Tenant & Platform Founder Console](#10-saas-multi-tenant--platform-founder-console)
11. [Keamanan, Kontrol Akses & Integritas Data](#11-keamanan-kontrol-akses--integritas-data)

---

## 1. EKOSISTEM PEMESANAN MANDIRI TAMU
*Solusi pemesanan meja instan tanpa antre, tanpa unduh aplikasi, dan tanpa hambatan operasional.*

---

### 1.1. QR Meja Bertoken Kriptografis Tertandatangani (Signed Secure QR Code)
* **Deskripsi & Kegunaan**: Setiap meja memiliki QR Code unik yang dienkripsi menggunakan token bertanda tangan kriptografis (bukan `table_id` angka polos). Ketika tamu memindai QR menggunakan kamera HP, sistem memvalidasi tanda tangan token dan membuka sesi meja secara aman.
* **Manfaat & Nilai Bisnis**: Mencegah kecurangan pengunjung usil yang mengubah parameter URL di browser untuk memesan atas nama meja lain. Stiker meja aman ditempel permanen.
* **Keunggulan Teknis**: Dilengkapi fitur regenerasi token instan dari panel kasir/admin. Jika ada stiker lama rusak atau dicurigai disalahgunakan, token baru dapat diterbitkan dalam 1 detik tanpa memutus sesi tamu yang sedang makan di meja tersebut.
* **Before vs After**:
  * *Before*: Sistem QR konvensional menggunakan URL terbuka seperti `resto.com/order?table=5`. Pengunjung jahil dari meja 1 bisa iseng memesan menu mahal atas nama meja 5.
  * *After*: Token bertanda tangan HMAC mencegah pemalsuan identitas meja 100%. Tamu hanya bisa memesan untuk meja tempat mereka duduk.
* **Sudut Pandang Promosi**: *"Bebas resiko pesanan fiktif! QR Code Meja Walk-In Resto dilindungi enkripsi kelas enterprise."*

---

### 1.2. Klaim Meja Cepat & PIN Proteksi 4 Digit
* **Deskripsi & Kegunaan**: Tamu pertama yang duduk dan memindai meja akan mengklaim meja tersebut (status visit menjadi `active`) dan secara otomatis memperoleh 4-digit PIN acak.
* **Manfaat & Nilai Bisnis**: Menetapkan penanggung jawab sesi meja dan mengunci meja agar tidak dibajak oleh tamu dari meja seberang yang mencoba memindai meja yang sama.
* **Keunggulan Teknis**: Memiliki batas waktu toleransi klaim (*Claim TTL*, default 10 menit). Jika meja diklaim namun tidak ada pesanan yang dibuat hingga waktu habis, meja otomatis dilepas kembali ke status *Available*. Salah memasukkan PIN 5 kali akan mengunci sesi selama 10 menit (proteksi anti-brute force).
* **Before vs After**:
  * *Before*: Dua kelompok tamu berebut meja atau meja tertahan lama oleh orang yang hanya duduk tanpa memesan.
  * *After*: Meja terorganisir rapi dengan penanggung jawab jelas. Meja yang ditinggalkan otomatis kembali tersedia.
* **Sudut Pandang Promosi**: *"Turnover meja lebih cepat dan tertib tanpa perlu pelayan berdiri mencatat pesanan berlama-lama."*

---

### 1.3. Keranjang Bersama Multi-Perangkat (Real-Time Shared Cart)
* **Deskripsi & Kegunaan**: Teman satu meja dapat memindai QR meja yang sama, memasukkan 4-digit PIN meja, lalu langsung tergabung dalam satu keranjang belanja bersama (*Shared Cart*).
* **Manfaat & Nilai Bisnis**: Menghilangkan momen canggung ketika 4–8 orang harus bergantian melihat satu buku menu fisik atau saling meminjam satu HP untuk memilih makanan.
* **Keunggulan Teknis**: Sinkronisasi data real-time berbasis state server. Ketika teman A menambahkan "Es Kopi Susu", item tersebut langsung muncul di layar teman B dan C secara bersamaan.
* **Before vs After**:
  * *Before*: Pelayan menunggu 15 menit di samping meja sementara tamu berdebat memilih menu. Buku menu fisik lecek dan terbatas.
  * *After*: Seluruh tamu di meja membuka menu di HP masing-masing, memilih favorit bersamaan, dan checkout dalam satu tagihan rapi.
* **Sudut Pandang Promosi**: *"Pesan rame-rame jadi seru! Satu meja, banyak HP, satu keranjang bersama."*

---

### 1.4. Katalog Menu Digital Visual & Responsif (Web App Tanpa Instalasi)
* **Deskripsi & Kegunaan**: Tampilan katalog menu berbasis web modern (PWA-ready) dengan navigasi kategori halus, pencarian menu cepat, foto berkualitas tinggi, badge rekomendasi, serta indikator stok habis (*Out of Stock*).
* **Manfaat & Nilai Bisnis**: Tamu tidak perlu membuang memori HP untuk mengunduh aplikasi di App Store/Play Store. Buka kamera $\rightarrow$ scan $\rightarrow$ langsung pesan dalam hitungan detik.
* **Keunggulan Teknis**: Gambar teroptimasi otomatis, responsif untuk semua ukuran layar smartphone Android maupun iOS, dan mendukung tema gelap/terang.
* **Before vs After**:
  * *Before*: Resto harus mencetak ulang ratusan buku menu setiap kali ada perubahan harga atau menu baru (biaya jutaan rupiah). Tamu enggan mengunduh aplikasi khusus resto.
  * *After*: Update harga dan foto menu dalam hitungan detik dari HP/laptop owner, langsung tayang di layar pelanggan tanpa biaya cetak sepeser pun.
* **Sudut Pandang Promosi**: *"Nol hambatan pesan makanan: Tanpa download aplikasi, tanpa registrasi berbelit-belit!"*

---

### 1.5. Varian Menu & Modifier Ekstra Berbayar Bertingkat
* **Deskripsi & Kegunaan**: Fitur kustomisasi hidangan yang lengkap, mulai dari pilihan varian (misal: Regular, Large, Hot, Iced) hingga grup ekstra/tambahan (misal: Tambah Telur, Extra Cheese, Pilihan Level Pedas, Jenis Sambal).
* **Manfaat & Nilai Bisnis**: Meningkatkan *Average Order Value (AOV)* hingga 25–40% melalui *upselling* otomatis. Sistem memaksa tamu memilih opsi wajib sebelum masuk keranjang.
* **Keunggulan Teknis**: Mendukung aturan pemilihan cerdas: *Min Selection*, *Max Selection*, opsi gratis maupun berbayar dengan kalkulasi harga dinamis.
* **Before vs After*:
  * *Before*: Pelayan sering lupa menawarkan *"Mau tambah keju atau ukuran jumbo, kak?"*, atau salah mencatat level kepedasan sehingga pelanggan komplain.
  * *After*: Sistem secara otomatis menawarkan opsi ekstra dan menghitung nominal secara akurat tanpa kesalahan manusia.
* **Sudut Pandang Promosi**: *"Dongkrak omzet penjualan otomatis lewat fitur Add-ons & Extra Topping di setiap menu."*

---

### 1.6. Pencatatan Catatan Khusus Per Item (Special Dietary Notes)
* **Deskripsi & Kegunaan**: Tamu dapat menyematkan catatan spesifik pada setiap porsi hidangan yang dipesan (contoh: *"Pisahkan kuah"*, *"Tanpa daun bawang"*, *"Sedikit gula"*).
* **Manfaat & Nilai Bisnis**: Mengurangi kesalahan pesanan dapur dan menjaga kepuasan pelanggan yang memiliki alergi atau preferensi rasa tertentu.
* **Keunggulan Teknis**: Catatan khusus disematkan pada item dan dicetak langsung ke tiket pesanan KDS dapur dan struk kasir.
* **Before vs After*:
  * *Before*: Pelayan menulis catatan tangan di kertas pesanan dengan tulisan sulit dibaca koki, berujung salah saji dan makanan terbuang (*waste*).
  * *After*: Catatan instruksi tertampil jelas dan tebal di layar dapur (KDS) dan struk kasir.
* **Sudut Pandang Promosi**: *"Pesanan kustom pelanggan tersampaikan 100% presisi ke staf dapur."*

---

### 1.7. Checkout QRIS Otomatis dengan Digit Unik Cerdas (Smart Anti-Collision QRIS)
* **Deskripsi & Kegunaan**: Saat tamu memilih pembayaran QRIS, sistem menampilkan gambar QRIS statis outlet dengan nominal yang ditambahkan 3 digit angka unik (0–999) secara otomatis.
* **Manfaat & Nilai Bisnis**: Kasir dapat langsung mengenali pesanan mana yang sudah dibayar hanya dengan melihat 3 angka terakhir di mutasi m-banking/QRIS tanpa perlu menunggu payment gateway mahal.
* **Keunggulan Teknis**: Digit unik dijamin tidak akan bentrok (*unique collision-free*) di antara seluruh pesanan yang sedang aktif di outlet tersebut. Nominal unik ini tidak dihitung sebagai omzet restoran, menjaga buku keuangan tetap bersih.
* **Before vs After*:
  * *Before*: Kasir bingung membedakan pembayaran saat ada 5 tamu membayar nominal yang sama persis (misal sama-sama Rp 50.000) pada jam sibuk.
  * *After*: Tamu A membayar Rp 50.124, Tamu B membayar Rp 50.389. Kasir mencocokkan mutasi hanya dalam 2 detik!
* **Sudut Pandang Promosi**: *"Terima QRIS statis resto Anda sendiri dengan kecepatan verifikasi layaknya sistem otomatis perbankan!"*

---

### 1.8. Checkout Tunai Terproteksi Geofencing GPS (Anti-Fake Order)
* **Deskripsi & Kegunaan**: Pemesanan dengan metode bayar tunai (Cash) mewajibkan browser tamu mengirimkan koordinat GPS. Sistem memeriksa apakah tamu benar-benar berada di dalam radius restoran (misal radius 30 meter).
* **Manfaat & Nilai Bisnis**: Menghilangkan risiko pesanan fiktif (*order prank*) dari luar restoran di mana orang iseng memesan makanan tunai namun tidak ada di lokasi.
* **Keunggulan Teknis**: Dilengkapi evaluasi akurasi sinyal GPS. Jika tamu berada di luar radius, checkout ditolak dengan instruksi untuk mendekat atau membayar via QRIS. Jika GPS akurasi rendah, order ditandai *"Butuh Override Kasir"* dengan audit trail ketat.
* **Before vs After*:
  * *Before*: Resto takut menerapkan self-order tunai karena rawan di-prank orang dari luar resto yang memesan makanan hingga dapur terlanjur memasak.
  * *After*: Dapur tenang! Pembayaran tunai hanya bisa diproses oleh tamu yang terbukti secara fisik berada di dalam meja restoran.
* **Sudut Pandang Promosi**: *"Keamanan tingkat tinggi: Sistem Geofencing GPS menjamin pesanan tunai hanya datang dari tamu asli di meja."*

---

### 1.9. Pelacakan Status Masak Real-Time (Live Order Cooking Status)
* **Deskripsi & Kegunaan**: Setelah pembayaran diverifikasi, halaman HP tamu otomatis beralih ke layar pelacak status: *Menunggu Kasir* $\rightarrow$ *Sedang Dimasak* $\rightarrow$ *Siap Diantar* $\rightarrow$ *Selesai*.
* **Manfaat & Nilai Bisnis**: Menenangkan tamu yang menunggu. Tamu tidak perlu memanggil pelayan berkali-kali untuk menanyakan *"Makanan saya sudah jadi belum?"*.
* **Keunggulan Teknis**: Terhubung langsung dengan Kitchen Display System (KDS) staf dapur melalui polling latar belakang yang ringan dan hemat kuota internet.
* **Before vs After*:
  * *Before*: Tamu gelisah, bolak-balik melambaikan tangan ke pelayan: *"Mbak, pesanan saya meja 4 kok belum keluar ya?"*.
  * *After*: Tamu santai memantau indikator status hidangannya yang bergerak secara transparan langsung di layar ponsel mereka.
* **Sudut Pandang Promosi**: *"Pengalaman makan modern: Tamu bisa memantau proses memasak pesanannya secara live."*

---

### 1.10. Pemesanan Tambahan Tanpa Putus Sesi (Seamless Add-on Ordering)
* **Deskripsi & Kegunaan**: Tamu yang ingin memesan makanan atau minuman tambahan (*repeat order*) dapat langsung menambahkan item ke keranjang dan checkout lagi tanpa perlu membuka sesi baru.
* **Manfaat & Nilai Bisnis**: Memaksimalkan penjualan makanan penutup (*dessert*) atau minuman tambahan saat tamu sedang asyik mengobrol.
* **Keunggulan Teknis**: Sistem membuat record order baru yang tetap tertaut pada visit meja yang sama, sehingga tiket pesanan baru langsung terkirim ke dapur tanpa merusak tiket lama.
* **Before vs After*:
  * *Before*: Mau nambah es teh manis harus memanggil pelayan, pelayan mengambil buku nota, mencatat ulang dari awal, dan mengantarnya ke kasir.
  * *After*: Tamu tinggal klik menu di HP, konfirmasi bayar, dan es teh manis langsung masuk antrean barista dalam hitungan detik.
* **Sudut Pandang Promosi**: *"Pesan tambah tanpa ribet: Tambah porsi, tambah minum, tinggal sekali klik di meja."*

---

### 1.11. Ulasan & Rating Pelanggan Terverifikasi (Verified Customer Feedback)
* **Deskripsi & Kegunaan**: Sesaat setelah sesi makan selesai, tamu dapat memberikan rating bintang (1–5) dan komentar ulasan langsung di layar HP mereka.
* **Manfaat & Nilai Bisnis**: Mengumpulkan masukan otentik untuk perbaikan layanan resto dan menampilkan ulasan positif di website landing resto untuk menarik pelanggan baru.
* **Keunggulan Teknis**: Dibatasi maksimal 1 ulasan valid per sesi visit terverifikasi untuk mencegah *spamming review* atau ulasan palsu dari pihak luar.
* **Before vs After*:
  * *Before*: Pemilik resto tidak tahu keluhan pelanggan sampai mereka melihat bintang 1 di Google Review yang merusak reputasi.
  * *After*: Ulasan langsung masuk ke sistem internal resto seketika, memberi kesempatan bagi manajemen untuk segera menindaklanjuti.
* **Sudut Pandang Promosi**: *"Bangun reputasi bintang 5 dengan sistem ulasan pelanggan nyata langsung di meja makan."*

---

### 1.12. Program Poin Loyalitas Tamu Berbasis WhatsApp (Zero-Friction Loyalty)
* **Deskripsi & Kegunaan**: Setiap kali tamu menyelesaikan pesanan dan memasukkan nomor WhatsApp, sistem secara otomatis mengkalkulasi poin loyalitas berdasarkan nominal belanja. Tamu dapat menggunakan poin tersebut sebagai potongan harga (diskon) pada kunjungan berikutnya.
* **Manfaat & Nilai Bisnis**: Mengunci loyalitas pelanggan agar terus kembali berkunjung (*repeat customer*) tanpa perlu kartu member fisik atau download aplikasi membership terpisah.
* **Keunggulan Teknis**: Terintegrasi penuh dalam `CustomerCrmService`. Dilengkapi tingkatan pelanggan (*Tiering*: Reguler, Silver, Gold, VIP) dan perlindungan idempotensi agar poin tidak pernah terhitung ganda.
* **Before vs After*:
  * *Before*: Kartu stamp kertas sering hilang atau tertinggal di rumah, pelanggan malas mendaftar program loyalty yang rumit.
  * *After*: Cukup sebutkan/masukkan nomor WhatsApp, poin otomatis terkumpul dan diskon langsung terpotong di layar.
* **Sudut Pandang Promosi**: *"Bikin pelanggan setia datang lagi dan lagi dengan program loyalitas instan berbasis nomor WhatsApp!"*

---

## 2. OPERASIONAL KASIR & POINT OF SALE (POS)
*Antarmuka kasir super cepat, tangguh, anti-selip, dan dilengkapi notifikasi suara instan.*

---

### 2.1. Panel Kasir Cepat (Create Cashier Order / FOH POS)
* **Deskripsi & Kegunaan**: Antarmuka kasir khusus staf depan (*Front of House*) untuk melayani tamu walk-in manual yang memesan langsung di kasir (tamu tanpa HP atau takeaway).
* **Manfaat & Nilai Bisnis**: Melayani transaksi offline dengan kecepatan tinggi dalam jam sibuk makan siang atau antrean takeaway yang padat.
* **Keunggulan Teknis**: Dilengkapi pencarian menu instan, filter kategori keyboard-friendly, kalkulator kembalian cepat, dan tombol uang pas (*Exact Cash Buttons*).
* **Before vs After*:
  * *Before*: Kasir lambat mencari menu di aplikasi POS yang berat, antrean mengular panjang, pelanggan komplain.
  * *After*: Pencarian menu kilat dengan shortcut pintar, transaksi tuntas dalam hitungan detik.
* **Sudut Pandang Promosi**: *"Kasir lincah, antrean lancar: POS modern yang dirancang untuk kecepatan transaksi puncak."*

---

### 2.2. Notifikasi Audio Real-Time Dual-Engine (0ms Bell Notification)
* **Deskripsi & Kegunaan**: Bunyi bel notifikasi instan saat ada pesanan baru dari tamu yang masuk ke layar kasir, didukung teknologi *Dual-Engine* (Web Audio API Buffer + HTMLMediaElement Fallback).
* **Manfaat & Nilai Bisnis**: Kasir tidak perlu terus-menerus menatap layar monitor. Kasir bisa mengerjakan hal lain (meracik minuman, menyapa tamu) dan langsung tahu saat ada pesanan masuk.
* **Keunggulan Teknis**:
  1. Suara kustom MP3 didekodekan langsung ke RAM browser (*AudioBuffer*), menghasilkan latensi pemutaran 0 milidetik.
  2. Bebas macet: Dilengkapi *safety timeout* (4 detik) dan manajemen antrean anti-bentrok (*anti-collision rush queue*).
  3. Tetap berbunyi meski tab kasir berada di latar belakang (*background tab protection*).
  4. Didukung tombol *Mute/Unmute* dan *Uji Coba Suara* langsung di pojok layar.
* **Before vs After*:
  * *Before*: Pesanan tamu terabaikan selama 10 menit di meja karena kasir tidak menyadari ada pesanan masuk di layar monitor.
  * *After*: Bel berbunyi nyaring dan ramah seketika tamu menekan tombol checkout, kasir langsung sigap memproses.
* **Sudut Pandang Promosi**: *"Anti-kelewatan! Notifikasi suara real-time memastikan kasir merespons setiap pesanan tamu tanpa jeda."*

---

### 2.3. Verifikasi & Approval Pembayaran Idempoten (Idempotent 1-Click Verification)
* **Deskripsi & Kegunaan**: Tombol satu klik bagi kasir untuk menyetujui pembayaran (QRIS atau Cash). Begitu disetujui, pesanan otomatis berstatus `paid` dan tiket masakan langsung dikirim ke layar dapur.
* **Manfaat & Nilai Bisnis**: Mencegah dobel transaksi akibat kasir menekan tombol approval berulang kali secara tidak sengaja saat koneksi lambat.
* **Keunggulan Teknis**: Dilindungi kunci konkurensi tingkat database (*Database Transaction Locks*). Jika kasir A sudah menekan approve, kasir B yang membuka pesanan sama akan menerima pesan aman *"Pesanan sudah diproses kasir lain"*.
* **Before vs After*:
  * *Before*: Kasir menekan tombol bayar 2 kali karena internet lambat, transaksi tercatat ganda di pembukuan dan pesanan dapur terduplikasi.
  * *After*: Transaksi dijamin 100% idempoten dan aman, pembukuan selalu akurat.
* **Sudut Pandang Promosi**: *"Keamanan pembukuan terjamin: Sistem transaksi pintar yang mencegah dobel data."*

---

### 2.4. Penolakan Pesanan dengan Alasan Wajib (Reject Order with Audit Reason)
* **Deskripsi & Kegunaan**: Jika tamu mengunggah bukti bayar QRIS palsu, nominal tidak sesuai, atau tamu mendadak batal, kasir dapat menolak pesanan dengan wajib memilih/mengisi alasan penolakan.
* **Manfaat & Nilai Bisnis**: Menghentikan pesanan curang sebelum masuk ke dapur, menghemat bahan baku dan tenaga koki.
* **Keunggulan Teknis**: Begitu pesanan ditolak (`rejected`), digit unik QRIS langsung dilepas kembali ke pool agar bisa digunakan oleh pelanggan lain. Alasan penolakan dicatat permanen dalam audit trail.
* **Before vs After*:
  * *Before*: Kasir menghapus pesanan sembarangan tanpa ada catatan, owner tidak tahu mengapa pesanan tersebut dibatalkan.
  * *After*: Setiap penolakan terdokumentasi rapi lengkap dengan nama kasir, jam penolakan, dan alasan spesifiknya.
* **Sudut Pandang Promosi**: *"Proteksi dari manipulasi pembayaran dan pembatalan sepihak."*

---

### 2.5. Struk Digital Otomatis via WhatsApp (Paperless PDF & Message via Fonnte)
* **Deskripsi & Kegunaan**: Pengiriman struk digital secara otomatis ke nomor WhatsApp tamu dalam bentuk pesan teks ringkas beserta tautan unduh dokumen PDF resmi sesaat setelah pembayaran diterima.
* **Manfaat & Nilai Bisnis**: Menghemat biaya kertas struk termal hingga 80%, ramah lingkungan (*paperless*), dan nomor WhatsApp tamu tersimpan otomatis untuk kebutuhan pemasaran masa depan.
* **Keunggulan Teknis**: Terintegrasi langsung dengan API Fonnte per restoran. Berjalan asinkron di antrean *background worker* sehingga jika internet WhatsApp mengalami gangguan, operasional kasir dan dapur tetap berjalan normal tanpa hambatan. Kasir juga memiliki tombol *Kirim Ulang Struk*.
* **Before vs After*:
  * *Before*: Kertas struk kasir sering habis mendadak, tamu membuang struk kertas sembarangan ke lantai, biaya roll kertas termal membengkak.
  * *After*: Struk elegan langsung masuk ke WhatsApp saku pelanggan, tersimpan rapi selamanya, dan meningkatkan citra profesional resto.
* **Sudut Pandang Promosi**: *"Restoran modern tanpa sampah kertas: Kirim struk PDF langsung ke WhatsApp pelanggan secara otomatis!"*

---

### 2.6. Cetak Struk Termal Standar 80mm Cepat
* **Deskripsi & Kegunaan**: Fitur cetak langsung ke printer kasir termal ukuran standar 80mm dengan tata letak struk yang rapi, mencakup nama outlet, nomor pesanan, meja, detail item, subtotal, pajak PB1, service charge, uang diterima, dan kembalian.
* **Manfaat & Nilai Bisnis**: Tetap mengakomodasi tamu yang membutuhkan bukti fisik pembayaran untuk keperluan klaim kantor (*reimbursement*).
* **Keunggulan Teknis**: Dilengkapi opsi *Auto-Print on Payment Approval*. Kasir menekan terima pembayaran $\rightarrow$ dialog cetak printer termal langsung terbuka otomatis tanpa klik tambahan.
* **Before vs After*:
  * *Before*: Format cetak berantakan, tulisan terpotong di pinggir kertas, kasir harus klik 4–5 kali untuk mencetak struk.
  * *After*: Desain struk presisi 80mm standar industri resto, tercetak rapi hanya dengan 1 kali klik.
* **Sudut Pandang Promosi**: *"Dukungan penuh printer kasir termal 80mm: Cetak cepat, format sempurna, siap pakai."*

---

### 2.7. Manajemen Pembatalan & Koreksi Menu (Item & Order Void with Waste Tracking)
* **Deskripsi & Kegunaan**: Fitur pembatalan item atau seluruh pesanan setelah pembayaran lunas yang disertai pencatatan alasan resmi dan perhitungan dampak limbah (*waste*).
* **Manfaat & Nilai Bisnis**: Mencegah kebocoran kas akibat staf membatalkan transaksi di belakang punggung pemilik resto.
* **Keunggulan Teknis**:
  * Jika item dibatalkan saat masih di antrean dapur (`queued`), nominal dipotong dari omzet bersih.
  * Jika item dibatalkan saat sudah mulai dimasak (`preparing`, `ready`, atau `served`), omzet tidak dipotong dan nominal dicatat sebagai kerugian bahan (*waste*) karena bahan baku sudah terpakai di wajan.
* **Before vs After*:
  * *Before*: Makanan batal dimasak tetap dibuang tanpa pencatatan, selisih bahan baku di gudang tidak pernah cocok dengan laporan penjualan.
  * *After*: Pengendalian kerugian transparan. Owner mengetahui dengan pasti berapa rupiah makanan yang terbuang dan staf mana yang membatalkannya.
* **Sudut Pandang Promosi**: *"Cegah kebocoran modal: Kontrol ketat pembatalan menu dengan pelacakan limbah makanan (waste management)."*

---

### 2.8. Manajemen Shift Kasir Lengkap (Cashier Shifts & Cash Reconciliation)
* **Deskripsi & Kegunaan**: Fitur pembukaan dan penutupan shift kasir (*Open/Close Shift*), pencatatan modal awal kas laci (*Starting Cash*), pergerakan kas kecil (*Petty Cash In/Out*), hingga rekonsiliasi kas aktual saat tutup shift.
* **Manfaat & Nilai Bisnis**: Menghilangkan selisih uang tunai di meja kasir. Setiap rupiah uang tunai di laci dapat dipertanggungjawabkan kepada kasir yang bertugas pada jam tersebut.
* **Keunggulan Teknis**: Sistem menghitung total penjualan tunai, penjualan non-tunai, kas masuk, dan kas keluar secara otomatis. Saat tutup shift, kasir memasukkan uang fisik yang dihitung di laci, dan sistem langsung menampilkan selisih lebih/kurang (*Over/Shortage*).
* **Before vs After*:
  * *Before*: Kasir pergantian shift sering berselisih paham karena uang di laci tidak cocok dengan total penjualan dan tidak diketahui siapa yang bersalah.
  * *After*: Serah terima shift transparan dalam 3 menit. Cetak slip rekap shift (X/Z Report) langsung dari printer kasir.
* **Sudut Pandang Promosi**: *"Tutup buku kasir tanpa pusing: Rekonsiliasi modal dan omzet laci kasir otomatis, rapi, dan anti-selisih."*

---

### 2.9. Pindah Meja & Ubah Nomor WhatsApp Visit
* **Deskripsi & Kegunaan**: Kasir dapat memindahkan tamu yang sedang makan dari Meja A ke Meja B yang masih kosong, serta memperbarui nomor WhatsApp tamu jika terjadi salah input saat scan awal.
* **Manfaat & Nilai Bisnis**: Fleksibilitas tinggi dalam menangani dinamika tamu di lapangan (contoh: tamu ingin pindah dari area *indoor* ke *outdoor* karena ingin merokok).
* **Keunggulan Teknis**: Seluruh pesanan, keranjang bersama, dan status KDS otomatis ikut berpindah ke meja baru tanpa mengacaukan antrean dapur. Aksi pemindahan dicatat dalam log aktivitas audit.
* **Before vs After*:
  * *Before*: Tamu pindah meja membuat pelayan salah mengantar makanan ke meja lama, pesanan tertukar, pelanggan kesal.
  * *After*: Meja dipindahkan di sistem dalam 2 ketukan, layar dapur dan denah kasir langsung terbarui seketika.
* **Sudut Pandang Promosi**: *"Hadapi situasi resto yang dinamis dengan fleksibilitas pemindahan meja instan."*

---

## 3. KITCHEN DISPLAY SYSTEM (KDS)
*Layar operasional dapur & bar tanpa kertas tiket, anti-hilang, dan terorganisir per stasiun.*

---

### 3.1. Layar Dapur Multi-Stasiun Terisolasi (Station-Based Display)
* **Deskripsi & Kegunaan**: Layar monitor/tablet dapur yang dapat dibagi berdasarkan stasiun kerja fisik (contoh: Stasiun Bar Minuman, Stasiun Dapur Panas/Kitchen, Stasiun Grill/Panggangan, Stasiun Dessert).
* **Manfaat & Nilai Bisnis**: Staf bar hanya melihat pesanan minuman; koki dapur hanya melihat hidangan makanan. Dapur menjadi tenang, fokus, dan tidak berisik.
* **Keunggulan Teknis**: Filter query database per `station_id`. Menu yang dipesan pelanggan di satu nota otomatis dipecah secara cerdas ke stasiun masing-masing.
* **Before vs After*:
  * *Before*: Kertas bon menumpuk di meja koki, koki harus berteriak ke barista: *"Woy, meja 3 pesen jus alpukat 2!"*, kertas basah atau hilang kena minyak.
  * *After*: Setiap bagian dapur melihat pesanan masing-masing di layar tablet digital tahan banting, pesanan tertata rapi sesuai waktu masuk.
* **Sudut Pandang Promosi**: *"Ubah dapur resto Anda menjadi dapur modern berstandar internasional tanpa kertas tiket kusut!"*

---

### 3.2. Alur Produksi Masak 4 Tahap (Queued $\rightarrow$ Preparing $\rightarrow$ Ready $\rightarrow$ Served)
* **Deskripsi & Kegunaan**: Manajemen siklus memasak bertahap yang dapat diubah statusnya oleh staf dapur dengan satu sentuhan di layar:
  1. **Queued (Antri)**: Makanan baru masuk setelah kasir menerima pembayaran.
  2. **Preparing (Dimasak)**: Koki mulai menyalakan kompor/memproses bahan.
  3. **Ready (Siap Antar)**: Makanan sudah matang di piring dan siap diambil pelayan/runner.
  4. **Served (Diantar)**: Makanan telah mendarat di meja pelanggan.
* **Manfaat & Nilai Bisnis**: Pelayan tahu kapan harus menjemput makanan di dapur tanpa perlu bolak-balik menengok ke dalam dapur.
* **Keunggulan Teknis**: Tab terpisah untuk item yang berstatus *Siap Antar* (*Ready Tab*) sehingga runner/waiter memiliki dashboard khusus hidangan siap saji.
* **Before vs After*:
  * *Before*: Makanan matang dibiarkan dingin di meja pasing karena pelayan tidak tahu makanan sudah selesai dimasak.
  * *After*: Makanan diantar dalam kondisi hangat dan segar seketika status di layar berubah menjadi 'Ready'.
* **Sudut Pandang Promosi**: *"Alur kerja dapur terkoordinasi sempurna: Dari wajan ke meja tamu dalam kondisi terbaik."*

---

### 3.3. Bel Notifikasi Dapur Otomatis (Kitchen Service Bell Sound Alert)
* **Deskripsi & Kegunaan**: Bunyi bel dapur harmonik otomatis (*service bell chime*) yang berdering nyaring di speaker tablet dapur setiap kali ada pesanan baru yang harus dimasak.
* **Manfaat & Nilai Bisnis**: Koki yang sedang sibuk memotong daging atau mengaduk wajan langsung waspada saat ada pesanan baru masuk tanpa perlu melirik layar setiap detik.
* **Keunggulan Teknis**: Berjalan dengan polling mandiri 5 detik (`wire:poll.5s="pollAlerts"`), audio ramah lingkungan (*synthesizer fallback* jika file audio belum terunduh), dan dilengkapi proteksi pemulihan otomatis jika layar disentuh.
* **Before vs After*:
  * *Before*: Pesanan baru diam di layar selama 15 menit tanpa dimasak karena koki tidak memperhatikan layar monitor.
  * *After*: Ting! Bel dapur berbunyi tegas, staf dapur langsung sigap menyalakan kompor.
* **Sudut Pandang Promosi**: *"Suara bel dapur otomatis memastikan tidak ada pesanan pelanggan yang tertunda sedetik pun."*

---

### 3.4. Indikator Timer Warna Waktu Masak (Aging Timer Band)
* **Deskripsi & Kegunaan**: Setiap kartu pesanan di KDS dilengkapi timer waktu tunggu yang berubah warna secara visual:
  * **Hijau**: Waktu tunggu aman (&lt; 10 menit).
  * **Kuning**: Waktu tunggu mulai lama (10–20 menit).
  * **Merah**: Waktu tunggu kritis (&gt; 20 menit) — harus segera diprioritaskan!
* **Manfaat & Nilai Bisnis**: Mencegah komplain tamu akibat makanan terlalu lama disajikan dan menjaga *Standard Operating Procedure (SOP)* kecepatan saji restoran.
* **Keunggulan Teknis**: Waktu dihitung dari cap waktu pembayaran riil di database server. Jika tablet dapur mati atau browser di-refresh, timer tidak ter-reset ke nol.
* **Before vs After*:
  * *Before*: Koki mendahulukan pesanan yang baru masuk dan melupakan pesanan tamu yang sudah menunggu 30 menit.
  * *After*: Kartu merah berkedip di layar dapur menjadi alarm visual bagi koki untuk segera menuntaskan pesanan yang tertunda.
* **Sudut Pandang Promosi**: *"Jaga standar kecepatan saji resto Anda dengan indikator visual waktu masak cerdas."*

---

### 3.5. Tampilan Agregasi Masak Sekaligus (Batch Cooking View)
* **Deskripsi & Kegunaan**: Koki dapat melihat total porsi hidangan yang sama yang sedang mengantre di seluruh meja (contoh: *"Total 5x Nasi Goreng Spesial"* dari Meja 2, Meja 5, dan Meja 8).
* **Manfaat & Nilai Bisnis**: Menghemat waktu dan gas elpiji. Koki bisa memasak 5 porsi nasi goreng sekaligus dalam satu wajan besar daripada memasak satu per satu secara terpisah.
* **Keunggulan Teknis**: Pengelompokan cerdas berdasarkan kesamaan nama menu, varian, dan catatan ekstra tanpa mengorbankan independensi penyelesaian kartu per meja.
* **Before vs After*:
  * *Before*: Koki memasak 1 porsi ayam bakar untuk meja 2. Lima menit kemudian baru tahu meja 4 juga pesan ayam bakar, harus memanggang ulang dari awal.
  * *After*: Koki melihat total ringkasan menu yang sama dan memasaknya sekaligus dalam satu siklus kerja efisien.
* **Sudut Pandang Promosi**: *"Hemat waktu, hemat bahan bakar: Tingkatkan efisiensi dapur hingga 2x lipat dengan Batch Cooking View."*

---

### 3.6. Ketahanan Kegagalan Perangkat & Pemadaman (Cloud Queue Resilience)
* **Deskripsi & Kegunaan**: Seluruh antrean KDS disimpan aman di server basis data *cloud*, bukan di memori lokal tablet.
* **Manfaat & Nilai Bisnis**: Menghilangkan kekhawatiran operasional jika tablet dapur kehabisan baterai, terjatuh, rusak terkena air, atau listrik padam.
* **Keunggulan Teknis**: Begitu tablet dinyalakan kembali atau diganti dengan HP/laptop staf lain, seluruh antrean beserta sisa waktu masaknya langsung muncul kembali persis seperti kondisi terakhir.
* **Before vs After*:
  * *Before*: Komputer POS lokal rusak atau printer kasir korslet, seluruh bon pesanan hilang total dan resto lumpuh total.
  * *After*: Tinggal ambil HP staf mana saja, buka link KDS, dan operasional dapur kembali berjalan normal dalam 30 detik!
* **Sudut Pandang Promosi**: *"Sistem berbasis Cloud anti-rusak: Dapur tetap jalan meski tablet jatuh atau mati lampu!"*

---

## 4. MANAJEMEN MEJA & DENAH INTERAKTIF
*Visualisasi tata letak restoran yang elegan, informatif, dan dinamis.*

---

### 4.1. Visual Floor Plan Editor Drag-and-Drop (Denah Meja Visual)
* **Deskripsi & Kegunaan**: Pemilik dan manajer resto dapat mengatur posisi meja secara visual di atas kanvas tata letak (*floor plan*) sesuai dengan denah fisik asli restoran.
* **Manfaat & Nilai Bisnis**: Mempermudah staf baru dan kasir dalam mengenali lokasi meja pelanggan secara instan tanpa perlu menghafal nomor meja.
* **Keunggulan Teknis**: Mendukung *drag-and-drop* koordinat X dan Y secara real-time, pengelompokan area (*Tabs*: Indoor, Outdoor, VIP, Lantai 2), serta tombol *Atur Otomatis (Auto-Layout Grid)* yang merapikan posisi meja secara otomatis.
* **Before vs After*:
  * *Before*: Kasir hanya melihat daftar teks nomor meja yang kaku di tabel biasa, pelayan baru kebingungan mencari posisi Meja 14 ada di sebelah mana.
  * *After*: Denah visual interaktif menampilkan tata letak meja persis seperti ruangan resto nyata.
* **Sudut Pandang Promosi**: *"Kelola ruangan restoran semudah bermain game dengan Visual Floor Plan Editor interaktif."*

---

### 4.2. Status Meja Cerdas Otomatis (Smart Automated Table Lifecycle)
* **Deskripsi & Kegunaan**: Indikator warna status meja yang diperbarui secara otomatis oleh sistem berdasarkan aktivitas pelanggan:
  * 🟢 **Available (Tersedia)**: Meja kosong dan bersih, siap ditempati tamu baru.
  * 🟡 **Claiming / Ordering (Memilih Menu)**: Tamu sudah duduk dan sedang memilih menu di HP.
  * 🔵 **Occupied (Terisi)**: Tamu sudah memesan dan hidangan sedang disiapkan/dinikmati.
  * 🟠 **Cleaning (Pembersihan)**: Tamu sudah selesai makan, meja menunggu staf untuk dibersihkan.
  * ⚫ **Out of Service (Rusak/Nonaktif)**: Meja sedang diperbaiki atau ditutup sementara.
* **Manfaat & Nilai Bisnis**: Kasir dan greeter depan (*host*) mengetahui meja mana yang kosong hanya dengan satu lirik layar tanpa perlu berjalan memeriksa seluruh ruangan restoran.
* **Keunggulan Teknis**: Status meja diturunkan (*derived status*) secara konsisten dari siklus hidup *Visit* dan *Order* sehingga tidak mungkin terjadi inkonsistensi data.
* **Before vs After*:
  * *Before*: Pelayan mengantar tamu ke meja di pojok, ternyata mejanya masih kotor berantakan bekas tamu sebelumnya, membuat tamu risih.
  * *After*: Meja kotor ditandai warna oranye 'Cleaning'. Setelah dilap bersih, staf menekan 'Tandai Siap' dan meja seketika berubah hijau 'Available'.
* **Sudut Pandang Promosi**: *"Maksimalkan kapasitas resto: Pantau ketersediaan dan kebersihan meja secara live dari meja kasir."*

---

### 4.3. Generator Stiker QR Code Berkualitas Tinggi (Printable PNG & Vector PDF)
* **Deskripsi & Kegunaan**: Generator otomatis untuk mencetak stiker QR Code meja dalam format gambar resolusi tinggi PNG maupun dokumen siap cetak PDF.
* **Manfaat & Nilai Bisnis**: Memudahkan tim restoran atau percetakan untuk langsung mencetak stiker akrilik meja dengan branding rapi.
* **Keunggulan Teknis**: Desain PDF dilengkapi panduan batas potong (*crop marks*), nomor meja tebal, dan instruksi ringkas scan bagi pelanggan.
* **Before vs After*:
  * *Before*: Owner harus mendesain stiker QR satu per satu di Photoshop atau Canva, memakan waktu seharian.
  * *After*: Klik tombol unduh PDF, seluruh stiker meja dari Meja 1 hingga Meja 50 langsung tercetak rapi siap tempel dalam 1 menit!
* **Sudut Pandang Promosi**: *"Cetak stiker QR meja instan dalam format PDF profesional siap kirim ke percetakan."*

---

## 5. DUA MODE OPERASIONAL RESTORAN
*Satu sistem untuk semua jenis bisnis F&B: Dari coffee shop kilat hingga restoran keluarga multi-stasiun.*

---

### 5.1. Perbandingan Karakteristik: Simple Mode vs Standar KDS

| Kebutuhan / Karakteristik | ⚡ Simple Mode (Cepat & Ringkas) | 🍳 Standar Mode (Full KDS Dapur) |
|---|---|---|
| **Cocok Untuk** | Coffee shop, fast food, booth boba, gerai takeaway, bakery, food court. | Casual dining, resto keluarga, seafood, bar & grill, fine dining. |
| **Keberadaan Staf Dapur** | Tanpa staf dapur khusus / barista merangkap kasir. | Ada staf koki, barista, dan runner terpisah. |
| **Layar Dapur (KDS)** | Tidak wajib dibuka (dapat dinonaktifkan). | Menggunakan tablet/monitor KDS per stasiun masak. |
| **Alur Tiket Masak** | Otomatis ditandai *Served/Selesai* saat kasir menerima pembayaran. | Melalui tahapan masak lengkap (*Queued $\rightarrow$ Preparing $\rightarrow$ Ready $\rightarrow$ Served*). |
| **Penyelesaian Meja** | Meja otomatis tertutup dan siap diisi tamu berikutnya. | Meja melalui fase *Occupied* dan *Cleaning*. |

* **Manfaat & Nilai Bisnis**: Fleksibilitas luar biasa bagi pemilik usaha. Satu outlet kedai kopi santai bisa memakai *Simple Mode*, sementara outlet cabang restoran besarnya memakai *Standar KDS* dalam satu akun yang sama.
* **Keunggulan Teknis**: Cukup beralih dengan satu tombol toggle di *Pengaturan Outlet*. Tidak memerlukan instalasi software ulang atau perubahan skema database.
* **Sudut Pandang Promosi**: *"Fleksibilitas tanpa batas: Sempurna untuk kedai kopi cepat saji hingga restoran mewah bintang lima."*

---

## 6. CMS RESTORAN & MULTI-TEMPLATE WEBSITE PUBLIK
*Ubah restoran Anda menjadi destinasi kuliner digital terpopuler dengan website profil instan.*

---

### 6.1. Website Profil Otomatis Per Restoran (`/{slug}`)
* **Deskripsi & Kegunaan**: Setiap restoran yang terdaftar di platform secara instan memperoleh landing page website resmi dengan alamat web khusus (contoh: `resto.com/kopi-nusantara`).
* **Manfaat & Nilai Bisnis**: Restoran memiliki eksistensi digital profesional tanpa perlu membayar biaya pembuatan website jutaan rupiah ke web developer. Tautan website bisa langsung dipasang di bio Instagram dan profil TikTok resto.
* **Keunggulan Teknis**: Dilengkapi informasi jam operasional buka/tutup dinamis (*Open Now / Closed Today*), peta petunjuk arah Google Maps, fasilitas resto (Wi-Fi, Parkir, Musholla, AC, Smoking Area), serta tautan sosial media dan WhatsApp resmi.
* **Before vs After*:
  * *Before*: Calon pelanggan bingung mencari daftar menu dan lokasi resto di Instagram karena tidak memiliki website resmi.
  * *After*: Sekali klik tautan di bio, pelanggan bisa melihat foto suasana, fasilitas, lokasi peta, dan membaca seluruh menu secara lengkap.
* **Sudut Pandang Promosi**: *"Gratis website restoran profesional siap pakai untuk pasang di bio Instagram & TikTok resto Anda!"*

---

### 6.2. Pilihan Multi-Template Desain Visual Modern
* **Deskripsi & Kegunaan**: Restoran dapat memilih berbagai tema tampilan visual landing page yang sesuai dengan karakter konsep usahanya:
  * **Classic Template**: Tampilan bersih, elegan, dan profesional untuk restoran keluarga dan bistro.
  * **Foodie Template**: Visual berani (*vibrant colors*) dengan fokus pada foto hidangan lezat untuk cafe dan resto cepat saji.
  * **Glassmorphism Template**: Estetika modern mewah dengan efek kaca tembus pandang (*frosted glass backdrop-filter*) untuk bar, lounge, dan resto kekinian.
* **Manfaat & Nilai Bisnis**: Menampilkan identitas visual restoran yang berkelas, unik, dan memikat mata pengunjung (*stunning first impression*).
* **Keunggulan Teknis**: Dibangun menggunakan *CSS Design Token* yang konsisten, mendukung *Dark Mode* otomatis, dan dioptimasi untuk kecepatan buka maksimal (*Core Web Vitals*).
* **Sudut Pandang Promosi**: *"Pilih desain website favorit Anda: Dari tema klasik elegan hingga glassmorphism modern yang memukau mata."*

---

### 6.3. Banner Promo Berjadwal Otomatis (Scheduled Promotional Banners)
* **Deskripsi & Kegunaan**: Manajer resto dapat mengunggah banner promosi (diskon akhir pekan, paket Ramadhan, menu musiman) lengkap dengan tanggal mulai dan tanggal berakhir penayangan.
* **Manfaat & Nilai Bisnis**: Menjalankan promosi tepat waktu tanpa takut lupa menurunkan banner promo yang sudah kedaluwarsa.
* **Keunggulan Teknis**: Query database memeriksa rentang tanggal `start_date` dan `end_date` secara otomatis. Banner yang sudah melewati tanggal kedaluwarsa tidak akan dirender oleh sistem.
* **Before vs After*:
  * *Before*: Banner promo diskon Hari Kemerdekaan masih terpampang di bulan Oktober karena staf lupa menghapusnya, memicu protes pelanggan yang menagih diskon.
  * *After*: Pasang jadwal tayang sekali di awal, banner promo muncul dan hilang secara otomatis tepat waktu.
* **Sudut Pandang Promosi**: *"Promosi otomatis tanpa repot: Pasang jadwal promo hari ini, sistem yang kelola penayangannya."*

---

### 6.4. Tata Letak Fleksibel Drag-and-Drop (Manage Landing Layout)
* **Deskripsi & Kegunaan**: Pemilik resto dapat menyalakan/mematikan dan mengubah urutan seksi di website landing (Hero Banner, Menu Unggulan, Galeri Foto, Ulasan Tamu, Cara Pesan, Lokasi & Jam, FAQ).
* **Manfaat & Nilai Bisnis**: Kustomisasi penuh tanpa keahlian coding (*Zero Code Customization*).
* **Keunggulan Teknis**: Dilengkapi fitur live preview langsung dari panel admin Filament.
* **Sudut Pandang Promosi**: *"Bebas atur tampilan website Anda sendiri dengan antarmuka seret-dan-lepas yang super mudah."*

---

## 7. CRM, PROGRAM LOYALITAS & RETENSI PELANGGAN
*Ubah pengunjung pertama menjadi pelanggan setia yang terus berbelanja.*

---

### 7.1. Database Pelanggan Otomatis (Zero-Friction WhatsApp CRM)
* **Deskripsi & Kegunaan**: Sistem mencatat identitas pelanggan (Nama, Nomor WhatsApp, Frekuensi Kunjungan, Total Uang yang Dibelanjakan, dan Tanggal Terakhir Berkunjung) secara otomatis setiap kali pelanggan bertransaksi.
* **Manfaat & Nilai Bisnis**: Membangun aset database pelanggan restoran yang sangat berharga tanpa memaksa tamu mengisi formulir kertas yang merepotkan.
* **Keunggulan Teknis**: Pembersihan dan normalisasi nomor WhatsApp standar Indonesia (`08...` $\rightarrow$ `628...`) secara otomatis. Dilengkapi fitur segmentasi loyalitas pelanggan.
* **Before vs After*:
  * *Before*: Resto melayani 3.000 pengunjung per bulan tetapi tidak memiliki satu pun nomor kontak pelanggan untuk diajak promosi kembali.
  * *After*: Resto memiliki ribuan kontak pelanggan loyal yang terverifikasi dan siap dihubungi saat ada peluncuran menu baru.
* **Sudut Pandang Promosi**: *"Kumpulkan ribuan database pelanggan restoran Anda secara otomatis tanpa formulir yang membosankan!"*

---

### 7.2. Poin Belanja & Tingkatan Member (Tiers: Reguler, Silver, Gold, VIP)
* **Deskripsi & Kegunaan**: Pelanggan secara otomatis naik level tingkatan keanggotaan (*Membership Tier*) seiring bertambahnya akumulasi belanja mereka, dengan imbalan persentase perolehan poin yang lebih tinggi.
* **Manfaat & Nilai Bisnis**: Memberikan rasa dihargai (*gamification reward*) bagi pelanggan loyal agar mereka enggan berpaling ke restoran kompetitor.
* **Keunggulan Teknis**: Kalkulasi poin transparan, saldo poin tersimpan rapi di akun pelanggan, dan dapat dipotongkan langsung di kasir maupun saat checkout mandiri di HP.
* **Before vs After*:
  * *Before*: Pelanggan yang sudah belanja 5 juta rupiah diperlakukan sama persis dengan orang yang baru pertama kali datang membeli air mineral.
  * *After*: Pelanggan VIP merasa bangga dan diistimewakan, terus membelanjakan uangnya di restoran Anda.
* **Sudut Pandang Promosi**: *"Bikin pelanggan bangga jadi member VIP: Sistem reward poin otomatis bikin omzet repeat order melesat!"*

---

## 8. BLOG, SEO & MESIN PEMASARAN KONTEN
*Tarik ribuan pengunjung baru dari mesin pencari Google secara organik tanpa biaya iklan.*

---

### 8.1. Manajemen Artikel & Konten Edukasi Kuliner Multi-Bahasa
* **Deskripsi & Kegunaan**: Modul CMS Blog terintegrasi untuk mempublikasikan artikel kuliner, resep rahasia chef, liputan acara, hingga ulasan menu dalam 2 bahasa (Bahasa Indonesia & English).
* **Manfaat & Nilai Bisnis**: Menempatkan restoran di peringkat atas pencarian Google (*SEO Ranking*) saat turis atau warga lokal mencari rekomendasi makanan (misal: *"Restoran seafood terbaik di Bandung"*).
* **Keunggulan Teknis**:
  * Didukung manajemen Kategori, Tag, dan profil Blogger/Author khusus.
  * Dilengkapi fitur Like artikel dan moderasi komentar publik.
  * Struktur SEO lengkap: Tag Judul, Meta Deskripsi, Heading semantik, dan Peta Situs XML Otomatis (`/sitemap.xml`).
* **Before vs After*:
  * *Before*: Resto harus terus membakar uang jutaan rupiah untuk pasang iklan berbayar di Instagram Ads / TikTok Ads agar dikenal orang.
  * *After*: Artikel blog mendatangkan ribuan calon pelanggan baru dari Google secara gratis setiap hari sepanjang tahun.
* **Sudut Pandang Promosi**: *"Dominasi pencarian Google: Website resto Anda dilengkapi blog kuliner profesional berstandar SEO internasional."*

---

## 9. ANALITIK BISNIS, LAPORAN FINANSIAL & REKONSILIASI
*Data akurat untuk keputusan bisnis pemilik restoran yang presisi dan menguntungkan.*

---

### 9.1. Dasbor KPI Real-Time Hari Ini vs Kemarin
* **Deskripsi & Kegunaan**: Dasbor analitik eksekutif yang merangkum kesehatan bisnis resto hari ini secara langsung:
  * **Omzet Bersih Harian** (vs capaian kemarin).
  * **Total Transaksi Pesanan** (Order Count).
  * **Rata-rata Nilai Transaksi per Meja** (*Average Order Value / AOV*).
  * **Bauran Pembayaran** (Persentase Tunai vs QRIS).
  * **Pelacakan Void & Makanan Terbuang** (*Waste Loss Amount*).
* **Manfaat & Nilai Bisnis**: Pemilik resto dapat memantau detak jantung bisnis dari mana saja secara live tanpa perlu menunggu laporan manual kasir di malam hari.
* **Keunggulan Teknis**: Dihitung berdasarkan zona waktu lokal restoran (`Asia/Jakarta`, `Asia/Makassar`, dll.) sehingga cut-off pergantian hari selalu akurat.
* **Before vs After*:
  * *Before*: Owner baru tahu resto rugi atau omzet turun di akhir bulan saat uang di rekening bank sudah menipis.
  * *After*: Pantau grafik omzet menit ke menit langsung dari layar smartphone Anda di mana pun Anda berada.
* **Sudut Pandang Promosi**: *"Ketahui detak jantung bisnis restoran Anda secara real-time dari genggaman tangan."*

---

### 9.2. Peringkat Menu Terlaris & Kontribusi Pendapatan (Top Menu Analytics)
* **Deskripsi & Kegunaan**: Grafik dan tabel analitik yang menampilkan menu mana yang paling banyak terjual secara kuantitas (*Best Seller by Volume*) dan menu mana yang menyumbang pendapatan rupiah terbesar (*Top Revenue Contributor*).
* **Manfaat & Nilai Bisnis**: Memudahkan manajemen dalam menentukan menu mana yang harus terus dipromosikan dan menu lambat jual mana (*slow moving*) yang harus dievaluasi atau diganti resepnya.
* **Keunggulan Teknis**: Perhitungan omzet menu mengecualikan item yang dibatalkan/divoid, menjamin integritas statistik data.
* **Sudut Pandang Promosi**: *"Ambil keputusan berbasis data: Ketahui menu tambang emas dan menu lambat jual Anda dalam 1 detik."*

---

### 9.3. Mesin Ekspor Laporan Finansial Multi-Format (Excel & PDF)
* **Deskripsi & Kegunaan**: Generator laporan keuangan dan operasional yang dapat diunduh dalam format Excel (.xlsx) atau PDF resmi:
  1. **Laporan Ringkasan Omzet Harian**: Pembagian omzet harian tunai vs QRIS.
  2. **Daftar Penjualan Item (Item Sales Detail)**: Rincian setiap porsi hidangan yang terjual beserta varian dan ekstra.
  3. **Daftar Penjualan Order**: Rincian nomor nota, meja, kasir penanggung jawab, pajak, dan diskon.
  4. **Laporan Audit Void Item & Pesanan**: Rekapitulasi hidangan yang dibatalkan beserta nama staf dan alasan pembatalan.
  5. **Katalog Master Menu**: Ekspor daftar harga master menu aktif dan stok habis.
* **Manfaat & Nilai Bisnis**: Mempermudah pelaporan pajak bulanan, perhitungan bagi hasil mitra/investor, dan pengarsipan data akuntansi.
* **Keunggulan Teknis**: Pemrosesan ekspor berjalan di latar belakang (*queue worker*). File hasil ekspor tersimpan aman di menu *Riwayat Ekspor* staf yang bersangkutan.
* **Before vs After*:
  * *Before*: Akuntan resto lembur 3 hari di akhir bulan menyusun nota kertas satu per satu ke dalam spreadsheet Excel.
  * *After*: Pilih rentang tanggal, klik tombol ekspor, dan laporan Excel rapi siap saji dalam 5 detik!
* **Sudut Pandang Promosi**: *"Tutup buku bulanan secepat kilat: Ekspor laporan penjualan lengkap ke Excel & PDF sekali klik."*

---

## 10. SAAS MULTI-TENANT & PLATFORM FOUNDER CONSOLE
*Infrastruktur cloud berdaya skala tinggi untuk mengelola ribuan restoran dalam satu platform SaaS.*

---

### 10.1. Onboarding Mandiri Restoran Baru (`/daftar`)
* **Deskripsi & Kegunaan**: Portal registrasi publik bagi calon pemilik restoran untuk mendaftarkan bisnis mereka sendiri, memilih paket langganan, dan langsung mendapatkan sistem operasional aktif secara instan.
* **Manfaat & Nilai Bisnis**: Platform SaaS dapat berkembang secara mandiri (*self-service onboarding*) tanpa campur tangan teknis manual dari pihak pengembang.
* **Keunggulan Teknis**: Provisioning otomatis tenant: membuat record `Restaurant`, menyiapkan satu `Outlet` default, membuat akun pengguna `User`, serta memasang peran `Owner` beserta seluruh perizinan operasional dalam satu transaksi database atomik.
* **Sudut Pandang Promosi**: *"Mulai digitalisasi restoran Anda hari ini: Daftar mandiri dalam 2 menit, langsung siap pakai!"*

---

### 10.2. Sistem Langganan & Penagihan Berjenjang (Subscription Gate System)
* **Deskripsi & Kegunaan**: Kontrol fitur berbasis paket langganan restoran (contoh paket: *Landing Page Only* vs *Management KDS Full Operasional*).
* **Manfaat & Nilai Bisnis**: Memonetisasi platform SaaS melalui model pendapatan berulang (*Monthly/Yearly Recurring Revenue - MRR/ARR*).
* **Keunggulan Teknis**: Dilengkapi sistem penagihan invoice otomatis, verifikasi transfer rekening bank, masa uji coba gratis (*Trial Period*), dan masa tenggang keterlambatan bayar (*Grace Period Mode*). Jika masa tenggang habis, panel beralih ke mode *Read-Only* secara aman tanpa menghapus data resto.
* **Sudut Pandang Promosi**: *"Model bisnis SaaS siap pakai: Kelola langganan ribuan tenant resto dengan billing invoice otomatis."*

---

### 10.3. Direktori Restoran Global dengan Pencarian Jarak GPS (`/`)
* **Deskripsi & Kegunaan**: Halaman portal utama platform yang menampilkan direktori seluruh restoran mitra yang terdaftar, dilengkapi filter kategori makanan, fasilitas, dan pencarian jarak radius kilometer berdasarkan lokasi GPS smartphone pengunjung.
* **Manfaat & Nilai Bisnis**: Memberikan nilai tambah promosi gratis bagi restoran yang berlangganan karena resto mereka akan ditemukan oleh pengunjung direktori lokal.
* **Keunggulan Teknis**: Perhitungan jarak matematis menggunakan formula geospasial Haversine yang cepat dan akurat.
* **Sudut Pandang Promosi**: *"Bukan cuma software kasir: Restoran Anda otomatis dipromosikan di Direktori Kuliner Global kami!"*

---

### 10.4. Pengaturan Suara Notifikasi Platform (Manage Sound Notifications)
* **Deskripsi & Kegunaan**: Fitur khusus bagi Founder/Operator untuk mengunggah file audio MP3 kustom untuk nada bel kasir dan nada bel dapur di tingkat platform.
* **Manfaat & Nilai Bisnis**: Memberikan pengalaman audio yang khas, profesional, dan menyenangkan bagi staf resto saat pesanan masuk.
* **Keunggulan Teknis**: Dilengkapi tombol uji dengar audio langsung di panel admin, penyimpanan aman di storage publik, serta resolusi URL relatif yang tahan terhadap protokol SSL/HTTPS.
* **Sudut Pandang Promosi**: *"Nada notifikasi berkelas yang dapat disesuaikan dengan identitas brand Anda."*

---

## 11. KEAMANAN, KONTROL AKSES & INTEGRITAS DATA
*Keamanan data tingkat enterprise untuk menjaga kerahasiaan dan integritas operasional restoran.*

---

### 11.1. Role-Based Access Control (RBAC) Berbasis Tenant
* **Deskripsi & Kegunaan**: Pembagian hak akses staf yang sangat ketat dan terisolasi per restoran (Owner, Kasir, Dapur, Waiter, Super Admin).
* **Manfaat & Nilai Bisnis**: Staf dapur tidak bisa mengintip laporan omzet kasir; kasir tidak bisa mengubah pengaturan profil resto milik owner; staf resto A tidak bisa melihat data resto B.
* **Keunggulan Teknis**: Ditenagai oleh *Spatie Permission* dan *Filament Shield* dengan isolasi tim (`team_id = restaurant_id`).
* **Sudut Pandang Promosi**: *"Keamanan hak akses terjamin: Setiap staf hanya memiliki akses sesuai tugas dan tanggung jawabnya."*

---

### 11.2. Log Aktivitas Anti-Manipulasi (Immutable Audit Trail)
* **Deskripsi & Kegunaan**: Setiap aksi krusial di sistem (Terima Pembayaran, Tolak Pesanan, Override GPS, Void Item, Buka Shift, Tutup Shift, Pindah Meja, Reset PIN) dicatat permanen dalam riwayat log aktivitas (*Activity Log*).
* **Manfaat & Nilai Bisnis**: Memberikan transparansi penuh bagi pemilik resto untuk mengaudit setiap kejadian mencurigakan di lapangan.
* **Keunggulan Teknis**: Tabel log bersifat *Append-Only* (tidak menyediakan tombol edit maupun tombol hapus), mencakup stempel waktu milidetik, ID pengguna, alamat IP, nilai data lama (*old values*), dan nilai data baru (*new values*).
* **Sudut Pandang Promosi**: *"Audit bisnis 100% transparan: Pantau setiap aktivitas staf tanpa celah manipulasi."*

---

### 11.3. Perlindungan Keranjang Sampah (Soft Deletes & Trash Recovery)
* **Deskripsi & Kegunaan**: Penghapusan data master (menu, kategori, modifier, meja, staf, banner) menggunakan mekanisme *Soft Delete*. Data yang terhapus masuk ke folder *Sampah (Trash)* dan dapat dipulihkan (*Restore*) kapan saja.
* **Manfaat & Nilai Bisnis**: Menghilangkan kepanikan jika ada staf yang tidak sengaja menekan tombol hapus pada menu andalan atau daftar meja restoran.
* **Keunggulan Teknis**: Relasi integritas basis data tetap terjaga; histori laporan penjualan lama yang merujuk ke menu terhapus tetap tampil utuh dan valid.
* **Sudut Pandang Promosi**: *"Bebas panik: Data yang tidak sengaja terhapus dapat dipulihkan kembali hanya dalam 1 klik."*

---

### 11.4. Menu Klik Kanan Cepat (Desktop Table Right-Click Context Menu)
* **Deskripsi & Kegunaan**: Navigasi cepat pada tabel kasir dan dapur menggunakan klik kanan mouse untuk memunculkan menu aksi kilat (*Quick Actions Context Menu*).
* **Manfaat & Nilai Bisnis**: Mempercepat alur kerja kasir dan staf dapur yang menggunakan PC desktop atau laptop.
* **Keunggulan Teknis**: Ditenagai oleh pustaka JavaScript native `filament-right-click.js` yang ringan dan terintegrasi mulus dengan Filament v4.
* **Sudut Pandang Promosi**: *"Operasional secepat kilat dengan dukungan navigasi klik kanan modern di komputer kasir."*

---

## 🎯 RINGKASAN NILAI JUAL UNTUK BAHAN KONTEN PROMOSI (MARKETING HIGHLIGHTS)

Bagi Anda yang ingin menyusun materi iklan, brosur, penawaran proposal, atau konten video TikTok/Reels, berikut adalah formula pesan utama yang paling memikat:

| Target Audiens | Masalah Terbesar Mereka (*Pain Point*) | Solusi Utama Walk-In Resto (*Killer Feature*) | Tagline Promosi Siap Pakai |
|---|---|---|---|
| **Pemilik Restoran (Owner)** | Takut uang kasir bocor, pesanan fiktif, omzet sulit diaudit, biaya software mahal per bulan. | Audit trail anti-edit, GPS Geofencing, digit unik QRIS, analitik live omzet, zero komisi gateway. | *"Kendalikan resto Anda dari mana saja: Omzet transparan, anti-bocor, dan laporan laba live di HP Anda!"* |
| **Kasir & Manajer Operasional** | Pusing saat jam makan siang sibuk, antrean mengular, nota dobel, salah hitung kembalian, printer termal rewel. | POS kilat, verifikasi bayar idempoten 1-klik, bel notifikasi suara instan, auto-print struk 80mm. | *"Kasir tenang di jam tersibuk: Pesanan masuk otomatis berdering, pembayaran diverifikasi dalam 1 klik!"* |
| **Koki & Staf Dapur (Kitchen)** | Kertas bon basah, koki teriak-teriak, pesanan terlewat, makanan dingin sebelum diantar. | KDS multi-stasiun, bel dapur otomatis, timer warna waktu masak, batch cooking view. | *"Dapur rapi tanpa kertas kusut: Koki fokus memasak, makanan terhidang tepat waktu dan hangat!"* |
| **Pelanggan Resto (Guest/Tamu)** | Pelayan lama dipanggil, antre pesan di kasir berdiri pegal, malu tanya menu berulang-ulang. | Scan QR langsung pesan di HP tanpa download app, keranjang bersama satu meja, live status masak. | *"Makan enak tanpa nunggu: Duduk di meja, scan QR di HP, makanan hangat langsung diantar ke meja Anda!"* |

---
*Dokumen ini dibuat secara komprehensif berdasarkan kode sumber riil dan arsitektur aktif sistem Walk-In Resto. Siap digunakan sebagai panduan dokumentasi produk internal dan amunisi pemasaran bisnis kuliner.*




📦 11 Modul Utama yang Terdokumentasi Lengkap:
Ekosistem Pemesanan Mandiri Tamu (Guest Self-Order & Dining Experience):

QR Meja Bertoken Kriptografis HMAC (Bebas manipulasi URL meja).
Klaim Meja & PIN Proteksi 4 Digit (Anti-bajak sesi & anti-brute force).
Keranjang Bersama Multi-Perangkat (Real-Time Shared Cart).
Katalog Menu Visual Responsif (PWA tanpa perlu unduh aplikasi).
Varian & Modifier Ekstra Berbayar Bertingkat (Dongkrak Average Order Value).
Catatan Khusus Per Item (Instruksi diet/selera presisi).
Checkout QRIS dengan Digit Unik Otomatis (Verifikasi mutasi 2 detik tanpa payment gateway mahal).
Checkout Tunai dengan Proteksi Geofencing GPS (Anti-pesanan fiktif/prank dari luar resto).
Live Order Status Tracking (Pantau proses masak langsung di HP tamu).
Add-on Re-Order Tanpa Putus Sesi Meja.
Ulasan & Rating Pelanggan Terverifikasi.
Program Poin Loyalitas Tamu Berbasis WhatsApp (Zero-Friction Loyalty).
Operasional Kasir & Point of Sale (POS Front of House):

Antarmuka Order Kasir Cepat (FOH POS untuk tamu walk-in manual & takeaway).
Notifikasi Audio Dual-Engine Real-Time (Latensi 0ms, proteksi tab latar belakang, safety timer).
Verifikasi & Approval Pembayaran Idempoten (Anti-transaksi dobel).
Penolakan Pesanan dengan Alasan Wajib & Audit Trail.
Struk Digital WhatsApp Otomatis via Fonnte (Hemat kertas 80%).
Cetak Struk Termal Standar 80mm Cepat dengan opsi Auto-Print.
Manajemen Void Bertingkat & Pelacakan Makanan Terbuang (Waste Loss Tracking).
Manajemen Shift Kasir Lengkap (Modal awal, Petty cash in/out, Rekonsiliasi kas aktual & selisih).
Slip Rekap Shift Kasir Cetak (X/Z Report) & Pemindahan Meja Operasional.
Kitchen Display System (KDS - Operasional Dapur & Bar):

Layar Dapur Multi-Stasiun Terisolasi (Bar, Kitchen, Grill, Dessert).
Siklus Produksi 4 Tahap (Queued $\rightarrow$ Preparing $\rightarrow$ Ready $\rightarrow$ Served).
Bel Notifikasi Dapur Otomatis (Service Bell Chime).
Indikator Timer Warna Waktu Masak (Aging Timer Band: Hijau, Kuning, Merah).
Tampilan Agregasi Masak Sekaligus (Batch Cooking View hemat gas & waktu).
Ketahanan Kegagalan Perangkat & Pemadaman (Cloud Queue Resilience).
Manajemen Meja & Denah Interaktif (Interactive Table Floor Plan):

Visual Floor Plan Editor Drag-and-Drop (Pengaturan tata letak meja visual).
Auto-Grid Floor Layout Generator per area (Indoor, Outdoor, VIP).
Status Meja Cerdas Otomatis (Available, Claiming, Occupied, Cleaning, Out of Service).
Generator Stiker QR Code Berkualitas Tinggi (Format PNG & Vector PDF siap cetak).
Dua Mode Operasional Restoran (Simple Mode vs Standar KDS):

Simple Mode: Untuk kedai kopi cepat, fast food, booth boba tanpa dapur terpisah (order otomatis selesai saat bayar).
Standar KDS: Untuk resto keluarga & casual dining dengan stasiun masak lengkap.
Beralih instan dengan 1 tombol toggle tanpa migrasi database.
CMS Restoran & Multi-Template Website Publik:

Website Profil Resto Otomatis (/{slug}) siap dipasang di bio Instagram/TikTok.
Pilihan Tema Visual Modern (Classic, Foodie, Glassmorphism).
Banner Promo Berjadwal Otomatis (Muncul dan hilang sesuai tanggal tayang).
Tata Letak Dinamis Drag-and-Drop untuk seksi landing page.
CRM, Program Loyalitas & Retensi Pelanggan:

Database Pelanggan Otomatis berbasis WhatsApp (Nama, No HP, Total Belanja, Frekuensi).
Tingkatan Member Otomatis (Reguler, Silver, Gold, VIP).
Poin Belanja & Diskon Penukaran Poin di Kasir/Self-Order.
Blog, SEO & Mesin Pemasaran Konten Terintegrasi:

CMS Artikel Edukasi Kuliner Multi-Bahasa (Indonesia & Inggris).
Struktur SEO Otomatis & Dynamic XML Sitemap (/sitemap.xml) untuk mendominasi halaman pencarian Google.
Fitur Like & Komentar Publik dengan moderasi.
Analitik Bisnis, Laporan Finansial & Rekonsiliasi:

Dasbor KPI Real-Time Hari Ini vs Kemarin (Omzet, Orders, AOV, Bauran Tunai vs QRIS, Void, Waste).
Peringkat Menu Terlaris (Top Volume & Top Revenue).
Mesin Ekspor Laporan Finansial Lengkap (Format Excel & PDF).
SaaS Multi-Tenant & Platform Founder Console:

Onboarding Mandiri Restoran Baru (/daftar) dalam 2 menit.
Manajemen Paket Langganan & Penagihan Invoice Otomatis (Subscription Gate).
Mode Masa Tenggang (Grace Period) & Pembatasan Read-Only.
Direktori Restoran Global Platform (/) dengan Filter Radius GPS.
Pengaturan Suara Notifikasi Platform Kustom (Upload MP3).
Keamanan, Kontrol Akses & Integritas Data:

Role-Based Access Control (RBAC) terisolasi per restoran.
Log Aktivitas Anti-Manipulasi (Immutable Append-Only Audit Trail).
Perlindungan Keranjang Sampah (Soft Deletes & Trash Recovery).
Menu Klik Kanan Cepat (Context Menu) pada antarmuka desktop.