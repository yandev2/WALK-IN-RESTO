@php
    $locCopy = $layout->copyFor('location');
@endphp

@if ($outlet)
    <section id="lokasi" class="scroll-mt-24 py-8 sm:py-12 relative">
        
        {{-- Ambient Light Orb --}}
        <div class="pointer-events-none absolute right-10 top-1/2 h-80 w-80 rounded-full bg-primary/20 blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <span>{{ $locCopy['label'] ?? 'Temukan Kami' }}</span>
                </span>
                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                    {{ $locCopy['title'] ?? 'Lokasi & Petunjuk Arah' }}
                </h2>
                <p class="mt-3 text-sm sm:text-base text-zinc-700 dark:text-zinc-300 leading-relaxed">
                    {{ $locCopy['subtitle'] ?? 'Kunjungi outlet kami dengan mudah. Parkir luas dan lokasi strategis.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                
                {{-- Address Info Card --}}
                <div class="lg:col-span-5 flex flex-col justify-between rounded-[2.8rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 p-6 sm:p-10 shadow-[0_20px_70px_rgba(0,0,0,0.07),inset_0_1px_2px_rgba(255,255,255,0.7)] dark:shadow-[0_20px_70px_rgba(0,0,0,0.5),inset_0_1px_2px_rgba(255,255,255,0.15)]">
                    <div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-primary to-accent text-white shadow-lg shadow-primary/30 border border-white/30 mb-6">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </div>
                        <h3 class="font-display font-bold text-xl sm:text-2xl text-zinc-900 dark:text-white">
                            {{ $outlet->name ?: $restaurant->name }}
                        </h3>
                        <p class="mt-4 text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                            {{ $outlet->address ?: 'Alamat belum diatur di sistem.' }}
                        </p>

                        @if ($outlet->phone)
                            <div class="mt-6 flex items-center gap-3">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $outlet->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 pt-6 border-t border-black/5 dark:border-white/10 flex flex-wrap gap-3">
                        @if ($mapsUrl)
                            <a
                                href="{{ $mapsUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-primary to-accent hover:opacity-95 text-white px-6 py-3.5 text-xs font-bold shadow-xl shadow-primary/30 transition-all hover:scale-105 border border-white/30"
                            >
                                <span>Buka di Google Maps</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif

                        @if ($whatsappUrl)
                            <a
                                href="{{ $whatsappUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-full bg-white/40 dark:bg-white/10 backdrop-blur-xl border border-white/60 dark:border-white/20 px-5 py-3.5 text-xs font-semibold text-zinc-900 dark:text-white hover:bg-white/60 dark:hover:bg-white/20 transition shadow-sm"
                            >
                                <span>Hubungi Kami</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Map Frame --}}
                <div class="lg:col-span-7 overflow-hidden rounded-[2.8rem] bg-white/20 dark:bg-white/[0.05] p-3 backdrop-blur-2xl border border-white/50 dark:border-white/15 shadow-xl min-h-[360px]">
                    @if ($mapEmbedUrl)
                        <iframe
                            src="{{ $mapEmbedUrl }}"
                            width="100%"
                            height="100%"
                            style="border:0; min-height: 380px;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="rounded-[2.2rem] w-full h-full"
                        ></iframe>
                    @else
                        <div class="h-full min-h-[380px] w-full rounded-[2.2rem] bg-gradient-to-br from-primary/15 to-accent/20 flex flex-col items-center justify-center p-6 text-center border border-white/30">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/40 dark:bg-white/10 text-primary mb-3">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            </div>
                            <h4 class="font-display font-bold text-lg text-zinc-900 dark:text-white">Peta Lokasi</h4>
                            <p class="mt-2 text-xs sm:text-sm text-zinc-700 dark:text-zinc-300 max-w-sm">
                                {{ $outlet->address }}
                            </p>
                            @if ($mapsUrl)
                                <a
                                    href="{{ $mapsUrl }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-5 inline-flex items-center gap-2 rounded-full bg-primary text-white px-5 py-2.5 text-xs font-bold shadow-md shadow-primary/30 border border-white/30"
                                >
                                    <span>Buka Peta Google Maps</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </section>
@endif
