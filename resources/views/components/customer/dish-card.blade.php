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
    {{ $attributes->merge(['class' => 'customer-card landing-card-hover group relative flex h-full flex-col overflow-hidden text-left']) }}
    @if ($gallery->isNotEmpty())
        x-data="imagePreview(@js($previewImages))"
    @endif
>
    <div
        @class([
            'relative shrink-0 overflow-hidden bg-surface-muted',
            'mx-auto mt-5 h-28 w-28 rounded-full' => $circular,
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
                            'h-full w-full object-cover landing-interactive group-hover:scale-105',
                            'rounded-full' => $circular,
                        ])
                    >
                </button>
            @endforeach
        @else
            <div class="flex h-full min-h-[8rem] items-center justify-center text-muted/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        @if ($discountPercent)
            <span class="pointer-events-none absolute left-3 top-3 z-10 customer-pill bg-red-500 text-white shadow-sm">-{{ $discountPercent }}%</span>
        @endif

        @if ($gallery->count() > 1)
            <div class="dish-card-thumbs absolute bottom-2 left-2 z-10 flex max-h-[72%] flex-col gap-1.5 overflow-y-auto">
                @foreach ($gallery as $thumbIndex => $thumbPhoto)
                    <button
                        type="button"
                        class="h-9 w-9 shrink-0 overflow-hidden rounded-md border-2 border-white/90 bg-surface-muted shadow-sm transition sm:h-10 sm:w-10"
                        :class="index === {{ $thumbIndex }} ? 'border-primary ring-1 ring-primary' : ''"
                        aria-label="Foto {{ $thumbIndex + 1 }}"
                        @click.stop="openPreview({{ $thumbIndex }})"
                    >
                        <img src="{{ $thumbPhoto }}" alt="" class="h-full w-full object-cover">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        @if ($category)
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">{{ $category }}</p>
        @endif
        <h3 class="mt-1 font-display text-lg font-bold text-body">{{ $name }}</h3>
        @if ($description)
            <p class="mt-2 line-clamp-2 flex-1 text-sm leading-relaxed text-muted">{{ $description }}</p>
        @else
            <div class="flex-1"></div>
        @endif

        <div class="mt-auto pt-3 flex items-end justify-between gap-3">
            <div>
                @if ($discountPercent && $originalPrice)
                    <p class="text-xs text-muted line-through">{{ $originalPrice }}</p>
                @endif
                @if ($price)
                    <p class="text-base font-bold text-primary">{{ $price }}</p>
                @endif
            </div>
            @if ($href)
                <a
                    href="{{ $href }}"
                    class="inline-flex rounded-full bg-primary/10 px-3 py-1.5 text-xs font-bold text-primary transition hover:bg-primary hover:text-white"
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
