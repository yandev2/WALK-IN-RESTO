@props([
    'card',
    'variant' => 'grid',
])

<article
    itemscope
    itemtype="https://schema.org/Restaurant"
    class="relative h-full"
>
    <a
        href="{{ $card['landing_url'] }}"
        itemprop="url"
        aria-label="Lihat detail restoran {{ $card['name'] }}"
        @class([
            'directory-card group relative flex h-full flex-col overflow-hidden rounded-[1.35rem] bg-surface-raised ring-1 ring-[color:var(--border-subtle)] shadow-sm hover:shadow-md transition-all duration-300',
            'directory-card-grid' => $variant === 'grid',
        ])
    >
        <div class="directory-card-media relative overflow-hidden bg-surface-muted">
            @if ($card['cover_url'])
                <img
                    src="{{ $card['cover_url'] }}"
                    alt="{{ $card['name'] }} - Foto Restoran"
                    itemprop="image"
                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    loading="lazy"
                >
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-surface-muted to-surface-section text-muted/35">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            <div class="absolute left-2.5 top-2.5 z-10 flex items-center gap-1.5">
                <span @class([
                    'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold backdrop-blur-md shadow-sm',
                    'bg-emerald-500/90 text-white' => $card['is_open_now'],
                    'bg-zinc-800/85 text-zinc-300 dark:bg-zinc-900/90' => ! $card['is_open_now'],
                ])>
                    <span @class([
                        'h-1.5 w-1.5 rounded-full',
                        'bg-white animate-pulse' => $card['is_open_now'],
                        'bg-zinc-400' => ! $card['is_open_now'],
                    ])></span>
                    {{ $card['is_open_now'] ? 'Buka' : 'Tutup' }}
                </span>
            </div>

            @if ($card['price_label'])
                <div class="absolute right-2.5 top-2.5 z-10">
                    <span itemprop="priceRange" class="inline-flex rounded-full bg-black/60 px-2.5 py-0.5 text-[11px] font-bold text-white backdrop-blur-md shadow-sm">
                        {{ $card['price_label'] }}
                    </span>
                </div>
            @endif
        </div>

        <div class="directory-card-body flex min-w-0 flex-1 flex-col justify-between p-4">
            <div>
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        @if ($card['categories_label'])
                            <p itemprop="servesCuisine" class="truncate text-xs font-semibold text-primary mb-1">{{ $card['categories_label'] }}</p>
                        @endif
                        <h3 itemprop="name" class="truncate font-display text-base font-bold text-body sm:text-lg group-hover:text-primary transition-colors duration-200">{{ $card['name'] }}</h3>
                    </div>
                </div>

                @if ($card['headline'])
                    <p itemprop="description" class="mt-1.5 line-clamp-2 text-xs sm:text-sm leading-relaxed text-muted">{{ $card['headline'] }}</p>
                @endif
            </div>

            <div class="mt-3 flex items-center justify-between border-t border-border-subtle/60 pt-3 text-xs sm:text-sm">
                <div class="flex items-center gap-2">
                    @if ($card['rating_average'])
                        <span
                            itemprop="aggregateRating"
                            itemscope
                            itemtype="https://schema.org/AggregateRating"
                            class="inline-flex items-center gap-1 font-bold text-body"
                            aria-label="Rating {{ number_format($card['rating_average'], 1) }} dari 5 bintang ({{ $card['rating_count'] }} ulasan)"
                        >
                            <meta itemprop="ratingValue" content="{{ number_format($card['rating_average'], 1) }}">
                            <meta itemprop="bestRating" content="5">
                            <meta itemprop="ratingCount" content="{{ $card['rating_count'] ?: 1 }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500 fill-amber-500" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ number_format($card['rating_average'], 1) }}
                            <span class="font-normal text-muted">({{ $card['rating_count'] }})</span>
                        </span>
                    @else
                        <span class="text-xs text-muted">Belum ada ulasan</span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @if ($card['distance_label'])
                        <span class="inline-flex items-center gap-1 font-medium text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $card['distance_label'] }}
                        </span>
                    @endif

                    <span class="hidden items-center text-xs font-bold text-primary group-hover:translate-x-1 transition-transform sm:inline-flex" aria-hidden="true">
                        Buka →
                    </span>
                </div>
            </div>
        </div>
    </a>
</article>
