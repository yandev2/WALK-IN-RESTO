@php
    use App\Support\CmsMedia;

    $bannerCopy = $layout->copyFor('banners');
    $banners = $restaurant->cmsBanners ?? collect();
    $slides = $banners
        ->map(fn ($banner) => [
            'title' => $banner->title,
            'subtitle' => $banner->subtitle,
            'badge' => $banner->badge_text,
            'price' => $banner->price_label,
            'cta' => $banner->cta_label ?: ($banner->link_url ? 'Lihat promo' : null),
            'link' => $banner->link_url,
            'image' => CmsMedia::url($banner->image_path),
        ])
        ->filter(fn (array $slide) => filled($slide['image']))
        ->values();

    $total = $slides->count();
    $menuUrl = $menuItems->isNotEmpty() ? route('landing.menu', $restaurant) : null;
    $label = $bannerCopy['label'] ?? 'Promo Spesial';
    $title = $bannerCopy['title'] ?? 'Penawaran spesial menanti Anda';
    $button = $bannerCopy['button'] ?? 'Lihat semua';
@endphp

@if ($total > 0)
    <section id="promo" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        
        {{-- True Frosted Glass Banner Container --}}
        <div class="relative overflow-hidden rounded-[2.8rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 p-6 sm:p-10 lg:p-12 shadow-[0_20px_70px_rgba(0,0,0,0.07),inset_0_1px_2px_rgba(255,255,255,0.7)] dark:shadow-[0_20px_70px_rgba(0,0,0,0.5),inset_0_1px_2px_rgba(255,255,255,0.15)]">
            
            {{-- Inner Glow --}}
            <div class="pointer-events-none absolute -top-24 -right-24 h-80 w-80 rounded-full bg-primary/20 blur-3xl" aria-hidden="true"></div>

            {{-- Header Row --}}
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between relative z-10">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                        <span>{{ $label }}</span>
                    </span>
                    <h2 class="mt-4 font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                        {{ $title }}
                    </h2>
                </div>

                @if ($menuUrl)
                    <a
                        href="{{ $menuUrl }}"
                        class="inline-flex shrink-0 items-center gap-2 rounded-full bg-white/40 dark:bg-white/10 backdrop-blur-xl border border-white/60 dark:border-white/20 px-6 py-2.5 text-xs font-bold text-zinc-900 dark:text-white shadow-md hover:bg-white/60 dark:hover:bg-white/20 transition-all hover:scale-103"
                    >
                        <span>{{ $button }}</span>
                        <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>

            {{-- Carousel Alpine Instance --}}
            <div
                x-data="{
                    active: 0,
                    total: {{ $total }},
                    timer: null,
                    init() {
                        if (this.total > 1) {
                            this.startAutoplay();
                        }
                    },
                    startAutoplay() {
                        this.stopAutoplay();
                        this.timer = setInterval(() => this.next(), 5000);
                    },
                    stopAutoplay() {
                        if (this.timer) {
                            clearInterval(this.timer);
                            this.timer = null;
                        }
                    },
                    next() {
                        this.active = (this.active + 1) % this.total;
                    },
                    prev() {
                        this.active = (this.active - 1 + this.total) % this.total;
                    },
                    goTo(index) {
                        this.active = index;
                    },
                    slideClass(index) {
                        const diff = (index - this.active + this.total) % this.total;

                        if (diff === 0) {
                            return 'z-10 pointer-events-auto';
                        }

                        if (this.total < 2) {
                            return 'z-0 pointer-events-none';
                        }

                        if (diff === 1 || diff === this.total - 1) {
                            return 'z-[1] hidden pointer-events-none sm:block';
                        }

                        return 'z-0 pointer-events-none';
                    },
                    slideStyle(index) {
                        const diff = (index - this.active + this.total) % this.total;

                        if (diff === 0) {
                            return 'transform: translate(-50%, -50%) scale(1); opacity: 1; filter: blur(0);';
                        }

                        if (this.total < 2) {
                            return 'transform: translate(-50%, -50%) scale(0.95); opacity: 0; filter: blur(0);';
                        }

                        if (diff === 1) {
                            return 'transform: translate(calc(-50% + 58%), -50%) scale(0.82); opacity: 0.45; filter: blur(6px);';
                        }

                        if (diff === this.total - 1) {
                            return 'transform: translate(calc(-50% - 58%), -50%) scale(0.82); opacity: 0.45; filter: blur(6px);';
                        }

                        return 'transform: translate(-50%, -50%) scale(0.75); opacity: 0; filter: blur(0);';
                    },
                }"
                x-on:mouseenter="stopAutoplay()"
                x-on:mouseleave="total > 1 && startAutoplay()"
                x-on:focusin="stopAutoplay()"
                x-on:focusout="total > 1 && startAutoplay()"
                class="relative"
            >
                <div class="relative mx-auto flex min-h-[18rem] max-w-5xl items-center justify-center sm:min-h-[20rem] md:min-h-[24rem] lg:min-h-[26rem]">
                    @if ($total > 1)
                        <button
                            type="button"
                            x-on:click="prev()"
                            class="absolute left-0 z-20 hidden h-12 w-12 items-center justify-center rounded-full bg-white/60 dark:bg-zinc-800/80 text-zinc-900 dark:text-white backdrop-blur-2xl border border-white/70 dark:border-white/20 shadow-xl transition-all hover:scale-110 sm:flex"
                            aria-label="Promo sebelumnya"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif

                    <div class="relative h-full w-full px-2 sm:px-12">
                        @foreach ($slides as $index => $slide)
                            <div
                                x-bind:class="slideClass({{ $index }})"
                                x-bind:style="slideStyle({{ $index }})"
                                class="promo-slide absolute left-1/2 top-1/2 w-[calc(100%-1rem)] max-w-4xl transition-all duration-500 ease-out sm:w-full"
                            >
                                <article
                                    x-bind:class="active === {{ $index }} ? 'shadow-2xl ring-2 ring-white/40' : 'shadow-lg'"
                                    class="relative flex min-h-[16rem] overflow-hidden rounded-[2.2rem] bg-zinc-950 transition-all duration-500 sm:min-h-[18rem] md:min-h-[22rem] lg:min-h-[24rem] border border-white/20"
                                >
                                    @if (filled($slide['badge']))
                                        <span class="absolute right-4 top-4 z-20 inline-flex max-w-[9rem] items-center justify-center rounded-full bg-rose-500/90 backdrop-blur-md px-3.5 py-1.5 text-center text-xs font-extrabold uppercase tracking-wide text-white border border-white/30 shadow-lg sm:right-6 sm:top-6">
                                            {{ $slide['badge'] }}
                                        </span>
                                    @endif

                                    @if (filled($slide['image']))
                                        <div class="pointer-events-none absolute inset-0">
                                            <img
                                                src="{{ $slide['image'] }}"
                                                alt="{{ $slide['title'] ?: 'Promo' }}"
                                                class="h-full w-full object-cover object-center"
                                                loading="lazy"
                                            >
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent sm:bg-gradient-to-r sm:from-black/95 sm:via-black/70 sm:to-transparent"></div>
                                        </div>
                                    @endif

                                    <div class="relative z-10 flex flex-1 flex-col justify-center p-6 md:max-w-[65%] md:p-10">
                                        @if (filled($slide['title']))
                                            <h3 class="text-xl font-bold leading-tight text-white drop-shadow-md md:text-3xl font-display">
                                                {{ $slide['title'] }}
                                            </h3>
                                        @endif

                                        @if (filled($slide['subtitle']))
                                            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-zinc-200 drop-shadow-xs md:text-base">
                                                {{ $slide['subtitle'] }}
                                            </p>
                                        @endif

                                        @if (filled($slide['price']))
                                            <span class="mt-4 inline-flex w-fit rounded-full border border-white/30 bg-white/10 backdrop-blur-md px-4 py-1 text-sm font-semibold text-white">
                                                {{ $slide['price'] }}
                                            </span>
                                        @endif

                                        @if (filled($slide['link']) && filled($slide['cta']))
                                            <a
                                                href="{{ $slide['link'] }}"
                                                @if (str_starts_with($slide['link'], 'http'))
                                                    target="_blank" rel="noopener noreferrer"
                                                @endif
                                                class="mt-6 inline-flex w-fit items-center gap-2 rounded-full bg-gradient-to-r from-primary to-accent hover:opacity-95 px-6 py-3 text-xs font-bold text-white shadow-xl shadow-primary/30 transition-all hover:scale-105 border border-white/30"
                                            >
                                                <span>{{ $slide['cta'] }}</span>
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>

                    @if ($total > 1)
                        <button
                            type="button"
                            x-on:click="next()"
                            class="absolute right-0 z-20 hidden h-12 w-12 items-center justify-center rounded-full bg-white/60 dark:bg-zinc-800/80 text-zinc-900 dark:text-white backdrop-blur-2xl border border-white/70 dark:border-white/20 shadow-xl transition-all hover:scale-110 sm:flex"
                            aria-label="Promo berikutnya"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L10.94 10 7.23 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif
                </div>

                @if ($total > 1)
                    <div class="mt-8 flex items-center justify-center gap-2">
                        @foreach ($slides as $index => $slide)
                            <button
                                type="button"
                                x-on:click="goTo({{ $index }})"
                                x-bind:class="active === {{ $index }} ? 'h-2.5 w-6 bg-primary rounded-full' : 'h-2.5 w-2.5 bg-white/40 dark:bg-white/20 rounded-full hover:bg-white/60'"
                                class="transition-all duration-300"
                                aria-label="Promo {{ $index + 1 }}"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </section>
@endif
