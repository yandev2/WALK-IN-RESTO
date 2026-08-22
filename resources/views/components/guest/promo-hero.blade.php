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
        class="relative overflow-hidden rounded-2xl shadow-[var(--card-shadow)] select-none"
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
        <div class="relative min-h-[12.5rem]">
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
                        class="landing-media-overlay absolute inset-0"
                        aria-hidden="true"
                    ></div>
                </div>
            @endforeach

            <div class="relative z-10 flex min-h-[12.5rem] flex-col justify-center gap-2.5 px-6 py-6 pb-10 sm:px-7">
                @foreach ($slides as $slideIndex => $slide)
                    <div
                        x-show="index === {{ $slideIndex }}"
                        class="flex flex-col gap-2.5"
                        @if ($slideIndex > 0) x-cloak @endif
                    >
                        @if (filled($slide['badge']))
                            <span class="w-fit rounded-full bg-white/20 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-white backdrop-blur-sm">
                                {{ $slide['badge'] }}
                            </span>
                        @endif
                        @if (filled($slide['title']))
                            <p class="max-w-[17rem] font-display text-xl font-bold leading-tight text-white">{{ $slide['title'] }}</p>
                        @endif
                        @if (filled($slide['subtitle']))
                            <p class="max-w-[18rem] text-sm leading-relaxed text-white/90">{{ $slide['subtitle'] }}</p>
                        @endif
                        @if (filled($slide['price']))
                            <p class="text-sm font-semibold text-white">{{ $slide['price'] }}</p>
                        @endif
                        @if (filled($slide['cta']) && filled($slide['link']))
                            <a
                                href="{{ $slide['link'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-0.5 w-fit rounded-full bg-white px-4 py-1.5 text-xs font-bold text-primary hover:bg-white/90"
                                @click.stop
                            >
                                {{ $slide['cta'] }}
                            </a>
                        @endif
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
