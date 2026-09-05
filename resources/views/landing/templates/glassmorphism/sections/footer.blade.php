@php
    $instagramUrl = $instagramUrl ?? ($outlet?->instagram ? \App\Support\CmsMedia::instagramUrl($outlet->instagram) : null);
@endphp

<footer class="mt-20 mx-4 sm:mx-6 lg:mx-8 mb-8">
    <div class="max-w-7xl mx-auto rounded-[2.8rem] bg-white/30 dark:bg-white/[0.05] backdrop-blur-3xl border border-white/50 dark:border-white/10 shadow-[0_20px_70px_rgba(0,0,0,0.06),inset_0_1px_2px_rgba(255,255,255,0.7)] dark:shadow-[0_20px_70px_rgba(0,0,0,0.4),inset_0_1px_2px_rgba(255,255,255,0.1)] p-8 sm:p-12 lg:p-14 transition-all duration-300">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10">
            
            {{-- Brand Column --}}
            <div class="lg:col-span-5 flex flex-col items-start">
                <a href="#atas" class="flex items-center gap-3 group">
                    @if ($logoUrl)
                        <img
                            src="{{ $logoUrl }}"
                            alt="{{ $restaurant->name }}"
                            class="h-10 w-10 rounded-full object-cover ring-2 ring-primary/40 group-hover:scale-105 transition-transform shadow-xs"
                        >
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-tr from-primary to-accent text-white font-display font-bold text-lg shadow-md shadow-primary/25 group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr($restaurant->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="font-display font-bold text-xl text-zinc-900 dark:text-white group-hover:text-primary transition-colors">
                        {{ $restaurant->name }}
                    </span>
                </a>

                <p class="mt-4 text-xs sm:text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed max-w-sm">
                    {{ $profile?->headline ?: 'Restoran pilihan keluarga dengan aneka sajian lezat, higienis, dan pelayanan ramah.' }}
                </p>

                @if ($outlet)
                    <div class="mt-4 flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400">
                        <svg class="h-4 w-4 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <span class="line-clamp-1">{{ $outlet->address }}</span>
                    </div>
                @endif
            </div>

            {{-- Nav Links Column --}}
            <div class="lg:col-span-3 flex flex-col">
                <h4 class="font-display font-bold text-sm text-zinc-900 dark:text-white uppercase tracking-wider mb-4">
                    Navigasi
                </h4>
                <ul class="space-y-2.5 text-xs sm:text-sm text-zinc-700 dark:text-zinc-300">
                    <li><a href="#atas" class="hover:text-primary transition-colors">Beranda</a></li>
                    @if (in_array('menu', $visibleSections, true))
                        <li><a href="#menu" class="hover:text-primary transition-colors">Daftar Menu</a></li>
                    @endif
                    @if (in_array('how_to', $visibleSections, true))
                        <li><a href="#cara-pesan" class="hover:text-primary transition-colors">Cara Pesan</a></li>
                    @endif
                    @if (in_array('about', $visibleSections, true))
                        <li><a href="#tentang" class="hover:text-primary transition-colors">Tentang Resto</a></li>
                    @endif
                    @if (in_array('reviews', $visibleSections, true))
                        <li><a href="#ulasan" class="hover:text-primary transition-colors">Ulasan Pelanggan</a></li>
                    @endif
                    @if (in_array('hours', $visibleSections, true) || in_array('location', $visibleSections, true))
                        <li><a href="#lokasi" class="hover:text-primary transition-colors">Jam Buka & Lokasi</a></li>
                    @endif
                    @if (in_array('faq', $visibleSections, true))
                        <li><a href="#faq" class="hover:text-primary transition-colors">Tanya Jawab (FAQ)</a></li>
                    @endif
                </ul>
            </div>

            {{-- Contact & Status Column --}}
            <div class="lg:col-span-4 flex flex-col">
                <h4 class="font-display font-bold text-sm text-zinc-900 dark:text-white uppercase tracking-wider mb-4">
                    Kontak & Layanan
                </h4>
                
                <div class="space-y-3 text-xs sm:text-sm text-zinc-700 dark:text-zinc-300">
                    @if ($outlet?->phone)
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>{{ $outlet->phone }}</span>
                        </div>
                    @endif

                    @if ($outlet?->instagram && $instagramUrl)
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-pink-500 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">
                                {{ str_starts_with($outlet->instagram, 'http') ? '@'.basename($outlet->instagram) : (str_starts_with($outlet->instagram, '@') ? $outlet->instagram : '@'.$outlet->instagram) }}
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        @if ($whatsappUrl)
                            <a
                                href="{{ $whatsappUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-full bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-800 dark:text-emerald-300 border border-emerald-500/35 backdrop-blur-md px-4 py-2 text-xs font-semibold transition shadow-xs"
                            >
                                <span>Chat WhatsApp</span>
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif

                        @if ($outlet?->instagram && $instagramUrl)
                            <a
                                href="{{ $instagramUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-full bg-pink-500/15 hover:bg-pink-500/25 text-pink-700 dark:text-pink-300 border border-pink-500/35 backdrop-blur-md px-4 py-2 text-xs font-semibold transition shadow-xs"
                            >
                                <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                </svg>
                                <span>Instagram</span>
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium {{ $isOpenNow ? 'bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-500/35' : 'bg-white/30 dark:bg-white/10 text-zinc-600 dark:text-zinc-400 border border-white/30' }} backdrop-blur-md">
                            <span class="h-1.5 w-1.5 rounded-full {{ $isOpenNow ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                            {{ $isOpenNow ? 'Buka Sekarang' : 'Tutup' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom Copyright Bar --}}
        <div class="mt-12 pt-8 border-t border-black/5 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-600 dark:text-zinc-400">
            <p>© {{ date('Y') }} {{ $restaurant->name }}. Hak Cipta Dilindungi.</p>
            <p>Didukung oleh Walk-in Resto.</p>
        </div>

    </div>
</footer>
