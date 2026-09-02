<header class="sticky top-0 z-40 bg-surface-raised/90 dark:bg-zinc-900/90 backdrop-blur-md border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            {{-- Brand Logo & Name --}}
            <a href="#atas" class="flex items-center gap-3 group">
                @if ($logoUrl)
                    <img
                        src="{{ $logoUrl }}"
                        alt="{{ $restaurant->name }}"
                        class="h-11 w-11 rounded-full object-cover ring-2 ring-primary/20 group-hover:scale-105 transition-transform"
                    >
                @else
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-linear-to-br from-primary to-accent text-white font-display font-bold text-xl shadow-sm group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr($restaurant->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex flex-col">
                    <span class="font-display font-bold text-lg sm:text-xl text-body group-hover:text-primary transition-colors tracking-tight">
                        {{ $restaurant->name }}
                    </span>
                    @if ($outlet)
                        <span class="text-[11px] text-muted -mt-0.5">
                            {{ $outlet->name ?: 'Outlet Utama' }}
                        </span>
                    @endif
                </div>
            </a>

            {{-- Desktop Navigation Links (Driven by visibleSections) --}}
            <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-muted">
                <a href="#atas" class="hover:text-primary transition-colors">Beranda</a>
                @if (in_array('menu', $visibleSections, true))
                    <a href="#menu" class="hover:text-primary transition-colors">Menu</a>
                @endif
                @if (in_array('how_to', $visibleSections, true))
                    <a href="#cara-pesan" class="hover:text-primary transition-colors">Cara Pesan</a>
                @endif
                @if (in_array('about', $visibleSections, true))
                    <a href="#tentang" class="hover:text-primary transition-colors">Tentang</a>
                @endif
                @if (in_array('reviews', $visibleSections, true))
                    <a href="#ulasan" class="hover:text-primary transition-colors">Ulasan</a>
                @endif
                @if (in_array('gallery', $visibleSections, true))
                    <a href="#galeri" class="hover:text-primary transition-colors">Galeri</a>
                @endif
                @if (in_array('hours', $visibleSections, true) || in_array('location', $visibleSections, true))
                    <a href="#lokasi" class="hover:text-primary transition-colors">Lokasi</a>
                @endif
                @if (in_array('faq', $visibleSections, true))
                    <a href="#faq" class="hover:text-primary transition-colors">FAQ</a>
                @endif
            </nav>

            {{-- Right Action Buttons: Theme Toggle & Order CTA --}}
            <div class="flex items-center gap-3">
                {{-- Dark / Light Mode Switcher --}}
                <x-customer.theme-toggle />

                @if ($whatsappUrl)
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener"
                        class="hidden sm:inline-flex items-center gap-2 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 px-4 py-2 text-xs font-semibold hover:bg-emerald-500/20 transition"
                    >
                        <span>WhatsApp</span>
                    </a>
                @endif

                @if ($menuItems->isNotEmpty())
                    <a
                        href="{{ route('landing.menu', $restaurant) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-primary hover:bg-primary-dark text-white px-5 py-2.5 text-xs sm:text-sm font-bold shadow-md shadow-primary/25 hover:shadow-lg transition-all hover:scale-103"
                    >
                        <span>Daftar Menu</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>

        </div>
    </div>
</header>
