@php
    $order = $simpleModeCompletedOrder ?? $this->simpleModeCompletedOrder ?? null;
@endphp

@if ($order)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/70 p-4 backdrop-blur-xs transition-opacity duration-200"
         role="dialog"
         aria-modal="true"
         aria-labelledby="simple-mode-modal-title"
         wire:keydown.escape="closeSimpleModeModal">

        <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-gray-950/10 dark:bg-gray-900 dark:ring-white/10 sm:max-w-lg transition-all transform animate-in fade-in zoom-in-95 duration-200">
            {{-- Top Header Section --}}
            <div class="relative px-6 pt-6 pb-4 text-center">
                {{-- Close X button in top-right --}}
                <button type="button"
                        wire:click="closeSimpleModeModal"
                        class="absolute right-4 top-4 rounded-full p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/10 dark:hover:text-gray-200 transition"
                        title="Tutup (Esc)">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Success Icon Badge --}}
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 shadow-xs ring-4 ring-emerald-50 dark:ring-emerald-950/30">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                {{-- Title & Mode Badge --}}
                <div class="flex items-center justify-center gap-2 mb-1">
                    <h3 id="simple-mode-modal-title" class="font-display text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                        Pesanan Selesai
                    </h3>
                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-0.5 text-xs font-bold text-gray-700 dark:bg-white/10 dark:text-gray-300">
                        #{{ $order['number'] }}
                    </span>
                </div>
                <div class="flex items-center justify-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Simple Mode • Transaksi Selesai Langsung</span>
                </div>
            </div>

            {{-- Receipt / Transaction Card --}}
            <div class="px-6 py-2">
                <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-4 dark:border-white/10 dark:bg-white/[0.03] space-y-3">
                    {{-- Order meta (Meja & Tamu) --}}
                    @if (!empty($order['table_name']) || !empty($order['customer_name']))
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pb-2.5 border-b border-dashed border-gray-200 dark:border-white/10">
                            <div class="flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M10 3v18M14 3v18" />
                                </svg>
                                <span>{{ !empty($order['table_name']) ? 'Meja ' . $order['table_name'] : 'Walk-in' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                                <svg class="h-3.5 w-3.5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ $order['customer_name'] ?? 'Tamu Walk-in' }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Payment Method & Total --}}
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Metode Bayar</span>
                        <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-0.5 text-xs font-semibold {{ ($order['payment_method'] ?? '') === 'QRIS' ? 'bg-info-50 text-info-700 dark:bg-info-950/40 dark:text-info-300' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' }}">
                            {{ $order['payment_method'] ?? 'Tunai' }}
                        </span>
                    </div>

                    <div class="flex items-baseline justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Total Tagihan</span>
                        <span class="text-xl font-bold tabular-nums text-gray-950 dark:text-white">
                            Rp {{ number_format($order['grand_payable'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Cash Details (if cash) --}}
                    @if (!empty($order['cash_received']))
                        <div class="border-t border-dashed border-gray-200 dark:border-white/10 pt-2.5 space-y-2">
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>Uang Diterima</span>
                                <span class="font-medium text-gray-900 dark:text-white tabular-nums">
                                    Rp {{ number_format($order['cash_received'], 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- Kembalian Highlight Box --}}
                            <div class="rounded-xl bg-emerald-500/10 border border-emerald-500/25 p-3.5 dark:bg-emerald-950/40 dark:border-emerald-700/40">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="block text-xs font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300">
                                            Kembalian
                                        </span>
                                        <span class="text-[11px] text-emerald-600/80 dark:text-emerald-400/80">
                                            {{ ($order['change_amount'] ?? 0) <= 0 ? 'Uang pas diterima' : 'Kembalikan ke pelanggan' }}
                                        </span>
                                    </div>
                                    <span class="text-2xl font-black tabular-nums tracking-tight text-emerald-700 dark:text-emerald-300">
                                        Rp {{ number_format(max(0, $order['change_amount'] ?? 0), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Prompt banner --}}
                <div class="mt-3 flex items-center justify-center gap-2 rounded-xl bg-gray-50/75 py-2 px-3 text-center text-xs text-gray-500 dark:bg-white/[0.02] dark:text-gray-400">
                    <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak struk belanja sekarang atau langsung buat order baru</span>
                </div>
            </div>

            {{-- Footer Action Buttons --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5 border-t border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-white/10 dark:bg-white/[0.02]">
                <button type="button"
                        wire:click="closeSimpleModeModal"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-xs hover:bg-gray-50 active:scale-[0.98] dark:border-white/20 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tutup / Order Baru</span>
                </button>
                <button type="button"
                        onclick="window.open('{{ $order['print_url'] ?? '#' }}', '_blank')"
                        wire:click="closeSimpleModeModal"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-500 active:scale-[0.98] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Struk</span>
                </button>
            </div>
        </div>
    </div>
@endif
