@php
    use App\Support\CmsMedia;
    $menuCopy = $layout->copyFor('menu');
    $title = $menuCopy['title'] ?? 'Hidangan populer hari ini';
    $label = $menuCopy['label'] ?? 'Menu Pilihan';
    $subtitle = $menuCopy['subtitle'] ?? 'Pesan lengkap dari HP setelah scan QR meja.';
    $categories = $menuCategories ?? collect();
    $avgRating = $ratingSummary['average'] ? number_format($ratingSummary['average'], 1) : '5.0';
@endphp

<section id="menu" class="scroll-mt-24 py-8 sm:py-12 relative">
    
    {{-- Ambient Light Orb Behind Menu --}}
    <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[500px] w-[500px] rounded-full bg-primary/20 blur-[130px] -z-10 opacity-70"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Glassmorphism Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                <span>{{ $label }}</span>
            </span>
            <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                {{ $title }}
            </h2>
            @if (filled($subtitle))
                <p class="mt-3 text-sm sm:text-base text-zinc-700 dark:text-zinc-300 leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div x-data="{ activeCategory: 'all' }">
            {{-- iOS Frosted Segment Control --}}
            @if ($categories->isNotEmpty())
                <div class="mb-10 flex items-center justify-start sm:justify-center gap-2 overflow-x-auto p-1.5 rounded-full bg-white/30 dark:bg-white/5 backdrop-blur-2xl border border-white/40 dark:border-white/10 w-fit mx-auto max-w-full [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden shadow-inner">
                    <button
                        type="button"
                        class="shrink-0 rounded-full px-5 py-2 text-xs sm:text-sm font-semibold transition-all duration-300"
                        :class="activeCategory === 'all'
                            ? 'bg-gradient-to-r from-primary to-accent text-white shadow-lg shadow-primary/30 scale-102 border border-white/30'
                            : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white hover:bg-white/40 dark:hover:bg-white/10'"
                        @click="activeCategory = 'all'"
                    >
                        Semua Menu
                    </button>
                    @foreach ($categories as $cat)
                        <button
                            type="button"
                            class="shrink-0 rounded-full px-5 py-2 text-xs sm:text-sm font-semibold transition-all duration-300"
                            :class="activeCategory === '{{ $cat->id }}'
                                ? 'bg-gradient-to-r from-primary to-accent text-white shadow-lg shadow-primary/30 scale-102 border border-white/30'
                                : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white hover:bg-white/40 dark:hover:bg-white/10'"
                            @click="activeCategory = '{{ $cat->id }}'"
                        >
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Glass Dish Cards Grid --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($menuItems as $item)
                    <div
                        class="h-full"
                        @if ($categories->isNotEmpty())
                            x-show="activeCategory === 'all' || activeCategory === '{{ $item->category_id }}'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
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
                            template="glassmorphism"
                            :rating="$avgRating"
                            :href="route('landing.menu', $restaurant)"
                        />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- View All Menu Glass CTA --}}
        <div class="mt-12 text-center">
            <a
                href="{{ route('landing.menu', $restaurant) }}"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-white/40 dark:bg-white/10 backdrop-blur-2xl border border-white/50 dark:border-white/20 text-zinc-900 dark:text-white hover:border-primary/50 hover:bg-white/60 dark:hover:bg-white/20 px-8 py-3.5 text-sm font-bold shadow-xl transition-all hover:scale-105"
            >
                <span>{{ $menuCopy['button'] ?? 'Lihat semua daftar menu' }}</span>
                <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

    </div>
</section>
