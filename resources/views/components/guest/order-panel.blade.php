@props([
    'visit',
    'cartItems',
    'subtotal',
    'compact' => false,
])

@php
    use App\Support\CmsMedia;
@endphp

<div {{ $attributes->merge(['class' => 'flex h-full flex-col']) }}>
    <div class="border-b border-border-subtle px-4 py-4">
        <h2 class="text-lg font-bold text-body">Pesanan saya</h2>
        @if ($visit)
            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-muted">
                @if ($visit->diningTable)
                    <span class="inline-flex items-center gap-1 rounded-full bg-surface-muted px-2.5 py-1 font-semibold text-body">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        Meja {{ $visit->diningTable->code }}
                    </span>
                @endif
                @if ($visit->customer_name)
                    <span>{{ $visit->customer_name }}</span>
                @endif
                @if ($visit->join_pin)
                    <span>PIN {{ $visit->join_pin }}</span>
                @endif
            </div>
        @endif
    </div>

    <div @class(['flex-1 overflow-y-auto px-4 py-3', 'max-h-72 lg:max-h-none' => $compact])>
        @if ($cartItems->isEmpty())
            <div class="flex h-full min-h-[8rem] flex-col items-center justify-center text-center">
                <p class="text-sm font-semibold text-body">Keranjang kosong</p>
                <p class="mt-1 text-xs text-muted">Pilih menu untuk mulai pesan.</p>
            </div>
        @else
            <ul class="space-y-3">
                @foreach ($cartItems as $item)
                    @php
                        $thumb = collect($item->menuItem?->photoUrls() ?? [])->first();
                    @endphp
                    <li class="rounded-2xl border border-border-subtle/60 bg-surface-raised p-2.5 shadow-sm">
                        <div class="flex gap-3">
                            @if ($thumb)
                                <img src="{{ $thumb }}" alt="" class="h-14 w-14 shrink-0 rounded-xl object-cover ring-1 ring-border-subtle/40">
                            @else
                                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-surface-muted text-muted/40">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="truncate text-sm font-bold text-body">{{ $item->menuItem?->name }}</p>
                                    <p class="shrink-0 text-xs font-bold tabular-nums text-primary">{{ CmsMedia::formatIdr($item->lineTotal()) }}</p>
                                </div>
                                @if ($item->variant)
                                    <p class="truncate text-xs text-muted">{{ $item->variant->name }}</p>
                                @endif
                                @if ($item->modifiers->isNotEmpty())
                                    <p class="truncate text-xs text-muted">{{ $item->modifiers->pluck('name')->implode(', ') }}</p>
                                @endif
                                <div class="mt-2 flex items-center gap-2">
                                    <button type="button" wire:click="minus({{ $item->id }})" class="flex h-7 w-7 items-center justify-center rounded-lg border border-primary/25 text-sm font-bold text-primary">−</button>
                                    <span class="min-w-[1rem] text-center text-xs font-bold">{{ $item->qty }}</span>
                                    <button type="button" wire:click="plus({{ $item->id }})" class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary text-sm font-bold text-white">+</button>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-auto border-t border-border-subtle px-4 py-4">
        <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-muted">Subtotal</span>
            <span class="text-lg font-bold tabular-nums text-body">{{ CmsMedia::formatIdr($subtotal) }}</span>
        </div>
        <p class="mt-1 text-[0.65rem] text-muted">Pajak & service dihitung saat bayar.</p>
        @if ($cartItems->isNotEmpty())
            <a
                href="{{ route('guest.checkout') }}"
                class="landing-btn-glow mt-3 block w-full rounded-full bg-primary py-3 text-center text-sm font-bold text-white hover:bg-primary-dark"
            >
                Lanjut bayar
            </a>
        @endif
        <a
            href="{{ route('guest.cart') }}"
            class="mt-2 block text-center text-xs font-semibold text-primary hover:underline"
        >
            Lihat keranjang lengkap
        </a>
    </div>
</div>
