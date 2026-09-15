<x-filament-panels::page>
    @php
        $stats = $analytics;
        $total = $stats['total_reviews'];
        $avg = $stats['avg_rating'];
        $csat = $stats['satisfaction_rate'];
    @endphp

    <div class="space-y-6 w-full">
        {{-- Banner Panduan Singkat CSAT --}}
        <div class="vision-card p-4 sm:p-5 border-l-4 border-l-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20">
            <div class="flex items-start sm:items-center justify-between gap-4 flex-wrap">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="vision-icon-box vision-icon-box-green shrink-0">
                        <x-heroicon-o-face-smile class="h-5 w-5 text-white" />
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base">
                            Indeks Kepuasan Pelanggan (Customer Satisfaction Score / CSAT)
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Data dihitung otomatis dari ulasan bintang 1–5 dan masukan langsung yang dikirimkan tamu setelah menyelesaikan santapan di meja.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ \App\Filament\Resources\CustomerReviews\CustomerReviewResource::getUrl('index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all duration-200">
                        <x-heroicon-o-chat-bubble-left-ellipsis class="h-4 w-4" />
                        <span>Buka Semua Ulasan di Tabel &rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Row 1: 4 Vision UI KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4 sm:gap-5">
            {{-- Card 1: Skor Rata-rata Rating --}}
            <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                Skor Rata-Rata Rating
                            </p>
                        </div>
                        <div class="vision-icon-box vision-icon-box-orange shadow-md">
                            <x-heroicon-o-star class="h-5 w-5 text-white" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                                {{ $avg > 0 ? number_format($avg, 2) : '0.0' }}
                            </span>
                            <span class="text-xs font-semibold text-slate-400">/ 5.0</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-amber-500">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($avg))
                                    <x-heroicon-s-star class="h-4 w-4 fill-amber-400 text-amber-400" />
                                @else
                                    <x-heroicon-o-star class="h-4 w-4 text-slate-300 dark:text-slate-600" />
                                @endif
                            @endfor
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300 ml-1.5">
                                {{ $avg >= 4.5 ? 'Luar Biasa' : ($avg >= 4.0 ? 'Sangat Baik' : ($avg >= 3.0 ? 'Cukup Baik' : 'Perlu Evaluasi')) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Bulan ini: {{ number_format($stats['this_month_avg'], 2) }}</span>
                    <span class="font-semibold text-amber-600 dark:text-amber-400">
                        {{ $stats['this_month_reviews'] }} Ulasan Baru
                    </span>
                </div>
            </div>

            {{-- Card 2: Tingkat Kepuasan (CSAT %) --}}
            <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                Tingkat Kepuasan (CSAT)
                            </p>
                        </div>
                        <div class="vision-icon-box vision-icon-box-green shadow-md">
                            <x-heroicon-o-hand-thumb-up class="h-5 w-5 text-white" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                            {{ $csat }}%
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20">
                                {{ $stats['positive_count'] }} Tamu Puas (⭐ 4–5)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Target Ideal: &ge; 85%</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                        Indeks Positif
                    </span>
                </div>
            </div>

            {{-- Card 3: Total Ulasan Terkumpul --}}
            <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                Total Ulasan Tamu
                            </p>
                        </div>
                        <div class="vision-icon-box vision-icon-box-blue shadow-md">
                            <x-heroicon-o-chat-bubble-bottom-center-text class="h-5 w-5 text-white" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                            {{ number_format($total) }}
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-sky-500/15 text-sky-600 dark:text-sky-400 border-sky-500/20">
                                +{{ $stats['this_month_reviews'] }} Masuk Bulan Ini
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Bulan lalu: {{ $stats['last_month_reviews'] }} ulasan</span>
                    <a href="{{ \App\Filament\Resources\CustomerReviews\CustomerReviewResource::getUrl('index') }}"
                        class="inline-flex items-center gap-1 font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors">
                        Lihat Data &rarr;
                    </a>
                </div>
            </div>

            {{-- Card 4: Ulasan Perlu Perhatian / Keluhan --}}
            <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                Perlu Perhatian (⭐ 1–2)
                            </p>
                        </div>
                        <div class="vision-icon-box vision-icon-box-purple shadow-md">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-white" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                            {{ number_format($stats['critical_count']) }}
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $stats['critical_count'] > 0 ? 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/20' : 'bg-slate-500/15 text-slate-600 dark:text-slate-400 border-slate-500/20' }}">
                                {{ $stats['critical_rate'] }}% Tingkat Keluhan
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>{{ $stats['neutral_count'] }} ulasan netral (⭐ 3)</span>
                    <span class="font-semibold {{ $stats['critical_count'] > 0 ? 'text-rose-500 dark:text-rose-400' : 'text-slate-400' }}">
                        {{ $stats['critical_count'] > 0 ? 'Perlu Ditindaklanjuti' : 'Kondisi Prima' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Row 2: Distribusi Rating & Wawasan Kepuasan --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom Kiri: Breakdown Distribusi Bintang 1 - 5 (Span 2) --}}
            <div class="lg:col-span-2 vision-card p-5 sm:p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-white/5">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                Distribusi Rating Ulasan (Bintang 1 – 5)
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Proporsi suara pelanggan berdasarkan bintang yang diberikan.
                            </p>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-300">
                            {{ number_format($total) }} Responden
                        </span>
                    </div>

                    {{-- Bar Distribusi Bintang --}}
                    <div class="mt-6 space-y-4">
                        @php
                            $colors = [
                                5 => ['bar' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                                4 => ['bar' => 'bg-sky-500', 'text' => 'text-sky-600 dark:text-sky-400'],
                                3 => ['bar' => 'bg-amber-400', 'text' => 'text-amber-600 dark:text-amber-400'],
                                2 => ['bar' => 'bg-orange-500', 'text' => 'text-orange-600 dark:text-orange-400'],
                                1 => ['bar' => 'bg-rose-500', 'text' => 'text-rose-600 dark:text-rose-400'],
                            ];
                        @endphp

                        @for ($s = 5; $s >= 1; $s--)
                            @php
                                $cnt = $stats['star_counts'][$s] ?? 0;
                                $pct = $stats['star_percentages'][$s] ?? 0.0;
                                $c = $colors[$s];
                            @endphp
                            <div class="flex items-center gap-3 sm:gap-4 text-xs">
                                <div class="w-20 shrink-0 flex items-center gap-1 font-bold text-slate-700 dark:text-slate-300">
                                    <span>{{ $s }} Bintang</span>
                                    <x-heroicon-s-star class="h-3.5 w-3.5 fill-amber-400 text-amber-400" />
                                </div>

                                {{-- Progress Bar --}}
                                <div class="flex-1 h-3.5 rounded-full bg-slate-100 dark:bg-slate-800/80 overflow-hidden relative">
                                    <div class="h-full rounded-full {{ $c['bar'] }} transition-all duration-500"
                                         style="width: {{ $pct }}%;"></div>
                                </div>

                                <div class="w-28 shrink-0 text-right font-medium text-slate-500 dark:text-slate-400 flex items-center justify-end gap-1.5">
                                    <span class="font-bold text-slate-900 dark:text-white">{{ number_format($cnt) }}</span>
                                    <span class="text-[11px] {{ $c['text'] }} font-semibold">({{ $pct }}%)</span>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Kapsul Sentimen di Bawah Bar --}}
                <div class="mt-8 pt-4 border-t border-slate-100 dark:border-white/5 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="vision-pill-card p-3 flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-emerald-500 shrink-0"></div>
                        <div>
                            <div class="text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold">Puas (⭐ 4–5)</div>
                            <div class="text-sm font-extrabold text-slate-900 dark:text-white mt-0.5">
                                {{ $stats['positive_count'] }} <span class="text-xs font-normal text-emerald-600 dark:text-emerald-400">({{ $csat }}%)</span>
                            </div>
                        </div>
                    </div>

                    <div class="vision-pill-card p-3 flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-amber-400 shrink-0"></div>
                        <div>
                            <div class="text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold">Netral (⭐ 3)</div>
                            <div class="text-sm font-extrabold text-slate-900 dark:text-white mt-0.5">
                                {{ $stats['neutral_count'] }} <span class="text-xs font-normal text-amber-600 dark:text-amber-400">({{ $stats['neutral_rate'] }}%)</span>
                            </div>
                        </div>
                    </div>

                    <div class="vision-pill-card p-3 flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-rose-500 shrink-0"></div>
                        <div>
                            <div class="text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold">Perlu Solusi (⭐ 1–2)</div>
                            <div class="text-sm font-extrabold text-slate-900 dark:text-white mt-0.5">
                                {{ $stats['critical_count'] }} <span class="text-xs font-normal text-rose-600 dark:text-rose-400">({{ $stats['critical_rate'] }}%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Wawasan & Performa Outlet (Span 1) --}}
            <div class="vision-card p-5 sm:p-6 flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-white/5">
                        <x-heroicon-o-light-bulb class="h-5 w-5 text-amber-500 shrink-0" />
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">
                            Wawasan &amp; Tips Kepuasan
                        </h3>
                    </div>

                    <div class="mt-4 space-y-3.5 text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                            <p class="font-bold text-slate-800 dark:text-slate-200">
                                🚀 Kecepatan Saji Dapur (SLA KDS)
                            </p>
                            <p class="text-slate-500 dark:text-slate-400 mt-1">
                                Keluhan tamu umumnya berkaitan dengan keterlambatan hidangan saat jam sibuk. Pantau timer SLA kuning/merah di Kitchen Display agar masakan keluar tepat waktu.
                            </p>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                            <p class="font-bold text-slate-800 dark:text-slate-200">
                                💬 Follow-Up Cepat via WhatsApp
                            </p>
                            <p class="text-slate-500 dark:text-slate-400 mt-1">
                                Jika tamu memberikan rating 1–3, gunakan tombol <strong>"WhatsApp"</strong> di tabel ulasan untuk segera menyampaikan permohonan maaf dan menanyakan kendala yang dialami.
                            </p>
                        </div>

                        @if ($stats['by_outlet']->isNotEmpty() && $stats['by_outlet']->count() > 1)
                            <div class="pt-2">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                    Performa per Outlet Cabang
                                </p>
                                <div class="space-y-2">
                                    @foreach ($stats['by_outlet'] as $out)
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/5">
                                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $out['name'] }}</span>
                                            <span class="font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                                ⭐ {{ $out['avg_rating'] }} <span class="text-[10px] text-slate-400">({{ $out['total_reviews'] }})</span>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-white/5">
                    <a href="{{ \App\Filament\Resources\CustomerReviews\CustomerReviewResource::getUrl('index') }}"
                        class="w-full inline-flex items-center justify-center gap-2 py-2 px-4 rounded-xl text-xs font-bold bg-primary-600 hover:bg-primary-700 text-white shadow-sm transition-all duration-200">
                        <x-heroicon-o-table-cells class="h-4 w-4" />
                        <span>Buka Daftar Ulasan &amp; Moderasi Web</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Row 3: Feed Ulasan & Masukan Terbaru --}}
        <div class="vision-card p-5 sm:p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-white/5 flex-wrap gap-2">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">
                        Ulasan &amp; Masukan Terbaru dari Pelanggan
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Menampilkan ulasan terakhir yang masuk dari tamu restoran.
                    </p>
                </div>
                <a href="{{ \App\Filament\Resources\CustomerReviews\CustomerReviewResource::getUrl('index') }}"
                    class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors inline-flex items-center gap-1">
                    <span>Lihat Semua ({{ number_format($total) }})</span>
                    <span>&rarr;</span>
                </a>
            </div>

            @if ($stats['recent_reviews']->isEmpty())
                <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                    <div class="flex justify-center mb-2">
                        <x-heroicon-o-chat-bubble-left-ellipsis class="h-10 w-10 text-slate-300 dark:text-slate-600" />
                    </div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada ulasan pelanggan</p>
                    <p class="text-xs mt-1">Ulasan akan otomatis tercatat saat tamu menyelesaikan pesanan dan mengirim rating di meja.</p>
                </div>
            @else
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($stats['recent_reviews'] as $rev)
                        <div class="p-4 rounded-2xl border border-slate-200/80 dark:border-white/5 bg-slate-50/70 dark:bg-white/[0.02] flex flex-col justify-between hover:border-slate-300 dark:hover:border-white/10 transition-all duration-200">
                            <div>
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-900 dark:text-white">
                                            {{ $rev->displayName() }}
                                        </h4>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mt-0.5">
                                            <span>Meja {{ $rev->visit?->diningTable?->name ?? '-' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $rev->submitted_at ? $rev->submitted_at->diffForHumans() : '-' }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs shrink-0 font-bold px-2 py-0.5 rounded-full border {{ $rev->rating >= 4 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' : ($rev->rating === 3 ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/20' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/20') }}">
                                        {{ $rev->starsString() }}
                                    </span>
                                </div>

                                <p class="mt-3 text-xs leading-relaxed text-slate-700 dark:text-slate-300 italic">
                                    "{{ $rev->comment }}"
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-white/5 flex items-center justify-between text-xs">
                                <span class="text-[11px] text-slate-400">
                                    {{ $rev->is_published ? '✅ Tayang di web' : '🔒 Disembunyikan' }}
                                </span>

                                @if ($rev->customerPhone())
                                    <a href="{{ $rev->waLink() }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                        <x-heroicon-o-chat-bubble-left-right class="h-3.5 w-3.5" />
                                        <span>Chat WhatsApp</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
