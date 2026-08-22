<div class="mx-auto max-w-md px-5 pb-24 pt-4" wire:poll.8s>
    <p class="customer-section-label">Status meja</p>
    <h1 class="mt-1 font-display text-3xl font-bold text-body">Pesanan Anda</h1>
    <p class="mt-3 rounded-2xl bg-surface-muted px-4 py-3 text-sm shadow-sm">
        PIN rombongan: <span class="font-bold tracking-[0.3em] text-primary">{{ $visit?->join_pin }}</span>
        <span class="mt-1 block text-muted">Teman scan QR meja yang sama, lalu masukkan PIN ini.</span>
    </p>

    @if ($orders->isEmpty())
        <div class="customer-card mt-8 p-6 text-center">
            <p class="text-muted">Belum ada pesanan. Isi keranjang lalu bayar.</p>
            <a href="{{ route('guest.menu') }}" class="mt-4 inline-block font-semibold text-primary">Ke menu</a>
        </div>
    @else
        <ul class="mt-6 space-y-4">
            @foreach ($orders as $order)
                @php
                    $activeItems = $order->items->whereIn('kds_status', ['queued', 'preparing', 'ready']);
                    $slowest = $activeItems->sortByDesc(fn ($item) => $item->elapsedMinutes())->first();
                    $bandClass = match ($slowest?->timerBand()) {
                        'green' => 'bg-moss/15 text-moss',
                        'yellow' => 'bg-amber-100 text-amber-800',
                        'red' => 'bg-primary/15 text-primary',
                        default => 'bg-surface-muted text-muted',
                    };
                @endphp
                <li class="customer-card p-4">
                    <div class="flex items-center justify-between gap-3">
                        <p class="font-bold text-body">Pesanan #{{ $order->number }}</p>
                        <span class="rounded-full bg-surface-muted px-2 py-0.5 text-xs font-semibold uppercase tracking-wide text-muted">{{ $order->status }}</span>
                    </div>
                    <p class="mt-1 text-sm text-muted">{{ \App\Support\CmsMedia::formatIdr($order->grand_payable) }} · {{ strtoupper($order->payment_method) }}</p>
                    @if ($slowest)
                        <p class="mt-2 inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $bandClass }}">
                            Timer dapur {{ $slowest->elapsedMinutes() }} m
                        </p>
                    @endif
                    <ul class="mt-3 space-y-1 text-sm text-muted">
                        @foreach ($order->items as $item)
                            @php
                                $item->setRelation('order', $order);
                                $itemBand = match ($item->timerBand()) {
                                    'green' => 'text-moss',
                                    'yellow' => 'text-amber-700',
                                    'red' => 'text-primary',
                                    default => 'text-muted',
                                };
                            @endphp
                            <li class="flex items-start justify-between gap-2">
                                <span>{{ $item->qty }}× {{ $item->displayName() }} — {{ $item->kds_status }}</span>
                                @if (in_array($item->kds_status, ['queued', 'preparing', 'ready'], true))
                                    <span class="shrink-0 text-xs font-semibold {{ $itemBand }}">{{ $item->elapsedMinutes() }} m</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @if ($order->status === 'awaiting_cashier')
                        <a href="{{ route('guest.pay', $order) }}" class="mt-3 inline-block text-sm font-semibold text-primary">
                            @if ($order->payment_method === 'qris')
                                {{ filled($order->payments->sortByDesc('id')->first()?->proof_image_path) ? 'Lihat bayar & bukti' : 'Bayar QRIS & unggah bukti' }}
                            @else
                                Lihat cara bayar
                            @endif
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    @if ($portalOpen)
        <section class="customer-card mt-6 overflow-hidden p-5">
            <p class="customer-section-label">Ulasan restoran</p>
            @if ($hasReview)
                <p class="mt-2 font-semibold text-body">Terima kasih sudah memberi ulasan!</p>
                <p class="mt-1 text-sm text-muted">Ulasan Anda membantu restoran kami menjadi lebih baik.</p>
                <a href="{{ route('guest.review') }}" class="mt-3 inline-block text-sm font-semibold text-primary">Lihat ulasan saya</a>
            @elseif ($canSubmitReview)
                <p class="mt-2 font-semibold text-body">Pesanan selesai — bagikan pengalaman Anda</p>
                <p class="mt-1 text-sm text-muted">Satu sesi meja, satu kali ulasan. Nama diambil dari profil meja Anda.</p>
                <a href="{{ route('guest.review') }}" class="landing-btn-glow mt-4 inline-flex w-full items-center justify-center rounded-full bg-primary py-3 text-sm font-semibold text-white hover:bg-primary-dark">
                    Beri ulasan
                </a>
            @else
                <p class="mt-2 font-semibold text-body">Ulasan akan segera terbuka</p>
                <p class="mt-1 text-sm text-muted">Form ulasan aktif setelah pesanan Anda selesai diproses dapur.</p>
            @endif
        </section>
    @endif

    @include('guest.partials.nav', ['cartCount' => $cartCount])
</div>
