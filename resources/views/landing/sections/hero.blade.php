@php
    $copy = $layout->copyFor('hero');
    $howToVisible = $layout->isVisible('how_to', $visibleSections);
@endphp

<section class="landing-hero-clip relative overflow-hidden bg-zinc-950 text-white">
    @if ($heroUrl)
        <img
            src="{{ $heroUrl }}"
            alt=""
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div
            class="landing-media-overlay absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/85 to-zinc-950/70"
            aria-hidden="true"
        ></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-950"></div>
        <div class="landing-media-overlay absolute inset-0 bg-radial from-primary/10 via-transparent to-transparent opacity-40" aria-hidden="true"></div>
    @endif

    <div class="landing-container relative z-10 py-10 sm:py-14 lg:py-16">
        <div class="grid items-center gap-8 lg:grid-cols-12 lg:gap-12">
            {{-- Kolom Kiri: Informasi Utama --}}
            <div class="landing-reveal lg:col-span-7">
                <div class="flex flex-wrap items-center gap-2.5">
                    @if ($outlet)
                        <span @class([
                            'customer-pill inline-flex items-center gap-2 backdrop-blur-md border',
                            'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' => $isOpenNow,
                            'bg-white/10 text-white/80 border-white/15' => ! $isOpenNow,
                        ])>
                            <span @class([
                                'h-2 w-2 rounded-full shrink-0',
                                'bg-emerald-400 animate-pulse' => $isOpenNow,
                                'bg-zinc-400' => ! $isOpenNow,
                            ])></span>
                            <span>{{ $isOpenNow ? 'Sedang buka' : 'Sedang tutup' }}</span>
                        </span>
                    @endif
                    @if (filled($copy['pill'] ?? null))
                        <span class="font-script text-xl text-primary sm:text-2xl md:text-3xl">{{ $copy['pill'] }}</span>
                    @endif
                </div>

                <h1 class="mt-4 font-display text-3xl font-bold leading-[1.12] tracking-tight text-white sm:text-4xl lg:text-5xl">
                    {{ $profile?->headline ?: $restaurant->name }}
                </h1>

                @if (filled($copy['subtitle'] ?? null))
                    <p class="mt-3.5 max-w-xl text-sm leading-relaxed text-zinc-300 sm:text-base">{{ $copy['subtitle'] }}</p>
                @endif

                {{-- Bar Cuplikan Info Cepat --}}
                <div class="mt-5 flex flex-wrap items-center gap-2 text-xs sm:text-sm">
                    @if (($ratingSummary['count'] ?? 0) > 0)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 font-semibold text-white border border-white/15 backdrop-blur-md shadow-xs">
                            <svg class="h-3.5 w-3.5 fill-amber-400 text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ number_format($ratingSummary['average'], 1) }}
                            <span class="font-normal text-zinc-400">({{ $ratingSummary['count'] }})</span>
                        </span>
                    @endif

                    @if ($todayHours)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 font-medium text-white/90 border border-white/15 backdrop-blur-md shadow-xs">
                            <svg class="h-3.5 w-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                            {{ $isOpenNow ? 'Buka hari ini' : 'Tutup' }}
                            @if (! $todayHours->is_closed && $todayHours->opens_at && $todayHours->closes_at)
                                ({{ $formatTime($todayHours->opens_at) }}–{{ $formatTime($todayHours->closes_at) }})
                            @endif
                        </span>
                    @endif

                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 font-medium text-white/90 border border-white/15 backdrop-blur-md shadow-xs">
                        <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Dine-in Walk-in Langsung
                    </span>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-6 flex flex-wrap items-center gap-3 sm:mt-7">
                    @if ($ctaUrl)
                        <a href="{{ $ctaUrl }}" class="landing-btn-glow inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-dark px-6 py-3 text-sm font-bold text-white shadow-lg hover:shadow-primary/30 transition hover:scale-105">
                            {{ $ctaLabel }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endif
                    @if ($howToVisible && filled($copy['secondary_cta'] ?? null))
                        <a href="#cara-pesan" class="landing-interactive inline-flex items-center justify-center rounded-full border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition backdrop-blur-md">
                            {{ $copy['secondary_cta'] }}
                        </a>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Showcase Visual Padat & Menarik --}}
            <div class="landing-reveal lg:col-span-5">
                @if ($heroUrl)
                    <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                        <div class="relative overflow-hidden rounded-3xl border border-white/20 shadow-2xl bg-zinc-900 aspect-[4/3] group">
                            <img
                                src="{{ $heroUrl }}"
                                alt="{{ $restaurant->name }}"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-white">
                                <div>
                                    <p class="font-display font-bold text-base sm:text-lg drop-shadow">{{ $restaurant->name }}</p>
                                    <p class="text-xs text-white/80">{{ $restaurant->displayCategoriesLabel() ?: 'Restoran Pilihan' }}</p>
                                </div>
                                @if ($outlet)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold backdrop-blur-md border border-white/20">
                                        <span class="h-2 w-2 rounded-full {{ $isOpenNow ? 'bg-emerald-400' : 'bg-zinc-400' }}"></span>
                                        <span>{{ $isOpenNow ? 'Buka' : 'Tutup' }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Kartu Showcase Pengalaman Walk-In Resto --}}
                    <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                        <div class="rounded-3xl border border-white/15 bg-white/10 p-5 sm:p-6 shadow-2xl backdrop-blur-md text-white">
                            <div class="flex items-center justify-between gap-3 border-b border-white/15 pb-3.5">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if ($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $restaurant->name }}" class="h-10 w-10 rounded-xl object-cover ring-1 ring-white/20 shrink-0">
                                    @else
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary-dark font-display font-bold text-white text-base shadow-md">
                                            {{ substr($restaurant->name, 0, 1) }}
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <h3 class="font-display text-sm sm:text-base font-bold text-white truncate">{{ $restaurant->name }}</h3>
                                        <p class="text-xs text-white/70 truncate">{{ $restaurant->displayCategoriesLabel() ?: 'Dine-in Walk-in' }}</p>
                                    </div>
                                </div>
                                <span class="rounded-full bg-primary/20 px-2.5 py-0.5 text-xs font-bold text-primary border border-primary/30 shrink-0">
                                    Walk-in
                                </span>
                            </div>

                            <div class="mt-3.5 space-y-2.5">
                                <div class="flex items-center gap-3 rounded-2xl bg-white/5 p-2.5 border border-white/10">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-primary/20 text-primary font-bold text-xs">
                                        1
                                    </span>
                                    <div class="text-xs">
                                        <p class="font-bold text-white">Pilih Meja Bebas</p>
                                        <p class="text-white/70 text-[11px]">Datang & duduk tanpa perlu reservasi</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 rounded-2xl bg-white/5 p-2.5 border border-white/10">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-primary/20 text-primary font-bold text-xs">
                                        2
                                    </span>
                                    <div class="text-xs">
                                        <p class="font-bold text-white">Scan Stiker QR di Meja</p>
                                        <p class="text-white/70 text-[11px]">Buka kamera HP & pilih menu langsung</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 rounded-2xl bg-white/5 p-2.5 border border-white/10">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-primary/20 text-primary font-bold text-xs">
                                        3
                                    </span>
                                    <div class="text-xs">
                                        <p class="font-bold text-white">Pesanan Diantar ke Meja</p>
                                        <p class="text-white/70 text-[11px]">Pesanan langsung masuk ke dapur resto</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
