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
            margin-bottom: 3.75rem;
            color: var(--muted);
        }

        .billing-guide__block {
            border: 1px solid var(--line);
            border-radius: 6px;
            background: var(--bg);
            margin-bottom: 0.85rem;
        }

        .billing-guide__block-head {
            padding: 0.7rem 1rem;
            border-bottom: 1px solid var(--line);
            font-size: 0.8125rem;
            font-weight: 600;
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
            grid-template-columns: 1.6rem minmax(0, 1fr);
            column-gap: 0.85rem;
            padding-bottom: 1.05rem;
        }

        .billing-guide__steps > li:last-child {
            padding-bottom: 0.35rem;
        }

        .billing-guide__steps > li:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 0.72rem;
            top: 1.7rem;
            bottom: 0;
            width: 1px;
            background: var(--line);
        }

        .billing-guide__step-no {
            display: flex;
            width: 1.6rem;
            height: 1.6rem;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background: var(--text);
            color: var(--bg);
            font-size: 0.7rem;
            font-weight: 700;
            line-height: 1;
        }

        .dark .billing-guide__step-no {
            background: #e5e7eb;
            color: #111827;
        }

        .billing-guide__step-title {
            display: block;
            font-weight: 600;
            line-height: 1.6rem;
        }

        .billing-guide__step-copy {
            margin-top: 0.15rem;
            color: var(--muted);
            font-size: 0.78rem;
        }

        .billing-guide__outcomes {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.45rem;
            padding-bottom: 0.85rem;
        }

        .billing-guide__meta {
            margin-top: 0.35rem;
            padding-top: 1rem;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 0.78rem;
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
            padding: 0.85rem 1rem;
            border: 1px solid var(--line);
            border-left: 3px solid var(--line);
            border-radius: 6px;
        }

        .billing-guide__rule--keep { border-left-color: #047857; }
        .billing-guide__rule--reset { border-left-color: #be123c; }

        .billing-guide__rule h3 {
            font-size: 0.8125rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .billing-guide__rule p {
            color: var(--muted);
            font-size: 0.78rem;
        }

        .billing-guide__notes {
            padding: 0.85rem 1rem 1rem;
            color: var(--muted);
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

    <p class="billing-guide__lead">
        Tagihan dibuat di halaman ini, dibayar lewat transfer, lalu Founder yang mengaktifkan paket.
        Satu restoran hanya boleh punya satu invoice yang belum lunas.
    </p>

    <section class="billing-guide__block">
        <div class="billing-guide__block-head">Alur transaksi</div>
        <div class="billing-guide__block-body">
            <ol class="billing-guide__steps">
                <li>
                    <span class="billing-guide__step-no">1</span>
                    <div>
                        <span class="billing-guide__step-title">Invoice dibuat</span>
                        <p class="billing-guide__step-copy">Owner, Founder, atau sistem H-7 membuat tagihan. Pilih paket dan durasi 1–12 bulan; nominal = harga paket × bulan.</p>
                    </div>
                </li>
                <li>
                    <span class="billing-guide__step-no">2</span>
                    <div>
                        <span class="billing-guide__step-title">Menunggu pembayaran</span>
                        <p class="billing-guide__step-copy">Transfer sesuai nominal ke rekening yang tertera di kartu paket. Paket belum berubah di tahap ini.</p>
                    </div>
                </li>
                <li>
                    <span class="billing-guide__step-no">3</span>
                    <div>
                        <span class="billing-guide__step-title">Unggah bukti</span>
                        <p class="billing-guide__step-copy">Dari baris invoice, unggah bukti transfer. Paket dan durasi masih bisa dipilih ulang sebelum dikirim.</p>
                    </div>
                </li>
                <li>
                    <span class="billing-guide__step-no">4</span>
                    <div>
                        <span class="billing-guide__step-title">Menunggu verifikasi</span>
                        <p class="billing-guide__step-copy">Founder meninjau bukti. Jika ditolak, status kembali ke menunggu pembayaran.</p>
                    </div>
                </li>
                <li>
                    <span class="billing-guide__step-no">5</span>
                    <div>
                        <span class="billing-guide__step-title">Keputusan Founder</span>
                        <p class="billing-guide__step-copy">Dua kemungkinan setelah tinjauan:</p>
                        <div class="billing-guide__outcomes">
                            <span class="billing-guide__tag billing-guide__tag--paid">Lunas</span>
                            <span class="billing-guide__tag billing-guide__tag--reject">Ditolak</span>
                        </div>
                    </div>
                </li>
            </ol>
            <p class="billing-guide__meta">
                Nomor invoice unik, format <code>INV-YYYYMM-…</code>.
                Invoice unpaid (termasuk H-7) boleh dihapus agar tombol Buat invoice terbuka lagi.
                Invoice lunas tidak bisa dihapus.
            </p>
        </div>
    </section>

    <div class="billing-guide__cols">
        <section class="billing-guide__block">
            <div class="billing-guide__block-head">Di halaman Langganan</div>
            <div class="billing-guide__block-body">
                <ul class="billing-guide__list">
                    <li><strong>Buat invoice</strong> hanya muncul jika tidak ada tagihan unpaid.</li>
                    <li>Pilih paket dan durasi, lalu transfer sesuai nominal yang dihitung sistem.</li>
                    <li><strong>Unggah bukti</strong> dari baris invoice yang masih terbuka.</li>
                    <li><strong>Hapus</strong> hanya untuk status menunggu pembayaran atau menunggu verifikasi.</li>
                </ul>
            </div>
        </section>

        <section class="billing-guide__block">
            <div class="billing-guide__block-head">Trial &amp; tagihan otomatis</div>
            <div class="billing-guide__block-body">
                <ul class="billing-guide__list">
                    <li>Pendaftaran baru mendapat uji coba <strong>{{ $trialDays ?? \App\Models\PlatformSetting::trialDays() }} hari</strong>.</li>
                    <li>Sistem bisa membuat invoice otomatis <strong>H-7</strong> jika belum ada yang unpaid.</li>
                    <li>Setelah masa aktif habis ada tenggang <strong>7 hari</strong> (panel read-only), lalu akses ditutup sampai ada pembayaran yang disetujui.</li>
                </ul>
            </div>
        </section>
    </div>

    <section class="billing-guide__block">
        <div class="billing-guide__block-head">Status tagihan</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Diubah oleh</th>
                        <th>Dampak</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="billing-guide__tag billing-guide__tag--wait">Menunggu pembayaran</span></td>
                        <td>Owner / sistem / Founder</td>
                        <td>Menunggu transfer; paket belum berubah</td>
                    </tr>
                    <tr>
                        <td><span class="billing-guide__tag billing-guide__tag--check">Menunggu verifikasi</span></td>
                        <td>Owner setelah unggah bukti</td>
                        <td>Founder meninjau bukti transfer</td>
                    </tr>
                    <tr>
                        <td><span class="billing-guide__tag billing-guide__tag--paid">Lunas</span></td>
                        <td>Founder menyetujui</td>
                        <td>Paket &amp; masa aktif diperbarui; tidak bisa dihapus</td>
                    </tr>
                    <tr>
                        <td><span class="billing-guide__tag billing-guide__tag--reject">Ditolak</span></td>
                        <td>Founder menolak bukti</td>
                        <td>Kembali menunggu pembayaran; paket tidak berubah</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <div class="billing-guide__split">
        <section class="billing-guide__rule billing-guide__rule--keep">
            <h3>Paket sama, masa aktif masih jalan</h3>
            <p>Durasi baru ditambahkan ke tanggal berlaku lama. Sisa hari tidak hangus.</p>
        </section>
        <section class="billing-guide__rule billing-guide__rule--reset">
            <h3>Ganti paket, atau sudah kedaluwarsa</h3>
            <p>Hitungan mulai dari hari Founder menyetujui. Sisa masa paket lama hangus.</p>
        </section>
    </div>

    <section class="billing-guide__block">
        <div class="billing-guide__block-head">Catatan</div>
        <div class="billing-guide__notes">
            <ul>
                <li>Transfer sesuai total tagihan dan unggah bukti yang jelas.</li>
                <li>Jangan buat tagihan ganda; hapus yang unpaid dulu jika ingin ganti paket atau durasi.</li>
                <li>Ganti paket memotong sisa masa aktif paket lama.</li>
            </ul>
        </div>
    </section>
</div>
