<div class="ad-guide">
    <style>
        .ad-guide {
            --ag-bg: #ffffff;
            --ag-surface: #f8fafc;
            --ag-border: #e2e8f0;
            --ag-text: #0f172a;
            --ag-muted: #64748b;
            --ag-primary: #0075ff;
            --ag-success: #10b981;
            --ag-amber: #f59e0b;
            --ag-danger: #ef4444;
            color: var(--ag-text);
            font-size: 0.8125rem;
            line-height: 1.6;
        }

        .dark .ad-guide {
            --ag-bg: #0b1437;
            --ag-surface: rgba(255, 255, 255, 0.04);
            --ag-border: rgba(255, 255, 255, 0.08);
            --ag-text: #f8fafc;
            --ag-muted: #94a3b8;
            --ag-primary: #2cd9ff;
            --ag-success: #34d399;
            --ag-amber: #fbbf24;
            --ag-danger: #f87171;
        }

        .ad-guide p, .ad-guide h3, .ad-guide h4, .ad-guide ul, .ad-guide ol {
            margin: 0;
        }

        .ad-guide__card {
            background: var(--ag-surface);
            border: 1px solid var(--ag-border);
            border-radius: 0.875rem;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            transition: all 0.2s ease;
        }

        .ad-guide__badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.025em;
            text-transform: uppercase;
        }

        .ad-guide__badge--blue {
            background: rgba(0, 117, 255, 0.12);
            color: #0284c7;
            border: 1px solid rgba(0, 117, 255, 0.25);
        }
        .dark .ad-guide__badge--blue {
            color: #38bdf8;
            border-color: rgba(56, 189, 248, 0.3);
        }

        .ad-guide__badge--green {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }
        .dark .ad-guide__badge--green {
            color: #34d399;
            border-color: rgba(52, 211, 153, 0.3);
        }

        .ad-guide__badge--amber {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }
        .dark .ad-guide__badge--amber {
            color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.3);
        }

        .ad-guide__badge--red {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }
        .dark .ad-guide__badge--red {
            color: #f87171;
            border-color: rgba(248, 113, 113, 0.3);
        }

        .ad-guide__table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.775rem;
            margin-top: 0.75rem;
        }

        .ad-guide__table th {
            text-align: left;
            padding: 0.5rem 0.75rem;
            background: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid var(--ag-border);
            color: var(--ag-muted);
            font-weight: 600;
        }
        .dark .ad-guide__table th {
            background: rgba(255, 255, 255, 0.02);
        }

        .ad-guide__table td {
            padding: 0.65rem 0.75rem;
            border-bottom: 1px solid var(--ag-border);
            vertical-align: top;
        }

        .ad-guide__code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background: rgba(0, 0, 0, 0.06);
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            color: #e11d48;
        }
        .dark .ad-guide__code {
            background: rgba(255, 255, 255, 0.08);
            color: #fb7185;
        }

        .ad-guide__code-block {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background: #0f172a;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            overflow-x: auto;
            margin-top: 0.5rem;
            line-height: 1.5;
        }
    </style>

    {{-- Intro Banner --}}
    <div class="ad-guide__card" style="background: linear-gradient(135deg, rgba(0,117,255,0.08) 0%, rgba(44,217,255,0.04) 100%); border-color: rgba(0,117,255,0.25);">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <span class="ad-guide__badge ad-guide__badge--blue">Buku Panduan Monetisasi</span>
                <h3 class="mt-2 text-base font-bold text-slate-900 dark:text-white">Dokumentasi & Panduan Integrasi Iklan</h3>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">
                    Modul ini mengelola integrasi jaringan periklanan <strong>Google AdSense</strong> dan <strong>Adsterra</strong> pada platform Walk-In-Resto. Halaman ini dirancang aman, compliant terhadap kebijakan publisher, dan dilengkapi sistem <em>Zero-Ads Protection</em>.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="ad-guide__badge ad-guide__badge--green">✓ Zero-Ads Active</span>
            </div>
        </div>
    </div>

    {{-- Section 1: Konsep Zero-Ads Protection --}}
    <div class="ad-guide__card">
        <div class="flex items-center gap-2 mb-2">
            <span class="ad-guide__badge ad-guide__badge--green">Keamanan & Kepatuhan</span>
            <h4 class="font-bold text-slate-900 dark:text-white text-sm">Prinsip Zero-Ads (Perlindungan Operasional Restoran)</h4>
        </div>
        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
            Platform Walk-In-Resto menerapkan arsitektur isolasi ketat: <strong>iklan HANYA tampil pada halaman publik editorial</strong> (Blog Kuliner & Katalog Direktori Publik). Sistem memblokir iklan 100% pada semua halaman operasional dan transaksional.
        </p>

        <table class="ad-guide__table">
            <thead>
                <tr>
                    <th style="width: 45%;">Halaman / Zona</th>
                    <th style="width: 25%;">Status Iklan</th>
                    <th style="width: 30%;">Alasan Kebijakan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Menu & Pemesanan Pelanggan</strong><br>
                        <span class="ad-guide__code">/menu/*</span>, <span class="ad-guide__code">/order/*</span>, <span class="ad-guide__code">/cart</span>, <span class="ad-guide__code">/checkout</span>
                    </td>
                    <td><span class="ad-guide__badge ad-guide__badge--red">✕ Dilarang Mutlak</span></td>
                    <td class="text-xs text-slate-500">Menjaga fokus pelanggan saat memesan makanan dan menghindari salah klik.</td>
                </tr>
                <tr>
                    <td>
                        <strong>Point of Sale (POS) Kasir & Dapur (KDS)</strong><br>
                        <span class="ad-guide__code">/pos/*</span>, <span class="ad-guide__code">/cashier/*</span>, <span class="ad-guide__code">/kds/*</span>
                    </td>
                    <td><span class="ad-guide__badge ad-guide__badge--red">✕ Dilarang Mutlak</span></td>
                    <td class="text-xs text-slate-500">Mencegah distraksi staf restoran dan menjaga kecepatan pelayanan kasir.</td>
                </tr>
                <tr>
                    <td>
                        <strong>Panel Manajemen (Internal)</strong><br>
                        <span class="ad-guide__code">/founder/*</span>, <span class="ad-guide__code">/admin/*</span>, <span class="ad-guide__code">/blogger/*</span>
                    </td>
                    <td><span class="ad-guide__badge ad-guide__badge--red">✕ Dilarang Mutlak</span></td>
                    <td class="text-xs text-slate-500">Kebijakan Google melarang penempatan iklan di area dashboard / auth.</td>
                </tr>
                <tr>
                    <td>
                        <strong>Blog Kuliner & Resep (Publik)</strong><br>
                        <span class="ad-guide__code">/blog</span>, <span class="ad-guide__code">/blog/{slug}</span>, <span class="ad-guide__code">/blog/category/*</span>
                    </td>
                    <td><span class="ad-guide__badge ad-guide__badge--green">✓ Diizinkan Penuh</span></td>
                    <td class="text-xs text-slate-500">Zona monetisasi utama bernilai tinggi (editorial & SEO search traffic).</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Section 2: Penjelasan Setiap Field (Tab demi Tab) --}}
    <div class="ad-guide__card">
        <div class="flex items-center gap-2 mb-3">
            <span class="ad-guide__badge ad-guide__badge--blue">Detail Field</span>
            <h4 class="font-bold text-slate-900 dark:text-white text-sm">Panduan Konfigurasi Lengkap per Field</h4>
        </div>

        {{-- Tab 1 --}}
        <div class="mb-4">
            <h5 class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-slate-200 mb-2 flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span> Tab 1: Jaringan Iklan (Networks)
            </h5>
            <table class="ad-guide__table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Field / Input</th>
                        <th style="width: 40%;">Kegunaan & Dampak</th>
                        <th style="width: 30%;">Contoh Format</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Aktifkan Monetisasi Iklan</strong><br><span class="ad-guide__code">is_enabled</span></td>
                        <td>Master switch darurat. Jika dimatikan (OFF), seluruh script iklan AdSense dan Adsterra di seluruh situs langsung dinonaktifkan seketika.</td>
                        <td>Toggle: <span class="text-emerald-600 font-bold">Aktif / Nonaktif</span></td>
                    </tr>
                    <tr>
                        <td><strong>Aktifkan Google AdSense</strong><br><span class="ad-guide__code">adsense_enabled</span></td>
                        <td>Mengontrol pemuatan library script resmi AdSense (<span class="ad-guide__code">adsbygoogle.js</span>) di bagian <span class="ad-guide__code">&lt;head&gt;</span> halaman blog.</td>
                        <td>Toggle: <span class="text-emerald-600 font-bold">Aktif / Nonaktif</span></td>
                    </tr>
                    <tr>
                        <td><strong>Client ID (Publisher ID)</strong><br><span class="ad-guide__code">adsense_client_id</span></td>
                        <td>ID unik akun Google AdSense Anda. Diperoleh dari dashboard AdSense di menu <em>Akun > Info Akun > ID Penayang</em>.</td>
                        <td><span class="ad-guide__code">ca-pub-1234567890123456</span></td>
                    </tr>
                    <tr>
                        <td><strong>Aktifkan Auto Ads</strong><br><span class="ad-guide__code">adsense_auto_ads</span></td>
                        <td>Mengizinkan AI Google secara otomatis menempatkan iklan pintar di posisi yang dirasa optimal bagi pengguna tanpa perlu membuat unit slot manual.</td>
                        <td>Toggle: <span class="text-emerald-600 font-bold">Aktif / Nonaktif</span></td>
                    </tr>
                    <tr>
                        <td><strong>Aktifkan Adsterra</strong><br><span class="ad-guide__code">adsterra_enabled</span></td>
                        <td>Master switch untuk seluruh format iklan dari jaringan Adsterra.</td>
                        <td>Toggle: <span class="text-emerald-600 font-bold">Aktif / Nonaktif</span></td>
                    </tr>
                    <tr>
                        <td><strong>Social Bar (Adsterra)</strong><br><span class="ad-guide__code">adsterra_social_bar_enabled</span> & kode</td>
                        <td>Format notifikasi mengambang (in-page push) interaktif di sudut layar pembaca artikel. Salin kode JS dari dashboard Adsterra.</td>
                        <td><span class="ad-guide__code">&lt;script type="text/javascript" src="..."&gt;&lt;/script&gt;</span></td>
                    </tr>
                    <tr>
                        <td><strong>Native Banners (Adsterra)</strong><br><span class="ad-guide__code">adsterra_native_enabled</span> & kode</td>
                        <td>Format iklan banner bawaan yang berbaur secara alami dengan tampilan artikel dan kartu blog.</td>
                        <td><span class="ad-guide__code">&lt;script async="async" data-cfasync="false" src="..."&gt;&lt;/script&gt;</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Tab 2 --}}
        <div class="mb-4">
            <h5 class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-slate-200 mb-2 flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span> Tab 2: Penempatan Slot (Placements)
            </h5>
            <p class="text-xs text-slate-500 mb-2">
                Setiap slot iklan memiliki pengaturan mandiri: <strong>Aktifkan Slot</strong>, <strong>Provider</strong> (<span class="ad-guide__code">adsense</span> / <span class="ad-guide__code">adsterra</span> / <span class="ad-guide__code">custom</span>), dan <strong>Kode Unit Iklan</strong> (potongan HTML/JS yang digenerate oleh AdSense/Adsterra).
            </p>
            <table class="ad-guide__table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Nama Slot</th>
                        <th style="width: 35%;">Posisi di Halaman Web</th>
                        <th style="width: 40%;">Rekomendasi Format & Ukuran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Blog: Atas Artikel</strong><br><span class="ad-guide__code">slot_blog_article_top</span></td>
                        <td>Di bawah judul artikel dan tepat di atas isi konten blog.</td>
                        <td>Responsive Display Banner / Leaderboard (728x90 desktop, 320x100 mobile).</td>
                    </tr>
                    <tr>
                        <td><strong>Blog: Tengah Artikel</strong><br><span class="ad-guide__code">slot_blog_article_middle</span></td>
                        <td>Disisipkan otomatis oleh server setelah <strong>paragraf ke-3</strong> artikel blog.</td>
                        <td>In-Article Ad (format khusus AdSense yang menyatu dengan teks bacaan). CTR tertinggi!</td>
                    </tr>
                    <tr>
                        <td><strong>Blog: Bawah Artikel</strong><br><span class="ad-guide__code">slot_blog_article_bottom</span></td>
                        <td>Di akhir konten artikel, tepat sebelum tombol share dan kolom komentar.</td>
                        <td>Matched Content, Multiplex Ad, atau Large Rectangle (336x280).</td>
                    </tr>
                    <tr>
                        <td><strong>Blog: Sidebar Desktop</strong><br><span class="ad-guide__code">slot_blog_sidebar</span></td>
                        <td>Kolom kanan artikel (hanya tampil di layar desktop / laptop). Bersifat <em>sticky</em> saat scroll.</td>
                        <td>Half Page (300x600) atau Medium Rectangle (300x250).</td>
                    </tr>
                    <tr>
                        <td><strong>Blog: In-Feed Grid</strong><br><span class="ad-guide__code">slot_blog_feed</span></td>
                        <td>Di antara grid artikel pada halaman Beranda Blog, Arsip, Kategori, & Tag (setelah artikel ke-3).</td>
                        <td>In-Feed Ad (menyerupai kartu postingan blog biasa).</td>
                    </tr>
                    <tr>
                        <td><strong>Direktori: Native Listing</strong><br><span class="ad-guide__code">slot_directory_native</span></td>
                        <td>Kartu promosi bersponsor di katalog direktori resto utama.</td>
                        <td>Adsterra Native Banner / Custom Sponsored Link.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Tab 3 --}}
        <div>
            <h5 class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-slate-200 mb-2 flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span> Tab 3: File ads.txt (Authorized Digital Sellers)
            </h5>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                File <span class="ad-guide__code">ads.txt</span> adalah standar resmi IAB Tech Lab yang <strong>wajib dimiliki</strong> agar akun Google AdSense disetujui dan bebas dari peringatan <em>"Risiko kerugian pendapatan — satu atau beberapa file ads.txt Anda hilang"</em>. File ini di-host secara dinamis di URL:
                <a href="{{ url('/ads.txt') }}" target="_blank" class="text-primary font-bold underline">{{ url('/ads.txt') }}</a>.
            </p>
            <div class="ad-guide__code-block">
# Baris resmi Google AdSense (ganti pub-XXXXXXXXXXXXXXXX dengan ID Anda):
google.com, pub-XXXXXXXXXXXXXXXX, DIRECT, f08c47fec0942fa0

# Baris resmi Adsterra (salin baris otorisasi dari dashboard Adsterra):
adsterra.com, XXXXXX, DIRECT
            </div>
        </div>
    </div>

    {{-- Section 3: Langkah Demi Langkah Integrasi (Quickstart Checklist) --}}
    <div class="ad-guide__card">
        <div class="flex items-center gap-2 mb-3">
            <span class="ad-guide__badge ad-guide__badge--amber">Langkah Praktis</span>
            <h4 class="font-bold text-slate-900 dark:text-white text-sm">Checklist Integrasi Baru (Langkah demi Langkah)</h4>
        </div>
        <ol class="space-y-2.5 text-xs text-slate-700 dark:text-slate-300 pl-4 list-decimal">
            <li>
                <strong>Daftarkan Domain di AdSense / Adsterra:</strong><br>
                Tambahkan domain Anda (misal: <span class="ad-guide__code">{{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'restoterdekat.com' }}</span>) di menu <em>Situs</em> pada dashboard penyedia iklan.
            </li>
            <li>
                <strong>Isi Tab "ads.txt":</strong><br>
                Buka Tab 3 di panel ini, tempelkan baris otorisasi dari AdSense dan Adsterra, lalu klik <strong>Simpan Pengaturan Iklan</strong>. Pastikan file dapat dibuka di <span class="ad-guide__code">/ads.txt</span>.
            </li>
            <li>
                <strong>Konfigurasikan Jaringan (Tab 1):</strong><br>
                Masukkan <strong>Client ID</strong> AdSense Anda (contoh: <span class="ad-guide__code">ca-pub-1234567890123456</span>) dan aktifkan sakelar AdSense. Jika menggunakan Adsterra, tempelkan script Social Bar.
            </li>
            <li>
                <strong>Buat Unit Iklan & Pasang di Slot (Tab 2):</strong><br>
                Di AdSense, buka menu <em>Iklan > Berdasarkan unit iklan</em>. Buat unit <strong>In-Article</strong> dan <strong>Display</strong>. Salin kode HTML-nya dan tempelkan ke slot yang diinginkan (misal: <em>Blog: Atas Artikel</em> dan <em>Blog: Tengah Artikel</em>).
            </li>
            <li>
                <strong>Aktifkan Master Switch & Uji Tampilan:</strong><br>
                Nyalakan toggle <strong>Aktifkan Monetisasi Iklan</strong>. Buka salah satu artikel di <a href="{{ route('blog.index') }}" target="_blank" class="text-primary font-bold underline">/blog</a> untuk memastikan iklan muncul rapi dengan label <em>IKLAN</em> tanpa mengganggu pengalaman membaca.
            </li>
        </ol>
    </div>

    {{-- Section 4: Mengapa Popunder Dinonaktifkan --}}
    <div class="ad-guide__card" style="border-left: 4px solid #ef4444;">
        <div class="flex items-center gap-2 mb-1">
            <span class="ad-guide__badge ad-guide__badge--red">Kebijakan Mutu</span>
            <h4 class="font-bold text-slate-900 dark:text-white text-sm">Mengapa Format Popunder & Direct Link Dinonaktifkan?</h4>
        </div>
        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
            Platform ini secara sengaja <strong>TIDAK menyediakan opsi Popunder</strong> (iklan tab baru otomatis saat mengklik layar). Alasan utamanya:
        </p>
        <ul class="mt-2 space-y-1 text-xs text-slate-600 dark:text-slate-300 list-disc pl-4">
            <li><strong>Pelanggaran Google SEO Core Web Vitals</strong>: Popunder memicu lonjakan bounce rate hingga 80% dan dapat menurunkan peringkat artikel di hasil pencarian Google.</li>
            <li><strong>Risiko Banned AdSense</strong>: Google melarang penayangan AdSense di halaman yang memicu popunder pihak ketiga secara agresif.</li>
            <li><strong>Kepercayaan Pengunjung</strong>: Format Native Banner dan Social Bar terbukti memberikan eCPM optimal sekaligus menjaga pengalaman pengguna tetap elegan dan profesional.</li>
        </ul>
    </div>
</div>
