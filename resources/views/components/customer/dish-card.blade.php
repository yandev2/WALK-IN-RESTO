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
    'template' => 'classic',
    'rating' => null,
    'isBestSeller' => false,
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

    $showBestSeller = filter_var($isBestSeller ?? false, FILTER_VALIDATE_BOOLEAN)
        || filter_var($attributes->get('is-best-seller'), FILTER_VALIDATE_BOOLEAN)
        || filter_var($attributes->get('is_best_seller'), FILTER_VALIDATE_BOOLEAN);
@endphp

@if ($template === 'glassmorphism' || $template === 'glassmorp')
    {{-- True Glassmorphism iOS Card --}}
    <article
        {{ $attributes->merge(['class' => 'group flex h-full flex-col justify-between overflow-hidden rounded-[2.2rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 p-4 sm:p-5 shadow-[0_12px_40px_rgba(0,0,0,0.06),inset_0_1px_1px_rgba(255,255,255,0.7)] dark:shadow-[0_12px_40px_rgba(0,0,0,0.4),inset_0_1px_1px_rgba(255,255,255,0.15)] hover:shadow-2xl hover:border-primary/50 hover:bg-white/35 dark:hover:bg-white/10 transition-all duration-300 hover:-translate-y-2']) }}
        @if ($gallery->isNotEmpty())
            x-data="imagePreview(@js($previewImages))"
        @endif
    >
        <div>
            {{-- Image Box with Frosted Badges --}}
            <div class="relative aspect-4/3 w-full overflow-hidden rounded-2xl bg-white/30 dark:bg-white/5 border border-white/40 dark:border-white/10 shadow-inner">
                @if ($gallery->isNotEmpty())
                    @foreach ($gallery as $galleryIndex => $galleryPhoto)
                        <button
                            type="button"
                            class="absolute inset-0 cursor-zoom-in"
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
                                alt="{{ $name }}"
                                class="h-full w-full object-cover group-hover:scale-108 transition-transform duration-500"
                                loading="lazy"
                            >
                        </button>
                    @endforeach
                @else
                    <div class="flex h-full w-full flex-col items-center justify-center gap-2 bg-gradient-to-br from-primary/10 to-accent/20 p-4 text-center text-primary">
                        <svg class="h-8 w-8 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="font-bold text-xs text-zinc-800 dark:text-zinc-200 line-clamp-2">{{ $name }}</span>
                    </div>
                @endif

                {{-- Frosted Glass Category Pill Top Left --}}
                @if ($category)
                    <div class="absolute top-3 left-3 z-10 pointer-events-none">
                        <span class="inline-flex items-center rounded-full bg-black/45 dark:bg-black/60 backdrop-blur-xl px-3 py-1 text-[11px] font-semibold text-white border border-white/30 shadow-md">
                            {{ $category }}
                        </span>
                    </div>
                @endif

                {{-- Glass Red Discount Pill Top Right --}}
                @if ($discountPercent)
                    <div class="absolute top-3 right-3 z-10 pointer-events-none">
                        <span class="inline-flex items-center rounded-full bg-rose-500/90 backdrop-blur-xl px-3 py-1 text-[11px] font-extrabold text-white border border-white/30 shadow-md">
                            -{{ $discountPercent }}%
                        </span>
                    </div>
                @endif

                {{-- Frosted Glass Best Seller Badge Bottom Left --}}
                @if ($showBestSeller)
                    <div class="absolute bottom-3 left-3 z-10 pointer-events-none">
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/95 dark:bg-amber-500/90 backdrop-blur-xl px-3 py-1 text-[11px] font-extrabold text-white border border-white/40 shadow-lg">
                            <svg class="h-3 w-3 text-amber-100 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>Best Seller</span>
                        </span>
                    </div>
                @endif

                {{-- Gallery Thumbnails Bottom Right --}}
                @if ($gallery->count() > 1)
                    <div class="dish-card-thumbs absolute bottom-2 right-2 z-10 flex max-h-[72%] flex-col gap-1.5 overflow-y-auto">
                        @foreach ($gallery as $thumbIndex => $thumbPhoto)
                            <button
                                type="button"
                                class="h-8 w-8 shrink-0 overflow-hidden rounded-lg border-2 border-white/90 bg-white/50 backdrop-blur-md shadow-sm transition"
                                :class="index === {{ $thumbIndex }} ? 'border-primary ring-2 ring-primary/40' : ''"
                                aria-label="Foto {{ $thumbIndex + 1 }}"
                                @click.stop="openPreview({{ $thumbIndex }})"
                            >
                                <img src="{{ $thumbPhoto }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info Block --}}
            <div class="mt-4">
                <h3 class="font-display font-bold text-base sm:text-lg text-zinc-900 dark:text-white group-hover:text-primary transition-colors line-clamp-1">
                    {{ $name }}
                </h3>

                @if (filled($description))
                    <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-300 line-clamp-2 leading-relaxed">
                        {{ $description }}
                    </p>
                @endif

                <div class="mt-2.5 flex items-center gap-1 text-xs text-amber-400">
                    <div class="flex">
                        @for ($s = 0; $s < 5; $s++)
                            <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="font-bold text-zinc-800 dark:text-zinc-200 ml-1">{{ $rating ?: '5.0' }}</span>
                </div>
            </div>
        </div>

        {{-- Price & iOS Order Pill --}}
        <div class="mt-5 pt-3.5 border-t border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div class="flex flex-col">
                @if ($discountPercent && $originalPrice)
                    <span class="text-[11px] text-zinc-400 dark:text-zinc-400 line-through">
                        {{ $originalPrice }}
                    </span>
                @endif
                <span class="font-display font-extrabold text-base sm:text-lg text-zinc-900 dark:text-white">
                    {{ $price }}
                </span>
            </div>

            @if ($href)
                <a
                    href="{{ $href }}"
                    class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-primary to-accent hover:opacity-95 text-white px-5 py-2.5 text-xs font-bold shadow-lg shadow-primary/25 transition-all hover:scale-105 border border-white/25"
                >
                    Pesan
                </a>
            @else
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-primary to-accent hover:opacity-95 text-white px-5 py-2.5 text-xs font-bold shadow-lg shadow-primary/25 transition-all hover:scale-105 border border-white/25"
                    @if ($gallery->isNotEmpty())
                        @click="openPreview(0)"
                    @endif
                >
                    Pesan
                </button>
            @endif
        </div>

        @if ($slot->isNotEmpty())
            <div class="mt-4">{{ $slot }}</div>
        @endif

        @if ($gallery->isNotEmpty())
            <x-customer.image-preview-modal />
        @endif
    </article>
@elseif ($template === 'foodie')
    {{-- Foodie Design Card --}}
    <article
        {{ $attributes->merge(['class' => 'group flex h-full flex-col justify-between overflow-hidden rounded-3xl bg-surface-raised dark:bg-zinc-900/90 border border-border-subtle dark:border-zinc-800 p-4 shadow-xs hover:shadow-2xl hover:shadow-primary/10 hover:border-primary/50 hover:-translate-y-1.5 transition-all duration-300']) }}
        @if ($gallery->isNotEmpty())
            x-data="imagePreview(@js($previewImages))"
        @endif
    >
        <div>
            {{-- Foodie Image with Floating Badges --}}
            <div class="relative aspect-4/3 w-full overflow-hidden rounded-2xl bg-surface-muted shadow-inner">
                @if ($gallery->isNotEmpty())
                    @foreach ($gallery as $galleryIndex => $galleryPhoto)
                        <button
                            type="button"
                            class="absolute inset-0 cursor-zoom-in"
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
                                alt="{{ $name }}"
                                class="h-full w-full object-cover group-hover:scale-108 transition-transform duration-500 ease-out"
                                loading="lazy"
                            >
                        </button>
                    @endforeach
                @else
                    <div class="flex h-full w-full flex-col items-center justify-center gap-2 bg-gradient-to-br from-primary/10 to-accent/20 p-4 text-center text-primary">
                        <svg class="h-8 w-8 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="font-bold text-xs text-body line-clamp-2">{{ $name }}</span>
                    </div>
                @endif

                {{-- Floating Category Badge Top Left --}}
                @if ($category)
                    <div class="absolute top-3 left-3 z-10 pointer-events-none">
                        <span class="inline-flex items-center rounded-lg bg-black/60 backdrop-blur-md px-2.5 py-1 text-[11px] font-semibold text-white shadow-xs">
                            {{ $category }}
                        </span>
                    </div>
                @endif

                {{-- Floating Discount Badge Top Right --}}
                @if ($discountPercent)
                    <div class="absolute top-3 right-3 z-10 pointer-events-none">
                        <span class="inline-flex items-center rounded-lg bg-red-600 px-2.5 py-1 text-[11px] font-extrabold text-white shadow-md">
                            -{{ $discountPercent }}%
                        </span>
                    </div>
                @endif

                {{-- Floating Best Seller Badge Bottom Left --}}
                @if ($showBestSeller)
                    <div class="absolute bottom-3 left-3 z-10 pointer-events-none">
                        <span class="inline-flex items-center gap-1 rounded-lg bg-amber-500 px-2.5 py-1 text-[11px] font-extrabold text-white shadow-md">
                            <svg class="h-3 w-3 text-white fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>Best Seller</span>
                        </span>
                    </div>
                @endif

                {{-- Multiple Photo Thumbnails Bottom Right --}}
                @if ($gallery->count() > 1)
                    <div class="dish-card-thumbs absolute bottom-2 right-2 z-10 flex max-h-[72%] flex-col gap-1.5 overflow-y-auto">
                        @foreach ($gallery as $thumbIndex => $thumbPhoto)
                            <button
                                type="button"
                                class="h-8 w-8 shrink-0 overflow-hidden rounded-md border-2 border-white/90 bg-surface-muted shadow-sm transition-all duration-200 hover:scale-110"
                                :class="index === {{ $thumbIndex }} ? 'border-primary ring-1 ring-primary' : 'hover:border-primary'"
                                aria-label="Foto {{ $thumbIndex + 1 }}"
                                @click.stop="openPreview({{ $thumbIndex }})"
                            >
                                <img src="{{ $thumbPhoto }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Foodie Content --}}
            <div class="mt-4">
                <h3 class="font-display font-bold text-base sm:text-lg text-body group-hover:text-primary transition-colors duration-200 line-clamp-1">
                    {{ $name }}
                </h3>

                @if (filled($description))
                    <p class="mt-1 text-xs text-muted line-clamp-2 leading-relaxed">
                        {{ $description }}
                    </p>
                @endif

                {{-- Star Ratings --}}
                <div class="mt-2.5 flex items-center gap-1 text-xs text-amber-400">
                    <div class="flex">
                        @for ($s = 0; $s < 5; $s++)
                            <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="font-bold text-body ml-1">{{ $rating ?: '5.0' }}</span>
                </div>
            </div>
        </div>

        {{-- Foodie Price & Action Row --}}
        <div class="mt-5 pt-3.5 border-t border-border-subtle dark:border-zinc-800 flex items-center justify-between gap-3">
            <div class="flex flex-col">
                @if ($discountPercent && $originalPrice)
                    <span class="text-[11px] text-muted line-through">
                        {{ $originalPrice }}
                    </span>
                @endif
                <span class="font-display font-extrabold text-base sm:text-lg text-body">
                    {{ $price }}
                </span>
            </div>

            @if ($href)
                <a
                    href="{{ $href }}"
                    class="inline-flex items-center justify-center rounded-full bg-linear-to-r from-primary to-accent hover:opacity-95 px-4 py-2 text-xs font-bold text-white shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/30 hover:scale-108 active:scale-95 transition-all duration-200 border border-white/20"
                >
                    Pesan
                </a>
            @else
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full bg-linear-to-r from-primary to-accent hover:opacity-95 px-4 py-2 text-xs font-bold text-white shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/30 hover:scale-108 active:scale-95 transition-all duration-200 border border-white/20"
                    @if ($gallery->isNotEmpty())
                        @click="openPreview(0)"
                    @endif
                >
                    Pesan
                </button>
            @endif
        </div>

        @if ($slot->isNotEmpty())
            <div class="mt-4">{{ $slot }}</div>
        @endif

        @if ($gallery->isNotEmpty())
            <x-customer.image-preview-modal />
        @endif
    </article>
@else
    {{-- Classic Design Card --}}
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

            {{-- Top Left Discount Badge --}}
            @if ($discountPercent)
                <div class="pointer-events-none absolute left-3 top-3 z-10">
                    <span class="rounded-full bg-rose-500 px-2.5 py-0.5 text-xs font-bold text-white shadow-sm">-{{ $discountPercent }}%</span>
                </div>
            @endif

            {{-- Bottom Left Best Seller Badge --}}
            @if ($showBestSeller)
                <div class="pointer-events-none absolute left-3 bottom-3 z-10">
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-500 px-2.5 py-0.5 text-xs font-bold text-white shadow-md">
                        <svg class="h-3 w-3 text-white fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Best Seller</span>
                    </span>
                </div>
            @endif

            {{-- Gallery Thumbnails Bottom Right --}}
            @if ($gallery->count() > 1)
                <div class="dish-card-thumbs absolute bottom-2 right-2 z-10 flex max-h-[72%] flex-col gap-1.5 overflow-y-auto">
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
@endif
