@props([
    'banners',
    'menuUrl' => null,
    'label' => 'Promo',
    'title' => 'Penawaran spesial menanti Anda',
    'highlight' => 'spesial',
    'button' => 'Lihat semua',
])

@php
    use App\Support\CmsMedia;

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
@endphp

@if ($total > 0)
    <section id="promo" class="relative z-0 overflow-hidden bg-surface-section">
        <div class="landing-container landing-section">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <x-customer.section-heading
                    :label="$label"
                    :title="$title"
                    :highlight="$highlight"
                    class="text-left"
                />
                @if ($menuUrl)
                    <a
                        href="{{ $menuUrl }}"
                        class="landing-interactive inline-flex shrink-0 items-center gap-2 rounded-full bg-surface-raised px-5 py-2.5 text-sm font-semibold text-body shadow-[var(--card-shadow)] ring-1 ring-[color:var(--border-subtle)] transition hover:text-primary hover:shadow-[var(--card-shadow-hover)]"
                    >
                        {{ $button }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L10.94 10 7.23 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>

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
                            class="absolute left-0 z-20 hidden h-11 w-11 items-center justify-center rounded-xl bg-surface-raised text-body shadow-[var(--card-shadow)] ring-1 ring-[color:var(--border-subtle)] transition hover:shadow-[var(--card-shadow-hover)] sm:flex"
                            aria-label="Promo sebelumnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
                                    x-bind:class="active === {{ $index }} ? 'shadow-[var(--card-shadow-hover)] ring-1 ring-white/20' : 'shadow-[var(--card-shadow)]'"
                                    class="promo-card relative flex min-h-[16rem] overflow-hidden rounded-[1.75rem] bg-zinc-900 transition-shadow duration-500 sm:min-h-[18rem] md:min-h-[22rem] lg:min-h-[24rem]"
                                >
                                    <div class="pointer-events-none absolute inset-0 opacity-25" aria-hidden="true">
                                        <div class="absolute -bottom-8 -left-8 h-40 w-40 rotate-12 rounded-3xl border border-white/20"></div>
                                        <div class="absolute bottom-6 left-10 h-16 w-16 rotate-45 rounded-lg border border-white/15"></div>
                                        <div class="absolute left-8 top-1/2 h-px w-24 -rotate-45 bg-white/20"></div>
                                    </div>

                                    @if (filled($slide['badge']))
                                        <span class="absolute right-4 top-4 z-20 inline-flex max-w-[9rem] items-center justify-center rounded-md bg-red-500 px-2.5 py-1.5 text-center text-[0.65rem] font-extrabold uppercase leading-tight tracking-wide text-white shadow-lg sm:right-5 sm:top-5 md:right-6 md:top-6 md:px-3 md:py-2 md:text-xs">
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
                                            <div
                                                class="landing-media-overlay absolute inset-0"
                                                aria-hidden="true"
                                            ></div>
                                        </div>
                                    @endif

                                    <div class="relative z-10 flex flex-1 flex-col justify-center p-6 md:max-w-[62%] md:p-10">
                                        @if (filled($slide['title']))
                                            <h3 class="text-xl font-bold leading-tight text-white drop-shadow-[0_1px_8px_rgba(0,0,0,0.45)] md:text-3xl">
                                                {{ $slide['title'] }}
                                            </h3>
                                        @endif

                                        @if (filled($slide['subtitle']))
                                            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-white drop-shadow-[0_1px_6px_rgba(0,0,0,0.4)] md:text-base">
                                                {{ $slide['subtitle'] }}
                                            </p>
                                        @endif

                                        @if (filled($slide['price']))
                                            <span class="mt-4 inline-flex w-fit rounded-full border border-white/35 bg-black/15 px-4 py-1 text-sm font-semibold text-white">
                                                {{ $slide['price'] }}
                                            </span>
                                        @endif

                                        @if (filled($slide['link']) && filled($slide['cta']))
                                            <a
                                                href="{{ $slide['link'] }}"
                                                @if (str_starts_with($slide['link'], 'http'))
                                                    target="_blank" rel="noopener noreferrer"
                                                @endif
                                                class="landing-btn-glow mt-5 inline-flex w-fit rounded-full bg-white px-6 py-2.5 text-sm font-bold text-primary transition hover:bg-surface-muted"
                                            >
                                                {{ $slide['cta'] }}
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
                            class="absolute right-0 z-20 hidden h-11 w-11 items-center justify-center rounded-xl bg-surface-raised text-body shadow-[var(--card-shadow)] ring-1 ring-[color:var(--border-subtle)] transition hover:shadow-[var(--card-shadow-hover)] sm:flex"
                            aria-label="Promo berikutnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
                                x-bind:class="active === {{ $index }} ? 'h-2.5 w-2.5 bg-primary' : 'h-2 w-2 bg-body/25 hover:bg-body/40'"
                                class="rounded-full transition-all"
                                aria-label="Promo {{ $index + 1 }}"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
