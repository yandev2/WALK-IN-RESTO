@php
    use App\Support\CmsMedia;
@endphp

<div>
    {{-- Header matching active landing template --}}
    @if (($template ?? 'classic') === 'foodie')
        @include('landing.templates.foodie.sections.header')
    @elseif (($template ?? 'classic') === 'glassmorphism' || ($template ?? 'classic') === 'glassmorp')
        @include('landing.templates.glassmorphism.sections.header')
    @else
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
    @endif

    <main id="atas" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <x-customer.section-heading
                    label="Daftar Menu"
                    title="Semua menu restoran"
                    highlight="menu"
                    class="text-left"
                />
                <p class="mt-2 text-sm text-muted">Jelajahi pilihan menu lengkap. Pesan dari HP setelah scan QR meja.</p>
            </div>
            <a href="{{ route('landing.show', $restaurant) }}" class="landing-nav-link text-sm font-semibold text-primary inline-flex items-center gap-1.5 hover:underline">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke beranda</span>
            </a>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="customer-card mt-8 overflow-visible p-2.5 rounded-3xl bg-surface-raised dark:bg-zinc-900 border border-border-subtle dark:border-zinc-800 shadow-xs">
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

                <div class="hidden w-px self-stretch bg-border-subtle dark:bg-zinc-800 lg:block"></div>

                <div class="grid grid-cols-2 gap-2 lg:w-[28rem]">
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

        {{-- Menu List Grid --}}
        @if ($menuItems->isEmpty())
            <div class="customer-card mt-10 p-12 text-center rounded-3xl bg-surface-raised dark:bg-zinc-900 border border-border-subtle dark:border-zinc-800">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-muted dark:bg-zinc-800 text-muted mb-4">
                    <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <p class="text-lg font-semibold text-body">Tidak ada menu yang cocok.</p>
                <p class="mt-1 text-sm text-muted">Coba ubah kata kunci pencarian atau filter kategori.</p>
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
                            :template="$template ?? 'classic'"
                            :rating="$ratingSummary['average'] ? number_format($ratingSummary['average'], 1) : '5.0'"
                        />
                    </li>
                @endforeach
            </ul>

            <div class="mt-12">
                {{ $menuItems->links('vendor.pagination.customer') }}
            </div>
        @endif
    </main>

    {{-- Footer matching active landing template --}}
    @if (($template ?? 'classic') === 'foodie')
        @include('landing.templates.foodie.sections.footer')
    @elseif (($template ?? 'classic') === 'glassmorphism' || ($template ?? 'classic') === 'glassmorp')
        @include('landing.templates.glassmorphism.sections.footer')
    @else
        @include('partials.customer.landing-footer', [
            'restaurant' => $restaurant,
            'outlet' => $outlet,
            'whatsappUrl' => $whatsappUrl,
            'mapsUrl' => $mapsUrl,
            'menuItems' => $previewMenuItems,
            'layout' => $layout,
            'visibleSections' => $visibleSections,
        ])
    @endif
</div>
