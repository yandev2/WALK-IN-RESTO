<div class="guest-status-shell mx-auto max-w-md px-4 pb-28 pt-6" wire:poll.8s>
    <div class="mb-4">
        <p class="text-[11px] font-bold uppercase tracking-wider text-muted dark:text-zinc-400">Status Meja</p>
        <h1 class="font-display text-2xl font-black text-body dark:text-white">Pesanan Anda</h1>
    </div>

    {{-- PIN Rombongan Card --}}
    @if ($visit?->join_pin)
        <div class="rounded-3xl border border-border-subtle/70 dark:border-white/10 bg-surface-raised dark:bg-zinc-900 p-4 shadow-xs">
            <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-semibold text-muted dark:text-zinc-400">PIN Rombongan</span>
                <span class="font-mono text-base font-black tracking-[0.25em] text-primary">{{ $visit->join_pin }}</span>
            </div>
            <p class="mt-2 text-xs leading-relaxed text-muted dark:text-zinc-400">
                Teman dapat scan QR meja yang sama, lalu masukkan PIN ini untuk memesan bersama.
            </p>
        </div>
    @endif

    @if ($orders->isEmpty())
        <div class="mt-6 rounded-3xl border border-border-subtle/70 dark:border-white/10 bg-surface-raised dark:bg-zinc-900 p-8 text-center shadow-xs">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-muted dark:bg-zinc-800 text-muted/50 mb-3">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="font-bold text-body dark:text-white">Belum ada pesanan aktif</p>
            <p class="mt-1 text-xs text-muted dark:text-zinc-400">Pilih menu favorit Anda dan lakukan pemesanan.</p>
            <a
                href="{{ route('guest.menu') }}"
                class="mt-5 inline-flex rounded-xl bg-primary px-5 py-2.5 text-xs font-bold text-white shadow-sm shadow-primary/25 hover:bg-primary-dark transition active:scale-95"
            >
                Pilih Menu
            </a>
        </div>
    @else
        <div class="mt-5 space-y-3.5">
            @foreach ($orders as $order)
                @php
                    $activeItems = $order->items->whereIn('kds_status', ['queued', 'preparing', 'ready']);
                    $slowest = $activeItems->sortByDesc(fn ($item) => $item->elapsedMinutes())->first();
                    $bandClass = match ($slowest?->timerBand()) {
                        'green' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20',
                        'yellow' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/20',
                        'red' => 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/20',
                        default => 'bg-surface-muted dark:bg-zinc-800 text-muted dark:text-zinc-400',
                    };
                    $statusLabel = match ($order->status) {
                        'draft' => 'Draft',
                        'awaiting_cashier' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => strtoupper($order->status),
                    };
                    $statusColor = match ($order->status) {
                        'paid', 'completed' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400',
                        'awaiting_cashier' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400',
                        'cancelled' => 'bg-rose-500/15 text-rose-600 dark:text-rose-400',
                        default => 'bg-surface-muted text-muted',
                    };
                @endphp
                <div class="rounded-3xl border border-border-subtle/70 dark:border-white/10 bg-surface-raised dark:bg-zinc-900 p-4 shadow-xs">
                    <div class="flex items-center justify-between gap-3 border-b border-border-subtle/40 dark:border-white/5 pb-3">
                        <div>
                            <p class="text-sm font-extrabold text-body dark:text-white">Pesanan #{{ $order->number }}</p>
                            <p class="text-[11px] text-muted dark:text-zinc-400 mt-0.5">
                                {{ \App\Support\CmsMedia::formatIdr($order->grand_payable) }} · <span class="uppercase font-semibold">{{ $order->payment_method }}</span>
                            </p>
                        </div>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    @if ($slowest)
                        <div class="mt-3 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 rounded-xl px-2.5 py-1 text-[11px] font-bold {{ $bandClass }}">
                                <svg class="h-3 w-3 animate-spin text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Timer Dapur: {{ $slowest->elapsedMinutes() }} m
                            </span>
                        </div>
                    @endif

                    <ul class="mt-3 space-y-1.5 text-xs">
                        @foreach ($order->items as $item)
                            @php
                                $item->setRelation('order', $order);
                                $itemBand = match ($item->timerBand()) {
                                    'green' => 'text-emerald-500',
                                    'yellow' => 'text-amber-500',
                                    'red' => 'text-rose-500',
                                    default => 'text-muted',
                                };
                            @endphp
                            <li class="flex items-start justify-between gap-2 py-0.5 text-muted dark:text-zinc-300">
                                <span class="line-clamp-1"><strong class="font-bold text-body dark:text-white">{{ $item->qty }}×</strong> {{ $item->displayName() }}</span>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="rounded-md bg-surface-muted dark:bg-zinc-800 px-1.5 py-0.2 text-[10px] uppercase font-semibold text-muted dark:text-zinc-400">
                                        {{ $item->kds_status }}
                                    </span>
                                    @if (in_array($item->kds_status, ['queued', 'preparing', 'ready'], true))
                                        <span class="text-[10px] font-bold {{ $itemBand }}">{{ $item->elapsedMinutes() }}m</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    @if ($order->status === 'awaiting_cashier')
                        <div class="mt-4 border-t border-border-subtle/40 dark:border-white/5 pt-3">
                            <a
                                href="{{ route('guest.pay', $order) }}"
                                class="flex w-full items-center justify-center gap-1.5 rounded-xl bg-primary py-2.5 text-xs font-bold text-white shadow-sm shadow-primary/20 hover:bg-primary-dark transition active:scale-95"
                            >
                                @if ($order->payment_method === 'qris')
                                    {{ filled($order->payments->sortByDesc('id')->first()?->proof_image_path) ? 'Lihat Bukti Bayar' : 'Bayar QRIS & Unggah Bukti' }}
                                @else
                                    Lihat Cara Bayar
                                @endif
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Review Section --}}
    @if ($portalOpen)
        <section class="mt-5 rounded-3xl border border-border-subtle/70 dark:border-white/10 bg-surface-raised dark:bg-zinc-900 p-4 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-muted dark:text-zinc-400">Ulasan Restoran</p>
            @if ($hasReview)
                <p class="mt-2 text-sm font-bold text-body dark:text-white">Terima kasih sudah memberi ulasan!</p>
                <p class="mt-1 text-xs text-muted dark:text-zinc-400">Ulasan Anda membantu kami meningkatkan pelayanan.</p>
                <a href="{{ route('guest.review') }}" class="mt-3 inline-block text-xs font-bold text-primary">Lihat Ulasan Saya →</a>
            @elseif ($canSubmitReview)
                <p class="mt-2 text-sm font-bold text-body dark:text-white">Pesanan Selesai</p>
                <p class="mt-1 text-xs text-muted dark:text-zinc-400">Bagikan pengalaman santap Anda bersama kami.</p>
                <a
                    href="{{ route('guest.review') }}"
                    class="mt-4 flex w-full items-center justify-center rounded-xl bg-primary py-2.5 text-xs font-bold text-white shadow-sm shadow-primary/20 hover:bg-primary-dark transition active:scale-95"
                >
                    Beri ulasan
                </a>
            @else
                <p class="mt-2 text-sm font-bold text-body dark:text-white">Ulasan Akan Segera Terbuka</p>
                <p class="mt-1 text-xs text-muted dark:text-zinc-400">Form ulasan aktif setelah pesanan Anda selesai diproses dapur.</p>
            @endif
        </section>
    @endif

    @include('guest.partials.nav', ['cartCount' => $cartCount, 'activeTab' => 'status'])
</div>
