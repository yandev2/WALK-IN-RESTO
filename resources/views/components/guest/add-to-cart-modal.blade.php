@props([
    'item',
    'variantId' => null,
    'selectedModifierIds' => [],
    'pickingQty' => 1,
    'pickerTotal' => 0,
])

@php
    use App\Support\CmsMedia;

    $gallery = collect($item->photoUrls())->filter()->values();
    $selectedModifierIds = collect($selectedModifierIds)->map(fn ($id) => (int) $id)->all();
@endphp

<div {{ $attributes->merge(['class' => 'flex w-full max-h-[calc(100dvh-5.75rem-env(safe-area-inset-bottom,0px))] flex-col overflow-hidden rounded-3xl bg-surface-raised shadow-2xl']) }}>
    <div class="flex shrink-0 justify-center pt-3">
        <span class="h-1 w-10 rounded-full bg-border-subtle/80" aria-hidden="true"></span>
    </div>

    <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain px-4 pb-4 [-webkit-overflow-scrolling:touch] touch-pan-y">
        <div
            @if ($gallery->count() > 1)
                x-data="{ index: 0 }"
            @endif
        >
            <div class="overflow-hidden rounded-2xl bg-surface-muted">
                @if ($gallery->isNotEmpty())
                    <div @class(['relative w-full overflow-hidden', 'h-44' => $gallery->count() > 1, 'h-48' => $gallery->count() <= 1])>
                        @foreach ($gallery as $galleryIndex => $galleryPhoto)
                            <img
                                src="{{ $galleryPhoto }}"
                                alt=""
                                @class(['h-full w-full object-cover', 'absolute inset-0' => $gallery->count() > 1])
                                @if ($gallery->count() > 1)
                                    x-show="index === {{ $galleryIndex }}"
                                @endif
                            >
                        @endforeach
                    </div>
                @else
                    <div class="flex h-48 items-center justify-center text-muted/40">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            @if ($gallery->count() > 1)
                <div class="mt-2.5 flex gap-2 overflow-x-auto pb-0.5">
                    @foreach ($gallery as $thumbIndex => $thumbPhoto)
                        <button
                            type="button"
                            @click="index = {{ $thumbIndex }}"
                            :class="index === {{ $thumbIndex }} ? 'ring-2 ring-primary' : 'opacity-70'"
                            class="h-14 w-14 shrink-0 overflow-hidden rounded-xl transition"
                        >
                            <img src="{{ $thumbPhoto }}" alt="" class="h-full w-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-4">
            <h3 class="font-display text-xl font-bold text-body">{{ $item->name }}</h3>
            <div class="mt-1">
                <x-customer.menu-price :item="$item" />
            </div>
            @if ($item->description)
                <p class="mt-3 text-sm leading-relaxed text-muted">{{ $item->description }}</p>
            @endif
            @if ($item->is_out_of_stock)
                <p class="customer-pill mt-3 bg-surface-muted text-muted">Stok habis</p>
            @endif
        </div>

        @if ($item->variants->isNotEmpty())
            <div class="mt-5">
                <p class="mb-2.5 text-sm font-semibold text-body">Varian</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($item->variants as $variant)
                        @php
                            $isActive = (string) $variantId === (string) $variant->id;
                        @endphp
                        <button
                            type="button"
                            wire:click="setVariant({{ $variant->id }})"
                            @class([
                                'rounded-full border px-4 py-2 text-sm font-medium transition',
                                'border-primary bg-primary text-white shadow-sm shadow-primary/20' => $isActive,
                                'border-border-subtle bg-surface-raised text-body hover:border-primary/40' => ! $isActive,
                            ])
                        >
                            {{ $variant->name }}
                            @if ($variant->price_delta)
                                <span @class(['ml-1 text-xs', 'text-white/90' => $isActive, 'text-muted' => ! $isActive])>
                                    {{ $variant->price_delta > 0 ? '+' : '' }}{{ CmsMedia::formatIdr($variant->price_delta) }}
                                </span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        @foreach ($item->modifierGroups as $group)
            <div class="mt-5">
                <p class="mb-2.5 text-sm font-semibold text-body">
                    {{ $group->name }}
                    @if ($group->is_required)
                        <span class="font-normal text-muted">· wajib</span>
                    @endif
                </p>
                <div class="space-y-2">
                    @foreach ($group->modifiers->where('is_active', true) as $modifier)
                        @php
                            $isChecked = in_array((int) $modifier->id, $selectedModifierIds, true);
                        @endphp
                        <button
                            type="button"
                            wire:click="toggleModifier({{ $modifier->id }})"
                            @class([
                                'flex w-full items-center gap-3 rounded-2xl border px-3.5 py-3 text-left transition',
                                'border-primary/50 bg-primary/[0.06]' => $isChecked,
                                'border-border-subtle bg-surface-raised hover:border-primary/30' => ! $isChecked,
                            ])
                        >
                            <span @class([
                                'flex h-5 w-5 shrink-0 items-center justify-center rounded-md border-2 transition',
                                'border-primary bg-primary text-white' => $isChecked,
                                'border-border-subtle bg-surface-raised' => ! $isChecked,
                            ])>
                                @if ($isChecked)
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </span>
                            <span class="min-w-0 flex-1 text-sm text-body">{{ $modifier->name }}</span>
                            @if ($modifier->price)
                                <span class="shrink-0 text-xs font-semibold tabular-nums text-primary">+{{ CmsMedia::formatIdr($modifier->price) }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="mt-5 flex items-center justify-between">
            <p class="text-sm font-semibold text-body">Jumlah pesanan</p>
            <div class="inline-flex items-center gap-3">
                <button
                    type="button"
                    wire:click="decrementPickingQty"
                    @disabled($pickingQty <= 1)
                    class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-primary/30 text-lg font-bold text-primary transition hover:bg-primary/5 disabled:cursor-not-allowed disabled:opacity-40"
                    aria-label="Kurangi jumlah"
                >
                    −
                </button>
                <span class="min-w-[1.5rem] text-center text-base font-bold tabular-nums text-body">{{ $pickingQty }}</span>
                <button
                    type="button"
                    wire:click="incrementPickingQty"
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-lg font-bold text-white shadow-sm shadow-primary/25 transition hover:bg-primary-dark"
                    aria-label="Tambah jumlah"
                >
                    +
                </button>
            </div>
        </div>

        <div class="mt-5">
            <label for="guest-order-notes" class="mb-2 block text-sm font-semibold text-body">Catatan</label>
            <textarea
                id="guest-order-notes"
                wire:model.blur="notes"
                rows="3"
                class="w-full resize-none rounded-2xl border border-border-subtle bg-surface-raised px-3.5 py-3 text-sm leading-relaxed text-body placeholder:text-muted/70 focus:border-primary/50 focus:outline-none focus:ring-2 focus:ring-primary/15"
                placeholder="Contoh: sambelnya banyakin ya"
            ></textarea>
        </div>
    </div>

    <div class="shrink-0 px-4 pb-4 pt-2">
        <button
            type="button"
            wire:click="confirmAdd"
            @disabled($item->is_out_of_stock)
            @class([
                'flex w-full items-center justify-between rounded-full px-5 py-3.5 transition',
                'bg-primary text-white shadow-lg shadow-primary/25 hover:bg-primary-dark' => ! $item->is_out_of_stock,
                'cursor-not-allowed bg-muted text-white/80' => $item->is_out_of_stock,
            ])
        >
            <span class="text-base font-bold tabular-nums">{{ CmsMedia::formatIdr($pickerTotal) }}</span>
            <span @class([
                'flex h-10 w-10 items-center justify-center rounded-full',
                'bg-white/20' => ! $item->is_out_of_stock,
                'bg-white/10' => $item->is_out_of_stock,
            ])>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-1.5 7h11M10 17a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/>
                </svg>
            </span>
        </button>
    </div>
</div>
