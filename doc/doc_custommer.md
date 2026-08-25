# Untuk Pemilik Restoran — Kenalan dengan Sistem Ini

Dokumen ini ditulis untuk **owner restoran**, bukan untuk tim IT. Bahasanya sederhana: masalah yang sering terjadi di lapangan, apa yang berubah setelah pakai sistem, dan keuntungan nyata dari setiap fitur.

Intinya satu kalimat:

> Tamu datang, duduk, scan stiker QR di meja, pesan sendiri dari HP. Kasir tinggal terima pembayaran. Dapur masak dari layar. Owner lihat omzet yang jelas.

Belum ada reservasi / booking meja dari HP. Ini untuk restoran **walk-in**: tamu datang dulu, baru pesan.

---

## 1. Cerita yang mungkin terasa familiar

Jam makan siang. Meja hampir penuh. Pelayan bolak-balik. Tamu panggil “Mas, nasi goreng pedas, extra telur, es tehnya kurang es.” Dicatat di kertas, atau diingat di kepala, lalu diteriakkan ke dapur.

Di kasir, antrean memanjang karena setiap orang masih memesan di tempat yang sama orangnya menghitung uang. Ada yang transfer QRIS nominal sama dengan meja sebelah — kasir bingung mutasi mana punya siapa. Ada tamu yang “sudah bayar tunai dari HP” padahal belum sampai resto. Ada nota yang pajaknya dihitung beda-beda tiap shift.

Malamnya owner buka buku, atau tanya kasir: “Omzet hari ini berapa? Nasi goreng laku berapa? Siapa yang void? Kenapa meja 5 lama sekali?” Jawabannya sering kira-kira.

Itu bukan salah orang. Itu cara kerja yang sudah kelewatan kapasitasnya.

---

## 2. Masalah di lapangan — dan apa yang sistem selesaikan

### 2.1 Pesanan salah, kurang, atau telat sampai dapur

**Yang sering terjadi**  
Tulisan pelayan tidak kebaca. Level pedas tertukar. Extra tidak ikut. Dapur baru tahu ada order setelah tamu komplain “sudah 20 menit.”

**Solusi sistem**  
Tamu pilih sendiri di HP: menu, porsi, extra (pedas, topping), plus catatan (“tanpa bawang”, “alergi seafood”). Dapur hanya menerima pesanan **setelah kasir konfirmasi lunas** — jadi tidak masak dulu, bayar belakangan, lalu tamu cabut.

### 2.2 Kasir jadi bottleneck

**Yang sering terjadi**  
Satu orang kasir harus catat pesanan, hitung pajak, terima uang, kasih kembalian, sambil jawab “es tehnya sudah keluar belum?” Antrean di depan kasir, meja kosong menunggu.

**Solusi sistem**  
Sebagian besar tamu sudah pesan dan pilih cara bayar dari meja. Kasir kerjaannya: **lihat antrian, cocokkan pembayaran, tekan terima**. Untuk tamu tanpa HP, kasir tetap bisa input order sendiri — lengkap dengan hitung kembalian otomatis.

### 2.3 Transfer QRIS yang “mirip-mirip”

**Yang sering terjadi**  
Dua meja pesan total yang sama. Kasir lihat mutasi Rp 55.000 — milik siapa?

**Solusi sistem**  
Setiap pesanan QRIS dapat **nominal sedikit berbeda** (kode unik di belakang), supaya kasir tinggal cocokkan angka di mutasi. Angka tambahan itu **tidak dihitung sebagai omzet** — laporan tetap bersih.

### 2.4 Tamu “pesan tunai dari luar resto”

**Yang sering terjadi**  
Foto stiker QR dibagikan, orang di luar “pesan tunai”, dapur masak, meja kosong.

**Solusi sistem**  
Bayar tunai dari HP tamu hanya jalan jika HP-nya **benar-benar di sekitar restoran**. Kalau GPS buram (di dalam ruangan), kasir yang putuskan: lihat dulu tamunya di meja, baru izinkan. QRIS tidak memaksa lokasi — kasir tetap yang pastikan tamu ada.

### 2.5 Dapur dan bar rebutan kertas, tidak tahu mana yang lama

**Yang sering terjadi**  
Kertas numpuk. Minuman dan makanan tercampur. Tidak jelas mana yang sudah 15 menit.

**Solusi sistem**  
Layar dapur terpisah dari layar bar (kalau Anda punya stasiun berbeda). Setiap hidangan punya status sendiri: antri → dimasak → siap antar → sudah dihidangkan. Ada **timer warna**: masih wajar, mulai lama, sudah telat. Suara ketika ada pesanan baru yang lunas.

### 2.6 Meja “milik siapa?” dan HP kedua

**Yang sering terjadi**  
Dua rombongan scan meja yang sama. Atau teman se-meja tidak bisa ikut pesan dari HP-nya.

**Solusi sistem**  
Satu meja, satu sesi makan. Yang duduk duluan (dan berhasil scan) yang pegang sesi. Teman cukup minta **PIN 4 digit** dari HP host, lalu gabung — keranjang belanja **bersama**. Stiker QR rusak atau diganti? Token lama mati, stiker baru dicetak.

### 2.7 Pajak, service, dan kembalian dihitung “menurut yang jaga”

**Yang sering terjadi**  
Shift A round-down, shift B lupa service. Kembalian dihitung di kalkulator HP, kadang selisih.

**Solusi sistem**  
Service charge dan PB1 mengikuti pengaturan resto, sama untuk semua. Kasir isi uang dari tamu, **kembalian tampil otomatis**. Tombol “uang pas” untuk yang pas.

### 2.8 Struk kertas hilang, tamu minta dikirim WA

**Yang sering terjadi**  
Printer macet. Tamu sudah pergi. Owner tidak punya salinan rapi.

**Solusi sistem**  
Struk digital (PDF). Bisa dicetak di kasir (ukuran struk 80 mm), diunduh, atau — jika Anda pasang pengiriman WhatsApp — dikirim ke nomor tamu. Kalau kirim WA gagal, **pesanan tetap lunas dan dapur tetap jalan**; kasir bisa kirim ulang.

### 2.9 Promo, jam buka, menu — tersebar di IG dan chat

**Yang sering terjadi**  
Tamu DM “buka jam berapa?” “ada parkir?” “menu terbaru?” Owner capek jawab yang itu-itu saja. Halaman Google dan IG tidak sinkron.

**Solusi sistem**  
Restoran punya **halaman resmi sendiri**: profil, foto, jam buka, peta, promo berjangka, menu, FAQ, ulasan tamu. Bisa tampil juga di direktori platform (orang cari resto terdekat). Tidak ada tombol “pesan meja” yang menyesatkan — ajakan yang jujur: datang, duduk, scan QR.

### 2.10 Owner tidak punya angka yang bisa dipercaya

**Yang sering terjadi**  
Omzet “kira-kira”. Menu laris tidak terukur. Void tidak tercatat. Ganti kasir, jejaknya hilang.

**Solusi sistem**  
Dasbor harian: omzet, jumlah order, rata-rata per bill, tunai vs QRIS, menu terlaris. Laporan bisa diunduh (Excel/PDF) tanpa mengganggu kasir yang sedang sibuk. Setiap terima bayar, tolak, void, pindah meja, dan sejenisnya **tercatat** — tidak bisa dihapus diam-diam.

### 2.11 Tamu tanpa HP, atau minta tambah pesanan

**Yang sering terjadi**  
Sistem digital sering “hanya untuk yang punya HP.” Tamu lansia atau yang HP-nya lowbat terlantar. Tambah es teh harus nunggu pelayan lagi dari nol.

**Solusi sistem**  
Kasir buat order di tempat. Tamu yang sudah duduk bisa **pesan lagi** di sesi yang sama (add-on) — masuk antrian kasir baru, tiket dapur baru, struk terpisah. Jelas, tidak nyampur dengan pesanan pertama.

---

## 3. Sebelum vs sesudah

Gambaran praktis, bukan janji muluk.

| Situasi | Sebelum | Sesudah memakai sistem |
|---|---|---|
| Tamu baru duduk | Tunggu pelayan, atau antre di kasir sambil bawa menu kertas | Duduk di meja kosong, scan stiker, pesan dari HP sendiri. Tidak perlu unduh aplikasi, tidak perlu daftar akun |
| Mencatat pesanan | Kertas / teriak / chat WA ke dapur | Tamu yang pilih. Dapur baca dari layar, lengkap extra dan catatan |
| Kasir di jam ramai | Catat + hitung + terima uang sekaligus | Fokus cocokkan bayar dan meja. Antrian tampil di layar |
| Dua orang transfer nominal sama | Kasir menebak mutasi | Nominal QRIS beda tipis, cocokkan angka |
| “Pesan tunai” dari luar lokasi | Sulit dicegah | Tunai dari HP hanya jika tamu di sekitar resto; kasir yang override kalau GPS buram |
| Dapur | Kertas campur makanan & minuman | Layar terpisah; kelihatan mana yang sudah lama |
| Kembalian tunai | Hitung manual | Isi uang diterima, kembalian otomatis |
| Tambah pesanan | Tulis nota baru, sering ketuker meja | Pesan lagi di HP / kasir, tetap ke meja yang sama |
| Teman se-meja mau pesan dari HP lain | Tidak bisa, atau bikin kacau | Minta PIN ke yang scan pertama, keranjang bareng |
| Meja selesai | Kadang masih “kepakai” di sistem kepala | Kasir tutup sesi → meja dibersihkan → baru bisa dipakai tamu berikutnya |
| Tamu minta struk | Kertas, atau “nanti dikirim” lalu lupa | Cetak, PDF, atau WhatsApp |
| Promo & jam buka | IG, highlight, jawab DM | Satu halaman resmi, promo bisa dijadwal kapan tayang |
| Omzet malam ini | Tanya kasir, buka laci, kira-kira | Buka dasbor: angka hari ini vs kemarin, menu laris |
| Salah hapus menu / meja | Hilang | Ada “tempat sampah”: bisa dikembalikan |
| Ganti orang jaga | Cara hitung beda-beda | Pajak & service sama. Jejak siapa yang terima bayar / void tetap ada |
| Restoran tutup | Masih ada yang scan dan “pesan” | Klaim meja & checkout baru ditolak; yang sudah antre tetap diselesaikan |

**Yang tidak berubah (dan ini disengaja)**  
Kasir tetap yang bilang “lunas.” Uang fisik dan mutasi QRIS tetap dicek manusia. Sistem membantu cocokkan dan mencatat, bukan mengganti rasa tanggung jawab di kasir.

---

## 4. Manfaat: operasional lebih ringan

Ini yang terasa di shift harian, bukan di slide presentasi.

**Lebih sedikit bolak-balik meja**  
Tamu pesan dan pantau status masakan dari HP. Pelayan bisa fokus antar makanan yang sudah “siap”, bukan mencatat dari nol.

**Kasir lebih tenang di jam ramai**  
Antrian pembayaran terlihat. Terima atau tolak (dengan alasan, kalau ditolak). Tidak perlu mengingat “meja 3 nasi goreng extra telur.”

**Dapur lebih adil**  
Yang masuk dulu, kelihatan dulu. Timer mengingatkan yang mulai telat. Bar tidak perlu mendengar teriakan untuk es teh meja 8.

**Meja lebih tertib**  
Jelas mana yang kosong, sedang pesan, sudah makan, atau sedang dibersihkan. Pindah tamu ke meja lain dicatat, bukan “sudah dipindah ya” lalu nota masih ke meja lama.

**Menu hidup tanpa cetak ulang booklet**  
Habis stok? Tandai habis, tamu tidak bisa checkout item itu. Harga berubah? Order yang sudah jalan tidak ikut kacau.

**Tamu tanpa HP tidak ditolak**  
Kasir input. Restoran tetap ramah untuk semua umur.

**Struk tidak tergantung printer saja**  
Printer bermasalah, struk digital tetap ada. Pengiriman WA opsional — nyalakan kalau Anda sudah siap.

---

## 5. Keuntungan dari fitur — bahasa owner, bukan bahasa sistem

Setiap blok di bawah: **apa adanya di produk**, dijelaskan sebagai keuntungan Anda.

### Halaman restoran & direktori

Anda punya “rumah” di internet: foto, cerita, jam buka, lokasi, promo, menu, pertanyaan yang sering ditanya, ulasan tamu setelah makan. Orang bisa cari resto terdekat di beranda platform.  
**Untungnya:** lebih sedikit DM berulang, tampilan lebih profesional dari linktree, promo bisa mati sendiri setelah tanggalnya habis. Tamu yang baru kenal resto Anda sudah lihat menu sebelum datang.

### Menu, extra, dan “habis”

Nasi goreng, level pedas, extra telur, catatan alergi — tamu yang isi. Item bisa dinonaktifkan atau ditandai habis.  
**Untungnya:** lebih sedikit komplain “bukan ini yang saya pesan.” Menu laris nanti kelihatan di laporan — keputusan restock dan promo tidak berdasarkan feeling semata.

### Stiker QR di meja

Setiap meja punya stiker. Tamu scan, isi nama (boleh dikosongkan) dan WhatsApp, lalu pesan.  
**Untungnya:** tidak paksa unduh aplikasi. Ganti stiker kalau rusak atau bocor ke luar (token lama tidak berlaku). Kapasitas meja hanya petunjuk — sistem tidak mengusir rombongan yang “kelebihan kursi.”

### Pesan dari HP, keranjang bareng, pesan lagi

Satu rombongan, beberapa HP, satu keranjang. Habis makan siang masih mau dessert? Pesan lagi, bayar lagi, dapur dapat tiket baru.  
**Untungnya:** add-on tidak hilang di kertas. Bill tetap rapi per pesanan. Teman tidak perlu merampas HP host.

### Bayar QRIS atau tunai — kasir yang kunci

Tamu pilih metode. QRIS: tampil kode bayar + nominal yang mudah dicocokkan. Tunai dari HP: harus di lokasi. Kasir lihat bukti / mutasi / uang, lalu terima.  
**Untungnya:** dapur tidak masak pesanan yang belum dibayar. Risiko “pesan palsu dari luar” turun. Omzet tidak kecampur angka bantu pencocokan QRIS.

### Uang tunai di kasir

Isi uang yang diterima, lihat kembalian, atau tekan uang pas.  
**Untungnya:** antrian di kasir tidak nunggu hitung manual. Selisih kembalian berkurang. Saat terima pesanan, tidak ditanya ulang kalau kembalian sudah diisi.

### Layar dapur / bar

Masak dari layar. Status per hidangan. Alarm pesanan baru.  
**Untungnya:** throughput dapur lebih terukur. Komplain “lama” bisa dilihat: memang antri, atau sudah siap tapi belum diantar. Listrik tablet mati? Pesanan tetap di server, timer tidak reset ke nol.

### Order dari kasir

Untuk yang tidak scan HP.  
**Untungnya:** satu sistem untuk semua tamu. Tidak ada “jalur kertas” dan “jalur digital” yang nantinya tidak ketemu di laporan.

### Void, tolak, pindah meja, tutup meja

Salah masak, tamu batal, pindah duduk, tamu pulang — ada tombolnya, ada alasan, ada jejak.  
**Untungnya:** omzet tidak “dipoles” diam-diam. Makanan yang sudah diproses lalu dibatalkan tetap kelihatan sebagai waste — owner tahu bocor di mana. Meja tidak “menggantung” sampai besok.

### Struk & WhatsApp

Cetak otomatis setelah lunas (kalau Anda nyalakan), atau cetak manual. Kirim WA jika nomor dan layanan WA resto sudah disetel.  
**Untungnya:** tamu dapat bukti. Anda punya arsip. Gagal kirim WA tidak menggagalkan penjualan.

### Dasbor & laporan

Hari ini vs kemarin. Tunai vs QRIS. Menu terlaris. Unduh omzet harian, penjualan per item/per nota, daftar void, bahkan katalog menu.  
**Untungnya:** keputusan berdasarkan angka. Laporan untuk partner atau pembukuan tidak diketik ulang dari nota. File diproses di belakang — kasir tidak nunggu “loading export” di jam ramai.

### Staf, hak akses, jejak

Kasir tidak perlu (dan tidak bisa) mengubah semua pengaturan owner. Dapur cukup layar masak. Log siapa melakukan apa.  
**Untungnya:** lebih aman saat ganti karyawan. Owner tidur lebih nyenyak soal “siapa yang void semalam.”

### Paket sesuai kebutuhan

- **Landing saja** — cocok jika Anda baru ingin halaman resmi, menu, dan tampil di direktori; operasional pesan-masak belum dipakai.  
- **Operasional lengkap** — halaman + pesan QR + kasir + dapur + laporan.

Daftar restoran dari web, ada masa coba. Langganan lewat invoice dan bukti transfer — transparan, tidak “langsung copot kartu kredit” tanpa Anda lihat.

### Restoran tutup / buka

Toggle buka-tutup dan jam per hari.  
**Untungnya:** tidak ada tamu yang “berhasil pesan” jam 2 pagi saat dapur sudah pulang. Antrian yang sudah masuk tetap bisa diselesaikan.

---

## 6. Yang Anda dapat, kalau diringkas ke uang dan waktu

Tidak ada sistem yang menggandakan omzet sendirian. Yang realistis:

1. **Jam ramai lebih muat** — kasir tidak jadi antrian pemesanan; meja berputar lebih tertib.  
2. **Lebih sedikit makanan terbuang karena salah order** — tamu yang pilih sendiri + dapur masak setelah lunas.  
3. **Lebih sedikit bocor tunai/QRIS yang tidak ketahuan** — nominal unik, konfirmasi kasir, jejak void.  
4. **Waktu owner** — tidak rekap nota semalaman; dasbor dan file laporan siap.  
5. **Citra** — restoran terasa rapi, modern, tanpa memaksa tamu instal aplikasi.  
6. **Ulasan di halaman sendiri** — tamu yang puas meninggalkan jejak yang calon tamu lain baca.

---

## 7. Siapa yang paling cocok

Cocok jika restoran Anda:

- walk-in (datang, duduk, pesan);
- sudah atau ingin pakai QRIS statis + tunai;
- punya (atau siap pasang) stiker di meja dan satu tablet dapur / kasir;
- ingin owner tetap pegang angka, bukan hanya “percaya shift.”

Kurang pas jika kebutuhan utamanya **reservasi meja dari jauh** — itu belum ada di tahap ini. Jujur lebih baik daripada janji fitur yang belum jalan.

---

## 8. Ajakan singkat

Kalau cerita di bagian awal terasa seperti restoran Anda kemarin, sistem ini dibuat untuk itu: **mengurangi chaos jam ramai, tanpa mengganti kasir dengan robot.**

Tamu tetap dijamu orang. Dapur tetap memasak. Yang berubah: catatan tidak hilang, bayar lebih mudah dicocokkan, layar dapur tidak berteriak, dan malamnya owner tidak menebak omzet.

Silakan daftar, pasang stiker di beberapa meja dulu, dan coba satu shift sepi. Yang meyakinkan owner biasanya bukan brosur — melainkan meja 4 yang pesan sendiri, kasir yang hanya tekan terima, dan dapur yang tidak ketinggalan es teh.
