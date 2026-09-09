@php
    use App\Support\CmsMedia;

    $copy = $layout->copyFor('menu');
    $categories = $menuCategories ?? collect();
@endphp

<section id="menu" class="scroll-mt-20 bg-surface-base">
    <div class="landing-container landing-section">
        <div class="landing-reveal mx-auto max-w-2xl text-center">
            <x-customer.section-heading
                :label="$copy['label'] ?? null"
                :title="$copy['title'] ?? 'Menu'"
                :highlight="$copy['highlight'] ?? null"
                class="text-center md:text-center"
            />
            @if (filled($copy['subtitle'] ?? null))
                <p class="mt-4 text-sm text-muted sm:text-base">{{ $copy['subtitle'] }}</p>
            @endif
        </div>

        <div
            class="mt-10"
            x-data="{ active: 'all' }"
        >
            @if ($categories->isNotEmpty())
                <div class="landing-reveal -mx-1 mb-8 overflow-x-auto px-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <div class="flex w-max min-w-full items-center justify-start gap-1 sm:w-auto sm:min-w-0 sm:justify-center">
                        <button type="button" class="landing-tab shrink-0" :class="active === 'all' && 'is-active'" @click="active = 'all'">Semua</button>
                        @foreach ($categories as $category)
                            <button
                                type="button"
                                class="landing-tab shrink-0"
                                :class="active === '{{ $category->id }}' && 'is-active'"
                                @click="active = '{{ $category->id }}'"
                            >
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <ul class="grid items-stretch gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($menuItems as $index => $item)
                    <li
                        class="h-full"
                        @if ($categories->isNotEmpty())
                            x-show="active === 'all' || active === '{{ $item->category_id }}'"
                            x-transition
                        @endif
                    >
                        <div
                            class="landing-reveal h-full"
                            style="transition-delay: {{ min($index * 60, 360) }}ms"
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
                                :href="route('landing.menu', $restaurant)"
                            />
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-10 text-center">
            <x-customer.btn-outline :href="route('landing.menu', $restaurant)">
                {{ $copy['button'] ?? 'Lihat semua daftar menu' }}
            </x-customer.btn-outline>
        </div>
    </div>
</section>
