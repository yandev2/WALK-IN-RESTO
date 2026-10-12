@props([
    'items' => [],
    'hasFilters' => false,
])

<div class="popular-dishes-container">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-2.5 sm:gap-3">
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-primary">Menu Populer</span>
            <h2 class="text-lg font-bold text-body font-display sm:text-xl">Yang Lagi Hits</h2>
        </div>

        @if (Route::has('directory.menus'))
            <a
                href="{{ route('directory.menus') }}"
                class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:text-primary-hover transition group/btn"
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
                <button
                    type="button"
                    @click="$dispatch('open-menu-modal', @js($item))"
                    class="group flex min-w-0 flex-col justify-between overflow-hidden rounded-2xl bg-surface-raised p-2.5 text-left ring-1 ring-[color:var(--border-subtle)] shadow-xs hover:shadow-md hover:ring-primary/40 transition duration-200 cursor-pointer"
                    title="Lihat detail {{ $item['name'] }}"
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

                        @if (empty($item['hide_price']) && ! empty($item['discount_percent']) && (int) $item['discount_percent'] > 0)
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

                    <div class="mt-2.5 flex min-w-0 flex-1 flex-col justify-between">
                        <div class="min-w-0">
                            @if (! empty($item['restaurant_name']))
                                <p class="truncate text-[11px] font-medium text-muted">{{ $item['restaurant_name'] }}</p>
                            @endif
                            <h3 class="truncate font-display text-xs font-bold text-body sm:text-sm group-hover:text-primary transition-colors">
                                {{ $item['name'] }}
                            </h3>
                            @if (! empty($item['description']))
                                <p class="mt-1 line-clamp-2 text-[11px] text-muted leading-relaxed">
                                    {{ $item['description'] }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-2 flex items-center justify-between gap-1 border-t border-border-subtle/50 pt-2 min-w-0">
                            @if (! empty($item['hide_price']))
                                <span class="truncate text-[11px] font-semibold text-primary">Kunjungi Restoran</span>
                            @elseif (! empty($item['has_discount']) && ! empty($item['effective_price_formatted']))
                                <div class="min-w-0 truncate">
                                    <span class="text-[10px] text-muted line-through mr-1">{{ $item['price_formatted'] }}</span>
                                    <span class="text-xs sm:text-sm font-bold text-primary">{{ $item['effective_price_formatted'] }}</span>
                                </div>
                            @else
                                <span class="truncate text-xs font-bold text-primary sm:text-sm">{{ $item['price_formatted'] }}</span>
                            @endif
                            <span class="shrink-0 inline-flex items-center gap-0.5 sm:gap-1 text-[10px] sm:text-[11px] font-semibold text-muted group-hover:text-primary transition">
                                <span>Detail</span>
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </button>
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

    {{-- Reusable Modal Detail Menu --}}
    <x-directory.menu-detail-modal />
</div>
