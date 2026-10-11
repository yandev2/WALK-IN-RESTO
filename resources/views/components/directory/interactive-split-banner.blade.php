@props([
    'promo' => null,
    'locationStatus' => 'idle',
])

<section aria-label="Penawaran Spesial & Peta Lokasi" class="directory-split-banner mb-10">
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)] items-stretch">
        {{-- Promo Card (Kiri) --}}
        <div class="group relative flex flex-col justify-between overflow-hidden rounded-[1.6rem] bg-surface-raised p-6 sm:p-8 ring-1 ring-[color:var(--border-subtle)] shadow-sm">
            @if ($promo && filled($promo['image_url'] ?? null))
                <img
                    src="{{ $promo['image_url'] }}"
                    alt="{{ $promo['title'] }}"
                    class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/70 to-black/40"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/20 via-primary/10 to-surface-muted"></div>
                <img
                    src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80"
                    alt="Promo Kuliner"
                    class="absolute inset-0 h-full w-full object-cover opacity-20 mix-blend-overlay transition duration-700 group-hover:scale-105"
                >
            @endif

            <div class="relative z-10">
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-primary/20 backdrop-blur-md border border-primary/30 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-primary">
                    {{ $promo['badge_text'] ?? 'Promo Spesial' }}
                </span>

                <h3 class="mt-3 font-display text-xl sm:text-2xl font-bold leading-snug {{ ($promo && filled($promo['image_url'] ?? null)) ? 'text-white' : 'text-body' }}">
                    {{ $promo['title'] ?? 'Diskon Spesial untuk Menu Pilihan' }}
                </h3>

                <p class="mt-2 text-xs sm:text-sm leading-relaxed {{ ($promo && filled($promo['image_url'] ?? null)) ? 'text-white/80' : 'text-muted' }}">
                    {{ $promo['subtitle'] ?? 'Nikmati berbagai promo hemat dan potongan harga di restoran favorit terdekat Anda.' }}
                </p>
            </div>

            <div class="relative z-10 mt-6 pt-2">
                @if ($promo && filled($promo['link_url'] ?? null))
                    <a
                        href="{{ $promo['link_url'] }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-primary-dark transition"
                    >
                        <span>{{ $promo['cta_label'] ?? 'Lihat Promo' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @else
                    <a
                        href="#directory-results"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-primary-dark transition"
                    >
                        <span>Eksplor Menu Pilihan</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        {{-- Map Panel Card (Kanan) --}}
        <div class="flex flex-col h-full">
            <x-directory.map-panel map-id="directory-map-desktop" :location-status="$locationStatus" />
        </div>
    </div>
</section>
