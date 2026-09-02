@php
    $ctaCopy = $layout->copyFor('cta');
@endphp

<section class="py-8 sm:py-12 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="relative overflow-hidden rounded-[3rem] bg-gradient-to-br from-primary/85 via-primary to-accent text-white p-8 sm:p-12 lg:p-16 shadow-2xl shadow-primary/35 border border-white/30">
            
            {{-- Floating Ambient Glass Orbs --}}
            <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-white/25 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-black/25 blur-3xl" aria-hidden="true"></div>

            <div class="relative z-10 max-w-2xl mx-auto text-center">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-white bg-white/20 px-4 py-1.5 rounded-full border border-white/40 backdrop-blur-xl shadow-xs">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                    <span>{{ $ctaCopy['label'] ?? 'Siap Menyambut Anda' }}</span>
                </span>

                <h2 class="mt-5 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight drop-shadow-md">
                    {{ $ctaCopy['title'] ?? 'Kunjungi restoran kami & nikmati kelezatannya' }}
                </h2>

                <p class="mt-4 text-sm sm:text-base text-white/95 leading-relaxed max-w-xl mx-auto drop-shadow-xs">
                    {{ $ctaCopy['subtitle'] ?? 'Datang sekarang, nikmati suasana hangat, dan pesan langsung dari meja pilihan Anda.' }}
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    @if ($menuItems->isNotEmpty())
                        <a
                            href="#menu"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-white text-zinc-950 hover:bg-white/95 px-8 py-4 text-sm font-bold shadow-2xl transition-all hover:scale-105 border border-white/40"
                        >
                            <span>{{ $ctaCopy['primary_button'] ?? 'Lihat Pilihan Menu' }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif

                    @if ($whatsappUrl)
                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-white/25 hover:bg-white/35 text-white border border-white/50 backdrop-blur-xl px-7 py-4 text-sm font-semibold transition-all hover:scale-103 shadow-lg"
                        >
                            <span>{{ $ctaCopy['secondary_button'] ?? 'Hubungi via WhatsApp' }}</span>
                        </a>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>
