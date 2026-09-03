@php
    $copy = $layout->copyFor('how_to');
    $steps = $copy['steps'] ?? [];
    $title = $copy['title'] ?? 'Cara pesan';
    $label = $copy['label'] ?? 'Mudah dipesan';
    $subtitle = $copy['subtitle'] ?? 'Tidak ada tombol pesan meja dari HP. Datang langsung ke restoran.';
    $howToImage = $howToImageUrl
        ?: ($aboutImageUrl
            ?: ($restaurant->cmsGalleryImages->first() ? \App\Support\CmsMedia::url($restaurant->cmsGalleryImages->first()->image_path) : null));
@endphp

<section id="cara-pesan" class="scroll-mt-20 py-16 sm:py-24 bg-surface-base border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
            @if (filled($label))
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-linear-to-r from-primary/15 to-accent/15 px-3.5 py-1.5 rounded-full border border-primary/25 shadow-2xs">
                    {{ $label }}
                </span>
            @endif
            <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-body tracking-tight">
                {{ $title }}
            </h2>
            @if (filled($subtitle))
                <p class="mt-3 text-sm sm:text-base text-muted leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- If image is available: Split layout, else Grid layout --}}
        @if ($howToImage)
            <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-16">
                {{-- Left Image Showcase --}}
                <div class="lg:col-span-5 relative group">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <div class="absolute inset-0 rounded-3xl bg-linear-to-br from-primary to-accent -rotate-2 group-hover:rotate-0 group-hover:scale-102 opacity-80 shadow-2xl transition-all duration-500"></div>
                        <div class="relative overflow-hidden rounded-3xl bg-surface-raised dark:bg-zinc-900 p-2.5 shadow-xl border border-border-subtle dark:border-zinc-800">
                            <img
                                src="{{ $howToImage }}"
                                alt="Langkah bersantap di {{ $restaurant->name }}"
                                class="h-[380px] sm:h-[440px] w-full object-cover rounded-2xl group-hover:scale-105 transition-transform duration-700 ease-out"
                                loading="lazy"
                            >
                        </div>
                    </div>
                </div>

                {{-- Right Steps List --}}
                <div class="lg:col-span-7 space-y-4">
                    @foreach ($steps as $index => $step)
                        <div class="group flex items-start gap-4 rounded-2xl bg-surface-raised dark:bg-zinc-900/90 p-5 border border-border-subtle dark:border-zinc-800 shadow-xs hover:border-primary/50 hover:shadow-xl hover:scale-102 hover:bg-surface-raised dark:hover:bg-zinc-800/80 transition-all duration-300">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-primary to-accent text-white font-display font-bold text-lg shadow-2xs group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-base sm:text-lg text-body group-hover:text-primary transition-colors duration-200">
                                    {{ $step['title'] ?? '' }}
                                </h3>
                                <p class="mt-1 text-xs sm:text-sm text-muted leading-relaxed">
                                    {{ $step['description'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Horizontal Grid Steps --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($steps as $index => $step)
                    <div class="group relative flex flex-col items-center text-center rounded-2xl bg-surface-raised dark:bg-zinc-900/90 border border-border-subtle dark:border-zinc-800 p-6 shadow-xs hover:shadow-2xl hover:border-primary/50 hover:-translate-y-2 transition-all duration-300">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-linear-to-br from-primary/20 to-accent/20 text-primary mb-4 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300 shadow-2xs">
                            @if ($index === 0)
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            @elseif ($index === 1)
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                            @elseif ($index === 2)
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            @endif
                        </div>
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-linear-to-br from-primary to-accent text-white text-xs font-bold mb-2 shadow-2xs group-hover:scale-110 transition-transform duration-200">
                            {{ $index + 1 }}
                        </span>
                        <h3 class="font-display font-bold text-base sm:text-lg text-body mb-1.5 group-hover:text-primary transition-colors duration-200">
                            {{ $step['title'] ?? '' }}
                        </h3>
                        <p class="text-xs text-muted leading-relaxed">
                            {{ $step['description'] ?? '' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
