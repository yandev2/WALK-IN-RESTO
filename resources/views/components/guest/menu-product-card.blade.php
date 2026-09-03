@props([
    'item',
    'outOfStock' => false,
])

@php
    use App\Support\CmsMedia;

    $photo = CmsMedia::url($item->photo_path);
    if (! $photo && $item->photos->isNotEmpty()) {
        $photo = CmsMedia::url($item->photos->first()?->photo_path);
    }
@endphp

<article {{ $attributes->merge(['class' => 'group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-surface-raised dark:bg-zinc-900/90 p-2.5 sm:p-3 shadow-xs border border-border-subtle/70 dark:border-white/5 transition-all duration-300 hover:shadow-md']) }}>
    <div>
        {{-- Image Container --}}
        <div class="relative aspect-square w-full overflow-hidden rounded-2xl bg-surface-muted dark:bg-zinc-800/70">
            @if ($photo)
                <img
                    src="{{ $photo }}"
                    alt="{{ $item->name }}"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    loading="lazy"
                >
            @else
                <div class="flex h-full w-full items-center justify-center text-muted/40">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            {{-- Top Left Discount Badge --}}
            @if ($item->hasDiscount())
                <div class="pointer-events-none absolute left-2 top-2 z-10">
                    <span class="inline-flex items-center rounded-full bg-rose-500/95 backdrop-blur-md px-2 py-0.5 text-[10px] font-extrabold text-white shadow-sm border border-white/20">
                        -{{ (int) $item->discount_percent }}%
                    </span>
                </div>
            @endif

            {{-- Top Right Bookmark / Favorite Button --}}
            <button
                type="button"
                @click.stop="toggleFavorite({{ $item->id }})"
                :class="isFavorite({{ $item->id }}) ? 'text-rose-500 bg-white dark:bg-zinc-900 shadow-sm' : 'text-zinc-600 dark:text-zinc-300 bg-white/80 dark:bg-black/50 backdrop-blur-md border border-black/5 dark:border-white/10 hover:scale-110'"
                class="absolute right-2 top-2 z-10 flex h-8 w-8 items-center justify-center rounded-xl shadow-xs transition duration-200"
                aria-label="Favorit"
            >
                <svg class="h-4 w-4" :fill="isFavorite({{ $item->id }}) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"/>
                </svg>
            </button>

            {{-- Bottom Left Best Seller Badge --}}
            @if ($item->is_best_seller)
                <div class="pointer-events-none absolute bottom-2 left-2 z-10">
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/95 backdrop-blur-md px-2 py-0.5 text-[10px] font-extrabold text-white shadow-sm border border-white/20">
                        <svg class="h-2.5 w-2.5 text-amber-100 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Best Seller</span>
                    </span>
                </div>
            @endif
        </div>

        {{-- Dish Info --}}
        <div class="mt-2.5 px-0.5">
            <h3 class="line-clamp-2 text-sm font-bold leading-snug text-body dark:text-white">
                {{ $item->name }}
            </h3>
            @if ($item->category)
                <div class="mt-1 flex items-center gap-1 text-[11px] font-medium text-muted dark:text-zinc-400">
                    <span class="line-clamp-1">{{ $item->category->name }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Price and Action Row --}}
    <div class="mt-3 flex items-center justify-between gap-1.5 border-t border-border-subtle/40 dark:border-white/5 px-0.5 pt-2">
        <div class="min-w-0 flex items-baseline gap-1.5 flex-wrap">
            <span class="text-sm font-extrabold text-primary">
                {{ CmsMedia::formatIdr($item->effectivePrice()) }}
            </span>
            @if ($item->hasDiscount())
                <span class="text-[10px] text-muted line-through">
                    {{ CmsMedia::formatIdr((int) $item->price) }}
                </span>
            @endif
        </div>

        @if ($outOfStock)
            <span class="shrink-0 rounded-full bg-surface-muted dark:bg-zinc-800 px-2 py-1 text-[10px] font-bold text-muted dark:text-zinc-400">
                Habis
            </span>
        @else
            <button
                type="button"
                wire:click="openPicker({{ $item->id }})"
                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-surface-muted dark:bg-zinc-800/90 text-body dark:text-white border border-border-subtle dark:border-zinc-700/80 shadow-xs transition duration-200 hover:bg-primary hover:text-white hover:border-primary active:scale-95"
                aria-label="Tambah {{ $item->name }}"
            >
                <svg class="h-4 w-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
        @endif
    </div>
</article>
