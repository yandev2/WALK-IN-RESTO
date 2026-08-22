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
@endphp

<article {{ $attributes->merge(['class' => 'customer-card landing-card-hover group relative flex h-full flex-col overflow-hidden text-left']) }}>
    <div
        @class([
            'relative shrink-0 overflow-hidden bg-surface-muted',
            'mx-auto mt-5 h-28 w-28 rounded-full' => $circular,
            'aspect-[4/3] w-full' => ! $circular,
        ])
        @if ($gallery->count() > 1)
            x-data="{ index: 0, total: {{ $gallery->count() }} }"
        @endif
    >
        @if ($gallery->isNotEmpty())
            @foreach ($gallery as $galleryIndex => $galleryPhoto)
                <img
                    src="{{ $galleryPhoto }}"
                    alt=""
                    @class([
                        'object-cover landing-interactive group-hover:scale-105',
                        'absolute inset-0 h-full w-full rounded-full' => $circular,
                        'absolute inset-0 h-full w-full' => ! $circular,
                    ])
                    @if ($gallery->count() > 1)
                        x-show="index === {{ $galleryIndex }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    @endif
                >
            @endforeach

            @if ($gallery->count() > 1)
                <div class="absolute inset-x-0 bottom-2 flex justify-center gap-1.5">
                    @foreach ($gallery as $galleryIndex => $galleryPhoto)
                        <button
                            type="button"
                            @click="index = {{ $galleryIndex }}"
                            :class="index === {{ $galleryIndex }} ? 'bg-white' : 'bg-white/50'"
                            class="h-1.5 w-1.5 rounded-full"
                            aria-label="Foto {{ $galleryIndex + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @endif
        @else
            <div class="flex h-full min-h-[8rem] items-center justify-center text-muted/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        @if ($discountPercent)
            <span class="absolute left-3 top-3 customer-pill bg-red-500 text-white shadow-sm">-{{ $discountPercent }}%</span>
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
</article>
