@php
    $copy = $layout->copyFor('hero');
    $headline = $profile?->headline ?: $restaurant->name;
    $subtitle = $copy['subtitle'] ?? 'Datang langsung, nikmati suasana nyaman, dan pesan aneka hidangan segar dengan mudah dari HP Anda.';
    $heroImage = $heroUrl
        ?: ($aboutImageUrl
            ?: ($howToImageUrl
                ?: ($restaurant->cmsGalleryImages->first() ? \App\Support\CmsMedia::url($restaurant->cmsGalleryImages->first()->image_path) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80')));
    $avgRating = $ratingSummary['average'] ? number_format($ratingSummary['average'], 1) : '5.0';
@endphp

<section class="relative overflow-hidden pt-8 pb-16 sm:pt-12 sm:pb-20 lg:pt-16 lg:pb-24 bg-surface-base transition-colors duration-200">
    {{-- Ambient Decorative Glows using brand colors --}}
    <div class="pointer-events-none absolute -top-24 left-1/2 -z-10 h-[500px] w-[700px] -translate-x-1/2 rounded-full bg-linear-to-tr from-primary/20 via-accent/15 to-transparent blur-3xl opacity-60 dark:opacity-30" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-8">
            
            {{-- Left Column: Copy & Actions --}}
            <div class="lg:col-span-7 flex flex-col items-start">
                
                {{-- Status & Pill Badges --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    @if ($outlet)
                        <span class="inline-flex items-center gap-2 rounded-full px-3.5 py-1 text-xs font-semibold {{ $isOpenNow ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20' : 'bg-surface-muted text-muted border border-border-subtle' }}">
                            <span class="h-2 w-2 rounded-full {{ $isOpenNow ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-400' }}"></span>
                            {{ $isOpenNow ? 'Buka Sekarang' : 'Sedang Tutup' }}
                        </span>
                    @endif

                    @if (filled($copy['pill'] ?? null))
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 border border-primary/20 px-3.5 py-1 text-xs font-bold text-primary shadow-2xs">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                            <span>{{ $copy['pill'] }}</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 border border-primary/20 px-3.5 py-1 text-xs font-bold text-primary shadow-2xs">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            <span>Walk-in & Fresh Food</span>
                        </span>
                    @endif
                </div>

                {{-- Main Hero Headline with dynamic brand highlight --}}
                <h1 class="mt-5 font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-body leading-[1.12]">
                    @php
                        $words = explode(' ', $headline);
                    @endphp
                    @if (count($words) > 1)
                        {{ $words[0] }}
                        <span class="relative inline-block px-3 py-0.5 mx-1 my-1 rounded-2xl bg-linear-to-r from-primary to-accent text-white shadow-md shadow-primary/20 transform -rotate-1">
                            {{ $words[1] }}
                        </span>
                        {{ implode(' ', array_slice($words, 2)) }}
                    @else
                        {{ $headline }}
                    @endif
                </h1>

                {{-- Subtitle --}}
                <p class="mt-4 text-base sm:text-lg text-muted leading-relaxed max-w-xl">
                    {{ $subtitle }}
                </p>

                {{-- CTA Action Buttons --}}
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    @if ($menuItems->isNotEmpty())
                        <a
                            href="#menu"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-primary hover:bg-primary-dark px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-primary/25 hover:shadow-xl transition-all hover:scale-103"
                        >
                            <span>Pesan Sekarang</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif

                    @if ($mapsUrl)
                        <a
                            href="{{ $mapsUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-surface-raised dark:bg-zinc-900 border border-border-subtle dark:border-zinc-800 px-6 py-3.5 text-sm font-semibold text-body shadow-xs hover:bg-surface-muted transition-all"
                        >
                            <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>Lokasi Resto</span>
                        </a>
                    @endif
                </div>

                {{-- Social Proof Snippet --}}
                @if (($ratingSummary['count'] ?? 0) > 0)
                    <div class="mt-8 flex items-center gap-3 rounded-2xl bg-surface-raised dark:bg-zinc-900/90 border border-border-subtle dark:border-zinc-800 p-3 shadow-xs backdrop-blur-xs">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-400/20 text-amber-500 font-bold text-sm shadow-xs">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div class="flex flex-col text-xs">
                            <div class="flex items-center gap-1.5 font-bold text-body">
                                <span class="text-amber-500 font-extrabold text-sm">{{ $avgRating }}</span>
                                <span class="text-muted">/ 5.0</span>
                            </div>
                            <span class="text-muted">{{ $ratingSummary['count'] }}+ ulasan puas dari pelanggan</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column: Visual Showcase & Floating Badges --}}
            <div class="lg:col-span-5 relative flex items-center justify-center">
                
                {{-- Decorative Backing Card --}}
                <div class="absolute inset-0 -m-4 sm:-m-6 rounded-3xl bg-linear-to-br from-primary via-accent to-primary rotate-2 opacity-85 shadow-2xl"></div>

                {{-- Main Image Frame --}}
                <div class="relative z-10 w-full overflow-hidden rounded-3xl bg-surface-raised dark:bg-zinc-900 p-2.5 shadow-xl border border-border-subtle dark:border-zinc-800">
                    <img
                        src="{{ $heroImage }}"
                        alt="{{ $restaurant->name }}"
                        class="h-[360px] sm:h-[420px] w-full object-cover rounded-2xl"
                    >

                    {{-- Floating Badge 1: Preparation Time / Service (Top Left) --}}
                    <div class="absolute top-6 left-6 z-20 flex items-center gap-2.5 rounded-full bg-surface-raised/95 dark:bg-zinc-900/95 px-4 py-2 text-xs font-bold text-body shadow-xl border border-border-subtle dark:border-zinc-800 backdrop-blur-xs">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <span class="block text-[10px] text-muted font-normal">Penyajian Cepat</span>
                            <span class="text-xs text-primary font-extrabold">Fresh & Hangat</span>
                        </div>
                    </div>

                    {{-- Floating Badge 2: Location Card (Bottom Right) --}}
                    @if ($outlet)
                        <div class="absolute bottom-6 right-6 z-20 flex items-center gap-2.5 rounded-2xl bg-surface-raised/95 dark:bg-zinc-900/95 p-3 text-xs font-semibold text-body shadow-xl border border-border-subtle dark:border-zinc-800 backdrop-blur-xs">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-muted font-normal">Kunjungi Kami</span>
                                <span class="text-xs font-bold text-body line-clamp-1 max-w-[140px]">{{ $outlet->name ?: 'Outlet Utama' }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
