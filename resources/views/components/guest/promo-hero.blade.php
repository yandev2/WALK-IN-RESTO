@props(['banners'])

@php
    use App\Support\CmsMedia;

    $slides = $banners
        ->map(fn ($banner) => [
            'title' => $banner->title,
            'subtitle' => $banner->subtitle,
            'badge' => $banner->badge_text,
            'price' => $banner->price_label,
            'cta' => $banner->cta_label,
            'link' => $banner->link_url,
            'image' => CmsMedia::url($banner->image_path),
        ])
        ->filter(fn (array $slide) => filled($slide['image']) || filled($slide['title']))
        ->values();

    $total = $slides->count();
@endphp

@if ($total > 0)
    <div
        class="relative overflow-hidden rounded-3xl shadow-sm border border-border-subtle/50 dark:border-white/5 select-none"
        x-data="{
            index: 0,
            total: {{ $total }},
            timer: null,
            touchStartX: 0,
            next() {
                if (this.total <= 1) return;
                this.index = (this.index + 1) % this.total;
                this.restart();
            },
            prev() {
                if (this.total <= 1) return;
                this.index = (this.index - 1 + this.total) % this.total;
                this.restart();
            },
            goTo(i) {
                this.index = i;
                this.restart();
            },
            start() {
                if (this.total <= 1) return;
                this.timer = setInterval(() => this.next(), 6000);
            },
            stop() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            },
            restart() {
                this.stop();
                this.start();
            },
            onTouchStart(e) {
                this.touchStartX = e.changedTouches[0].screenX;
                this.stop();
            },
            onTouchEnd(e) {
                const dx = e.changedTouches[0].screenX - this.touchStartX;
                if (Math.abs(dx) > 40) {
                    dx < 0 ? this.next() : this.prev();
                } else {
                    this.start();
                }
            },
        }"
        x-init="start()"
        x-on:destroy="stop()"
        @touchstart.passive="onTouchStart($event)"
        @touchend.passive="onTouchEnd($event)"
    >
        <div class="relative min-h-[13.5rem]">
            @foreach ($slides as $slideIndex => $slide)
                <div
                    x-show="index === {{ $slideIndex }}"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0"
                    @if ($slideIndex > 0) x-cloak @endif
                >
                    @if (filled($slide['image']))
                        <img
                            src="{{ $slide['image'] }}"
                            alt=""
                            class="absolute inset-0 h-full w-full object-cover"
                            draggable="false"
                        >
                    @else
                        <div class="absolute inset-0 bg-zinc-900"></div>
                    @endif

                    <div
                        class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/65 to-black/25"
                        aria-hidden="true"
                    ></div>
                </div>
            @endforeach

            <div class="relative z-10 flex min-h-[13.5rem] flex-col justify-center gap-2 p-5 sm:p-6 pb-9">
                @foreach ($slides as $slideIndex => $slide)
                    <div
                        x-show="index === {{ $slideIndex }}"
                        class="flex flex-col gap-2"
                        @if ($slideIndex > 0) x-cloak @endif
                    >
                        @if (filled($slide['title']))
                            <h2 class="max-w-[18rem] font-display text-xl sm:text-2xl font-black leading-tight text-white tracking-tight">
                                {{ $slide['title'] }}
                            </h2>
                        @endif

                        @if (filled($slide['subtitle']))
                            <p class="max-w-[19rem] text-xs sm:text-sm leading-relaxed text-zinc-200 line-clamp-2">
                                {{ $slide['subtitle'] }}
                            </p>
                        @endif

                        {{-- Bottom Row: CTA Button + Price / Badges aligned side by side --}}
                        <div class="mt-1 flex flex-wrap items-center gap-2">
                            @if (filled($slide['cta']) && filled($slide['link']))
                                <a
                                    href="{{ $slide['link'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-white px-3.5 py-1.5 text-xs font-bold text-primary shadow-sm hover:bg-zinc-100 active:scale-95 transition"
                                    @click.stop
                                >
                                    <span>{{ $slide['cta'] }}</span>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                </a>
                            @endif

                            @if (filled($slide['price']))
                                <span class="inline-flex items-center rounded-xl bg-black/45 backdrop-blur-md border border-white/20 px-2.5 py-1 text-xs font-extrabold text-white shadow-xs">
                                    {{ $slide['price'] }}
                                </span>
                            @endif

                            @if (filled($slide['badge']))
                                <span class="inline-flex items-center rounded-xl bg-amber-500/90 backdrop-blur-md border border-white/20 px-2.5 py-1 text-[11px] font-extrabold text-white shadow-xs uppercase tracking-wide">
                                    {{ $slide['badge'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($total > 1)
            <div class="pointer-events-none absolute inset-x-0 bottom-3 z-20 flex justify-center gap-1.5">
                @foreach ($slides as $dotIndex => $slide)
                    <button
                        type="button"
                        @click.stop="goTo({{ $dotIndex }})"
                        :class="index === {{ $dotIndex }} ? 'w-5 bg-white' : 'w-2 bg-white/55'"
                        class="pointer-events-auto h-1.5 rounded-full shadow-sm transition-all"
                        aria-label="Slide {{ $dotIndex + 1 }}"
                    ></button>
                @endforeach
            </div>
        @endif
    </div>
@endif
