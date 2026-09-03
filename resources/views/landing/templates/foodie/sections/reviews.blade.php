@php
    $reviewsCopy = $layout->copyFor('reviews');
    $firstReview = $restaurant->reviews->first();
    $reviewComment = $firstReview?->comment
        ?: 'Pengalaman bersantap yang sangat luar biasa! Makanan disajikan hangat, rasa bumbunya meresap sempurna, dan suasananya sangat nyaman untuk makan bersama keluarga.';
    $reviewerName = $firstReview?->displayName() ?: 'Tamu Setia';
    $firstRating = $firstReview?->rating ?? 5;
    $avgRating = $ratingSummary['average'] ? number_format($ratingSummary['average'], 1) : '5.0';

    $chefImage = $aboutImageUrl
        ?: ($howToImageUrl
            ?: ($restaurant->cmsGalleryImages->first() ? \App\Support\CmsMedia::url($restaurant->cmsGalleryImages->first()->image_path) : 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=800&q=80'));
@endphp

<section id="ulasan" class="scroll-mt-20 py-16 sm:py-24 bg-surface-base border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-16">
            
            {{-- Left Column: Testimonial & Quote --}}
            <div class="lg:col-span-7">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-linear-to-r from-primary/15 to-accent/15 px-3.5 py-1.5 rounded-full border border-primary/25 shadow-2xs hover:scale-105 hover:shadow-xs transition-all duration-300 cursor-default">
                    {{ $reviewsCopy['label'] ?? 'Ulasan & Testimoni' }}
                </span>

                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-body tracking-tight leading-tight">
                    {{ $reviewsCopy['title'] ?? 'Kata Mereka Tentang Kami' }}
                </h2>

                {{-- Big Quote Box from Real DB Review --}}
                <div class="group relative mt-8 rounded-3xl bg-surface-raised dark:bg-zinc-900/90 p-6 sm:p-8 shadow-xl border border-border-subtle dark:border-zinc-800 hover:shadow-2xl hover:border-primary/40 hover:-translate-y-1 transition-all duration-300">
                    <span class="absolute -top-4 -left-2 text-6xl font-serif text-primary/30 select-none group-hover:text-primary/50 transition-colors duration-300">“</span>

                    <p class="relative z-10 text-base sm:text-lg text-body leading-relaxed italic">
                        {{ $reviewComment }}
                    </p>

                    <div class="mt-6 pt-5 border-t border-border-subtle dark:border-zinc-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-linear-to-tr from-primary to-accent text-white font-bold text-base shadow-sm group-hover:scale-110 transition-transform duration-200">
                                {{ strtoupper(substr($reviewerName, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-body group-hover:text-primary transition-colors duration-200">{{ $reviewerName }}</h4>
                                <span class="text-xs text-muted">Tamu Terverifikasi</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 text-amber-400 text-sm">
                            <div class="flex">
                                @for ($i = 0; $i < $firstRating; $i++)
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="font-bold text-xs text-body ml-1">{{ number_format($firstRating, 1) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Other Reviews Grid if available in DB --}}
                @if ($restaurant->reviews->count() > 1)
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($restaurant->reviews->skip(1)->take(2) as $rev)
                            <div class="group rounded-2xl bg-surface-raised dark:bg-zinc-900/90 p-4 border border-border-subtle dark:border-zinc-800 shadow-2xs hover:shadow-lg hover:border-primary/40 hover:-translate-y-1 transition-all duration-300">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-xs text-body group-hover:text-primary transition-colors duration-200">{{ $rev->displayName() }}</span>
                                    <span class="inline-flex items-center gap-1 text-amber-400 text-xs font-bold">
                                        <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ $rev->rating }}
                                    </span>
                                </div>
                                <p class="text-xs text-muted line-clamp-2 leading-relaxed">
                                    {{ $rev->comment ?: 'Pelayanan cepat dan rasa makanan lezat!' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right Column: Satisfaction Visual Badge --}}
            <div class="lg:col-span-5 relative flex items-center justify-center group">
                <div class="relative w-full max-w-sm mx-auto">
                    {{-- Decorative Backdrop --}}
                    <div class="absolute inset-0 rounded-3xl bg-linear-to-tr from-primary to-accent rotate-3 group-hover:rotate-6 group-hover:scale-102 shadow-2xl opacity-80 transition-all duration-500"></div>

                    <div class="relative overflow-hidden rounded-3xl bg-surface-raised dark:bg-zinc-900 p-3 shadow-xl border border-border-subtle dark:border-zinc-800">
                        <img
                            src="{{ $chefImage }}"
                            alt="Dapur & Hidangan Kami"
                            class="h-[380px] w-full object-cover rounded-2xl group-hover:scale-105 transition-transform duration-700 ease-out"
                            loading="lazy"
                        >

                        {{-- Floating Satisfaction Badge --}}
                        <div class="absolute bottom-6 right-6 rounded-2xl bg-surface-raised/95 dark:bg-zinc-900/95 backdrop-blur-md p-3.5 shadow-xl border border-border-subtle dark:border-zinc-800 flex items-center gap-3 hover:scale-105 hover:shadow-2xl transition-all duration-300 cursor-default">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-linear-to-br from-primary/20 to-accent/20 text-primary">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-body">Kepuasan Rasa</span>
                                <span class="text-[11px] text-primary font-extrabold">{{ $avgRating }} / 5.0 Rating Tamu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
