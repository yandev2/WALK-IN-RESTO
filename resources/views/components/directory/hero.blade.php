@props([
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
    $heroImageUrl = $home['hero_image_url'] ?? 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1000&q=80';
@endphp

<section class="directory-hero relative isolate overflow-hidden bg-gradient-to-b from-surface-muted/50 via-surface-base to-surface-base border-b border-border-subtle/50 py-8 sm:py-12 lg:py-16">
    <div class="landing-container">
        <div class="grid items-center gap-8 lg:grid-cols-12 lg:gap-12">
            {{-- Left Column: Copywriting & Headline --}}
            <div class="lg:col-span-7">
                @if (filled($home['hero_eyebrow'] ?? null))
                    <div class="inline-flex items-center gap-2 rounded-lg bg-primary/10 border border-primary/20 px-3.5 py-1 text-xs font-semibold text-primary shadow-xs">
                        <span class="h-1.5 w-1.5 rounded-lg bg-primary animate-pulse"></span>
                        <span class="font-medium tracking-wide uppercase">{{ $home['hero_eyebrow'] }}</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 rounded-lg bg-primary/10 border border-primary/20 px-3.5 py-1 text-xs font-semibold text-primary shadow-xs">
                        <span class="h-1.5 w-1.5 rounded-lg bg-primary animate-pulse"></span>
                        <span class="font-medium tracking-wide uppercase">Jelajahi Rasa, Temukan Favoritmu</span>
                    </div>
                @endif

                <h1 class="mt-3 sm:mt-4 font-display text-2xl sm:text-4xl md:text-5xl font-extrabold leading-[1.15] tracking-tight text-body">
                    {!! \App\Models\PlatformSetting::heroTitleHtml($home) !!}
                </h1>

                @if (filled($home['hero_subtitle'] ?? null))
                    <p class="mt-3 sm:mt-4 max-w-xl text-xs sm:text-base md:text-lg leading-relaxed text-muted">
                        {{ $home['hero_subtitle'] }}
                    </p>
                @else
                    <p class="mt-3 sm:mt-4 max-w-xl text-xs sm:text-base md:text-lg leading-relaxed text-muted">
                        Temukan restoran terbaik di sekitarmu, nikmati menu favorit, dan rasakan pengalaman kuliner yang lebih mudah dan menyenangkan.
                    </p>
                @endif

                {{-- Quick Value Props / Trust Badges --}}
                <div class="mt-5 sm:mt-6 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-muted sm:gap-6 sm:text-sm">
                    <div class="inline-flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="font-medium text-body">Restoran Terpercaya</span>
                    </div>

                    <div class="inline-flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </span>
                        <span class="font-medium text-body">Dine-In QR Meja</span>
                    </div>

                    <div class="inline-flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </span>
                        <span class="font-medium text-body">Promo & Diskon</span>
                    </div>
                </div>
            </div>

            {{-- Right Column: Appetizing Food Showcase Card --}}
            <div class="mt-6 lg:mt-0 lg:col-span-5">
                <div class="group relative overflow-hidden rounded-[1.6rem] sm:rounded-[2rem] bg-surface-raised p-2 shadow-xl ring-1 ring-[color:var(--border-subtle)] transition-all duration-300 hover:shadow-2xl">
                    <div class="relative aspect-[16/10] sm:aspect-[4/3] overflow-hidden rounded-[1.3rem] sm:rounded-[1.6rem]">
                        <img
                            src="{{ $heroImageUrl }}"
                            alt="{{ strip_tags($home['hero_title'] ?? $home['site_name']) }}"
                            class="directory-hero-media h-full w-full object-cover transition duration-700 group-hover:scale-105"
                            fetchpriority="high"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                        {{-- Floating Tagline Overlay --}}
                        <div class="absolute bottom-3 left-3 right-3 sm:bottom-4 sm:left-4 sm:right-4 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 sm:gap-2 rounded-lg bg-black/70 backdrop-blur-md px-3 sm:px-3.5 py-1 sm:py-1.5 text-[11px] sm:text-xs font-bold text-white shadow-md">
                                <span class="h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-lg bg-primary animate-pulse"></span>
                                Rasa Enak Jadi Lebih Dekat
                            </span>
                            <span class="rounded-lg bg-white/20 backdrop-blur-md p-1 sm:p-1.5 text-white">
                                <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
