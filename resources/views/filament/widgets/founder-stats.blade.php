<x-filament-widgets::widget>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 w-full">
        <!-- Card 1: Total Restoran Terdaftar -->
        <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-gray-400">
                            Total Restoran Terdaftar
                        </span>
                    </div>
                    <div class="vision-icon-box vision-icon-box-blue shadow-md">
                        <x-filament::icon icon="heroicon-o-building-storefront" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ number_format($kpi['totalRestaurants']) }} Resto
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 flex-wrap">
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $kpi['restaurantGrowthPct'] >= 0 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/20' }}">
                            <x-filament::icon :icon="$kpi['restaurantGrowthPct'] >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down'" class="h-3 w-3" />
                            {{ $kpi['restaurantGrowthPct'] >= 0 ? '+' : '' }}{{ $kpi['restaurantGrowthPct'] }}% bln ini
                        </span>
                        <span class="text-xs text-slate-500 dark:text-gray-400">
                            {{ $kpi['newRestaurantsThisMonth'] }} resto baru
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-3.5 flex items-center justify-between text-xs text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-white/5 pt-2.5">
                <span>Aktif: <strong class="text-slate-800 dark:text-white">{{ $kpi['activeRestaurants'] }}</strong> resto</span>
                <a href="{{ $tenantUrl }}" class="inline-flex items-center gap-0.5 text-primary-600 dark:text-primary-400 hover:underline font-semibold text-[11px]">
                    Kelola Resto &rarr;
                </a>
            </div>
        </div>

        <!-- Card 2: Pendapatan Sewa Bulan Ini -->
        <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-gray-400">
                            Pendapatan Sewa Bulan Ini
                        </span>
                    </div>
                    <div class="vision-icon-box vision-icon-box-green shadow-md">
                        <x-filament::icon icon="heroicon-o-banknotes" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Rp {{ number_format($kpi['revenueThisMonth'], 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 flex-wrap">
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $kpi['revenueGrowthPct'] >= 0 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/20' }}">
                            <x-filament::icon :icon="$kpi['revenueGrowthPct'] >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down'" class="h-3 w-3" />
                            {{ $kpi['revenueGrowthPct'] >= 0 ? '+' : '' }}{{ $kpi['revenueGrowthPct'] }}% MoM
                        </span>
                        <span class="text-xs text-slate-500 dark:text-gray-400">
                            vs bulan lalu
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-3.5 flex items-center justify-between text-xs text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-white/5 pt-2.5">
                <span>Flat: <strong class="text-slate-800 dark:text-white">Rp {{ number_format($kpi['revenueFlatThisMonth'] / 1000, 0, ',', '.') }}k</strong> • Komisi: <strong class="text-slate-800 dark:text-white">Rp {{ number_format($kpi['revenueCommissionThisMonth'] / 1000, 0, ',', '.') }}k</strong></span>
                <a href="{{ $invoicesUrl }}" class="inline-flex items-center gap-0.5 text-emerald-600 dark:text-emerald-400 hover:underline font-semibold text-[11px]">
                    Invoices &rarr;
                </a>
            </div>
        </div>

        <!-- Card 3: Restoran Mangkir / Overdue -->
        <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-gray-400">
                            Restoran Mangkir / Overdue
                        </span>
                    </div>
                    <div class="vision-icon-box vision-icon-box-orange shadow-md">
                        <x-filament::icon icon="heroicon-o-exclamation-triangle" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ number_format($kpi['overdueRestaurantsCount']) }} Resto
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 flex-wrap">
                        @if ($kpi['overdueRestaurantsCount'] > 0)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/20">
                                <x-filament::icon icon="heroicon-m-exclamation-circle" class="h-3 w-3" />
                                Perlu Ditagih
                            </span>
                            <span class="text-xs font-bold text-rose-600 dark:text-rose-400">
                                Rp {{ number_format($kpi['totalOverdueDebt'], 0, ',', '.') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20">
                                <x-filament::icon icon="heroicon-m-check-circle" class="h-3 w-3" />
                                Semua Lancar
                            </span>
                            <span class="text-xs text-slate-500 dark:text-gray-400">Nihil tunggakan</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-3.5 flex items-center justify-between text-xs text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-white/5 pt-2.5">
                <span>{{ $kpi['graceCount'] }} Grace • {{ $kpi['expiredCount'] }} Expired</span>
                <span class="text-amber-600 dark:text-amber-400 font-semibold text-[11px]">Prioritas Tagih</span>
            </div>
        </div>

        <!-- Card 4: Invoice Perlu Konfirmasi -->
        <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-gray-400">
                            Invoice Perlu Konfirmasi
                        </span>
                    </div>
                    <div class="vision-icon-box vision-icon-box-purple shadow-md">
                        <x-filament::icon icon="heroicon-o-clock" class="h-5 w-5 text-white" />
                    </div>
                </div>

                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ number_format($kpi['pendingVerificationCount']) }} Invoice
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 flex-wrap">
                        @if ($kpi['pendingVerificationCount'] > 0)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/20">
                                <x-filament::icon icon="heroicon-m-arrow-path" class="h-3 w-3 animate-spin" />
                                Validasi Menunggu
                            </span>
                            <span class="text-xs font-bold text-amber-600 dark:text-amber-400">
                                Rp {{ number_format($kpi['pendingVerificationAmount'], 0, ',', '.') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20">
                                <x-filament::icon icon="heroicon-m-check-circle" class="h-3 w-3" />
                                Tervalidasi Semua
                            </span>
                            <span class="text-xs text-slate-500 dark:text-gray-400">Siap</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-3.5 flex items-center justify-between text-xs text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-white/5 pt-2.5">
                <span>Total: <strong class="text-slate-800 dark:text-white">Rp {{ number_format($kpi['pendingVerificationAmount'], 0, ',', '.') }}</strong></span>
                <a href="{{ $pendingInvoicesUrl }}" class="inline-flex items-center gap-0.5 text-purple-600 dark:text-purple-400 hover:underline font-semibold text-[11px]">
                    Review Sekarang &rarr;
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
