# Untuk Pemilik Restoran — Kenalan dengan Sistem Ini

Dokumen ini ditulis untuk **owner restoran**, bukan untuk tim IT. Bahasanya sederhana: masalah yang sering terjadi di lapangan, apa yang berubah setelah pakai sistem, dan keuntungan nyata dari setiap fitur.

Intinya satu kalimat:

> Tamu datang, duduk, scan stiker QR di meja, pesan sendiri dari HP. Kasir tinggal terima pembayaran. Dapur masak dari layar. Owner lihat omzet yang jelas.

Belum ada reservasi / booking meja dari HP. Ini untuk restoran **walk-in**: tamu datang dulu, baru pesan.

---

## 1. Cerita yang mungkin terasa familiar

Jam makan siang. Meja hampir penuh. Pelayan bolak-balik. Tamu panggil “Mas, nasi goreng pedas, extra telur, es tehnya kurang es.” Dicatat di kertas, atau diingat di kepala, lalu diteriakkan ke dapur.

Di kasir, antrean memanjang karena setiap orang masih memesan di tempat yang sama orangnya menghitung uang. Ada yang transfer QRIS nominal sama dengan meja sebelah — kasir bingung mutasi mana punya siapa. Ada pesanan dadakan take-away yang bikin kasir repot mencari menu di katalog panjang. Printer struk macet atau kasir lupa menekan tombol print. Ada tamu yang “sudah bayar tunai dari HP” padahal belum sampai resto. Ada nota yang pajaknya dihitung beda-beda tiap shift.

Malamnya owner buka buku, atau tanya kasir: “Omzet hari ini berapa? Nasi goreng laku berapa? Siapa yang void? Kenapa meja 5 lama sekali?” Jawabannya sering kira-kira. Belum lagi foto-foto menu di website yang diupload dari HP ukurannya terlalu besar sehingga website resto dibuka tamu lemot setengah mati.

Itu bukan salah orang. Itu cara kerja yang sudah kelewatan kapasitasnya.

---

## 2. Masalah di lapangan — dan apa yang sistem selesaikan

### 2.1 Pesanan salah, kurang, atau telat sampai dapur

**Yang sering terjadi**  
Tulisan pelayan tidak kebaca. Level pedas tertukar. Extra tidak ikut. Dapur baru tahu ada order setelah tamu komplain “sudah 20 menit.”

**Solusi sistem**  
Tamu pilih sendiri di HP: menu, porsi, extra (pedas, topping), plus catatan (“tanpa bawang”, “alergi seafood”). Dapur hanya menerima pesanan **setelah kasir konfirmasi lunas** — jadi tidak masak dulu, bayar belakangan, lalu tamu cabut.

### 2.2 Kasir jadi bottleneck di jam sibuk

**Yang sering terjadi**  
Satu orang kasir harus catat pesanan, hitung pajak, terima uang, kasih kembalian, sambil jawab “es tehnya sudah keluar belum?” Antrean di depan kasir, meja kosong menunggu.

**Solusi sistem**  
Sebagian besar tamu sudah pesan dan pilih cara bayar dari meja. Kasir kerjaannya: **lihat antrian, cocokkan pembayaran, tekan terima**. Untuk tamu tanpa HP, kasir tetap bisa input order sendiri dengan dua pilihan mode: katalog penuh atau **Mode Cepat (Simple Order)** untuk transaksi kilat tanpa ribet.

### 2.3 Antrean pesanan take-away / dadakan bikin kasir lambat

**Yang sering terjadi**  
Ada tamu mau pesan cepat 1 kopi dan 1 roti bakar langsung di kasir, tapi kasir harus klik kategori, pilih varian, dan navigasi katalog panjang hanya untuk 1 transaksi kilat.

**Solusi sistem**  
Kasir punya tombol **Mode Cepat (Simple Order)**: masukkan item atau nominal dengan hitungan detik, tentukan cara bayar, dan cetak struk seketika. Kasir tidak kehilangan waktu di jam sibuk.

### 2.4 Transfer QRIS yang “mirip-mirip”

**Yang sering terjadi**  
Dua meja pesan total yang sama. Kasir lihat mutasi Rp 55.000 — milik siapa?

**Solusi sistem**  
Setiap pesanan QRIS dapat **nominal sedikit berbeda** (kode unik di belakang), supaya kasir tinggal cocokkan angka di mutasi bank/e-wallet. Angka tambahan itu **tidak dihitung sebagai omzet** — laporan keuangan tetap bersih dan akurat.

### 2.5 Tamu “pesan tunai dari luar resto”

**Yang sering terjadi**  
Foto stiker QR dibagikan, orang di luar “pesan tunai”, dapur masak, meja kosong.

**Solusi sistem**  
Bayar tunai dari HP tamu hanya jalan jika HP-nya **benar-benar di sekitar restoran** (ada validasi jarak lokasi). Kalau GPS buram (misalnya di dalam ruangan/basement), kasir yang putuskan: lihat dulu tamunya di meja, baru izinkan. QRIS tidak memaksa lokasi — kasir tetap yang pastikan tamu ada.

### 2.6 Dapur dan bar rebutan kertas, tidak tahu mana yang lama

**Yang sering terjadi**  
Kertas numpuk. Minuman dan makanan tercampur. Tidak jelas mana yang sudah 15 menit.

**Solusi sistem**  
Layar dapur terpisah dari layar bar (kalau Anda punya stasiun berbeda). Setiap hidangan punya status sendiri: antri → dimasak → siap antar → sudah dihidangkan. Ada **timer warna**: hijau (masih wajar), kuning (mulai lama), merah (sudah telat). Bunyi alarm otomatis ketika ada pesanan baru yang lunas.

### 2.7 Meja “milik siapa?”, rombongan banyak HP, dan denah lantai

**Yang sering terjadi**  
Dua rombongan scan meja yang sama. Teman se-meja tidak bisa ikut pesan dari HP-nya. Pelayan bingung meja mana yang masih kosong, mana yang sedang makan, dan mana yang kotor belum dibersihkan.

**Solusi sistem**  
Satu meja, satu sesi makan. Yang duduk duluan (dan berhasil scan) yang pegang sesi. Teman cukup minta **PIN 4 digit** dari HP host, lalu gabung — keranjang belanja **bersama**.  
Di panel staf ada **Denah Meja Visual (Floor Plan)** dengan indikator warna status langsung: meja tersedia, sedang memilih menu, terisi makan, atau butuh dibersihkan (*cleaning*). Stiker QR rusak? Token lama dimatikan, stiker baru dicetak tanpa mengganggu sesi yang sedang makan.

### 2.8 Pajak, service, dan kembalian dihitung “menurut yang jaga”

**Yang sering terjadi**  
Shift A round-down, shift B lupa service. Kembalian dihitung di kalkulator HP, kadang selisih kas di akhir hari.

**Solusi sistem**  
Service charge dan pajak PB1 mengikuti pengaturan resto, otomatis dan konsisten untuk semua shift. Kasir cukup ketik nominal uang dari tamu, **kembalian tampil otomatis**. Ada tombol kilat “uang pas” untuk tamu yang bayar pas.

### 2.9 Cetak struk bikin kasir repot bolak-balik klik

**Yang sering terjadi**  
Kasir harus berkali-kali klik konfirmasi bayar, klik buka struk, baru klik print. Kalau antrean sedang 10 orang, waktu terbuang hanya untuk operasional klik printer.

**Solusi sistem**  
Ada opsi **Cetak Struk Otomatis (Auto-Print Thermal)**. Begitu kasir klik "Terima Pembayaran", mesin printer thermal (58mm atau 80mm) langsung mencetak struk detik itu juga. Hasil cetakan rapi dengan logo resto, nomor meja, rincian menu, varian, dan catatan pembayaran.

### 2.10 Struk kertas hilang, tamu minta dikirim WhatsApp

**Yang sering terjadi**  
Printer thermal kehabisan kertas. Tamu terburu-buru dan minta bukti transaksi dikirim ke HP.

**Solusi sistem**  
Sistem menyediakan struk digital via WhatsApp (integrasi Fonnte). Begitu lunas, pesan WA otomatis terkirim lengkap dengan detail pesanan dan **tautan unduh PDF resmi**. Jika nomor WA tamu salah ketik atau jaringan sempat gangguan, kasir bisa edit nomor dan kirim ulang dengan sekali klik. Gagal kirim WA tidak pernah menggagalkan transaksi penjualan.

### 2.11 Tampilan website restoran kaku atau tidak sesuai konsep resto

**Yang sering terjadi**  
Banyak sistem POS memberi tampilan website yang kaku, seragam, dan membosankan. Cafe estetik dipaksa pakai tampilan seperti warung cepat saji, atau sebaliknya. Mau sewa web developer biayanya jutaan rupiah.

**Solusi sistem**  
Owner punya fitur **Multi-Template Landing Page**. Anda bisa memilih gaya desain website resmi restoran hanya dengan 1 klik:
- **Foodie**: Desain modern, ceria, dan menggugah selera — pas untuk resto keluarga, kafe kasual, dan resto cepat saji.
- **Classic**: Desain bersih, elegan, dan rapi — cocok untuk bistro, restoran tradisional, atau kedai kopi klasik.
- **Glassmorphism**: Desain mewah dengan efek kaca tembus pandang (*frosted glass* ala iOS) — sempurna untuk lounge, resto modern, atau cafe kekinian.  
Semua section (Hero, Tentang Kami, Menu, Jam Buka, Ulasan, FAQ, Galeri, Peta) otomatis menyesuaikan tanpa perlu utak-atik kode.

### 2.12 Calon tamu ingin lihat menu lengkap sebelum datang ke resto

**Yang sering terjadi**  
Tamu di kantor atau di jalan ingin merencanakan makan siang, tapi foto menu di IG terpotong-potong atau harganya sudah basi.

**Solusi sistem**  
Restoran punya halaman **Katalog Menu Lengkap (`/{slug}/menu`)**: calon tamu bisa mencari nama menu (misal: "Latte"), memfilter kategori (Makanan, Minuman, Dessert), serta mengurutkan dari harga termurah ke termahal langsung dari HP mereka.

### 2.13 Foto menu yang diupload staf ukurannya raksasa bikin web lemot

**Yang sering terjadi**  
Staf foto makanan pakai kamera HP 50 MP, lalu langsung diupload ke admin. File 15 MB membuat website lambat dibuka oleh tamu yang sinyalnya pas-pasan.

**Solusi sistem**  
Ada fitur **Optimasi & Kompresi Gambar Otomatis**. Berapa pun ukuran foto yang diupload, sistem di belakang layar otomatis mengecilkan dimensi (*auto-scale*) dan mengompresi gambar tanpa mengurangi ketajaman mata. Website resto tetap super ringan dan cepat terbuka di semua HP tamu.

### 2.14 Owner dan kasir ingin tahu performa hari ini secara instan

**Yang sering terjadi**  
Untuk tahu omzet siang ini, kasir harus logout atau owner harus mengunduh file laporan rumit. Kasir tidak tahu berapa transaksi tunai vs QRIS yang sudah masuk di laci.

**Solusi sistem**  
Di layar kasir dan daftar pesanan admin langsung tersedia **Widget Statistik Penjualan Hari Ini**: terpampang jelas total pesanan lunas, pesanan yang sedang menunggu pembayaran, serta perbandingan omzet Tunai vs QRIS secara *real-time*.

### 2.15 Tak sengaja hapus menu atau meja saat jam sibuk

**Yang sering terjadi**  
Staf salah klik tombol hapus pada menu favorit atau meja kasir. Semua data hilang dan harus diketik ulang dari awal.

**Solusi sistem**  
Sistem dilengkapi fitur **Tempat Sampah (Trash / Soft Deletes)**. Data menu, kategori, atau meja yang terhapus tidak hilang permanen. Owner atau admin bisa mengembalikannya (*restore*) hanya dalam 1 klik.

### 2.16 Sulit ditemukan calon tamu baru di internet

**Yang sering terjadi**  
Restoran baru buka tapi susah dicari di Google. Tidak ada peta dan informasi jam buka yang rapi.

**Solusi sistem**  
Sistem sudah dilengkapi **Peta Lokasi Interaktif**, direktori restoran dengan slider rekomendasi (*Featured Resto*), serta **SEO Otomatis & Google Sitemap**. Google bisa langsung membaca nama restoran, menu, jam buka, dan ulasan tamu Anda secara otomatis.

---

## 3. Sebelum vs sesudah

Gambaran praktis, bukan janji muluk.

| Situasi di Lapangan | Sebelum Menggunakan Sistem | Sesudah Memakai Sistem |
|---|---|---|
| **Tamu baru duduk** | Tunggu pelayan datang bawa buku menu, atau antre berdiri di kasir | Duduk di meja kosong, scan stiker, pesan dari HP sendiri. Tidak perlu unduh aplikasi, tidak perlu daftar akun |
| **Mencatat pesanan** | Kertas / teriak / chat WA ke dapur; rawan salah tulis pedas & varian | Tamu yang pilih varian & catatan sendiri. Dapur baca dari layar, rapi dan jelas |
| **Kasir di jam ramai** | Catat pesanan + hitung kalkulator + terima uang sekaligus | Fokus cocokkan bayar dan meja. Antrean pesanan tampil otomatis di layar |
| **Transaksi kilat di kasir** | Cari menu satu per satu di katalog panjang | Pakai **Mode Cepat (Simple Order)**: ketik nominal/item kilat, selesai dalam hitungan detik |
| **Dua orang transfer nominal sama** | Kasir menebak mutasi bank milik siapa | Nominal QRIS diberi digit unik di belakang, kasir tinggal cocokkan angka |
| **“Pesan tunai” dari luar resto** | Sulit dicegah, makanan terlanjur dimasak tapi orangnya fiktif | Tunai dari HP hanya bisa jika tamu berada di radius restoran; kasir punya hak konfirmasi manual |
| **Dapur & Bar** | Kertas numpuk, makanan & minuman campur aduk | Layar dapur terpisah; timer warna mengingatkan makanan yang mulai telat |
| **Cetak struk kasir** | Kasir harus klik tombol print berulang kali tiap order | **Auto-Print Thermal**: struk langsung tercetak otomatis begitu pembayaran diklik lunas |
| **Tamu minta struk dikirim WA** | Kasir catat nomor di kertas, sering lupa kirim | Otomatis terkirim via WhatsApp Fonnte lengkap dengan link download PDF resmi |
| **Kembalian tunai** | Hitung manual di kalkulator, rawan tekor saat lelah | Ketik nominal uang diterima, nilai kembalian muncul otomatis; ada tombol uang pas |
| **Status meja fisik** | Pelayan harus jalan keliling ngecek meja mana yang kosong/kotor | **Denah Meja Visual (Floor Plan)**: kelihatan mana meja kosong, sedang pesan, terisi, atau perlu dibersihkan |
| **Tambah pesanan (add-on)** | Tulis nota baru, sering tertukar nomor meja | Pesan lagi dari HP di meja yang sama, masuk sebagai pesanan baru tapi tetap di meja yang sama |
| **Teman se-meja mau ikut pesan** | Harus pinjam-pinjaman 1 HP | Cukup minta PIN 4 digit ke yang scan pertama, keranjang belanja langsung bersama |
| **Tampilan website restoran** | Tampilan kaku / seragam / sewa jasa mahal | Bebas ganti template desain 1-klik (**Foodie, Classic, Glassmorphism**) sesuai konsep resto |
| **Upload foto menu makanan** | Foto resolusi tinggi bikin website berat dan kuota jebol | Sistem **otomatis mengompresi & menyesuaikan ukuran gambar** tanpa mengurangi ketajaman |
| **Cari menu dari rumah** | DM admin Instagram atau bolak-balik tanya harga | Calon tamu buka **Katalog Menu Lengkap (`/menu`)**, bisa cari menu dan urutkan harga termurah |
| **Pantauan omzet hari ini** | Tunggu tutup toko malam hari atau download rekap rumit | **Widget Statistik Live di Kasir**: total order, omzet tunai vs QRIS langsung terlihat real-time |
| **Salah hapus menu/meja** | Data hilang selamanya, panik harus input ulang | Masuk ke **Tempat Sampah (Trash)** dan bisa dipulihkan (*restore*) seketika |
| **Restoran tutup jam operasional** | Masih ada orang luar yang scan dan pesan saat dapur pulang | Sistem tolak pemesanan baru otomatis di luar jam operasional; pesanan yang sudah ada tetap diselesaikan |

---

## 4. Manfaat: operasional lebih ringan

Ini yang terasa di shift harian, bukan di slide presentasi.

**Lebih sedikit bolak-balik meja**  
Tamu pesan dan pantau status masakan langsung dari HP. Pelayan bisa fokus mengantar makanan yang statusnya sudah “siap saji”, bukan menghabiskan waktu mencatat pesanan dari nol.

**Kasir lebih gesit & fleksibel**  
Antrian pembayaran terlihat rapi. Kasir bisa menerima atau menolak pesanan dengan alasan yang jelas. Untuk pesanan langsung di kasir, kasir bebas beralih antara katalog menu visual atau **Mode Cepat (Simple Order)**. Ditambah **Auto-Print**, struk thermal langsung keluar tanpa jeda klik.

**Dapur lebih adil & transparan**  
Pesanan yang masuk lebih dulu akan tampil paling depan. Timer warna mengingatkan juru masak jika ada pesanan yang mulai terlambat. Bar minuman tidak perlu lagi mendengar teriakan pesanan es teh dari meja ujung.

**Denah meja terpantau tanpa harus keliling ruangan**  
Panel denah visual memberi kepastian: meja mana yang siap menerima tamu baru, meja mana yang masih santai menikmati makanan, dan meja mana yang baru saja ditinggal tamu sehingga perlu segera dibersihkan.

**Website restoran elegan dan berkelas tanpa biaya developer**  
Pilihan template modern (termasuk efek kaca *Glassmorphism* yang mewah) dan warna brand yang bisa disesuaikan membuat restoran Anda terlihat sangat profesional di mata pelanggan.

**Foto menu otomatis dioptimalkan**  
Owner dan staf bebas memotret menu langsung dari kamera smartphone terbaik. Sistem otomatis mengompresi gambar sehingga website tetap terbuka secepat kilat bagi pelanggan.

**Data terlindungi dari keteledoran staf**  
Menu atau denah meja yang terhapus saat jam sibuk tidak lagi memicu kepanikan, karena semua tersimpan rapi di tempat sampah (*trash*) dan siap dikembalikan kapan saja.

---

## 5. Keuntungan dari fitur — bahasa owner, bukan bahasa sistem

Setiap poin di bawah adalah apa yang ada di dalam produk, dijelaskan dari kacamata keuntungan bisnis Anda:

### Halaman restoran resmi & pilihan template desain (Foodie, Classic, Glassmorphism)
Anda memiliki website resmi modern lengkap dengan profil, galeri foto, jam operasional, peta lokasi, FAQ, promo berjangka, dan ulasan pelanggan. Anda bebas memilih tema tampilan (*Foodie* yang bersahabat, *Classic* yang elegan, atau *Glassmorphism* yang estetik dan mewah).  
**Untungnya:** Kesan pertama pelanggan menjadi sangat profesional. Anda tidak perlu menyewa jasa web developer mahal. Promo memiliki tanggal tayang otomatis sehingga tidak ada lagi pelanggan komplain promo kedaluwarsa.

### Katalog menu lengkap dengan pencarian & filter harga (`/{slug}/menu`)
Selain menu ringkas di halaman depan, ada halaman khusus katalog menu di mana calon pelanggan bisa mencari menu berdasarkan nama, memilih kategori makanan/minuman, serta mengurutkan harga termurah.  
**Untungnya:** Mengurangi pertanyaan berulang di WhatsApp/DM seperti "ada menu apa saja?" atau "harganya berapa?". Calon pelanggan bisa menentukan pilihan sebelum melangkah masuk ke resto Anda.

### Mode Kasir Cepat (Simple Order Mode)
Selain layar kasir POS lengkap, kasir memiliki tombol mode cepat untuk melayani pembelian kilat, take-away dadakan, atau pesanan khusus tanpa harus menelusuri ratusan menu di katalog.  
**Untungnya:** Antrean kasir di jam sibuk terurai jauh lebih cepat. Pelanggan yang hanya membeli satu minuman tidak perlu menunggu lama.

### Cetak struk otomatis (Auto-Print Thermal Printer)
Sistem dapat disambungkan ke printer thermal kasir (58mm atau 80mm) dan disetel untuk mencetak struk secara otomatis tepat saat kasir menekan tombol lunas.  
**Untungnya:** Menghemat waktu kasir 5–10 detik per transaksi. Kasir tidak perlu membuka tab baru atau menekan tombol cetak manual.

### Struk WhatsApp Fonnte & tautan unduh PDF mandiri
Jika tamu mencantumkan nomor WhatsApp, struk digital akan otomatis terkirim ke WhatsApp mereka, lengkap dengan tautan untuk mengunduh file PDF resmi kapan saja. Kasir juga bisa mengubah nomor dan mengirim ulang jika terjadi salah ketik.  
**Untungnya:** Tamu merasa terlayani dengan canggih. Anda menghemat kertas struk thermal. Jika pengiriman WhatsApp gagal (misal kuota habis atau nomor salah), operasional penjualan tetap aman 100%.

### Kompresi & penyesuaian gambar otomatis (Auto Image Optimizer)
Setiap foto banner, galeri, logo, atau menu makanan yang diunggah ke sistem akan otomatis dikompresi dan disesuaikan ukurannya di latar belakang.  
**Untungnya:** Website restoran dan menu digital tetap terbuka kencang di HP tamu, menghemat kuota data pelanggan, dan tidak membebani kapasitas server.

### Denah meja visual (Floor Plan) & status warna meja
Tampilan denah meja interaktif yang menunjukkan status meja secara visual: hijau (tersedia), kuning (sedang memilih menu), biru (sedang makan/terisi), abu-abu (perlu dibersihkan), atau merah (rusak/nonaktif).  
**Untungnya:** Pelayan di pintu depan langsung tahu meja mana yang bisa diisi tamu baru tanpa harus berteriak atau berjalan menyisir seluruh ruangan.

### Menu dinamis, level pedas, extra topping, dan tombol "Habis"
Kelola nama menu, foto, level kepedasan, pilihan extra (misal: tambah keju, extra sambal), serta catatan khusus dari tamu. Menu yang stok bahannya habis bisa dinonaktifkan dalam satu klik.  
**Untungnya:** Menghilangkan komplain klasik "tadi saya minta jangan pedas kok pedas?". Tamu tidak bisa memesan menu yang stoknya sedang kosong.

### Stiker QR meja & keamanan sesi makan
Setiap meja memiliki stiker QR unik. Tamu cukup mengarahkan kamera HP, mengisi nama, dan langsung memesan tanpa perlu mendownload aplikasi dari Play Store/App Store.  
**Untungnya:** Nol hambatan bagi tamu lansia atau memori HP penuh. Jika stiker QR meja lama difoto orang iseng dari luar, Anda bisa mengganti kode QR meja dalam 5 detik — kode lama seketika hangus tanpa mengganggu tamu yang sedang makan.

### Keranjang belanja bersama (Multi-Device PIN)
Rombongan 5 orang di satu meja bisa memesan dari HP masing-masing ke dalam satu keranjang belanja yang sama cukup dengan memasukkan PIN 4 digit dari HP pemesan pertama.  
**Untungnya:** Tidak ada momen berebut HP. Pesanan satu meja terkumpul rapi dalam satu struk pembayaran.

### Pembayaran QRIS dengan kode unik pencocokan
Tamu yang memilih bayar QRIS akan mendapatkan nominal dengan digit pembeda unik di belakangnya.  
**Untungnya:** Kasir langsung tahu uang yang masuk di mutasi bank berasal dari meja mana, meski ada tiga meja yang total pesanannya sama persis. Digit unik tersebut otomatis dipisahkan dari pencatatan omzet murni.

### Validasi lokasi untuk pembayaran tunai dari HP
Tamu yang ingin bayar tunai lewat HP wajib terdeteksi berada di lokasi restoran.  
**Untungnya:** Mencegah pesanan jahil dari luar restoran yang berpura-pura memesan tunai padahal tidak berniat datang.

### Layar dapur & bar (Kitchen Display System)
Layar monitor atau tablet di dapur dan bar yang menampilkan pesanan yang sudah dibayar secara runtut, lengkap dengan pengingat warna waktu masak.  
**Untungnya:** Dapur tenang, tidak ada kertas pesanan basah atau hilang, dan komplain makanan lama bisa dilacak secara objektif.

### Dasbor harian & Widget statistik penjualan langsung
Widget di layar kasir menampilkan angka penjualan hari ini, total transaksi tunai vs QRIS, dan jumlah pesanan lunas. Tersedia pula laporan komprehensif yang bisa diunduh ke format Excel/PDF.  
**Untungnya:** Owner memegang angka pasti setiap saat tanpa perlu menebak atau menunggu laporan kasir di malam hari.

### Tempat Sampah (Soft Deletes / Trash Recovery)
Menu, meja, atau kategori yang terhapus secara tidak sengaja dapat dipulihkan kapan saja dari halaman Trash.  
**Untungnya:** Keamanan data operasional terjamin dari kesalahan klik staf.

### Pendaftaran restoran mandiri yang mudah (Multi-Step Wizard)
Pendaftaran pemilik restoran baru dilengkapi panduan langkah demi langkah: data akun, info resto & tautan sosial media (Instagram), pemilihan warna brand/tema, dan pemilihan paket langganan.  
**Untungnya:** Pemilik restoran baru bisa langsung mencoba sistem secara mandiri dalam hitungan menit.

---

## 6. Yang Anda dapat, kalau diringkas ke uang dan waktu

Tidak ada sistem yang menggandakan omzet secara ajaib dalam semalam. Yang realistis dan terbukti di lapangan:

1. **Kapasitas meja berputar lebih cepat** — tamu tidak menunggu pelayan mencatat pesanan; kasir tidak menjadi antrean yang macet; meja yang selesai makan langsung diketahui untuk dibersihkan.
2. **Nol kerugian dari pesanan salah masak** — tamu memilih sendiri varian dan catatannya; dapur hanya memasak pesanan yang sudah dikonfirmasi lunas oleh kasir.
3. **Pencocokan QRIS & kas tunai 100% klop** — nominal unik QRIS dan hitungan kembalian otomatis mencegah selisih uang di laci kasir.
4. **Waktu owner kembali bernilai** — tidak ada lagi rekap nota manual hingga larut malam; angka omzet, menu terlaris, dan file Excel/PDF sudah siap di dasbor.
5. **Citra restoran naik kelas** — tampilan website estetik (bisa berganti tema *Foodie*, *Classic*, hingga *Glassmorphism*), menu cepat diakses, dan struk terkirim rapi ke WhatsApp.
6. **Staf bekerja lebih tenang dan minim stres** — tidak ada teriak-teriak antara kasir, pelayan, dan juru masak di jam ramai.

---

## 7. Siapa yang paling cocok

Sistem ini dirancang khusus jika restoran atau cafe Anda:

- Menerapkan konsep **walk-in** (tamu datang, duduk di meja, lalu memesan);
- Menerima pembayaran **QRIS** dan **Uang Tunai**;
- Ingin meja tertata rapi dengan stiker QR dan satu tablet/layar di kasir serta dapur;
- Ingin memiliki website resmi restoran yang estetik dengan katalog menu online yang mudah dicari;
- Ingin owner memegang kendali atas angka omzet yang transparan, bukan sekadar "percaya pada perkiraan shift".

Kurang pas jika kebutuhan utama Anda saat ini adalah **reservasi meja dari jauh / booking DP berhari-hari sebelumnya** — sistem ini memprioritaskan kelancaran operasional tamu yang datang langsung ke restoran.

---

## 8. Ajakan singkat

Sistem ini dibangun untuk satu tujuan utama: **menghilangkan kekacauan di jam sibuk, tanpa menggantikan kehangatan pelayanan restoran Anda dengan robot.**

Tamu tetap disambut dengan ramah. Juru masak tetap fokus meracik hidangan lezat. Yang berubah: catatan tidak pernah hilang, pencocokan bayar selesai dalam hitungan detik, layar dapur bekerja tanpa teriakan, struk langsung tercetak atau terkirim ke WhatsApp, dan di penghujung hari, owner melihat angka omzet yang jujur dan rapi.

Silakan coba di beberapa meja terlebih dahulu pada satu shift. Yang biasanya meyakinkan pemilik restoran bukanlah penjelasan panjang di atas — melainkan melihat meja nomor 4 memesan sendiri dengan lancar, kasir cukup menekan satu tombol terima, dan dapur menyajikan makanan tanpa ada pesanan yang tertinggal.

