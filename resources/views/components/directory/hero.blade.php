@props([
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
    $heroImageUrl = $home['hero_image_url'] ?? null;
@endphp

<section class="directory-hero relative isolate min-h-[22rem] overflow-hidden bg-zinc-950 sm:min-h-[26rem] md:min-h-[28rem]">
    @if (filled($heroImageUrl))
        <img
            src="{{ $heroImageUrl }}"
            alt="{{ strip_tags($home['hero_title'] ?? $home['site_name']) }}"
            class="directory-hero-media absolute inset-0 h-full w-full object-cover object-center scale-105 transition duration-700"
            fetchpriority="high"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/65 to-black/40" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20" aria-hidden="true"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-950"></div>
    @endif

    <div class="landing-container relative z-10 flex min-h-[22rem] items-center py-12 sm:min-h-[26rem] sm:py-14 md:min-h-[28rem] md:py-16">
        <div class="max-w-2xl">
            @if (filled($home['hero_eyebrow'] ?? null))
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1 text-sm backdrop-blur-md border border-white/15">
                    <span class="h-2 w-2 rounded-full bg-accent animate-pulse"></span>
                    <span class="font-script text-lg text-white font-medium sm:text-xl">{{ $home['hero_eyebrow'] }}</span>
                </div>
            @endif

            <h1 class="mt-4 font-display text-3xl font-bold leading-[1.14] tracking-tight text-white sm:text-4xl md:text-5xl drop-shadow-sm">
                {!! \App\Models\PlatformSetting::heroTitleHtml($home) !!}
            </h1>

            @if (filled($home['hero_subtitle'] ?? null))
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-white/85 sm:text-base md:text-lg">
                    {{ $home['hero_subtitle'] }}
                </p>
            @endif
        </div>
    </div>
</section>
