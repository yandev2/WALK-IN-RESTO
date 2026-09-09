@php
    $trialDays = $trialDays ?? \App\Models\PlatformSetting::trialDays();
    $commissionPercent = $commissionPercent ?? \App\Models\PlatformSetting::cashierCommissionPercentage();
@endphp

<div class="billing-guide">
    <style>
        .billing-guide {
            --bg: #fff;
            --bg-muted: #f4f4f5;
            --line: #e4e4e7;
            --text: #18181b;
            --muted: #52525b;
            --accent: var(--primary-600, #ea580c);
            color: var(--text);
            font-size: 0.8125rem;
            line-height: 1.55;
        }

        .dark .billing-guide {
            --bg: #111827;
            --bg-muted: #1f2937;
            --line: #374151;
            --text: #f4f4f5;
            --muted: #a1a1aa;
        }

        .billing-guide p,
        .billing-guide h3,
        .billing-guide ol,
        .billing-guide ul {
            margin: 0;
        }

        .billing-guide__lead {
            margin-bottom: 1.25rem;
            color: var(--muted);
            font-size: 0.875rem;
            line-height: 1.6;
        }

        .billing-guide__badge-highlight {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.6rem;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .dark .billing-guide__badge-highlight {
            background: rgba(6, 78, 59, 0.4);
            color: #6ee7b7;
            border-color: #065f46;
        }

        .billing-guide__block {
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--bg);
            margin-bottom: 0.85rem;
            overflow: hidden;
        }

        .billing-guide__block-head {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--bg-muted);
            font-size: 0.8125rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .billing-guide__block-body {
            padding: 1rem;
        }

        .billing-guide__steps {
            list-style: none;
            padding: 0;
        }

        .billing-guide__steps > li {
            position: relative;
            display: grid;
            grid-template-columns: 1.75rem minmax(0, 1fr);
            column-gap: 0.85rem;
            padding-bottom: 1.15rem;
        }

        .billing-guide__steps > li:last-child {
            padding-bottom: 0.2rem;
        }

        .billing-guide__steps > li:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 0.85rem;
            top: 1.85rem;
            bottom: 0;
            width: 1px;
            background: var(--line);
        }

        .billing-guide__step-no {
            display: flex;
            width: 1.75rem;
            height: 1.75rem;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            background: var(--text);
            color: var(--bg);
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1;
        }

        .dark .billing-guide__step-no {
            background: #e5e7eb;
            color: #111827;
        }

        .billing-guide__step-title {
            display: block;
            font-weight: 700;
            line-height: 1.75rem;
            color: var(--text);
        }

        .billing-guide__step-copy {
            margin-top: 0.2rem;
            color: var(--muted);
            font-size: 0.78rem;
            line-height: 1.5;
        }

        .billing-guide__cols {
            display: grid;
            gap: 0.85rem;
        }

        .billing-guide__list {
            padding: 0;
            list-style: none;
        }

        .billing-guide__list li {
            padding: 0.55rem 0;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 0.78rem;
            line-height: 1.45;
        }

        .billing-guide__list li:first-child {
            padding-top: 0;
            border-top: 0;
        }

        .billing-guide__list strong {
            color: var(--text);
            font-weight: 600;
        }

        .billing-guide table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
        }

        .billing-guide th {
            padding: 0.55rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--muted);
            background: var(--bg-muted);
            border-bottom: 1px solid var(--line);
        }

        .billing-guide td {
            padding: 0.7rem 1rem;
            color: var(--muted);
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        .billing-guide tr:last-child td {
            border-bottom: 0;
        }

        .billing-guide__tag {
            display: inline-block;
            padding: 0.28rem 0.5rem;
            border-radius: 4px;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 600;
            line-height: 1.25;
            white-space: nowrap;
        }

        .billing-guide__tag--wait { background: #b45309; }
        .billing-guide__tag--check { background: #0369a1; }
        .billing-guide__tag--paid { background: #047857; }
        .billing-guide__tag--reject { background: #be123c; }

        .billing-guide__split {
            display: grid;
            gap: 0.85rem;
            margin-bottom: 0.85rem;
        }

        .billing-guide__rule {
            padding: 0.9rem 1rem;
            border: 1px solid var(--line);
            border-left: 4px solid var(--line);
            border-radius: 6px;
            background: var(--bg);
        }

        .billing-guide__rule--free { border-left-color: #047857; }
        .billing-guide__rule--warn { border-left-color: #ea580c; }

        .billing-guide__rule h3 {
            font-size: 0.8125rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
            color: var(--text);
        }

        .billing-guide__rule p {
            color: var(--muted);
            font-size: 0.78rem;
            line-height: 1.45;
        }

        .billing-guide__notes {
            padding: 0.85rem 1rem 1rem;
            color: var(--muted);
            font-size: 0.78rem;
        }

        .billing-guide__notes ul {
            padding-left: 1.1rem;
        }

        .billing-guide__notes li + li {
            margin-top: 0.35rem;
        }

        .billing-guide__notes strong {
            color: var(--text);
        }

        @media (min-width: 720px) {
            .billing-guide__cols,
            .billing-guide__split {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    <div class="billing-guide__lead">
        <p>
            Walk-In Resto menggunakan sistem <strong>komisi omzet kasir</strong> (Pay-as-you-earn).
            Restoran tidak dibebani biaya langganan bulanan tetap. Anda hanya membayar komisi dari hasil pesanan yang sukses di kasir pada akhir bulan.
        </p>
    </div>

    {{-- 5 Steps --}}
    <section class="billing-guide__block">
        <div class="billing-guide__block-head">
            <span>Alur Transaksi &amp; Pembayaran Komisi</span>
            <span class="billing-guide__badge-highlight">Periode Bulanan</span>
        </div>
        <div class="billing-guide__block-body">
            <ol class="billing-guide__steps">
                <li>
                    <span class="billing-guide__step-no">1</span>
                    <div>
                        <span class="billing-guide__step-title">Akumulasi Real-Time Setiap Transaksi</span>
                        <p class="billing-guide__step-copy">
                            Setiap pesanan kasir yang selesai dibayar (<strong>Paid / Success</strong>) secara otomatis dihitung komisinya (standar <strong>{{ number_format($commissionPercent, 0) }}%</strong>) dan mengakumulasi invoice bulan berjalan. Anda dapat memantau omzet dan estimasi tagihan setiap saat.
                        </p>
                    </div>
                </li>
                <li>
                    <span class="billing-guide__step-no">2</span>
                    <div>
                        <span class="billing-guide__step-title">Notifikasi Pengingat (H-3 Akhir Bulan)</span>
                        <p class="billing-guide__step-copy">
                            Tiga hari sebelum akhir bulan (H-3), sistem mengirim notifikasi pengingat ke panel dashboard agar owner dapat menyiapkan dana pelunasan tagihan komisi.
                        </p>
                    </div>
                </li>
                <li>
                    <span class="billing-guide__step-no">3</span>
                    <div>
                        <span class="billing-guide__step-title">Pembayaran Dibuka di Akhir Bulan</span>
                        <p class="billing-guide__step-copy">
                            Selama bulan masih berjalan, tombol unggah bukti pembayaran ditutup agar akumulasi omzet final selesai. Tombol pembayaran &amp; unggah bukti akan aktif tepat pada tanggal akhir bulan (tanggal 28–31 sesuai kalender).
                        </p>
                    </div>
                </li>
                <li>
                    <span class="billing-guide__step-no">4</span>
                    <div>
                        <span class="billing-guide__step-title">Transfer &amp; Unggah Bukti Bayar</span>
                        <p class="billing-guide__step-copy">
                            Transfer nominal tagihan ke rekening bank atau scan QR Code Founder yang tersedia di kartu transfer, lalu unggah bukti transfer dari baris tagihan terkait.
                        </p>
                    </div>
                </li>
                <li>
                    <span class="billing-guide__step-no">5</span>
                    <div>
                        <span class="billing-guide__step-title">Verifikasi Founder &amp; Akses Lancar</span>
                        <p class="billing-guide__step-copy">
                            Founder meninjau dan memvalidasi bukti pembayaran Anda. Setelah disetujui, status tagihan berubah menjadi <strong>Lunas</strong> dan seluruh operasional Kasir &amp; KDS berlanjut lancar ke bulan berikutnya.
                        </p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    {{-- 2 Cols --}}
    <div class="billing-guide__cols">
        <section class="billing-guide__block">
            <div class="billing-guide__block-head">Skema Paket &amp; Trial</div>
            <div class="billing-guide__block-body">
                <ul class="billing-guide__list">
                    <li><strong>Biaya Dasar:</strong> Rp 0 / bulan (tanpa biaya langganan flat bulanan).</li>
                    <li><strong>Tarif Komisi:</strong> {{ number_format($commissionPercent, 0) }}% per transaksi kasir yang berhasil dibayar.</li>
                    <li><strong>Trial {{ $trialDays }} Hari Pertama:</strong> Restoran baru mendapat uji coba 100% <strong>bebas komisi (0%)</strong> selama {{ $trialDays }} hari masa trial.</li>
                    <li><strong>Pembaruan Otomatis:</strong> Tagihan bulan baru dibuat otomatis oleh sistem di awal bulan.</li>
                </ul>
            </div>
        </section>

        <section class="billing-guide__block">
            <div class="billing-guide__block-head">Landing Page vs Layanan Kasir</div>
            <div class="billing-guide__block-body">
                <ul class="billing-guide__list">
                    <li><strong>Landing Page &amp; Web Profil:</strong> <strong>100% Gratis Selamanya</strong>. Seluruh profil resto, katalog menu digital publik, dan informasi resto tetap aktif tanpa biaya.</li>
                    <li><strong>KDS &amp; Layanan Kasir:</strong> Fitur operasional kasir dan kitchen display hanya dikenakan komisi dari transaksi berhasil.</li>
                    <li><strong>Perlindungan Publik:</strong> Pelanggan tetap bisa melihat menu dan katalog Anda meskipun kasir sedang jatuh tempo.</li>
                </ul>
            </div>
        </section>
    </div>

    {{-- Rules Split --}}
    <div class="billing-guide__split">
        <section class="billing-guide__rule billing-guide__rule--free">
            <h3>Omzet Rp 0 = Otomatis Lunas</h3>
            <p>
                Jika dalam satu bulan resto Anda sedang libur atau tidak ada transaksi kasir yang sukses (omzet Rp 0), tagihan akan otomatis ditandai <strong>Lunas (Paid)</strong> tanpa perlu melakukan transfer.
            </p>
        </section>
        <section class="billing-guide__rule billing-guide__rule--warn">
            <h3>Konsekuensi Keterlambatan (Overdue)</h3>
            <p>
                Jika tagihan komisi belum dilunasi setelah jatuh tempo akhir bulan: menu <strong>Kasir</strong>, <strong>Kitchen (KDS)</strong>, dan <strong>Role Pengguna</strong> akan dinonaktifkan sementara. Landing page publik tetap aktif.
            </p>
        </section>
    </div>

    {{-- Status Table --}}
    <section class="billing-guide__block">
        <div class="billing-guide__block-head">Arti Status Tagihan</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi yang Diperlukan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="billing-guide__tag billing-guide__tag--wait">Menunggu pembayaran</span></td>
                        <td>Tagihan komisi periode berjalan sedang diakumulasikan atau sudah masuk tanggal akhir bulan dan belum dibayar.</td>
                        <td>Tunggu tanggal akhir bulan untuk finalisasi, lalu lakukan transfer dan unggah bukti pembayaran.</td>
                    </tr>
                    <tr>
                        <td><span class="billing-guide__tag billing-guide__tag--check">Menunggu verifikasi</span></td>
                        <td>Bukti transfer telah berhasil Anda unggah dan sedang ditinjau oleh Founder.</td>
                        <td>Tunggu konfirmasi Founder. Fitur kasir tetap dapat berjalan selagi verifikasi berlangsung.</td>
                    </tr>
                    <tr>
                        <td><span class="billing-guide__tag billing-guide__tag--paid">Lunas</span></td>
                        <td>Pembayaran telah diverifikasi Founder, atau otomatis lunas karena omzet kasir Rp 0.</td>
                        <td>Tidak ada tindakan diperlukan. Layanan Kasir &amp; KDS aktif penuh.</td>
                    </tr>
                    <tr>
                        <td><span class="billing-guide__tag billing-guide__tag--reject">Ditolak</span></td>
                        <td>Bukti pembayaran yang diunggah tidak valid atau nominal tidak sesuai.</td>
                        <td>Periksa kembali catatan penolakan dari Founder dan unggah ulang bukti transfer yang benar.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Additional Notes --}}
    <section class="billing-guide__block">
        <div class="billing-guide__block-head">Catatan Tambahan</div>
        <div class="billing-guide__notes">
            <ul>
                <li><strong>Kemudahan Pembayaran:</strong> Klik gambar QR code pada kartu info rekening untuk memperbesar gambar QR dan memindai langsung menggunakan aplikasi m-Banking atau e-Wallet favorit Anda.</li>
                <li><strong>Nomor Rekening:</strong> Gunakan tombol <strong>Salin</strong> di samping nomor rekening untuk mencegah kesalahan input saat melakukan transfer antar-bank.</li>
                <li><strong>Pertanyaan &amp; Bantuan:</strong> Jika membutuhkan bantuan terkait rincian transaksi atau verifikasi pembayaran, hubungi kontak email atau WhatsApp pengelola yang tertera di bagian bawah halaman ini.</li>
            </ul>
        </div>
    </section>
</div>
