@php
    use App\Support\CmsMedia;
    $menuCopy = $layout->copyFor('menu');
    $title = $menuCopy['title'] ?? 'Hidangan populer hari ini';
    $label = $menuCopy['label'] ?? 'Menu Pilihan';
    $subtitle = $menuCopy['subtitle'] ?? 'Pesan lengkap dari HP setelah scan QR meja.';
    $categories = $menuCategories ?? collect();
    $avgRating = $ratingSummary['average'] ? number_format($ratingSummary['average'], 1) : '5.0';
@endphp

<section id="menu" class="scroll-mt-20 py-16 sm:py-24 bg-surface-base border-y border-border-subtle dark:border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
            @if (filled($label))
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-linear-to-r from-primary/15 to-accent/15 px-3.5 py-1.5 rounded-full border border-primary/25 shadow-2xs hover:scale-105 hover:shadow-xs transition-all duration-300 cursor-default">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                    <span>{{ $label }}</span>
                </span>
            @endif
            <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-body tracking-tight">
                {{ $title }}
            </h2>
            @if (filled($subtitle))
                <p class="mt-3 text-sm sm:text-base text-muted leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div x-data="{ activeCategory: 'all' }">
            {{-- Category Filter Tabs --}}
            @if ($categories->isNotEmpty())
                <div class="mb-10 flex items-center justify-start sm:justify-center gap-2.5 overflow-x-auto pb-2 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <button
                        type="button"
                        class="shrink-0 rounded-full px-5 py-2 text-xs sm:text-sm font-semibold transition-all duration-200 hover:scale-105 active:scale-95"
                        :class="activeCategory === 'all'
                            ? 'bg-linear-to-r from-primary to-accent text-white shadow-md shadow-primary/25 scale-105'
                            : 'bg-surface-raised dark:bg-zinc-900 text-muted hover:text-body hover:border-primary/40 border border-border-subtle dark:border-zinc-800 shadow-2xs'"
                        @click="activeCategory = 'all'"
                    >
                        Semua Menu
                    </button>
                    @foreach ($categories as $cat)
                        <button
                            type="button"
                            class="shrink-0 rounded-full px-5 py-2 text-xs sm:text-sm font-semibold transition-all duration-200 hover:scale-105 active:scale-95"
                            :class="activeCategory === '{{ $cat->id }}'
                                ? 'bg-linear-to-r from-primary to-accent text-white shadow-md shadow-primary/25 scale-105'
                                : 'bg-surface-raised dark:bg-zinc-900 text-muted hover:text-body hover:border-primary/40 border border-border-subtle dark:border-zinc-800 shadow-2xs'"
                            @click="activeCategory = '{{ $cat->id }}'"
                        >
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Menu Grid from Real Database Items using Foodie Dish Card --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($menuItems as $item)
                    <div
                        class="h-full"
                        @if ($categories->isNotEmpty())
                            x-show="activeCategory === 'all' || activeCategory === '{{ $item->category_id }}'"
                            x-transition
                        @endif
                    >
                        <x-customer.dish-card
                            class="h-full"
                            :name="$item->name"
                            :description="$item->description"
                            :photos="$item->photoUrls()"
                            :category="$item->category?->name"
                            :price="CmsMedia::formatIdr($item->effectivePrice())"
                            :original-price="$item->hasDiscount() ? CmsMedia::formatIdr($item->price) : null"
                            :discount-percent="$item->hasDiscount() ? $item->discount_percent : null"
                            :is-best-seller="(bool) $item->is_best_seller"
                            :hide-price="(bool) ($outlet?->hide_landing_menu_prices ?? false)"
                            template="foodie"
                            :rating="$avgRating"
                            :href="route('landing.menu', $restaurant)"
                        />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- View All Menu CTA --}}
        <div class="mt-12 text-center">
            <a
                href="{{ route('landing.menu', $restaurant) }}"
                class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-primary text-primary hover:bg-linear-to-r hover:from-primary hover:to-accent hover:text-white hover:border-transparent px-8 py-3.5 text-sm font-bold shadow-xs hover:shadow-xl hover:shadow-primary/25 transition-all duration-300 hover:scale-105 active:scale-95 group"
            >
                <span>{{ $menuCopy['button'] ?? 'Lihat semua daftar menu' }}</span>
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

    </div>
</section>
