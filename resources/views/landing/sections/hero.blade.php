@php
    $copy = $layout->copyFor('hero');
    $howToVisible = $layout->isVisible('how_to', $visibleSections);
@endphp

<section class="landing-hero-clip relative min-h-[min(92vh,44rem)] overflow-hidden bg-surface-muted">
    @if ($heroUrl)
        <img
            src="{{ $heroUrl }}"
            alt=""
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div
            class="landing-media-overlay absolute inset-0"
            aria-hidden="true"
        ></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-black"></div>
    @endif

    <div class="landing-container relative z-10 flex min-h-[min(88vh,40rem)] flex-col justify-center py-16 sm:min-h-[min(92vh,44rem)] sm:py-20 md:py-28">
        <div class="landing-reveal max-w-2xl">
            <div class="flex flex-wrap items-center gap-2">
                @if ($outlet)
                    <span @class([
                        'customer-pill backdrop-blur-sm',
                        'bg-white/20 text-white' => $isOpenNow,
                        'bg-black/35 text-white/80' => ! $isOpenNow,
                    ])>
                        {{ $isOpenNow ? 'Sedang buka' : 'Sedang tutup' }}
                    </span>
                @endif
                @if (filled($copy['pill'] ?? null))
                    <span class="font-script text-xl text-white/95 sm:text-2xl md:text-3xl">{{ $copy['pill'] }}</span>
                @endif
            </div>

            <h1 class="mt-4 font-display text-[2.15rem] font-bold leading-[1.08] tracking-tight text-white sm:text-5xl lg:text-6xl">
                {{ $profile?->headline ?: $restaurant->name }}
            </h1>

            @if (filled($copy['subtitle'] ?? null))
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-white/90 sm:mt-5 sm:text-base md:text-lg">{{ $copy['subtitle'] }}</p>
            @endif

            <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                @if ($ctaUrl)
                    <a href="{{ $ctaUrl }}" class="landing-btn-glow inline-flex items-center justify-center rounded-full bg-white px-6 py-3.5 text-sm font-bold text-primary hover:bg-white/90">
                        {{ $ctaLabel }}
                    </a>
                @endif
                @if ($howToVisible && filled($copy['secondary_cta'] ?? null))
                    <a href="#cara-pesan" class="landing-interactive inline-flex items-center justify-center rounded-full border-2 border-white/70 px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/10">
                        {{ $copy['secondary_cta'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
