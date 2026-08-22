@php
    use App\Support\CmsMedia;
@endphp

<div class="guest-cart-shell mx-auto max-w-md px-4 pb-28 pt-4">
    <p class="customer-section-label">Keranjang</p>
    <h1 class="mt-1 font-display text-3xl font-bold text-body">Pesanan Anda</h1>

    @if ($items->isEmpty())
        <div class="mt-8 rounded-3xl border border-border-subtle/80 bg-surface-raised p-8 text-center shadow-sm">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-muted text-muted/50">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-1.5 7h11M10 17a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/>
                </svg>
            </div>
            <p class="mt-4 text-sm font-semibold text-body">Keranjang masih kosong</p>
            <p class="mt-1 text-sm text-muted">Pilih menu dulu, baru lanjut bayar.</p>
            <a
                href="{{ route('guest.menu') }}"
                class="mt-5 inline-flex rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark"
            >
                Pilih menu
            </a>
        </div>
    @else
        <ul class="mt-6 space-y-3">
            @foreach ($items as $item)
                @php
                    $thumb = collect($item->menuItem?->photoUrls() ?? [])->first();
                    $meta = collect([
                        $item->variant?->name,
                        $item->modifiers->isNotEmpty() ? $item->modifiers->pluck('name')->implode(', ') : null,
                        $item->notes ? 'Catatan: '.$item->notes : null,
                    ])->filter()->values();
                @endphp
                <li class="rounded-3xl border border-border-subtle/70 bg-surface-raised p-3.5 shadow-sm">
                    <div class="flex gap-3.5">
                        @if ($thumb)
                            <img
                                src="{{ $thumb }}"
                                alt=""
                                class="h-[4.75rem] w-[4.75rem] shrink-0 rounded-2xl object-cover ring-1 ring-border-subtle/50"
                            >
                        @else
                            <div class="flex h-[4.75rem] w-[4.75rem] shrink-0 items-center justify-center rounded-2xl bg-surface-muted text-muted/40 ring-1 ring-border-subtle/40">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="truncate text-base font-bold text-body">{{ $item->menuItem?->name }}</p>
                                    @if ($meta->isNotEmpty())
                                        <p class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-muted">{{ $meta->implode(' · ') }}</p>
                                    @endif
                                </div>
                                <p class="shrink-0 text-sm font-bold tabular-nums text-primary">{{ CmsMedia::formatIdr($item->lineTotal()) }}</p>
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-3">
                                <div class="inline-flex items-center gap-2.5">
                                    <button
                                        type="button"
                                        wire:click="minus({{ $item->id }})"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-primary/25 text-base font-bold text-primary transition hover:bg-primary/5"
                                        aria-label="Kurangi {{ $item->menuItem?->name }}"
                                    >
                                        −
                                    </button>
                                    <span class="min-w-[1.25rem] text-center text-sm font-bold tabular-nums text-body">{{ $item->qty }}</span>
                                    <button
                                        type="button"
                                        wire:click="plus({{ $item->id }})"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-primary text-base font-bold text-white shadow-sm shadow-primary/20 transition hover:bg-primary-dark"
                                        aria-label="Tambah {{ $item->menuItem?->name }}"
                                    >
                                        +
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    wire:click="remove({{ $item->id }})"
                                    class="text-xs font-semibold text-muted transition hover:text-primary"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-5 rounded-3xl border border-border-subtle/70 bg-surface-raised p-4 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <span class="text-sm font-semibold text-body">Subtotal</span>
                <span class="text-lg font-bold tabular-nums text-primary">{{ CmsMedia::formatIdr($subtotal) }}</span>
            </div>
            <p class="mt-1.5 text-xs text-muted">Pajak PB1 dan service dihitung saat bayar.</p>
        </div>

        <a
            href="{{ route('guest.checkout') }}"
            class="landing-btn-glow mt-5 flex w-full items-center justify-center rounded-full bg-primary py-3.5 text-sm font-bold text-white shadow-lg shadow-primary/25 hover:bg-primary-dark"
        >
            Lanjut bayar
        </a>
    @endif

    @include('guest.partials.nav', ['cartCount' => $cartCount])
</div>
