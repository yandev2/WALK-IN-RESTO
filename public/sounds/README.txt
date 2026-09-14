Folder ini opsional untuk menyimpan file audio kustom (MP3) notifikasi resto:

1. cashier-order.mp3
   - Suara saat ada pesanan baru masuk ke kasir (status: awaiting_cashier).
   - Jika file ini ada, sistem otomatis memutar MP3 ini.
   - Jika file ini tidak ada, sistem otomatis menggunakan Web Audio API Synthesizer (Chime D5-A5 bawaan).

2. kitchen-order.mp3
   - Suara bel dapur saat kasir mengonfirmasi pembayaran di mode bukan simple (status item: queued).
   - Jika file ini ada, sistem otomatis memutar MP3 ini.
   - Jika file ini tidak ada, sistem otomatis menggunakan Web Audio API Synthesizer (Triple Harmonic Bell C5-G5-C6 bawaan).
