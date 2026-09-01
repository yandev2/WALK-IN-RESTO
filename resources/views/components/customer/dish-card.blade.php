@props([
    'name',
    'price' => null,
    'originalPrice' => null,
    'discountPercent' => null,
    'description' => null,
    'photo' => null,
    'photos' => [],
    'category' => null,
    'circular' => false,
    'href' => null,
])

@php
    $gallery = collect($photos)->filter()->values();

    if ($gallery->isEmpty() && filled($photo)) {
        $gallery = collect([$photo]);
    }

    $previewImages = $gallery
        ->map(fn ($src) => [
            'src' => $src,
            'caption' => (string) $name,
        ])
        ->all();
@endphp

<article
    {{ $attributes->merge(['class' => 'customer-card landing-card-hover group relative flex h-full flex-col overflow-hidden text-left shadow-sm hover:shadow-md transition-all duration-300 ring-1 ring-[color:var(--border-subtle)]']) }}
    @if ($gallery->isNotEmpty())
        x-data="imagePreview(@js($previewImages))"
    @endif
>
    <div
        @class([
            'relative shrink-0 overflow-hidden bg-surface-muted',
            'mx-auto mt-5 h-28 w-28 rounded-full ring-2 ring-primary/20' => $circular,
            'aspect-[4/3] w-full' => ! $circular,
        ])
    >
        @if ($gallery->isNotEmpty())
            @foreach ($gallery as $galleryIndex => $galleryPhoto)
                <button
                    type="button"
                    @class([
                        'absolute inset-0 cursor-zoom-in',
                        'rounded-full' => $circular,
                    ])
                    @if ($gallery->count() > 1)
                        @if ($galleryIndex > 0)
                            x-cloak
                        @endif
                        x-show="index === {{ $galleryIndex }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    @endif
                    aria-label="Perbesar foto {{ $name }}"
                    @click="openPreview({{ $galleryIndex }})"
                >
                    <img
                        src="{{ $galleryPhoto }}"
                        alt=""
                        @class([
                            'h-full w-full object-cover transition duration-500 group-hover:scale-105',
                            'rounded-full' => $circular,
                        ])
                    >
                </button>
            @endforeach
        @else
            <div class="flex h-full min-h-[8rem] items-center justify-center bg-gradient-to-br from-surface-muted to-surface-section text-muted/35">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        @if ($discountPercent)
            <span class="pointer-events-none absolute left-3 top-3 z-10 rounded-full bg-rose-500 px-2.5 py-0.5 text-xs font-bold text-white shadow-sm">-{{ $discountPercent }}%</span>
        @endif

        @if ($gallery->count() > 1)
            <div class="absolute bottom-2.5 right-2.5 z-10 flex items-center gap-1 rounded-full bg-black/60 px-2 py-0.5 text-[11px] font-medium text-white backdrop-blur-md shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $gallery->count() }} foto</span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        @if ($category)
            <p class="text-xs font-bold uppercase tracking-wider text-primary mb-1">{{ $category }}</p>
        @endif
        <h3 class="font-display text-base sm:text-lg font-bold text-body group-hover:text-primary transition-colors duration-200">{{ $name }}</h3>
        @if ($description)
            <p class="mt-1.5 line-clamp-2 flex-1 text-xs sm:text-sm leading-relaxed text-muted">{{ $description }}</p>
        @else
            <div class="flex-1"></div>
        @endif

        <div class="mt-auto pt-3 flex items-end justify-between gap-3 border-t border-border-subtle/60">
            <div>
                @if ($discountPercent && $originalPrice)
                    <p class="text-xs text-muted/80 line-through">{{ $originalPrice }}</p>
                @endif
                @if ($price)
                    <p class="text-base font-bold text-primary">{{ $price }}</p>
                @endif
            </div>
            @if ($href)
                <a
                    href="{{ $href }}"
                    class="inline-flex rounded-full bg-primary/10 px-3.5 py-1.5 text-xs font-bold text-primary transition hover:bg-primary hover:text-white shadow-sm"
                >
                    Lihat
                </a>
            @endif
        </div>

        @if ($slot->isNotEmpty())
            <div class="mt-4">{{ $slot }}</div>
        @endif
    </div>

    @if ($gallery->isNotEmpty())
        <x-customer.image-preview-modal />
    @endif
</article>
