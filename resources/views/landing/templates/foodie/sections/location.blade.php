@php
    $copy = $layout->copyFor('location');
@endphp

<section id="lokasi" class="scroll-mt-20 py-16 sm:py-24 bg-surface-base border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-16">
            
            {{-- Left Column: Address & Action --}}
            <div class="lg:col-span-5">
                @if (filled($copy['label'] ?? null))
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3.5 py-1.5 rounded-full border border-primary/20 shadow-2xs">
                        {{ $copy['label'] }}
                    </span>
                @endif

                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-body tracking-tight leading-tight">
                    {{ $copy['title'] ?? 'Lokasi Restoran' }}
                </h2>

                @if ($outlet?->address)
                    <div class="mt-6 flex items-start gap-3 rounded-2xl bg-surface-raised dark:bg-zinc-900 p-5 border border-border-subtle dark:border-zinc-800 shadow-xs">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </div>
                        <div>
                            <span class="block text-xs text-muted font-medium uppercase tracking-wider">Alamat Lengkap</span>
                            <p class="mt-1 text-sm sm:text-base font-semibold text-body leading-relaxed">
                                {{ $outlet->address }}
                            </p>
                            @if ($outlet->name)
                                <span class="block text-xs text-muted mt-1">{{ $outlet->name }}</span>
                            @endif
                        </div>
                    </div>
                @endif

                @if ($mapsUrl)
                    <div class="mt-8">
                        <a
                            href="{{ $mapsUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-primary hover:bg-primary-dark px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-primary/25 hover:scale-103 transition-all"
                        >
                            <span>{{ $copy['button'] ?? 'Buka di Google Maps' }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Right Column: Google Maps Embed Frame --}}
            <div class="lg:col-span-7">
                <div class="overflow-hidden rounded-3xl bg-surface-muted shadow-xl border border-border-subtle dark:border-zinc-800">
                    @if ($mapEmbedUrl)
                        <iframe
                            title="Peta {{ $restaurant->name }}"
                            src="{{ $mapEmbedUrl }}"
                            class="h-[320px] sm:h-[380px] w-full border-0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    @else
                        <div class="flex h-[320px] items-center justify-center p-6 text-center text-sm text-muted">
                            Peta belum diatur. Koordinat outlet atau URL embed dapat diatur di CMS.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
