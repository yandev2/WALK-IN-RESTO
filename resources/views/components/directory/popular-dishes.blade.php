@props([
    'items' => [],
    'hasFilters' => false,
])

<div class="popular-dishes-container">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-2">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-primary">Menu Populer</span>
            <h2 class="text-lg font-bold text-body font-display sm:text-xl">Yang Lagi Hits</h2>
        </div>

        @if (Route::has('directory.menus'))
            <a
                href="{{ route('directory.menus') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:text-primary-hover transition group/btn"
                id="link-all-menus"
            >
                <span>Lihat Semua Menu</span>
                <svg class="h-3.5 w-3.5 transition group-hover/btn:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        @endif
    </div>

    @if (! empty($items))
        <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
            @foreach ($items as $item)
                @php
                    $targetUrl = $item['landing_url'] ?: '#';
                @endphp
                <a
                    href="{{ $targetUrl }}"
                    class="group flex flex-col justify-between overflow-hidden rounded-2xl bg-surface-raised p-2.5 ring-1 ring-[color:var(--border-subtle)] shadow-xs hover:shadow-md hover:ring-primary/40 transition duration-200"
                    title="Kunjungi {{ $item['restaurant_name'] ?? 'Restoran' }}"
                >
                    <div class="relative aspect-square overflow-hidden rounded-xl bg-surface-muted">
                        @if (! empty($item['photo_url']))
                            <img
                                src="{{ $item['photo_url'] }}"
                                alt="{{ $item['name'] }}"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                loading="lazy"
                                onerror="this.onerror=null;this.classList.add('hidden');if(this.nextElementSibling){this.nextElementSibling.classList.remove('hidden');}"
                            >
                            <div class="hidden flex h-full w-full items-center justify-center bg-surface-muted text-muted/30">
                                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                        @else
                            <div class="flex h-full w-full items-center justify-center text-muted/30">
                                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                        @endif

                        @if (! empty($item['discount_percent']) && (int) $item['discount_percent'] > 0)
                            <span class="absolute top-2 left-2 rounded-lg bg-rose-500 px-2 py-0.5 text-[10px] font-bold text-white shadow-xs">
                                -{{ $item['discount_percent'] }}%
                            </span>
                        @endif

                        @if (! empty($item['distance_label']))
                            <span class="absolute bottom-2 left-2 rounded-md bg-surface-base/90 backdrop-blur-xs px-1.5 py-0.5 text-[10px] font-semibold text-body ring-1 ring-border-subtle shadow-xs">
                                {{ $item['distance_label'] }}
                            </span>
                        @endif
                    </div>

                    <div class="mt-2.5 flex flex-1 flex-col justify-between">
                        <div>
                            @if (! empty($item['restaurant_name']))
                                <p class="truncate text-[11px] font-medium text-muted">{{ $item['restaurant_name'] }}</p>
                            @endif
                            <h3 class="truncate font-display text-xs font-bold text-body sm:text-sm group-hover:text-primary transition-colors">
                                {{ $item['name'] }}
                            </h3>
                        </div>

                        <div class="mt-2 flex items-center justify-between border-t border-border-subtle/50 pt-2">
                            <span class="text-xs font-bold text-primary sm:text-sm">{{ $item['price_formatted'] }}</span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-muted group-hover:text-primary transition">
                                <span>Resto</span>
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @elseif ($hasFilters)
        <div class="rounded-2xl border border-dashed border-border-subtle bg-surface-raised/50 p-6 text-center">
            <p class="text-sm font-medium text-body">Belum ada menu hits yang cocok dengan filter aktif.</p>
            <p class="mt-1 text-xs text-muted">Coba ubah filter kategori atau perluas jarak pencarian.</p>
            @if (Route::has('directory.menus'))
                <a
                    href="{{ route('directory.menus') }}"
                    class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-primary/10 px-4 py-1.5 text-xs font-semibold text-primary hover:bg-primary/20 transition"
                >
                    Jelajahi Semua Menu
                </a>
            @endif
        </div>
    @endif
</div>
