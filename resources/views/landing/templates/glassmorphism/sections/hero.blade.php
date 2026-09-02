@php
    $copy = $layout->copyFor('hero');
    $headline = $profile?->headline ?: $restaurant->name;
    $subtitle = $copy['subtitle'] ?? 'Datang langsung ke restoran, nikmati suasana nyaman, dan pesan aneka hidangan segar dengan mudah dari HP Anda.';
    $heroImage = $heroUrl
        ?: ($aboutImageUrl
            ?: ($howToImageUrl
                ?: ($restaurant->cmsGalleryImages->first() ? \App\Support\CmsMedia::url($restaurant->cmsGalleryImages->first()->image_path) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80')));
    $avgRating = $ratingSummary['average'] ? number_format($ratingSummary['average'], 1) : '5.0';
@endphp

<section class="pt-4 sm:pt-8 lg:pt-10 pb-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Hero Grand Frosted Glass Showcase Container --}}
        <div class="relative overflow-hidden rounded-[2.8rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 p-6 sm:p-10 lg:p-14 shadow-[0_20px_70px_rgba(0,0,0,0.07),inset_0_1px_2px_rgba(255,255,255,0.7)] dark:shadow-[0_20px_70px_rgba(0,0,0,0.5),inset_0_1px_2px_rgba(255,255,255,0.15)]">
            
            {{-- Inner Glowing Light Orbs --}}
            <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-primary/25 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-accent/25 blur-3xl" aria-hidden="true"></div>

            <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-10">
                
                {{-- Left Column: Copy & Glass Actions --}}
                <div class="lg:col-span-7 flex flex-col items-start relative z-10">
                    
                    {{-- Status Pills --}}
                    <div class="flex flex-wrap items-center gap-2.5">
                        @if ($outlet)
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold {{ $isOpenNow ? 'bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-500/35' : 'bg-white/30 dark:bg-white/10 text-zinc-700 dark:text-zinc-300 border border-white/40 dark:border-white/15' }} backdrop-blur-xl shadow-xs">
                                <span class="h-2 w-2 rounded-full {{ $isOpenNow ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-400' }}"></span>
                                {{ $isOpenNow ? 'Resto Buka Sekarang' : 'Sedang Tutup' }}
                            </span>
                        @endif

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/15 text-primary border border-primary/30 backdrop-blur-xl px-4 py-1.5 text-xs font-bold shadow-xs">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            <span>{{ $copy['pill'] ?? 'Walk-in & Dine In' }}</span>
                        </span>
                    </div>

                    {{-- Main Headline --}}
                    <h1 class="mt-6 font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-white leading-[1.12]">
                        @php
                            $words = explode(' ', $headline);
                        @endphp
                        @if (count($words) > 1)
                            {{ $words[0] }}
                            <span class="relative inline-block px-4 py-1 mx-1 my-1 rounded-2xl bg-gradient-to-r from-primary to-accent text-white shadow-xl shadow-primary/35 border border-white/30 transform -rotate-1">
                                {{ $words[1] }}
                            </span>
                            {{ implode(' ', array_slice($words, 2)) }}
                        @else
                            {{ $headline }}
                        @endif
                    </h1>

                    {{-- Subtitle --}}
                    <p class="mt-5 text-base sm:text-lg text-zinc-700 dark:text-zinc-200 leading-relaxed max-w-xl">
                        {{ $subtitle }}
                    </p>

                    {{-- Action Buttons --}}
                    <div class="mt-8 flex flex-nowrap sm:flex-wrap items-center gap-2.5 sm:gap-4 w-full sm:w-auto">
                        @if ($menuItems->isNotEmpty())
                            <a
                                href="#menu"
                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 sm:gap-2 rounded-xl bg-gradient-to-r from-primary via-primary to-accent hover:opacity-95 px-3.5 sm:px-8 py-3 sm:py-4 text-xs sm:text-sm font-bold text-white shadow-xl shadow-primary/35 transition-all hover:scale-105 active:scale-95 border border-white/30 text-center whitespace-nowrap"
                            >
                                <span>Eksplor Menu</span>
                                <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif

                        @if ($mapsUrl)
                            <a
                                href="{{ $mapsUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 sm:gap-2 rounded-xl bg-white/40 dark:bg-white/10 backdrop-blur-xl border border-white/60 dark:border-white/20 px-3.5 sm:px-7 py-3 sm:py-4 text-xs sm:text-sm font-semibold text-zinc-900 dark:text-white shadow-lg hover:bg-white/60 dark:hover:bg-white/20 transition-all hover:scale-103 text-center whitespace-nowrap"
                            >
                                <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span>Petunjuk Arah</span>
                            </a>
                        @endif
                    </div>

                    {{-- iOS Widget Snippet --}}
                    @if (($ratingSummary['count'] ?? 0) > 0)
                        <div class="mt-9 flex items-center gap-3.5 rounded-2xl bg-white/40 dark:bg-white/10 backdrop-blur-xl border border-white/50 dark:border-white/15 p-3.5 shadow-md">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-amber-400 to-orange-500 text-white font-bold text-sm shadow-sm">
                                <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div class="flex flex-col text-xs">
                                <div class="flex items-center gap-1.5 font-bold text-zinc-900 dark:text-white">
                                    <span class="text-amber-500 font-extrabold text-sm">{{ $avgRating }}</span>
                                    <span class="text-zinc-500 dark:text-zinc-400">/ 5.0</span>
                                </div>
                                <span class="text-zinc-600 dark:text-zinc-300">{{ $ratingSummary['count'] }}+ ulasan tamu asli</span>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Right Column: Showcase Frame & Floating Glass Widgets --}}
                <div class="lg:col-span-5 relative flex items-center justify-center">
                    
                    {{-- Main Photo Frame with Double Glass Border --}}
                    <div class="relative w-full overflow-hidden rounded-[2.5rem] bg-white/30 dark:bg-white/10 p-3 backdrop-blur-2xl border border-white/60 dark:border-white/20 shadow-2xl">
                        <img
                            src="{{ $heroImage }}"
                            alt="{{ $restaurant->name }}"
                            class="h-[380px] sm:h-[430px] w-full object-cover rounded-[2rem]"
                        >

                        {{-- Floating Glass Widget 1: Rapid Service --}}
                        <div class="absolute top-6 left-6 z-20 flex items-center gap-3 rounded-2xl bg-white/60 dark:bg-zinc-900/75 backdrop-blur-2xl px-4 py-2.5 text-xs font-bold text-zinc-900 dark:text-white shadow-2xl border border-white/70 dark:border-white/20 animate-bounce" style="animation-duration: 5s;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-primary/20 text-primary">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <span class="block text-[10px] text-zinc-500 dark:text-zinc-400 font-normal">Sajian Segar</span>
                                <span class="text-xs text-primary font-extrabold">Dibuat Saat Dipesan</span>
                            </div>
                        </div>

                        {{-- Floating Glass Widget 2: Outlet Name --}}
                        @if ($outlet)
                            <div class="absolute bottom-6 right-6 z-20 flex items-center gap-3 rounded-2xl bg-white/60 dark:bg-zinc-900/75 backdrop-blur-2xl p-3 text-xs font-semibold text-zinc-900 dark:text-white shadow-2xl border border-white/70 dark:border-white/20">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-primary/20 text-primary">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-zinc-500 dark:text-zinc-400 font-normal">Outlet Resto</span>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-white line-clamp-1 max-w-[130px]">{{ $outlet->name ?: 'Utama' }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
