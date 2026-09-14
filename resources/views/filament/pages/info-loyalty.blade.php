@php
    $settings = $settings ?? [
        'enabled' => true,
        'spend_per_point' => 10000,
        'points_earned' => 1,
        'silver_min_spent' => 500000,
        'gold_min_spent' => 1500000,
        'vip_min_spent' => 5000000,
    ];
@endphp

<div class="loyalty-guide">
    <style>
        .loyalty-guide {
            --bg: #ffffff;
            --bg-muted: #f8fafc;
            --line: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --accent: #0075ff;
            color: var(--text);
            font-size: 0.8125rem;
            line-height: 1.55;
        }

        .dark .loyalty-guide {
            --bg: #0b1437;
            --bg-muted: rgba(255, 255, 255, 0.04);
            --line: rgba(255, 255, 255, 0.08);
            --text: #f8fafc;
            --muted: #94a3b8;
            --accent: #2cd9ff;
        }

        .loyalty-guide p,
        .loyalty-guide h3,
        .loyalty-guide h4,
        .loyalty-guide ol,
        .loyalty-guide ul {
            margin: 0;
        }

        .loyalty-guide__lead {
            margin-bottom: 1.25rem;
            color: var(--muted);
            font-size: 0.875rem;
            line-height: 1.6;
        }

        .loyalty-guide__badge-highlight {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.65rem;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .dark .loyalty-guide__badge-highlight {
            background: rgba(30, 58, 138, 0.3);
            color: #93c5fd;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .loyalty-guide__block {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--bg);
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .loyalty-guide__block-head {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--bg-muted);
            font-size: 0.8125rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .loyalty-guide__block-body {
            padding: 1rem;
        }

        .loyalty-guide__steps {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .loyalty-guide__steps > li {
            position: relative;
            display: grid;
            grid-template-columns: 1.75rem minmax(0, 1fr);
            column-gap: 0.85rem;
            padding-bottom: 1.15rem;
        }

        .loyalty-guide__steps > li:last-child {
            padding-bottom: 0.2rem;
        }

        .loyalty-guide__steps > li:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 0.85rem;
            top: 1.85rem;
            bottom: 0;
            width: 1px;
            background: var(--line);
        }

        .loyalty-guide__step-no {
            display: flex;
            width: 1.75rem;
            height: 1.75rem;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            background: #0075ff;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            line-height: 1;
        }

        .loyalty-guide__step-title {
            display: block;
            font-weight: 700;
            line-height: 1.75rem;
            color: var(--text);
        }

        .loyalty-guide__step-copy {
            margin-top: 0.2rem;
            color: var(--muted);
            font-size: 0.78rem;
            line-height: 1.5;
        }

        .loyalty-guide table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
        }

        .loyalty-guide th {
            padding: 0.6rem 1rem;
            text-align: left;
            font-weight: 700;
            color: var(--muted);
            background: var(--bg-muted);
            border-bottom: 1px solid var(--line);
        }

        .loyalty-guide td {
            padding: 0.75rem 1rem;
            color: var(--muted);
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        .loyalty-guide tr:last-child td {
            border-bottom: 0;
        }

        .loyalty-guide__pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.55rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .loyalty-guide__pill-vip {
            background: rgba(168, 85, 247, 0.15);
            color: #9333ea;
            border-color: rgba(168, 85, 247, 0.3);
        }
        .dark .loyalty-guide__pill-vip {
            color: #c084fc;
        }

        .loyalty-guide__pill-gold {
            background: rgba(245, 158, 11, 0.15);
            color: #d97706;
            border-color: rgba(245, 158, 11, 0.3);
        }
        .dark .loyalty-guide__pill-gold {
            color: #fbbf24;
        }

        .loyalty-guide__pill-silver {
            background: rgba(6, 182, 212, 0.15);
            color: #0891b2;
            border-color: rgba(6, 182, 212, 0.3);
        }
        .dark .loyalty-guide__pill-silver {
            color: #22d3ee;
        }

        .loyalty-guide__pill-reguler {
            background: rgba(100, 116, 139, 0.1);
            color: #475569;
            border-color: rgba(100, 116, 139, 0.2);
        }
        .dark .loyalty-guide__pill-reguler {
            color: #94a3b8;
        }
    </style>

    {{-- Header Lead --}}
    <div class="mb-4">
        <div class="flex items-center justify-between gap-2 mb-2">
            <span class="loyalty-guide__badge-highlight">
                <x-heroicon-o-sparkles class="h-3.5 w-3.5" />
                Loyalty Program & CRM Engine
            </span>
            <span class="text-xs font-semibold {{ $settings['enabled'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                Status: {{ $settings['enabled'] ? '● Aktif' : '○ Sedang Dinonaktifkan' }}
            </span>
        </div>
        <p class="loyalty-guide__lead">
            Sistem Loyalitas & CRM dirancang untuk mengubah pembeli kasual menjadi <strong>pelanggan setia berulang (repeat customers)</strong>, mendongkrak omset resto tanpa biaya iklan mahal, dan memberikan apresiasi nyata kepada tamu yang sering berbelanja.
        </p>
    </div>

    {{-- Block 1: Apa Kegunaan CRM & Sistem Poin Ini? --}}
    <div class="loyalty-guide__block">
        <div class="loyalty-guide__block-head">
            <div class="flex items-center gap-2">
                <x-heroicon-o-trophy class="h-4 w-4 text-amber-500" />
                <span>1. Apa Manfaat & Kegunaan Utama Bagi Pemilik Resto?</span>
            </div>
        </div>
        <div class="loyalty-guide__block-body">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="p-3 rounded-lg border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02]">
                    <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                        <x-heroicon-o-arrow-path class="h-4 w-4 text-emerald-500" />
                        Meningkatkan Repeat Order
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Tamu yang mengumpulkan poin dan memiliki tier (Silver, Gold, VIP) memiliki keterikatan psikologis untuk datang kembali ke resto Anda dibanding ke kompetitor.
                    </p>
                </div>

                <div class="p-3 rounded-lg border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02]">
                    <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                        <x-heroicon-o-banknotes class="h-4 w-4 text-sky-500" />
                        Menaikkan Nilai Belanja (AOV)
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Tamu terdorong menambah pesanan (upselling) agar total belanja mencapai kelipatan poin berikutnya atau memenuhi syarat upgrade ke tier yang lebih bergengsi.
                    </p>
                </div>

                <div class="p-3 rounded-lg border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02]">
                    <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                        <x-heroicon-o-device-phone-mobile class="h-4 w-4 text-purple-500" />
                        Database Pelanggan Otomatis
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Setiap kasir menginput nomor WhatsApp di POS Kasir, data tamu langsung tercatat aman ke dalam database CRM resto lengkap dengan riwayat pesanannya.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Block 2: Bagaimana Cara Kerjanya? --}}
    <div class="loyalty-guide__block">
        <div class="loyalty-guide__block-head">
            <div class="flex items-center gap-2">
                <x-heroicon-o-cog-6-tooth class="h-4 w-4 text-sky-500" />
                <span>2. Alur Cara Kerja Sistem (Otomatis & Tanpa Ribet)</span>
            </div>
        </div>
        <div class="loyalty-guide__block-body">
            <ol class="loyalty-guide__steps">
                <li>
                    <span class="loyalty-guide__step-no">1</span>
                    <div>
                        <span class="loyalty-guide__step-title">Kasir Memasukkan Nomor WhatsApp Tamu</span>
                        <div class="loyalty-guide__step-copy">
                            Saat tamu memesan di POS Kasir, kasir memasukkan nama dan nomor WhatsApp tamu. Jika nomor sudah pernah bertransaksi sebelumnya, sistem otomatis mengenali nama, tier, dan saldo poin tamu tersebut.
                        </div>
                    </div>
                </li>
                <li>
                    <span class="loyalty-guide__step-no">2</span>
                    <div>
                        <span class="loyalty-guide__step-title">Perhitungan Poin Otomatis Saat Pesanan Lunas</span>
                        <div class="loyalty-guide__step-copy">
                            Begitu pesanan diselesaikan (tunai atau QRIS), sistem secara otomatis menghitung poin berdasarkan rasio belanja yang Anda atur:
                            <div class="mt-1 p-2 rounded bg-slate-100 dark:bg-white/5 font-mono text-[11px] text-slate-700 dark:text-slate-300">
                                Saat ini: Setiap belanja <strong>Rp {{ number_format($settings['spend_per_point'], 0, ',', '.') }}</strong> mendapatkan <strong>{{ $settings['points_earned'] }} Poin</strong>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <span class="loyalty-guide__step-no">3</span>
                    <div>
                        <span class="loyalty-guide__step-title">Evaluasi Kenaikan Tier Member Secara Otomatis</span>
                        <div class="loyalty-guide__step-copy">
                            Sistem melacak akumulasi total belanja sepanjang masa (LTV). Jika total belanja tamu melampaui batas tier, status tamu otomatis naik kelas (Reguler → Silver → Gold → VIP).
                        </div>
                    </div>
                </li>
                <li>
                    <span class="loyalty-guide__step-no">4</span>
                    <div>
                        <span class="loyalty-guide__step-title">Tercetak di Struk Kasir & PDF Pelanggan</span>
                        <div class="loyalty-guide__step-copy">
                            Jika program poin diaktifkan, struk thermal kasir (58mm/80mm) dan unduhan PDF struk tamu akan mencantumkan: <strong>Nama Tamu</strong>, <strong>Tier Member</strong>, <strong>Poin Diperoleh</strong>, dan <strong>Saldo Poin Akhir</strong>.
                        </div>
                    </div>
                </li>
            </ol>
        </div>
    </div>

    {{-- Block 3: Ketentuan Ambang Batas Tier Saat Ini --}}
    <div class="loyalty-guide__block">
        <div class="loyalty-guide__block-head">
            <div class="flex items-center gap-2">
                <x-heroicon-o-shield-check class="h-4 w-4 text-emerald-500" />
                <span>3. Tingkatan Tier & Syarat Akumulasi Belanja</span>
            </div>
            <span class="text-[11px] text-slate-400">Bisa di-custom di menu Pengaturan</span>
        </div>
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Tier</th>
                        <th>Syarat Minimal Akumulasi Belanja</th>
                        <th>Kategori Tamu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="loyalty-guide__pill loyalty-guide__pill-reguler">Reguler</span></td>
                        <td>Rp 0 (Tamu terdaftar)</td>
                        <td>Tamu baru yang baru bertransaksi 1-2 kali</td>
                    </tr>
                    <tr>
                        <td><span class="loyalty-guide__pill loyalty-guide__pill-silver">Silver</span></td>
                        <td><strong>Rp {{ number_format($settings['silver_min_spent'], 0, ',', '.') }}</strong></td>
                        <td>Pelanggan yang mulai rutin berkunjung</td>
                    </tr>
                    <tr>
                        <td><span class="loyalty-guide__pill loyalty-guide__pill-gold">Gold</span></td>
                        <td><strong>Rp {{ number_format($settings['gold_min_spent'], 0, ',', '.') }}</strong></td>
                        <td>Pelanggan setia dengan kontribusi omset stabil</td>
                    </tr>
                    <tr>
                        <td><span class="loyalty-guide__pill loyalty-guide__pill-vip">VIP</span></td>
                        <td><strong>Rp {{ number_format($settings['vip_min_spent'], 0, ',', '.') }}</strong></td>
                        <td>Top Spender / Pelanggan paling berharga bagi restoran Anda</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Block 4: Penukaran Poin Menjadi Diskon & Keadilan Komisi --}}
    <div class="loyalty-guide__block border-cyan-500/30 dark:border-cyan-500/20">
        <div class="loyalty-guide__block-head bg-cyan-500/10 text-cyan-800 dark:text-cyan-300">
            <div class="flex items-center gap-2">
                <x-heroicon-o-ticket class="h-4 w-4 text-cyan-500" />
                <span class="font-bold">4. Penukaran Poin Menjadi Diskon (Point-as-Discount)</span>
            </div>
            <span class="loyalty-guide__badge-highlight">Point-to-Discount</span>
        </div>
        <div class="loyalty-guide__block-body space-y-3.5">
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Pelanggan dapat menukarkan poin loyalitas yang dimilikinya menjadi potongan harga langsung saat checkout pesanan, baik melalui <strong>POS Kasir</strong> maupun <strong>Pemesanan Mandiri Scan QR Meja di HP</strong>.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="p-3 rounded-lg border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02]">
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nilai Tukar 1 Poin</div>
                    <div class="text-base font-extrabold text-slate-900 dark:text-white mt-0.5">
                        1 Poin = Rp {{ number_format($settings['point_redemption_rate'] ?? 1000, 0, ',', '.') }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">Dapat diatur bebas oleh Owner di menu Pengaturan.</div>
                </div>

                <div class="p-3 rounded-lg border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02]">
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Minimal Penukaran</div>
                    <div class="text-base font-extrabold text-slate-900 dark:text-white mt-0.5">
                        {{ $settings['min_redeem_points'] ?? 10 }} Poin
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">Mencegah penukaran poin receh yang tidak efisien.</div>
                </div>

                <div class="p-3 rounded-lg border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02]">
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Batas Maksimal Potongan</div>
                    <div class="text-base font-extrabold text-slate-900 dark:text-white mt-0.5">
                        {{ $settings['max_redeem_percentage'] ?? 50 }}% Subtotal
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">Melindungi kas resto agar tamu tetap membayar sisa tagihan secara riil.</div>
                </div>
            </div>

            {{-- Special Highlight Callout: Bebas Komisi Platform --}}
            <div class="p-3.5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-900 dark:text-emerald-200 flex items-start gap-3 shadow-sm">
                <x-heroicon-o-check-badge class="h-5 w-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" />
                <div class="text-xs leading-relaxed">
                    <strong class="font-bold text-emerald-800 dark:text-emerald-300 block text-[13px] mb-1">
                        Restoran TIDAK Dikenakan Potongan Komisi atas Diskon Poin!
                    </strong>
                    Sistem Walk-In Resto sangat adil bagi pemilik resto. Komisi platform <strong>murni dihitung dari omzet riil yang diterima restoran setelah diskon</strong> (net omzet). Anda <strong>TIDAK dikenakan potongan komisi platform sepeser pun</strong> atas diskon poin yang Anda berikan kepada pelanggan setia!
                </div>
            </div>

            {{-- Audit & Rekap Shift Kasir Transparency --}}
            <div class="p-3 rounded-xl border border-blue-500/20 bg-blue-500/5 text-blue-900 dark:text-blue-200 flex items-start gap-3">
                <x-heroicon-o-scale class="h-5 w-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                <div class="text-xs leading-relaxed">
                    <strong class="font-bold text-blue-800 dark:text-blue-300 block mb-0.5">
                        Transparansi Tutup Shift & Rekonsiliasi Kasir
                    </strong>
                    Pada struk rekap tutup shift (thermal & PDF), potongan poin tercatat secara eksplisit sebagai: <em>"Diskon Poin Member: -Rp X (Y Poin)"</em>. Sistem memastikan saldo kas fisik di laci kasir tetap <strong>PAS (Rp 0 selisih)</strong> karena kasir dan owner mengetahui dengan jelas bahwa Rp sekian ditukarkan melalui program poin.
                </div>
            </div>
        </div>
    </div>

    {{-- Block 5: Fitur Praktis Lainnya --}}
    <div class="loyalty-guide__block">
        <div class="loyalty-guide__block-head">
            <div class="flex items-center gap-2">
                <x-heroicon-o-chat-bubble-left-ellipsis class="h-4 w-4 text-emerald-500" />
                <span>5. Fitur Praktis Lainnya</span>
            </div>
        </div>
        <div class="loyalty-guide__block-body space-y-3">
            <div>
                <strong class="text-slate-900 dark:text-white block mb-0.5">● Penyesuaian Poin Manual (Bonus / Tukar Reward):</strong>
                <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">
                    Di menu <strong>CRM & Pelanggan -> Master Pelanggan</strong>, Anda dapat menambahkan poin (misal: hadiah ulang tahun pelanggan) atau mengurangi poin (saat pelanggan menukarkan poin dengan menu gratis/diskon). Setiap perubahan mencatat alasan audit log secara rapi.
                </p>
            </div>
            <div>
                <strong class="text-slate-900 dark:text-white block mb-0.5">● Re-engagement Tamu Dormant (>30 Hari Tidak Datang):</strong>
                <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">
                    Sistem mendeteksi tamu yang belum berkunjung dalam 30 hari terakhir. Cukup klik tombol <strong>"Sapa WA"</strong> pada tabel analitik untuk langsung membuka WhatsApp dengan template sapaan ramah siap kirim.
                </p>
            </div>
            <div>
                <strong class="text-slate-900 dark:text-white block mb-0.5">● Fitur Fleksibel (Bisa Dinonaktifkan):</strong>
                <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">
                    Jika konsep resto Anda tidak memerlukan sistem poin, matikan opsi ini melalui tombol <strong>"Pengaturan Poin & Tier"</strong>. <em>Analitik CRM akan tetap 100% aktif</em> mencatat profil tamu, frekuensi kunjungan, dan omset belanja tanpa menghitung poin atau mencetak tier pada struk kasir.
                </p>
            </div>
        </div>
    </div>
</div>
