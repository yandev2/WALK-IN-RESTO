@php
    $howToCopy = $layout->copyFor('how_to');
    $steps = $howToCopy['steps'] ?? [];
@endphp

<section id="cara-pesan" class="scroll-mt-24 py-8 sm:py-12 relative">
    
    {{-- Ambient Light Orb --}}
    <div class="pointer-events-none absolute -left-20 top-1/3 h-80 w-80 rounded-full bg-accent/20 blur-3xl -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>{{ $howToCopy['label'] ?? 'Alur Praktis' }}</span>
            </span>
            <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                {{ $howToCopy['title'] ?? 'Cara mudah memesan di resto' }}
            </h2>
            <p class="mt-3 text-sm sm:text-base text-zinc-700 dark:text-zinc-300 leading-relaxed">
                {{ $howToCopy['subtitle'] ?? 'Tanpa perlu antre lama, cukup ikuti 4 langkah cepat ini dari meja Anda.' }}
            </p>
        </div>

        {{-- Glassmorphism 4 Step Cards Grid with Clean Heroicons --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($steps as $index => $step)
                <div class="group relative flex flex-col justify-between rounded-[2.4rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 p-6 sm:p-7 shadow-[0_12px_40px_rgba(0,0,0,0.06),inset_0_1px_1px_rgba(255,255,255,0.7)] dark:shadow-[0_12px_40px_rgba(0,0,0,0.4),inset_0_1px_1px_rgba(255,255,255,0.15)] hover:shadow-2xl hover:border-primary/50 hover:bg-white/35 dark:hover:bg-white/10 transition-all duration-300 hover:-translate-y-2">
                    
                    {{-- Top Step Number & SVG Heroicon --}}
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-primary to-accent text-white font-display font-extrabold text-lg shadow-lg shadow-primary/35 border border-white/30 group-hover:scale-110 transition-transform">
                                0{{ $index + 1 }}
                            </span>
                            
                            {{-- Clean SVG Heroicons per Step --}}
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/40 dark:bg-white/10 border border-white/50 dark:border-white/15 text-primary shadow-sm group-hover:scale-110 transition-transform">
                                @if ($index === 0)
                                    {{-- Table / Seat Icon --}}
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                @elseif ($index === 1)
                                    {{-- QR / Scan Phone Icon --}}
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                @elseif ($index === 2)
                                    {{-- Fresh Dish / Sparkle Icon --}}
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                @else
                                    {{-- Payment / Check Icon --}}
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                @endif
                            </div>
                        </div>

                        {{-- Step Title & Description --}}
                        <h3 class="font-display font-bold text-lg text-zinc-900 dark:text-white group-hover:text-primary transition-colors">
                            {{ $step['title'] ?? '' }}
                        </h3>
                        <p class="mt-2.5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                            {{ $step['desc'] ?? '' }}
                        </p>
                    </div>

                    {{-- Bottom Pill Status --}}
                    <div class="mt-6 pt-4 border-t border-black/5 dark:border-white/10">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-primary">
                            <span>Langkah {{ $index + 1 }}</span>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
