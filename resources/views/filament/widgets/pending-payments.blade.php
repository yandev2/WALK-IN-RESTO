<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class(['h-full', 'w-full'])
    "
>
    @php
        $orders = $this->getPendingOrders();
        $pendingCount = $this->getPendingCount();
        $ordersUrl = $this->getOrdersUrl();
    @endphp

    <div class="vision-card h-full flex flex-col justify-between p-5 sm:p-6">
        <div>
            {{-- Header --}}
            <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100 dark:border-white/5">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                        Pesanan Masuk Terbaru
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Antrian kasir & konfirmasi pesanan meja aktif
                    </p>
                </div>

                @if ($pendingCount > 0)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/20 shadow-sm shrink-0">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        {{ $pendingCount }} Menunggu
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Semua Lunas
                    </span>
                @endif
            </div>

            {{-- List of Recent Pending Orders --}}
            <div class="mt-3 divide-y divide-slate-100 dark:divide-white/5">
                @forelse ($orders as $order)
                    @php
                        $tableCode = $order->visit?->diningTable?->code ?: 'M';
                        $avatarText = strtoupper(substr($tableCode, 0, 2));
                        $customerWa = $order->visit?->customer_wa;
                        $itemsSummary = $order->items->take(2)->map(fn ($item) => $item->name . ' x' . $item->qty)->implode(', ');
                        if ($order->items->count() > 2) {
                            $itemsSummary .= ', +' . ($order->items->count() - 2) . ' lainnya';
                        }
                    @endphp

                    <div class="py-3 flex items-center justify-between gap-3 group hover:bg-slate-50/50 dark:hover:bg-white/[0.02] -mx-2 px-2 rounded-xl transition-colors">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            {{-- Table Avatar Badge --}}
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-600 to-cyan-500 flex items-center justify-center font-extrabold text-white text-xs shrink-0 shadow-sm shadow-sky-500/20">
                                {{ $avatarText }}
                            </div>

                            {{-- Details --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                        Meja {{ $order->visit?->diningTable?->code ?? '-' }}
                                    </h4>
                                    <span class="text-xs font-medium text-slate-400">·</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                        #{{ $order->number }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                    {{ filled($itemsSummary) ? $itemsSummary : \App\Support\CmsMedia::formatIdr($order->grand_payable) }}
                                    · <span class="uppercase font-semibold text-slate-700 dark:text-slate-300">{{ $order->payment_method ?? 'CASH' }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Right Time and Indicator --}}
                        <div class="flex items-center gap-2.5 shrink-0 text-right">
                            <div>
                                <p class="text-xs font-bold text-slate-900 dark:text-white">
                                    {{ \App\Support\CmsMedia::formatIdr($order->grand_payable) }}
                                </p>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">
                                    {{ $order->created_at?->diffForHumans(short: true) }}
                                </span>
                            </div>
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50 animate-pulse" title="Menunggu Kasir"></span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 mb-2.5">
                            <x-filament::icon icon="heroicon-o-check-circle" class="h-6 w-6 text-emerald-500" />
                        </div>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            Antrian kasir kosong
                        </p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 max-w-xs">
                            Pesanan baru akan muncul di sini setelah tamu melakukan pemesanan meja atau kasir.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <span>{{ $pendingCount }} antrian aktif</span>
            <a
                href="{{ $ordersUrl }}"
                class="font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors inline-flex items-center gap-1"
            >
                Buka Semua Pesanan Masuk →
            </a>
        </div>
    </div>
</x-filament-widgets::widget>
