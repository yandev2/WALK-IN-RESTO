@php
    use App\Support\CmsMedia;
@endphp

<div>
    @include('partials.customer.landing-header', [
        'restaurant' => $restaurant,
        'logoUrl' => $logoUrl,
        'ctaUrl' => $ctaUrl,
        'ctaLabel' => $ctaLabel,
        'menuItems' => $previewMenuItems,
        'landingUrl' => route('landing.show', $restaurant),
        'homeUrl' => route('landing.show', $restaurant),
        'layout' => $layout,
        'visibleSections' => $visibleSections,
    ])

    <main id="atas" class="mx-auto max-w-6xl px-5 py-12 md:py-16">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <x-customer.section-heading
                    label="Hidangan"
                    title="Semua menu"
                    highlight="menu"
                    class="text-left"
                />
                <p class="mt-2 text-sm text-muted">Jelajahi daftar lengkap. Pesan dari HP setelah scan QR meja.</p>
            </div>
            <a href="{{ route('landing.show', $restaurant) }}" class="landing-nav-link text-sm font-semibold text-primary">
                ← Kembali ke beranda
            </a>
        </div>

        <div class="customer-card mt-8 overflow-visible p-2 ring-1 ring-[color:var(--border-subtle)]">
            <div class="flex flex-col gap-2 lg:flex-row lg:items-stretch">
                <label class="relative min-w-0 flex-1">
                    <span class="sr-only">Cari menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                    </svg>
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama menu..."
                        class="h-12 w-full rounded-2xl border-0 bg-transparent py-3 pl-11 pr-4 text-sm text-body placeholder:text-muted focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30"
                    >
                </label>

                <div class="hidden w-px self-stretch bg-[var(--border-subtle)] lg:block"></div>

                <div class="grid grid-cols-2 gap-1 lg:w-[28rem]">
                    <x-customer.dropdown
                        property="categoryId"
                        :value="$categoryId"
                        :options="collect([['value' => null, 'label' => 'Semua kategori']])->concat($categories->map(fn ($category) => ['value' => $category->id, 'label' => $category->name]))->all()"
                    />
                    <x-customer.dropdown
                        property="priceSort"
                        :value="$priceSort"
                        :options="[
                            ['value' => 'asc', 'label' => 'Harga terendah'],
                            ['value' => 'desc', 'label' => 'Harga tertinggi'],
                        ]"
                    />
                </div>
            </div>
        </div>

        @if ($menuItems->isEmpty())
            <div class="customer-card mt-10 p-10 text-center">
                <p class="text-lg font-semibold text-body">Tidak ada menu yang cocok.</p>
                <p class="mt-2 text-sm text-muted">Coba ubah kata kunci atau filter kategori.</p>
            </div>
        @else
            <ul class="mt-10 grid items-stretch gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($menuItems as $item)
                    <li wire:key="menu-item-{{ $item->id }}" class="h-full">
                        <x-customer.dish-card
                            class="h-full"
                            :name="$item->name"
                            :description="$item->description"
                            :photos="$item->photoUrls()"
                            :category="$item->category?->name"
                            :price="CmsMedia::formatIdr($item->effectivePrice())"
                            :original-price="$item->hasDiscount() ? CmsMedia::formatIdr($item->price) : null"
                            :discount-percent="$item->hasDiscount() ? $item->discount_percent : null"
                        />
                    </li>
                @endforeach
            </ul>

            <div class="mt-10">
                {{ $menuItems->links('vendor.pagination.customer') }}
            </div>
        @endif
    </main>

    @include('partials.customer.landing-footer', [
        'restaurant' => $restaurant,
        'outlet' => $outlet,
        'whatsappUrl' => $whatsappUrl,
        'mapsUrl' => $mapsUrl,
        'menuItems' => $previewMenuItems,
        'layout' => $layout,
        'visibleSections' => $visibleSections,
    ])
</div>
