@php
    $instagramUrl = $instagramUrl ?? ($outlet?->instagram ? \App\Support\CmsMedia::instagramUrl($outlet->instagram) : null);
@endphp

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
                    @if ($outlet?->instagram && $instagramUrl)
                        <li class="flex items-center gap-2 text-zinc-400 hover:text-white transition-colors duration-200">
                            <svg class="h-4 w-4 text-pink-500 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors duration-200">
                                {{ str_starts_with($outlet->instagram, 'http') ? '@'.basename($outlet->instagram) : (str_starts_with($outlet->instagram, '@') ? $outlet->instagram : '@'.$outlet->instagram) }}
                            </a>
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
