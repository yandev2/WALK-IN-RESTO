@php
    $aboutCopy = $layout->copyFor('about');
    $aboutTitle = $aboutCopy['title'] ?? 'Cerita di balik dapur';
    $aboutLabel = $aboutCopy['label'] ?? 'Tentang kami';
    $aboutImg = $aboutImageUrl
        ?: ($howToImageUrl
            ?: ($heroUrl
                ?: ($restaurant->cmsGalleryImages->first() ? \App\Support\CmsMedia::url($restaurant->cmsGalleryImages->first()->image_path) : null)));
@endphp

<section id="tentang" class="scroll-mt-20 py-16 sm:py-24 bg-surface-section border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-16">
            
            {{-- Left Column: Appetizing Food Image Platter --}}
            @if ($aboutImg)
                <div class="lg:col-span-6 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        {{-- Decorative Background Ring using brand colors --}}
                        <div class="absolute -top-6 -left-6 -z-10 h-72 w-72 rounded-full bg-primary/20 blur-2xl"></div>
                        <div class="absolute -bottom-6 -right-6 -z-10 h-72 w-72 rounded-full bg-accent/20 blur-2xl"></div>

                        <div class="overflow-hidden rounded-3xl bg-surface-raised dark:bg-zinc-900 p-3 shadow-2xl border border-border-subtle dark:border-zinc-800">
                            <img
                                src="{{ $aboutImg }}"
                                alt="Suasana hidangan {{ $restaurant->name }}"
                                class="h-[380px] sm:h-[440px] w-full object-cover rounded-2xl"
                                loading="lazy"
                            >
                        </div>

                        {{-- Floating badge --}}
                        <div class="absolute bottom-6 left-6 rounded-2xl bg-surface-raised/95 dark:bg-zinc-900/95 backdrop-blur-md px-4 py-2.5 shadow-lg border border-border-subtle dark:border-zinc-800 flex items-center gap-2.5">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            </span>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-body">Bahan Segar Pilihan</span>
                                <span class="text-[10px] text-muted">Dimasak higienis setiap hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Right Column: About Content from Real DB about_html --}}
            <div class="{{ $aboutImg ? 'lg:col-span-6' : 'lg:col-span-12 max-w-3xl mx-auto text-center' }}">
                @if (filled($aboutLabel))
                    <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3.5 py-1.5 rounded-full border border-primary/20 shadow-2xs">
                        {{ $aboutLabel }}
                    </span>
                @endif

                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-body tracking-tight leading-tight">
                    {{ $aboutTitle }}
                </h2>

                @if (filled($profile?->about_html))
                    <div class="mt-6 prose prose-base dark:prose-invert max-w-none text-muted leading-relaxed">
                        {!! $profile->about_html !!}
                    </div>
                @else
                    <p class="mt-4 text-base sm:text-lg text-muted leading-relaxed">
                        Setiap piring kami sajikan dengan dedikasi penuh terhadap kualitas, kebersihan, dan cita rasa yang tak terlupakan bagi setiap tamu.
                    </p>
                @endif
            </div>

        </div>
    </div>
</section>
