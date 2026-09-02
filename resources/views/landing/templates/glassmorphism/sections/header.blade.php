<div class="sticky top-4 z-50 px-3 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <header class="h-16 rounded-full bg-white/40 dark:bg-zinc-900/50 backdrop-blur-2xl border border-white/50 dark:border-white/15 shadow-[0_10px_35px_rgba(0,0,0,0.06),inset_0_1px_1px_rgba(255,255,255,0.5)] dark:shadow-[0_10px_35px_rgba(0,0,0,0.4),inset_0_1px_1px_rgba(255,255,255,0.1)] px-4 sm:px-6 flex items-center justify-between transition-all duration-300">
        
        {{-- Brand Logo & Name --}}
        <a href="#atas" class="flex items-center gap-3 group">
            @if ($logoUrl)
                <img
                    src="{{ $logoUrl }}"
                    alt="{{ $restaurant->name }}"
                    class="h-9 w-9 rounded-full object-cover ring-2 ring-primary/40 group-hover:scale-105 transition-transform shadow-xs"
                >
            @else
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-tr from-primary to-accent text-white font-display font-bold text-base shadow-md shadow-primary/25 group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr($restaurant->name, 0, 1)) }}
                </div>
            @endif
            <div class="flex flex-col">
                <span class="font-display font-bold text-base sm:text-lg text-zinc-900 dark:text-white tracking-tight group-hover:text-primary transition-colors drop-shadow-2xs">
                    {{ $restaurant->name }}
                </span>
            </div>
        </a>

        {{-- iOS Frosted Segment Navigation --}}
        <nav class="hidden md:flex items-center gap-1 bg-white/30 dark:bg-white/5 p-1 rounded-full border border-white/40 dark:border-white/10 backdrop-blur-xl text-xs font-semibold text-zinc-700 dark:text-zinc-300 shadow-inner">
            <a href="#atas" class="px-3.5 py-1.5 rounded-full hover:bg-white/70 dark:hover:bg-white/15 hover:text-zinc-900 dark:hover:text-white hover:shadow-xs transition-all">Beranda</a>
            @if (in_array('menu', $visibleSections, true))
                <a href="#menu" class="px-3.5 py-1.5 rounded-full hover:bg-white/70 dark:hover:bg-white/15 hover:text-zinc-900 dark:hover:text-white hover:shadow-xs transition-all">Menu</a>
            @endif
            @if (in_array('how_to', $visibleSections, true))
                <a href="#cara-pesan" class="px-3.5 py-1.5 rounded-full hover:bg-white/70 dark:hover:bg-white/15 hover:text-zinc-900 dark:hover:text-white hover:shadow-xs transition-all">Cara Pesan</a>
            @endif
            @if (in_array('about', $visibleSections, true))
                <a href="#tentang" class="px-3.5 py-1.5 rounded-full hover:bg-white/70 dark:hover:bg-white/15 hover:text-zinc-900 dark:hover:text-white hover:shadow-xs transition-all">Tentang</a>
            @endif
            @if (in_array('reviews', $visibleSections, true))
                <a href="#ulasan" class="px-3.5 py-1.5 rounded-full hover:bg-white/70 dark:hover:bg-white/15 hover:text-zinc-900 dark:hover:text-white hover:shadow-xs transition-all">Ulasan</a>
            @endif
            @if (in_array('gallery', $visibleSections, true))
                <a href="#galeri" class="px-3.5 py-1.5 rounded-full hover:bg-white/70 dark:hover:bg-white/15 hover:text-zinc-900 dark:hover:text-white hover:shadow-xs transition-all">Galeri</a>
            @endif
            @if (in_array('hours', $visibleSections, true) || in_array('location', $visibleSections, true))
                <a href="#lokasi" class="px-3.5 py-1.5 rounded-full hover:bg-white/70 dark:hover:bg-white/15 hover:text-zinc-900 dark:hover:text-white hover:shadow-xs transition-all">Lokasi</a>
            @endif
            @if (in_array('faq', $visibleSections, true))
                <a href="#faq" class="px-3.5 py-1.5 rounded-full hover:bg-white/70 dark:hover:bg-white/15 hover:text-zinc-900 dark:hover:text-white hover:shadow-xs transition-all">FAQ</a>
            @endif
        </nav>

        {{-- Actions: Theme Toggle & Order CTA --}}
        <div class="flex items-center gap-2.5">
            <x-customer.theme-toggle />

            @if ($whatsappUrl)
                <a
                    href="{{ $whatsappUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 backdrop-blur-md px-3.5 py-1.5 text-xs font-semibold hover:bg-emerald-500/25 transition shadow-xs"
                >
                    <span>WA</span>
                </a>
            @endif

            @if ($menuItems->isNotEmpty())
                <a
                    href="{{ route('landing.menu', $restaurant) }}"
                    class="inline-flex items-center justify-center gap-1.5 rounded-full bg-gradient-to-r from-primary to-accent hover:opacity-95 text-white px-4 py-2 text-xs font-bold shadow-lg shadow-primary/30 transition-all hover:scale-105 active:scale-95 border border-white/20"
                >
                    <span>Menu</span>
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>

    </header>
</div>
