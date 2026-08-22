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

<article {{ $attributes->merge(['class' => 'group relative flex flex-col overflow-hidden rounded-2xl bg-surface-raised shadow-sm ring-1 ring-border-subtle/60 transition hover:shadow-md']) }}>
    <div class="relative aspect-[4/3] overflow-hidden bg-surface-muted">
        @if ($photo)
            <img
                src="{{ $photo }}"
                alt=""
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            >
        @else
            <div class="flex h-full w-full items-center justify-center text-muted/40">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <button
            type="button"
            @click="toggleFavorite({{ $item->id }})"
            :class="isFavorite({{ $item->id }}) ? 'text-primary' : 'text-muted/70'"
            class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 shadow-sm backdrop-blur transition hover:scale-105"
            aria-label="Favorit"
        >
            <svg class="h-4 w-4" :fill="isFavorite({{ $item->id }}) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
            </svg>
        </button>
    </div>

    <div class="flex flex-1 flex-col p-3">
        <p class="line-clamp-1 text-sm font-bold text-body">{{ $item->name }}</p>
        @if ($item->category)
            <p class="mt-0.5 line-clamp-1 text-xs text-muted">{{ $item->category->name }}</p>
        @endif
        <div class="mt-auto flex items-end justify-between gap-2 pt-2">
            <x-customer.menu-price :item="$item" />
            @if ($outOfStock)
                <span class="shrink-0 text-xs font-semibold text-muted">Habis</span>
            @else
                <button
                    type="button"
                    wire:click="openPicker({{ $item->id }})"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary text-lg font-bold text-white shadow-sm shadow-primary/30 hover:bg-primary-dark"
                    aria-label="Tambah {{ $item->name }}"
                >
                    +
                </button>
            @endif
        </div>
    </div>
</article>
