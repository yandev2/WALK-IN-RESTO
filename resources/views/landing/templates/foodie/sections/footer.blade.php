<footer class="bg-zinc-950 dark:bg-black text-zinc-300 dark:text-zinc-400 pt-16 pb-12 border-t border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-12 pb-12 border-b border-zinc-800">
            
            {{-- Brand Column --}}
            <div class="lg:col-span-4 flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $restaurant->name }}" class="h-10 w-10 rounded-full object-cover ring-1 ring-white/20">
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-linear-to-br from-primary to-accent text-white font-bold text-lg shadow-sm">
                            {{ strtoupper(substr($restaurant->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="font-display font-bold text-xl text-white tracking-tight">
                        {{ $restaurant->name }}
                    </span>
                </div>

                <p class="text-xs text-zinc-400 leading-relaxed max-w-sm">
                    {{ $profile?->headline ?: 'Nikmati hidangan lezat dan segar setiap hari. Walk-in langsung tanpa ribet reservasi.' }}
                </p>

                <div class="mt-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-zinc-900 px-3 py-1 text-xs text-zinc-300 border border-zinc-800">
                        <span class="h-2 w-2 rounded-full {{ $isOpenNow ? 'bg-emerald-400 animate-pulse' : 'bg-zinc-500' }}"></span>
                        {{ $isOpenNow ? 'Resto Sedang Buka Hari Ini' : 'Resto Sedang Tutup' }}
                    </span>
                </div>
            </div>

            {{-- Navigation Column (Driven by visibleSections) --}}
            <div class="lg:col-span-2">
                <h4 class="font-bold text-sm text-white uppercase tracking-wider mb-4">Navigasi</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#atas" class="hover:text-primary hover:translate-x-1 transition-all duration-200 inline-block">Beranda</a></li>
                    @if (in_array('menu', $visibleSections, true))
                        <li><a href="#menu" class="hover:text-primary hover:translate-x-1 transition-all duration-200 inline-block">Menu Populer</a></li>
                    @endif
                    @if (in_array('how_to', $visibleSections, true))
                        <li><a href="#cara-pesan" class="hover:text-primary hover:translate-x-1 transition-all duration-200 inline-block">Cara Pesan</a></li>
                    @endif
                    @if (in_array('about', $visibleSections, true))
                        <li><a href="#tentang" class="hover:text-primary hover:translate-x-1 transition-all duration-200 inline-block">Tentang Kami</a></li>
                    @endif
                    @if (in_array('reviews', $visibleSections, true))
                        <li><a href="#ulasan" class="hover:text-primary hover:translate-x-1 transition-all duration-200 inline-block">Ulasan Tamu</a></li>
                    @endif
                    @if (in_array('hours', $visibleSections, true) || in_array('location', $visibleSections, true))
                        <li><a href="#lokasi" class="hover:text-primary hover:translate-x-1 transition-all duration-200 inline-block">Jam & Lokasi</a></li>
                    @endif
                </ul>
            </div>

            {{-- Contact Column --}}
            <div class="lg:col-span-3">
                <h4 class="font-bold text-sm text-white uppercase tracking-wider mb-4">Kontak</h4>
                <ul class="space-y-3 text-xs">
                    @if ($outlet?->address)
                        <li class="flex items-start gap-2 text-zinc-400 hover:text-white transition-colors duration-200">
                            <svg class="h-4 w-4 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>{{ $outlet->address }}</span>
                        </li>
                    @endif
                    @if ($outlet?->phone)
                        <li class="flex items-center gap-2 text-zinc-400 hover:text-white transition-colors duration-200">
                            <svg class="h-4 w-4 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>{{ $outlet->phone }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Maps Action Column --}}
            <div class="lg:col-span-3 flex flex-col gap-3">
                <h4 class="font-bold text-sm text-white uppercase tracking-wider mb-1">Kunjungi Resto</h4>
                <p class="text-xs text-zinc-400">
                    Buka Google Maps untuk mendapatkan rute tercepat menuju outlet kami.
                </p>
                @if ($mapsUrl)
                    <a
                        href="{{ $mapsUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-linear-to-r from-primary to-accent hover:opacity-95 px-5 py-3 text-xs font-bold text-white shadow-md hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 border border-white/15 group"
                    >
                        <span>Buka di Google Maps</span>
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endif
            </div>

        </div>

        {{-- Copyright & Credit --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-500">
            <p>© {{ date('Y') }} {{ $restaurant->name }}. Hak cipta dilindungi.</p>
            <p class="flex items-center gap-1">
                <span>Powered by</span>
                <span class="font-semibold text-zinc-400">Walk-in Resto Platform</span>
            </p>
        </div>

    </div>
</footer>
