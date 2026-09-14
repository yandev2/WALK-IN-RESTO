@props([
    'summary' => [],
])

@php
    $fmt = fn($val) => 'Rp ' . number_format((int) $val, 0, ',', '.');
    $from = $summary['from'] ?? now()->startOfMonth();
    $to = $summary['to'] ?? now();
    $timezone = $summary['timezone'] ?? 'Asia/Jakarta';
    $isCommissionPlan = $summary['is_commission_plan'] ?? true;
    $commissionRate = $summary['commission_rate'] ?? 10.0;
    $isTrial = $summary['is_trial_active_throughout'] ?? false;
    $relatedInvoice = $summary['related_invoice'] ?? null;
    $tenant = \Filament\Facades\Filament::getTenant();
    $billingUrl = null;
    try {
        $billingUrl = $tenant ? \App\Filament\Pages\SubscriptionStatus::getUrl(tenant: $tenant) : null;
    } catch (\Throwable) {
        $billingUrl = null;
    }
@endphp

<div class="space-y-4 sm:space-y-5 mb-6 w-full">
    {{-- Top Context Bar: Active Range & Scheme Indicator --}}
    <div class="vision-card px-4 py-3 sm:px-5 sm:py-3.5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5 flex-wrap">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                <x-filament::icon icon="heroicon-o-calendar-days"
                    class="h-4 w-4 text-sky-500 dark:text-cyan-400 shrink-0" />
                <span>Periode:</span>
                <span class="font-bold text-slate-900 dark:text-white">
                    {{ $from->format('d M Y') }} — {{ $to->format('d M Y') }}
                </span>
            </div>
            <span
                class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20">
                {{ $timezone }}
            </span>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if ($isTrial)
                <span
                    class="inline-flex items-center gap-1.5 text-[11px] font-bold px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Masa Uji Coba (Bebas Komisi)
                </span>
            @elseif($isCommissionPlan)
                <span
                    class="inline-flex items-center gap-1.5 text-[11px] font-bold px-3 py-1 rounded-full bg-cyan-500/15 text-cyan-700 dark:text-cyan-300 border border-cyan-500/20 shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                    Bagi Hasil Kasir ({{ $commissionRate }}%)
                </span>
            @else
                <span
                    class="inline-flex items-center gap-1.5 text-[11px] font-bold px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Paket Langganan Tetap (Bebas Komisi)
                </span>
            @endif
        </div>
    </div>

    {{-- Row 1: Vision UI Main 4 KPI Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-4 gap-4 sm:gap-5">
        {{-- 1. Penjualan Kotor (Gross Sales) --}}
        <div
            class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                            Penjualan Kotor (Menu)
                        </p>
                    </div>
                    <div class="vision-icon-box vision-icon-box-blue shadow-md shadow-sky-500/20">
                        <x-filament::icon icon="heroicon-o-shopping-bag" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-3">
                    <div
                        class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                        {{ $fmt($summary['gross_sales'] ?? 0) }}
                    </div>
                    <div class="mt-2.5">
                        <span
                            class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-slate-500/10 text-slate-600 dark:text-slate-300 border-slate-500/20">
                            {{ $summary['total_orders'] ?? 0 }} Pesanan Berhasil
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex flex-col gap-1 text-xs text-slate-500 dark:text-slate-400">
                <div class="flex items-center justify-between">
                    <span>Diskon & Void:</span>
                    <span class="font-semibold text-rose-600 dark:text-rose-400">
                        -{{ $fmt(($summary['discount_amount'] ?? 0) + ($summary['void_cut_amount'] ?? 0)) }}
                    </span>
                </div>
                @if (($summary['points_discount_amount'] ?? 0) > 0)
                    <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium flex items-center justify-between">
                        <span>✨ Diskon Poin ({{ $summary['points_redeemed'] ?? 0 }} Poin):</span>
                        <span class="font-bold">-{{ $fmt($summary['points_discount_amount'] ?? 0) }} (Bebas Komisi)</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. Penjualan Bersih (Net Sales) --}}
        <div
            class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                            Penjualan Bersih (Menu)
                        </p>
                    </div>
                    <div class="vision-icon-box vision-icon-box-purple shadow-md shadow-indigo-500/20">
                        <x-filament::icon icon="heroicon-o-presentation-chart-line" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-3">
                    <div
                        class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                        {{ $fmt($summary['net_sales'] ?? 0) }}
                    </div>
                    <div class="mt-2.5">
                        <span
                            class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border-cyan-500/20">
                            Dasar Komisi (Murni Menu)
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                <span>PB1 & Service:</span>
                <span class="font-semibold text-emerald-600 dark:text-emerald-400">+{{ $fmt($summary['tax_service_amount'] ?? 0) }} (Bebas Komisi)</span>
            </div>
        </div>

        {{-- 3. Komisi Platform --}}
        <div
            class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                            Komisi Platform ({{ $commissionRate }}%)
                        </p>
                    </div>
                    <div class="vision-icon-box vision-icon-box-orange shadow-md shadow-orange-500/20">
                        <x-filament::icon icon="heroicon-o-bolt" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-3">
                    <div
                        class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                        {{ $fmt($summary['commission_amount'] ?? 0) }}
                    </div>
                    <div class="mt-2.5">
                        @if (!$isCommissionPlan)
                            <span
                                class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20">
                                Bebas Komisi (Paket Tetap)
                            </span>
                        @elseif($isTrial)
                            <span
                                class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20">
                                Uji Coba (Bebas Komisi)
                            </span>
                        @elseif(!empty($relatedInvoice))
                            <span
                                class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/20">
                                Tagihan #{{ $relatedInvoice['number'] }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-orange-500/15 text-orange-600 dark:text-orange-400 border-orange-500/20">
                                Porsi Layanan Kasir
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                <span>Dasar hitung:</span>
                <span class="font-semibold text-sky-600 dark:text-cyan-400">Murni Menu Saja (0% Pajak)</span>
            </div>
        </div>

        {{-- 4. Hak Bersih Restoran --}}
        <div
            class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                            Hak Bersih Restoran
                        </p>
                    </div>
                    <div class="vision-icon-box vision-icon-box-green shadow-md shadow-emerald-500/20">
                        <x-filament::icon icon="heroicon-o-banknotes" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-3">
                    <div
                        class="text-2xl sm:text-3xl font-extrabold tracking-tight text-emerald-600 dark:text-emerald-400 cursor-default">
                        {{ $fmt($summary['net_payout'] ?? 0) }}
                    </div>
                    <div class="mt-2.5">
                        <span
                            class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20">
                            Total Kas Masuk - Komisi
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                <span>Hasil operasional:</span>
                <span class="font-semibold text-emerald-600 dark:text-emerald-400">100% Hak Resto</span>
            </div>
        </div>
    </div>

    {{-- Row 2: Realisasi Kas Masuk & Jaminan Transparansi --}}
    <div class="grid grid-cols-1 lg:grid-cols-1 2xl:grid-cols-2 gap-4 sm:gap-5">
        {{-- Card A: Realisasi Kas Masuk (Laci Kasir vs Digital) --}}
        <div class="vision-card p-5 sm:p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100 dark:border-white/5">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                            Realisasi Kas Masuk
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Pemisahan uang fisik di laci kasir dan uang non-tunai digital
                        </p>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-500/10 text-slate-700 dark:text-slate-300 border border-slate-500/20 shrink-0">
                        Total: {{ $fmt(($summary['cash_collected'] ?? 0) + ($summary['qris_collected'] ?? 0)) }}
                    </span>
                </div>

                <div class="mt-4 space-y-3">
                    {{-- Cash in Drawer Pill --}}
                    <div class="vision-pill-card px-4 py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                <x-filament::icon icon="heroicon-o-banknotes" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-white block">Uang Tunai (Laci
                                    Kasir)</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Diterima langsung kasir
                                    tunai</span>
                            </div>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900 dark:text-white">
                            {{ $fmt($summary['cash_collected'] ?? 0) }}
                        </span>
                    </div>

                    {{-- QRIS / Digital Settlement Pill --}}
                    <div class="vision-pill-card px-4 py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-lg bg-sky-500/15 flex items-center justify-center text-sky-600 dark:text-cyan-400 shrink-0">
                                <x-filament::icon icon="heroicon-o-qr-code" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-white block">Non-Tunai (QRIS /
                                    Transfer)</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Masuk rekening settlement
                                    digital</span>
                            </div>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900 dark:text-white">
                            {{ $fmt($summary['qris_collected'] ?? 0) }}
                        </span>
                    </div>

                    @if (($summary['tax_service_amount'] ?? 0) > 0)
                        {{-- Tax & Service Breakdown Pill --}}
                        <div class="px-3.5 py-2.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-xs text-slate-700 dark:text-slate-300 flex flex-wrap items-center justify-between gap-2">
                            <span>Omzet Murni Menu: <strong>{{ $fmt($summary['net_sales'] ?? 0) }}</strong></span>
                            <span class="text-emerald-700 dark:text-emerald-400 font-bold">+ Titipan Pajak & Service: {{ $fmt($summary['tax_service_amount'] ?? 0) }} (Bebas Komisi)</span>
                        </div>
                    @endif
                </div>
            </div>

            <div
                class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 text-xs text-slate-500 dark:text-slate-400">
                <span>Dihitung dari seluruh pesanan berstatus lunas / selesai pada rentang tanggal aktif.</span>
            </div>
        </div>

        {{-- Card B: Jaminan Transparansi & Kebijakan Nol Selisih --}}
        <div class="vision-card p-5 sm:p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100 dark:border-white/5">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                            Jaminan Transparansi & Kebijakan
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Standar bagi hasil kasir transparan tanpa perselisihan angka
                        </p>
                    </div>
                    <div class="vision-icon-box vision-icon-box-blue shadow-sm shrink-0">
                        <x-filament::icon icon="heroicon-o-shield-check" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    {{-- Void Policy Pill --}}
                    <div class="vision-pill-card px-4 py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-lg bg-rose-500/15 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0">
                                <x-filament::icon icon="heroicon-o-no-symbol" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-white block">Pesanan Void (Omzet
                                    Dicut)</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Pesanan batal sebelum
                                    dimasak / dicut</span>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            Bebas Komisi (Rp 0)
                        </span>
                    </div>

                    {{-- Loyalty Points Policy Pill --}}
                    <div class="vision-pill-card px-4 py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                <x-filament::icon icon="heroicon-o-ticket" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-white block">Diskon Poin Member</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Potongan harga ditukar dari poin loyalitas</span>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            Bebas Komisi (Rp 0)
                        </span>
                    </div>

                    {{-- Tax & Service Charge Policy Pill --}}
                    <div class="vision-pill-card px-4 py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-lg bg-cyan-500/15 flex items-center justify-center text-cyan-600 dark:text-cyan-400 shrink-0">
                                <x-filament::icon icon="heroicon-o-receipt-percent" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-white block">Pajak PB1 &amp; Service Charge</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Titipan kas daerah &amp; hak layanan staf</span>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            Bebas Komisi (Rp 0)
                        </span>
                    </div>

                    {{-- Invoice Link Pill --}}
                    <div class="vision-pill-card px-4 py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                                <x-filament::icon icon="heroicon-o-document-currency-dollar" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-white block">Tagihan Resmi
                                    Platform</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                    @if (!empty($relatedInvoice))
                                        Invoice #{{ $relatedInvoice['number'] }}
                                        ({{ ucfirst($relatedInvoice['status']) }})
                                    @else
                                        Diterbitkan otomatis pada tanggal 1 bulan berikutnya
                                    @endif
                                </span>
                            </div>
                        </div>
                        @if ($billingUrl)
                            <a href="{{ $billingUrl }}"
                                class="inline-flex items-center gap-1 font-bold text-sky-600 dark:text-cyan-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors">
                                <span>Status Tagihan</span>
                                <x-filament::icon icon="heroicon-m-arrow-up-right" class="h-3.5 w-3.5" />
                            </a>
                        @else
                            <span
                                class="inline-flex items-center gap-1 font-semibold text-slate-500 dark:text-slate-400">
                                <span>Status Tagihan</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 text-xs text-slate-500 dark:text-slate-400 italic">
                * Platform HANYA mengambil komisi dari penjualan murni harga menu makanan/minuman saja. Pajak PB1 daerah, Service Charge, Void, dan Diskon Poin 100% bebas dari potongan komisi platform.
            </div>
        </div>
    </div>
</div>
