@props([
    'card',
    'variant' => 'grid',
])

<a
    href="{{ $card['landing_url'] }}"
    @class([
        'directory-card group overflow-hidden rounded-[1.35rem] bg-surface-raised ring-1 ring-[color:var(--border-subtle)] transition hover:-translate-y-0.5 hover:shadow-[var(--card-shadow-hover)]',
        'directory-card-grid' => $variant === 'grid',
    ])
>
    <div class="directory-card-media relative bg-surface-muted">
        @if ($card['cover_url'])
            <img src="{{ $card['cover_url'] }}" alt="" class="transition duration-300 group-hover:scale-105" loading="lazy">
        @else
            <div class="flex h-full items-center justify-center text-muted/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>

    <div class="directory-card-body flex min-w-0 flex-col p-3.5 sm:p-4">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <h3 class="truncate text-base font-bold text-body sm:text-lg">{{ $card['name'] }}</h3>
                <p class="mt-0.5 truncate text-xs text-muted">{{ $card['categories_label'] ?: ' ' }}</p>
            </div>
            <span class="shrink-0 text-sm font-semibold text-muted">{{ $card['price_label'] ?: ' ' }}</span>
        </div>

        <p class="mt-1.5 line-clamp-2 text-sm leading-5 text-muted">{{ $card['headline'] ?: ' ' }}</p>

        <div class="mt-auto flex min-w-0 items-center gap-2 overflow-hidden pt-2 text-xs sm:text-sm">
            @if ($card['rating_average'])
                <span class="inline-flex shrink-0 items-center gap-1 font-semibold text-body">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#e4b343]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ number_format($card['rating_average'], 1) }}
                    <span class="font-normal text-muted">({{ $card['rating_count'] }})</span>
                </span>
            @endif

            <span @class([
                'inline-flex shrink-0 truncate rounded-full px-2.5 py-1 text-xs font-semibold',
                'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' => $card['is_open_now'],
                'bg-zinc-500/10 text-muted' => ! $card['is_open_now'],
            ])>
                {{ $card['is_open_now'] ? 'Buka' : 'Tutup' }}
            </span>

            <span class="ml-auto shrink-0 text-muted">
                @if ($card['distance_label'])
                    {{ $card['distance_label'] }}
                @endif
            </span>
        </div>
    </div>
</a>
