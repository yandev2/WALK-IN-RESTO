@php
    use App\Support\CmsMedia;
@endphp

<div class="guest-cart-shell mx-auto max-w-md px-4 pb-28 pt-6">
    <div class="mb-4">
        <p class="text-[11px] font-bold uppercase tracking-wider text-muted dark:text-zinc-400">Keranjang Pesanan</p>
        <h1 class="font-display text-2xl font-black text-body dark:text-white">Pesanan Anda</h1>
    </div>

    @if ($items->isEmpty())
        <div class="mt-6 rounded-3xl border border-border-subtle/70 dark:border-white/10 bg-surface-raised dark:bg-zinc-900 p-8 text-center shadow-xs">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-muted dark:bg-zinc-800 text-muted/50 mb-3">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-1.5 7h11M10 17a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/>
                </svg>
            </div>
            <p class="font-bold text-body dark:text-white">Keranjang masih kosong</p>
            <p class="mt-1 text-xs text-muted dark:text-zinc-400">Pilih hidangan lezat dan masukkan ke keranjang.</p>
            <a
                href="{{ route('guest.menu') }}"
                class="mt-5 inline-flex rounded-xl bg-primary px-5 py-2.5 text-xs font-bold text-white shadow-sm shadow-primary/25 hover:bg-primary-dark transition active:scale-95"
            >
                Pilih Menu
            </a>
        </div>
    @else
        <ul class="mt-5 space-y-3">
            @foreach ($items as $item)
                @php
                    $thumb = collect($item->menuItem?->photoUrls() ?? [])->first();
                    $meta = collect([
                        $item->variant?->name,
                        $item->modifiers->isNotEmpty() ? $item->modifiers->pluck('name')->implode(', ') : null,
                        $item->notes ? 'Catatan: '.$item->notes : null,
                    ])->filter()->values();
                @endphp
                <li class="rounded-3xl border border-border-subtle/70 dark:border-white/10 bg-surface-raised dark:bg-zinc-900 p-3.5 shadow-xs">
                    <div class="flex gap-3">
                        @if ($thumb)
                            <img
                                src="{{ $thumb }}"
                                alt=""
                                class="h-20 w-20 shrink-0 rounded-2xl object-cover ring-1 ring-border-subtle/50 dark:ring-white/10"
                            >
                        @else
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-surface-muted dark:bg-zinc-800 text-muted/40 ring-1 ring-border-subtle/40 dark:ring-white/10">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="min-w-0 flex-1 flex flex-col justify-between">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-extrabold text-body dark:text-white">{{ $item->menuItem?->name }}</p>
                                    @if ($meta->isNotEmpty())
                                        <p class="mt-0.5 line-clamp-2 text-[11px] leading-relaxed text-muted dark:text-zinc-400">{{ $meta->implode(' · ') }}</p>
                                    @endif
                                </div>
                                <p class="shrink-0 text-sm font-extrabold tabular-nums text-primary">{{ CmsMedia::formatIdr($item->lineTotal()) }}</p>
                            </div>

                            <div class="mt-2.5 flex items-center justify-between gap-3">
                                <div class="inline-flex items-center gap-2.5">
                                    <button
                                        type="button"
                                        wire:click="minus({{ $item->id }})"
                                        class="flex h-7 w-7 items-center justify-center rounded-xl border-2 border-primary/30 text-sm font-bold text-primary transition hover:bg-primary/5 active:scale-95"
                                        aria-label="Kurangi {{ $item->menuItem?->name }}"
                                    >
                                        −
                                    </button>
                                    <span class="min-w-[1.25rem] text-center text-xs font-bold tabular-nums text-body dark:text-white">{{ $item->qty }}</span>
                                    <button
                                        type="button"
                                        wire:click="plus({{ $item->id }})"
                                        class="flex h-7 w-7 items-center justify-center rounded-xl bg-primary text-sm font-bold text-white shadow-sm shadow-primary/20 transition hover:bg-primary-dark active:scale-95"
                                        aria-label="Tambah {{ $item->menuItem?->name }}"
                                    >
                                        +
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    wire:click="remove({{ $item->id }})"
                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-muted dark:text-zinc-400 hover:text-rose-500 dark:hover:text-rose-400 transition"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-5 rounded-3xl border border-border-subtle/70 dark:border-white/10 bg-surface-raised dark:bg-zinc-900 p-4 shadow-xs">
            <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-semibold text-muted dark:text-zinc-400">Subtotal</span>
                <span class="text-base font-extrabold tabular-nums text-primary">{{ CmsMedia::formatIdr($subtotal) }}</span>
            </div>
            <p class="mt-1 text-[11px] text-muted dark:text-zinc-400">Pajak PB1 dan service dihitung pada tahap checkout.</p>
        </div>

        <a
            href="{{ route('guest.checkout') }}"
            class="mt-4 flex w-full items-center justify-center rounded-2xl bg-primary py-3.5 text-xs font-bold text-white shadow-lg shadow-primary/25 hover:bg-primary-dark transition active:scale-[0.99]"
        >
            Lanjut Bayar
        </a>
    @endif

    @include('guest.partials.nav', ['cartCount' => $cartCount, 'activeTab' => 'cart'])
</div>
