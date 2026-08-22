@props([
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
    $heroImageUrl = $home['hero_image_url'] ?? null;
@endphp

<section class="directory-hero relative isolate min-h-[22rem] overflow-hidden bg-zinc-950 sm:min-h-[26rem] md:min-h-[30rem]">
    @if (filled($heroImageUrl))
        <img
            src="{{ $heroImageUrl }}"
            alt=""
            class="directory-hero-media absolute inset-0 h-full w-full object-cover object-center"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-black/30" aria-hidden="true"></div>
    @endif

    <div class="landing-container relative z-10 flex min-h-[22rem] items-center py-12 sm:min-h-[26rem] sm:py-14 md:min-h-[30rem] md:py-16">
        <div class="max-w-xl">
            @if (filled($home['hero_eyebrow'] ?? null))
                <p class="font-script text-xl text-white/90 sm:text-2xl">{{ $home['hero_eyebrow'] }}</p>
            @endif

            <h1 class="mt-3 font-display text-3xl font-bold leading-[1.12] tracking-tight text-white sm:text-4xl md:text-[2.5rem]">
                {!! \App\Models\PlatformSetting::heroTitleHtml($home) !!}
            </h1>

            @if (filled($home['hero_subtitle'] ?? null))
                <p class="mt-4 text-sm leading-relaxed text-white/80 sm:text-base">
                    {{ $home['hero_subtitle'] }}
                </p>
            @endif
        </div>
    </div>
</section>
