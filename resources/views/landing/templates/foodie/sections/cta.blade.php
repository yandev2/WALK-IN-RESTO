@php
    use App\Support\LandingLayout;
    $copy = $layout->copyFor('cta');
    $title = LandingLayout::interpolate($copy['title'] ?? 'Kunjungi :restaurant Sekarang', $restaurant->name);
    $subtitle = $copy['subtitle'] ?? 'Datang langsung ke outlet kami untuk menikmati hidangan lezat dan suasana bersantap yang berkesan.';
@endphp

<section id="cta" class="scroll-mt-20 py-16 sm:py-24 bg-surface-base border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- High-Impact CTA Card --}}
        <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-primary via-primary-dark to-accent px-6 py-12 sm:px-12 sm:py-16 text-center text-white shadow-2xl hover:shadow-primary/20 hover:scale-[1.01] transition-all duration-500">
            
            {{-- Decorative Ambient Elements --}}
            <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-2xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-black/10 blur-2xl" aria-hidden="true"></div>

            <div class="relative z-10 max-w-2xl mx-auto">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/30 px-3.5 py-1 text-xs font-bold text-white shadow-2xs hover:scale-105 transition-transform duration-200 cursor-default">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                    <span>Promo & Penawaran</span>
                </span>

                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                    {{ $title }}
                </h2>

                <p class="mt-3 text-sm sm:text-base text-white/90 leading-relaxed">
                    {{ $subtitle }}
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-3.5">
                    @if ($whatsappUrl)
                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-full bg-white text-zinc-900 px-7 py-3.5 text-sm font-bold shadow-lg hover:bg-zinc-100 hover:shadow-2xl hover:scale-108 active:scale-95 transition-all duration-200"
                        >
                            <span>{{ $copy['wa_button'] ?? 'Hubungi WhatsApp' }}</span>
                            <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        </a>
                    @endif

                    @if ($mapsUrl)
                        <a
                            href="{{ $mapsUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-full bg-white/20 backdrop-blur-md border border-white/30 px-6 py-3.5 text-sm font-bold text-white shadow-md hover:bg-white/30 hover:shadow-xl hover:scale-108 active:scale-95 transition-all duration-200"
                        >
                            <span>{{ $copy['button'] ?? 'Petunjuk Lokasi Resto' }}</span>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
