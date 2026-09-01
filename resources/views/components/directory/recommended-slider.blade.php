@props([
    'cards' => [],
])

@if (! empty($cards))
    <div
        class="recommended-slider-container relative mb-8 overflow-hidden rounded-[2rem] bg-zinc-950 text-white shadow-2xl ring-1 ring-white/10"
        x-data="{
            active: 0,
            total: {{ count($cards) }},
            timer: null,
            next() {
                this.active = (this.active + 1) % this.total;
            },
            prev() {
                this.active = (this.active - 1 + this.total) % this.total;
            },
            startAutoplay() {
                if (this.total > 1) {
                    this.timer = setInterval(() => this.next(), 6500);
                }
            },
            stopAutoplay() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            }
        }"
        x-init="startAutoplay()"
        @mouseenter="stopAutoplay()"
        @mouseleave="startAutoplay()"
    >
        <div class="relative min-h-[420px] sm:min-h-[460px] md:min-h-[500px]">
            @foreach ($cards as $index => $card)
                <div
                    x-show="active === {{ $index }}"
                    x-transition:enter="transition-opacity duration-700 ease-out"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 pointer-events-none"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0 h-full w-full"
                    @if ($index > 0) x-cloak @endif
                >
                    {{-- Hero Media & Cinematic Gradient Overlays --}}
                    @if ($card['hero_url'])
                        <img
                            src="{{ $card['hero_url'] }}"
                            alt="{{ $card['name'] }}"
                            class="recommended-hero-img"
                            loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        >
                    @endif

                    {{-- Multi-directional Gradient Vignettes --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/85 to-transparent sm:bg-gradient-to-r sm:from-zinc-950 sm:from-20% sm:via-zinc-950/75 sm:via-50% sm:to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-zinc-950 via-zinc-950/50 to-transparent"></div>

                    {{-- Badge Populer di Pojok Kanan Atas Kontainer --}}
                    <div class="absolute right-5 top-5 sm:right-8 sm:top-8 z-20">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/35 px-3.5 py-1.5 text-xs font-bold text-emerald-400 backdrop-blur-md shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Populer
                        </span>
                    </div>

                    {{-- Slide Content --}}
                    <div class="relative z-10 flex h-full min-h-[420px] sm:min-h-[460px] md:min-h-[500px] flex-col justify-between p-6 sm:p-10 md:p-12 max-w-2xl">
                        {{-- Top Badge Rekomendasi Spesial --}}
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/20 border border-primary/40 px-3.5 py-1.5 text-xs font-bold text-primary shadow-sm backdrop-blur-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 fill-primary text-primary" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Rekomendasi Spesial
                            </span>
                        </div>

                        {{-- Main Body Info --}}
                        <div
                            class="my-auto py-6"
                            x-show="active === {{ $index }}"
                            x-transition:enter="transition ease-out duration-700 delay-100"
                            x-transition:enter-start="opacity-0 translate-y-1.5"
                            x-transition:enter-end="opacity-100 translate-y-0"
                        >
                            <h2 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-[1.1]">
                                {{ $card['name'] }}
                            </h2>

                            @if ($card['categories_label'])
                                <div class="mt-3 flex items-center gap-2 text-xs sm:text-sm font-bold text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span>{{ $card['categories_label'] }}</span>
                                </div>
                            @endif

                            <p class="mt-3 text-xs sm:text-sm md:text-base leading-relaxed text-zinc-300 line-clamp-3 max-w-xl">
                                {{ $card['headline'] ?: 'Pengalaman kuliner istimewa dengan sajian lezat, bahan pilihan, dan pelayanan walk-in dari meja yang nyaman.' }}
                            </p>

                            {{-- Dark Glass Rating Capsule --}}
                            <div class="mt-5 inline-flex items-center gap-3 rounded-2xl bg-white/10 backdrop-blur-md px-4 py-2 text-xs sm:text-sm border border-white/10 shadow-inner w-fit">
                                <span class="flex items-center gap-1.5 font-bold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-amber-400 text-amber-400" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ number_format($card['rating_average'] ?: 5.0, 1) }}
                                    <span class="font-normal text-zinc-400">({{ $card['rating_count'] ?: 1 }})</span>
                                </span>
                                <span class="h-3.5 w-px bg-white/20"></span>
                                <span class="text-zinc-300">{{ $card['rating_count'] ? $card['rating_count'].'+ ulasan' : 'Pilihan Utama' }}</span>
                            </div>

                            {{-- CTA Button --}}
                            <div class="mt-6">
                                <a
                                    href="{{ $card['landing_url'] }}"
                                    class="inline-flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-primary to-primary-dark px-6 py-3.5 text-sm font-bold text-white shadow-lg hover:shadow-primary/30 transition hover:scale-[1.02] active:scale-[0.98]"
                                >
                                    Lihat Detail Restoran
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Left / Right Arrow Navigation --}}
        <button
            type="button"
            @click="prev()"
            x-show="total > 1"
            class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 z-20 flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-md border border-white/15 transition shadow-lg"
            aria-label="Restoran sebelumnya"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
        </button>

        <button
            type="button"
            @click="next()"
            x-show="total > 1"
            class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 z-20 flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-md border border-white/15 transition shadow-lg"
            aria-label="Restoran berikutnya"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </button>

        {{-- Pagination Dots --}}
        <div x-show="total > 1" class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
            <template x-for="i in total" :key="i">
                <button
                    type="button"
                    @click="active = i - 1"
                    :class="active === i - 1 ? 'w-6 bg-primary' : 'w-2 bg-white/35 hover:bg-white/60'"
                    class="h-2 rounded-full transition-all duration-300"
                    :aria-label="`Slide ${i}`"
                ></button>
            </template>
        </div>
    </div>
@endif
