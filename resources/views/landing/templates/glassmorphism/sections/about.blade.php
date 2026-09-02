@php
    $aboutCopy = $layout->copyFor('about');
    $aboutImage = $aboutImageUrl
        ?: ($heroUrl
            ?: ($restaurant->cmsGalleryImages->first() ? \App\Support\CmsMedia::url($restaurant->cmsGalleryImages->first()->image_path) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1000&q=80'));
@endphp

@if (filled($profile?->about_html))
    <section id="tentang" class="scroll-mt-24 py-8 sm:py-12 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="relative overflow-hidden rounded-[2.8rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 p-6 sm:p-10 lg:p-14 shadow-[0_20px_70px_rgba(0,0,0,0.07),inset_0_1px_2px_rgba(255,255,255,0.7)] dark:shadow-[0_20px_70px_rgba(0,0,0,0.5),inset_0_1px_2px_rgba(255,255,255,0.15)]">
                
                {{-- Ambient Background Glow --}}
                <div class="pointer-events-none absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-primary/25 blur-3xl" aria-hidden="true"></div>

                <div class="grid items-center gap-10 lg:grid-cols-12">
                    
                    {{-- Left Image with Glass Border --}}
                    <div class="lg:col-span-5 relative">
                        <div class="overflow-hidden rounded-[2.2rem] bg-white/30 dark:bg-white/10 p-2.5 backdrop-blur-2xl border border-white/60 dark:border-white/20 shadow-xl">
                            <img
                                src="{{ $aboutImage }}"
                                alt="Tentang {{ $restaurant->name }}"
                                class="h-[340px] sm:h-[400px] w-full object-cover rounded-[1.8rem]"
                            >
                        </div>
                    </div>

                    {{-- Right Content --}}
                    <div class="lg:col-span-7 flex flex-col items-start">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span>{{ $aboutCopy['label'] ?? 'Kisah Kami' }}</span>
                        </span>

                        <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                            {{ $aboutCopy['title'] ?? 'Cerita di balik dapur kami' }}
                        </h2>

                        {{-- Rich Text Content from CMS --}}
                        <div class="mt-6 prose prose-zinc dark:prose-invert prose-p:text-sm sm:prose-p:text-base prose-p:leading-relaxed text-zinc-700 dark:text-zinc-200 max-w-none">
                            {!! $profile->about_html !!}
                        </div>

                        @if ($menuItems->isNotEmpty())
                            <div class="mt-8">
                                <a
                                    href="#menu"
                                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-primary to-accent hover:opacity-95 text-white px-7 py-3.5 text-sm font-bold shadow-xl shadow-primary/30 transition-all hover:scale-105 border border-white/30"
                                >
                                    <span>Cicipi Menu Kami</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

        </div>
    </section>
@endif
