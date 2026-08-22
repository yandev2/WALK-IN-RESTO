@php
    use App\Support\CmsMedia;
@endphp

<div
    class="guest-menu-shell mx-auto max-w-md px-4 pb-28 pt-2"
    x-data="{
        cartOpen: false,
        favorites: JSON.parse(sessionStorage.getItem('guest_menu_favorites') || '[]'),
        isFavorite(id) { return this.favorites.includes(id); },
        toggleFavorite(id) {
            if (this.isFavorite(id)) {
                this.favorites = this.favorites.filter(f => f !== id);
            } else {
                this.favorites.push(id);
            }
            sessionStorage.setItem('guest_menu_favorites', JSON.stringify(this.favorites));
        },
    }"
>
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-muted">Menu</p>
        <h1 class="font-display text-2xl font-bold text-body">Pilih hidangan</h1>
    </div>

    <label class="relative mb-4 block">
        <span class="sr-only">Cari menu</span>
        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
        </svg>
        <input
            type="search"
            wire:model.live.debounce.300ms="search"
            placeholder="Mau makan apa hari ini?"
            class="w-full rounded-2xl border border-border-subtle bg-surface-muted py-3.5 pl-12 pr-4 text-sm text-body placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
        >
    </label>

    @if ($flash)
        <p class="mb-4 rounded-2xl bg-moss/10 px-4 py-2 text-sm text-moss">{{ $flash }}</p>
    @endif

    @if ($banners->isNotEmpty())
        <div class="mb-4">
            <x-guest.promo-hero :banners="$banners" />
        </div>
    @endif

    @if ($categories->isNotEmpty())
        <div class="mb-4 overflow-x-auto rounded-2xl bg-surface-muted p-1.5 ring-1 ring-border-subtle/50">
            <div class="flex min-w-max gap-1">
                <button
                    type="button"
                    wire:click="setCategory(null)"
                    @class([
                        'shrink-0 rounded-xl px-4 py-2 text-sm font-semibold transition',
                        $categoryId === null
                            ? 'bg-surface-raised text-primary shadow-sm ring-1 ring-border-subtle/60'
                            : 'text-muted hover:text-body',
                    ])
                >
                    Semua
                </button>
                @foreach ($categories as $category)
                    <button
                        type="button"
                        wire:click="setCategory({{ $category->id }})"
                        @class([
                            'shrink-0 rounded-xl px-4 py-2 text-sm font-semibold transition',
                            $categoryId === $category->id
                                ? 'bg-surface-raised text-primary shadow-sm ring-1 ring-border-subtle/60'
                                : 'text-muted hover:text-body',
                        ])
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    @if ($menuItems->isEmpty())
        <div class="rounded-2xl bg-surface-muted px-6 py-12 text-center">
            <p class="font-semibold text-body">Menu tidak ditemukan</p>
            <p class="mt-1 text-sm text-muted">Coba kata kunci lain atau pilih kategori berbeda.</p>
        </div>
    @else
        <div class="grid grid-cols-2 gap-3">
            @foreach ($menuItems as $item)
                <x-guest.menu-product-card
                    :item="$item"
                    :out-of-stock="$item->is_out_of_stock"
                    wire:key="menu-item-{{ $item->id }}"
                />
            @endforeach
        </div>
    @endif

    @if ($cartCount > 0)
        <div class="pointer-events-none fixed inset-x-0 bottom-[4.75rem] z-30 flex justify-center px-4">
            <button
                type="button"
                @click="cartOpen = true"
                class="pointer-events-auto flex w-full max-w-md items-center justify-between rounded-2xl bg-primary px-4 py-3 text-white shadow-lg shadow-primary/30"
            >
                <span class="text-sm font-semibold">{{ $cartCount }} item di keranjang</span>
                <span class="text-sm font-bold">{{ CmsMedia::formatIdr($subtotal) }} →</span>
            </button>
        </div>
    @endif

    {{-- Cart drawer --}}
    <div
        x-show="cartOpen"
        x-cloak
        class="fixed inset-0 z-40"
        @keydown.escape.window="cartOpen = false"
    >
        <div class="absolute inset-0 bg-black/40" @click="cartOpen = false"></div>
        <div
            x-show="cartOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="absolute inset-x-0 bottom-0 max-h-[85vh] overflow-hidden rounded-t-3xl bg-surface-raised shadow-2xl"
        >
            <div class="flex items-center justify-between border-b border-border-subtle px-4 py-3">
                <h3 class="font-bold text-body">Pesanan saya</h3>
                <button type="button" @click="cartOpen = false" class="rounded-full p-2 text-muted hover:bg-surface-muted" aria-label="Tutup">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <x-guest.order-panel
                :visit="$visit"
                :cart-items="$cartItems"
                :subtotal="$subtotal"
                :compact="true"
            />
        </div>
    </div>

    {{-- Add to cart modal (extra + catatan) — sits above bottom nav --}}
    @if ($pickingItem)
        <div
            class="fixed inset-0 z-50 flex items-end justify-center bg-black/40 px-4 pt-4 pb-[calc(4.75rem+env(safe-area-inset-bottom,0px))]"
            wire:click.self="cancelPicking"
        >
            <div class="w-full max-w-md min-h-0" wire:click.stop>
                <x-guest.add-to-cart-modal
                    :item="$pickingItem"
                    :variant-id="$variantId"
                    :selected-modifier-ids="$selectedModifierIds"
                    :picking-qty="$pickingQty"
                    :picker-total="$pickerTotal"
                />
            </div>
        </div>
    @endif

    @include('guest.partials.nav', ['cartCount' => $cartCount])
</div>
