@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $lockDetails = \App\Support\SubscriptionAccess::getLockDetails($tenant instanceof \App\Models\Restaurant ? $tenant : null);
@endphp

<div
    x-data="{
        open: false,
        details: @js($lockDetails),
        close() {
            this.open = false;
        }
    }"
    @open-subscription-lock-modal.window="
        open = true;
        if ($event.detail && typeof $event.detail === 'object') {
            details = Object.assign({}, details, $event.detail);
        }
    "
    @keydown.escape.window="if (open) close()"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="subscription-lock-modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;"
>
    {{-- Backdrop with smooth blur --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
        @click="close()"
        aria-hidden="true"
    ></div>

    {{-- Modal Dialog Panel --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800"
            @click.stop
        >
            {{-- Close Button X in Top Right --}}
            <button
                type="button"
                @click="close()"
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg p-1 transition-colors"
                aria-label="Tutup"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="p-6 sm:p-7">
                {{-- Icon Gembok / Warning Header --}}
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400 ring-8 ring-rose-500/5 dark:ring-rose-500/10">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <div>
                        <span class="inline-flex items-center rounded-md bg-rose-100 dark:bg-rose-950/80 px-2 py-0.5 text-[11px] font-bold tracking-wider text-rose-700 dark:text-rose-300 uppercase">
                            Akses Dinonaktifkan Sementara
                        </span>
                        <h3 id="subscription-lock-modal-title" class="mt-1 text-lg font-bold text-slate-900 dark:text-white leading-snug">
                            Layanan Terkunci — Selesaikan Pembayaran
                        </h3>
                    </div>
                </div>

                {{-- Detail Tagihan Box --}}
                <div class="mt-5 rounded-xl border border-rose-200/80 bg-rose-50/70 p-4 text-xs leading-relaxed text-rose-900 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
                    <div class="flex items-start gap-2.5">
                        <svg class="h-5 w-5 shrink-0 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-rose-950 dark:text-rose-100">
                                Terdapat tagihan komisi kasir / langganan yang belum diselesaikan
                                <template x-if="details.period_month">
                                    <span>(Periode: <strong x-text="details.period_month"></strong><template x-if="details.formatted_amount"><span> &bull; Total: <strong x-text="details.formatted_amount"></strong></span></template>).</span>
                                </template>
                            </p>
                            <p class="mt-1 text-slate-600 dark:text-slate-400">
                                Fitur operasional (Order Kasir POS, Shift Kasir, Dapur KDS, Manajemen Meja), Laporan, serta CRM Pelanggan dinonaktifkan sementara sampai pembayaran diverifikasi.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Guarantee Banner (Tenang, Website Publik Tetap Aktif) --}}
                <div class="mt-3.5 flex items-center gap-2.5 rounded-xl border border-emerald-200/80 bg-emerald-50/60 p-3 text-xs text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-950/20 dark:text-emerald-300">
                    <svg class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-[11.5px] leading-tight">
                        <strong>Website publik & katalog menu restoran Anda tetap 100% aktif</strong> dan dapat diakses pelanggan.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-6 flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 sm:gap-3">
                    <button
                        type="button"
                        @click="close()"
                        class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 transition"
                    >
                        Tutup
                    </button>
                    <a
                        :href="details.billing_url"
                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 hover:bg-primary-700 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:shadow transition"
                    >
                        <span>Lihat Tagihan & Lunasi Sekarang</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
