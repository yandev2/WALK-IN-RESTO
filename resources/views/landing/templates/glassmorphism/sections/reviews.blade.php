@php
    $reviewsCopy = $layout->copyFor('reviews');
    $reviews = $restaurant->reviews ?? collect();
    $avgRating = $ratingSummary['average'] ? number_format($ratingSummary['average'], 1) : '5.0';
    $totalReviews = $ratingSummary['count'] ?? 0;
@endphp

@if ($reviews->isNotEmpty())
    <section id="ulasan" class="scroll-mt-24 py-8 sm:py-12 relative">
        
        {{-- Ambient Light Orb --}}
        <div class="pointer-events-none absolute right-10 top-1/2 h-80 w-80 rounded-full bg-primary/20 blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span>{{ $reviewsCopy['label'] ?? 'Kata Mereka' }}</span>
                </span>
                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                    {{ $reviewsCopy['title'] ?? 'Ulasan jujur dari tamu kami' }}
                </h2>
                <p class="mt-3 text-sm sm:text-base text-zinc-700 dark:text-zinc-300 leading-relaxed">
                    {{ $reviewsCopy['subtitle'] ?? 'Pengalaman nyata dari pelanggan yang telah berkunjung dan menikmati sajian kami.' }}
                </p>
            </div>

            {{-- Glass Review Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($reviews->take(6) as $review)
                    <div class="flex flex-col justify-between rounded-[2.4rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 p-6 shadow-[0_12px_40px_rgba(0,0,0,0.06),inset_0_1px_1px_rgba(255,255,255,0.7)] dark:shadow-[0_12px_40px_rgba(0,0,0,0.4),inset_0_1px_1px_rgba(255,255,255,0.15)] hover:shadow-2xl hover:border-primary/50 hover:bg-white/35 dark:hover:bg-white/10 transition-all duration-300 hover:-translate-y-2">
                        <div>
                            {{-- Star Rating Pill --}}
                            <div class="flex items-center justify-between">
                                <div class="flex text-amber-400 text-sm gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-zinc-300 dark:text-zinc-600' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="rounded-full bg-emerald-500/20 border border-emerald-500/35 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-800 dark:text-emerald-300 backdrop-blur-md">
                                    ✓ Tamu Terverifikasi
                                </span>
                            </div>

                            {{-- Comment Text --}}
                            <p class="mt-4 text-sm text-zinc-700 dark:text-zinc-200 leading-relaxed line-clamp-4">
                                "{{ $review->comment }}"
                            </p>
                        </div>

                        {{-- Guest Info --}}
                        <div class="mt-6 pt-4 border-t border-black/5 dark:border-white/10 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-tr from-primary to-accent text-white font-bold text-sm shadow-md shadow-primary/25 border border-white/30">
                                {{ strtoupper(substr($review->customer_name ?: 'Tamu', 0, 1)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-xs text-zinc-900 dark:text-white">{{ $review->customer_name ?: 'Tamu Walk-in' }}</span>
                                <span class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ $review->submitted_at ? \Carbon\Carbon::parse($review->submitted_at)->diffForHumans() : 'Baru saja' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary Satisfaction Banner --}}
            <div class="mt-10 rounded-[2.5rem] bg-gradient-to-r from-primary/20 via-accent/20 to-primary/20 backdrop-blur-2xl border border-white/50 dark:border-white/20 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl">
                <div class="flex items-center gap-4 text-center sm:text-left">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/50 dark:bg-white/10 backdrop-blur-xl border border-white/60 dark:border-white/20 shadow-md">
                        <span class="font-display font-extrabold text-2xl text-primary">{{ $avgRating }}</span>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-base sm:text-lg text-zinc-900 dark:text-white">Kepuasan Pelanggan</h3>
                        <p class="text-xs text-zinc-700 dark:text-zinc-300">Berdasarkan {{ $totalReviews }}+ penilaian pengunjung restoran.</p>
                    </div>
                </div>

                @if ($whatsappUrl)
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-primary to-accent hover:opacity-95 text-white px-6 py-3 text-xs font-bold shadow-lg shadow-primary/30 transition-all hover:scale-105 border border-white/30"
                    >
                        <span>Tanya Meja via WhatsApp</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endif
            </div>

        </div>
    </section>
@endif
