<x-filament-panels::page>
    @php
        $stats = $analytics;
        $settings = $loyaltySettings;
        $isLoyalty = (bool) ($settings['enabled'] ?? false);
    @endphp

    <div class="space-y-6 w-full">
        {{-- Status Banner jika Program Poin & Tier Dinonaktifkan --}}
        @if (! $isLoyalty)
            <div class="vision-card p-4 sm:p-5 border-l-4 border-l-amber-500 bg-amber-50/60 dark:bg-amber-950/20">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="vision-icon-box vision-icon-box-orange shrink-0">
                        <x-heroicon-o-information-circle class="h-5 w-5 text-white" />
                    </div>
                    <div class="text-sm text-slate-700 dark:text-slate-300">
                        <p class="font-bold text-slate-900 dark:text-white">Program Poin & Member Loyalty Sedang Dinonaktifkan</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Analitik CRM tetap berjalan 100% melacak profil tamu, frekuensi kunjungan, dan akumulasi omset belanja. Poin reward tidak dihitung dan badge tier tidak dicetak pada struk kasir. Anda dapat mengaktifkannya kembali sewaktu-waktu melalui tombol <span class="font-semibold text-amber-600 dark:text-amber-400">"Pengaturan Poin & Tier"</span> di pojok kanan atas.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Row 1: Vision UI Main KPI Cards (4 Kolom Sesuai Dashboard) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4 sm:gap-5">
            {{-- Card 1: Total Tamu Terdata --}}
            <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                Total Tamu Terdata (CRM)
                            </p>
                        </div>
                        <div class="vision-icon-box vision-icon-box-blue shadow-md">
                            <x-heroicon-o-user-group class="h-5 w-5 text-white" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                            {{ number_format($stats['total_customers']) }}
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border-cyan-500/20">
                                +{{ number_format($stats['new_this_month']) }} Tamu Baru Bulan Ini
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>{{ number_format($stats['one_time_customers']) }} tamu 1x kunjungan</span>
                    <a href="{{ \App\Filament\Resources\Customers\CustomerResource::getUrl('index') }}"
                        class="inline-flex items-center gap-1 font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors shrink-0 ml-2">
                        Kelola Tamu →
                    </a>
                </div>
            </div>

            {{-- Card 2: Tingkat Tamu Berulang (Repeat Retention) --}}
            <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                Tamu Setia (Repeat)
                            </p>
                        </div>
                        <div class="vision-icon-box vision-icon-box-green shadow-md">
                            <x-heroicon-o-arrow-path class="h-5 w-5 text-white" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                            {{ number_format($stats['repeat_customers']) }} Tamu
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20">
                                {{ $stats['repeat_rate'] }}% Tingkat Kedatangan Ulang
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Rata-rata {{ $stats['avg_orders_per_customer'] }}x order / tamu</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                        Customer Retention
                    </span>
                </div>
            </div>

            {{-- Card 3: Total Omset CRM (LTV) --}}
            <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                Total Omset CRM (LTV)
                            </p>
                        </div>
                        <div class="vision-icon-box vision-icon-box-purple shadow-md">
                            <x-heroicon-o-banknotes class="h-5 w-5 text-white" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                            Rp {{ number_format($stats['total_ltv'], 0, ',', '.') }}
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-purple-500/15 text-purple-600 dark:text-purple-400 border-purple-500/20">
                                Rata-rata LTV: Rp {{ number_format($stats['average_ltv'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Akumulasi belanja tamu terdata</span>
                    <span class="font-semibold text-slate-400 dark:text-slate-500">
                        Kontribusi Omset
                    </span>
                </div>
            </div>

            {{-- Card 4: Saldo Poin Beredar atau Rata-rata Belanja (AOV) --}}
            <div class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                {{ $isLoyalty ? 'Saldo Poin Beredar' : 'Rata-rata Belanja (AOV)' }}
                            </p>
                        </div>
                        <div class="vision-icon-box vision-icon-box-orange shadow-md">
                            @if ($isLoyalty)
                                <x-heroicon-o-sparkles class="h-5 w-5 text-white" />
                            @else
                                <x-heroicon-o-calculator class="h-5 w-5 text-white" />
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default">
                            @if ($isLoyalty)
                                {{ number_format($stats['total_points']) }} Poin
                            @else
                                Rp {{ number_format($stats['average_order_value'], 0, ',', '.') }}
                            @endif
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            @if ($isLoyalty)
                                <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/20">
                                    AOV: Rp {{ number_format($stats['average_order_value'], 0, ',', '.') }} /order
                                </span>
                            @else
                                <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/20">
                                    Dari {{ number_format($stats['total_orders_count']) }} total pesanan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    @if ($isLoyalty)
                        <span>1 Poin per Rp {{ number_format($settings['spend_per_point'], 0, ',', '.') }}</span>
                        <span class="font-semibold text-amber-600 dark:text-amber-400">
                            Reward Aktif
                        </span>
                    @else
                        <span>Rata-rata nilai transaksi per pesanan</span>
                        <span class="font-semibold text-slate-400 dark:text-slate-500">
                            Basket Size
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Row 2: Sub-Row Vision Pill Capsules (Persis seperti Kapsul Dashboard KPI) --}}
        <div>
            <div class="flex items-center justify-between mb-2.5 px-0.5">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-chart-pie class="h-4 w-4 text-sky-500 dark:text-cyan-400" />
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        {{ $isLoyalty ? 'Distribusi Member Tier & Keaktifan Tamu' : 'Segmentasi & Keaktifan Tamu' }}
                    </span>
                </div>
                <span class="text-xs text-slate-400 dark:text-slate-500">
                    {{ number_format($stats['total_orders_count']) }} Total Transaksi CRM
                </span>
            </div>

            @if ($isLoyalty)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
                    {{-- Tier Reguler --}}
                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400 shrink-0"></span>
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-300 truncate">Tier Reguler</span>
                        </div>
                        <span class="font-bold text-slate-800 dark:text-white text-xs shrink-0 ml-1">
                            {{ number_format($stats['tier_counts']['reguler']) }}
                        </span>
                    </div>

                    {{-- Tier Silver --}}
                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300 border border-slate-400 shrink-0"></span>
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-300 truncate">Tier Silver</span>
                        </div>
                        <span class="font-bold text-slate-800 dark:text-white text-xs shrink-0 ml-1">
                            {{ number_format($stats['tier_counts']['silver']) }}
                        </span>
                    </div>

                    {{-- Tier Gold --}}
                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shrink-0"></span>
                            <span class="text-xs font-medium text-amber-600 dark:text-amber-400 truncate font-semibold">Tier Gold</span>
                        </div>
                        <span class="font-bold text-amber-600 dark:text-amber-400 text-xs shrink-0 ml-1">
                            {{ number_format($stats['tier_counts']['gold']) }}
                        </span>
                    </div>

                    {{-- Tier VIP --}}
                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500 shadow-sm shadow-purple-500/50 shrink-0"></span>
                            <span class="text-xs font-medium text-purple-600 dark:text-purple-400 truncate font-semibold">Tier VIP</span>
                        </div>
                        <span class="font-bold text-purple-600 dark:text-purple-400 text-xs shrink-0 ml-1">
                            {{ number_format($stats['tier_counts']['vip']) }}
                        </span>
                    </div>

                    {{-- Aktif 30 Hari Terakhir --}}
                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <x-heroicon-o-fire class="h-4 w-4 text-emerald-500 shrink-0" />
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-300 truncate">Aktif (30h)</span>
                        </div>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400 text-xs shrink-0 ml-1">
                            {{ number_format($stats['active_30d_customers']) }}
                        </span>
                    </div>

                    {{-- Dormant / Perlu Disapa --}}
                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <x-heroicon-o-clock class="h-4 w-4 text-amber-500 shrink-0" />
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-300 truncate">Dormant (>30h)</span>
                        </div>
                        <span class="font-bold text-slate-500 dark:text-slate-400 text-xs shrink-0 ml-1">
                            {{ number_format($stats['dormant_customers']) }}
                        </span>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 font-medium">
                            <x-heroicon-o-sparkles class="h-4 w-4 text-cyan-500" />
                            <span>Tamu Baru Bulan Ini</span>
                        </div>
                        <span class="font-bold text-slate-800 dark:text-white">
                            {{ number_format($stats['new_this_month']) }} Tamu
                        </span>
                    </div>

                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 font-medium">
                            <x-heroicon-o-fire class="h-4 w-4 text-emerald-500" />
                            <span>Aktif (30 Hari Terakhir)</span>
                        </div>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">
                            {{ number_format($stats['active_30d_customers']) }} Tamu
                        </span>
                    </div>

                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 font-medium">
                            <x-heroicon-o-clock class="h-4 w-4 text-amber-500" />
                            <span>Dormant (>30 Hari Tidak Datang)</span>
                        </div>
                        <span class="font-bold text-slate-500 dark:text-slate-400">
                            {{ number_format($stats['dormant_customers']) }} Tamu
                        </span>
                    </div>

                    <div class="vision-pill-card px-3.5 py-2.5 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 font-medium">
                            <x-heroicon-o-shopping-bag class="h-4 w-4 text-sky-500" />
                            <span>Total Pesanan CRM</span>
                        </div>
                        <span class="font-bold text-slate-800 dark:text-white">
                            {{ number_format($stats['total_orders_count']) }} Transaksi
                        </span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Row 3A: Tabel 1 Full Width - Pelanggan Paling Sering Datang (Most Frequent) --}}
        <div class="vision-card w-full p-5 sm:p-6">
            <div>
                {{-- Header Table --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-white/5">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide flex items-center gap-2">
                            <x-heroicon-o-arrow-trending-up class="h-5 w-5 text-sky-500 dark:text-cyan-400" />
                            Pelanggan Paling Sering Datang
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Urutan berdasarkan akumulasi frekuensi kunjungan & transaksi di restoran Anda
                        </p>
                    </div>
                    <div class="vision-icon-box vision-icon-box-blue shadow-sm">
                        <x-heroicon-o-arrow-trending-up class="h-5 w-5 text-white" />
                    </div>
                </div>

                {{-- Table Full Width --}}
                <div class="mt-3 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="px-4 py-2.5 w-12 text-center">#</th>
                                <th class="px-4 py-2.5">Pelanggan</th>
                                @if ($isLoyalty)
                                    <th class="px-4 py-2.5 text-center">Tier</th>
                                @endif
                                <th class="px-4 py-2.5 text-right">Kunjungan</th>
                                <th class="px-4 py-2.5 text-right">Rata-rata/Order (AOV)</th>
                                <th class="px-4 py-2.5 text-right">Total Belanja (LTV)</th>
                                @if ($isLoyalty)
                                    <th class="px-4 py-2.5 text-right">Poin</th>
                                @endif
                                <th class="px-4 py-2.5 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse ($stats['top_frequent'] as $idx => $c)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    {{-- Rank Badge --}}
                                    <td class="px-4 py-3 text-center">
                                        @if ($idx === 0)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white bg-gradient-to-br from-amber-400 to-amber-600 shadow-sm shadow-amber-500/30">1</span>
                                        @elseif ($idx === 1)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white bg-gradient-to-br from-slate-300 to-slate-500 shadow-sm shadow-slate-500/30">2</span>
                                        @elseif ($idx === 2)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white bg-gradient-to-br from-amber-700 to-amber-900 shadow-sm shadow-amber-800/30">3</span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400">{{ $idx + 1 }}</span>
                                        @endif
                                    </td>

                                    {{-- Data Pelanggan --}}
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900 dark:text-white leading-tight">
                                            {{ $c->name ?: 'Tamu Terdaftar' }}
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            <span>{{ $c->formattedPhone() }}</span>
                                            @if ($c->last_visit_at)
                                                <span class="text-[10px] px-1.5 py-0.2 rounded bg-slate-100 dark:bg-white/5 text-slate-400">
                                                    {{ $c->lastVisitForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Tier Badge --}}
                                    @if ($isLoyalty)
                                        <td class="px-4 py-3 text-center">
                                            @php
                                                $tier = strtolower($c->tier);
                                                $tierClass = match($tier) {
                                                    'vip' => 'bg-purple-500/15 text-purple-600 dark:text-purple-400 border-purple-500/25',
                                                    'gold' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/25',
                                                    'silver' => 'bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border-cyan-500/25',
                                                    default => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $tierClass }}">
                                                {{ $c->tierLabel() }}
                                            </span>
                                        </td>
                                    @endif

                                    {{-- Kunjungan --}}
                                    <td class="px-4 py-3 text-right">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-extrabold bg-sky-500/10 text-sky-600 dark:text-cyan-400 border border-sky-500/20">
                                            {{ number_format($c->total_orders) }}x
                                        </span>
                                    </td>

                                    {{-- Rata-rata per Order (AOV) --}}
                                    <td class="px-4 py-3 text-right font-mono text-xs text-slate-600 dark:text-slate-300">
                                        Rp {{ number_format($c->averageSpendPerOrder(), 0, ',', '.') }}
                                    </td>

                                    {{-- Total Belanja --}}
                                    <td class="px-4 py-3 text-right font-mono font-bold text-slate-900 dark:text-white">
                                        Rp {{ number_format($c->total_spent, 0, ',', '.') }}
                                    </td>

                                    {{-- Saldo Poin --}}
                                    @if ($isLoyalty)
                                        <td class="px-4 py-3 text-right font-mono font-bold text-amber-600 dark:text-amber-400 text-xs">
                                            {{ number_format($c->points_balance) }}
                                        </td>
                                    @endif

                                    {{-- Aksi Direct WA --}}
                                    <td class="px-4 py-3 text-center">
                                        @if ($c->waLink())
                                            <a href="{{ $c->waLink('Halo Kak ' . ($c->name ?: '') . ', terima kasih sudah menjadi pelanggan setia resto kami!') }}"
                                                target="_blank"
                                                title="Sapa via WhatsApp"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/25 transition-all">
                                                <x-heroicon-o-chat-bubble-left-ellipsis class="h-3.5 w-3.5 text-emerald-500" />
                                                <span>WA</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isLoyalty ? 8 : 6 }}" class="py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 mb-2">
                                                <x-heroicon-o-user-group class="h-5 w-5 text-slate-400" />
                                            </div>
                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada data pelanggan</p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Tamu akan terdata otomatis saat kasir menginput nomor WhatsApp di POS Kasir.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Card --}}
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs">
                <span class="text-slate-400 dark:text-slate-500">Menampilkan 10 tamu paling sering berkunjung</span>
                <a href="{{ \App\Filament\Resources\Customers\CustomerResource::getUrl('index') }}"
                    class="font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors inline-flex items-center gap-1">
                    Lihat Seluruh Tamu CRM di Master Data →
                </a>
            </div>
        </div>

        {{-- Row 3B: Tabel 2 Full Width - Pelanggan Belanja Terbesar (Top Spenders / Top LTV) --}}
        <div class="vision-card w-full p-5 sm:p-6">
            <div>
                {{-- Header Table --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-white/5">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide flex items-center gap-2">
                            <x-heroicon-o-trophy class="h-5 w-5 text-amber-500" />
                            Pelanggan Belanja Terbesar (Top LTV)
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Urutan berdasarkan kontribusi akumulasi omset tertinggi bagi resto Anda
                        </p>
                    </div>
                    <div class="vision-icon-box vision-icon-box-orange shadow-sm">
                        <x-heroicon-o-trophy class="h-5 w-5 text-white" />
                    </div>
                </div>

                {{-- Table Full Width --}}
                <div class="mt-3 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="px-4 py-2.5 w-12 text-center">#</th>
                                <th class="px-4 py-2.5">Pelanggan</th>
                                @if ($isLoyalty)
                                    <th class="px-4 py-2.5 text-center">Tier</th>
                                @endif
                                <th class="px-4 py-2.5 text-right">Total Belanja (LTV)</th>
                                <th class="px-4 py-2.5 text-right">Kunjungan</th>
                                <th class="px-4 py-2.5 text-right">Rata-rata/Order (AOV)</th>
                                @if ($isLoyalty)
                                    <th class="px-4 py-2.5 text-right">Poin</th>
                                @endif
                                <th class="px-4 py-2.5 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse ($stats['top_spenders'] as $idx => $c)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    {{-- Rank Badge --}}
                                    <td class="px-4 py-3 text-center">
                                        @if ($idx === 0)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white bg-gradient-to-br from-amber-400 to-amber-600 shadow-sm shadow-amber-500/30">1</span>
                                        @elseif ($idx === 1)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white bg-gradient-to-br from-slate-300 to-slate-500 shadow-sm shadow-slate-500/30">2</span>
                                        @elseif ($idx === 2)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white bg-gradient-to-br from-amber-700 to-amber-900 shadow-sm shadow-amber-800/30">3</span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400">{{ $idx + 1 }}</span>
                                        @endif
                                    </td>

                                    {{-- Data Pelanggan --}}
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900 dark:text-white leading-tight">
                                            {{ $c->name ?: 'Tamu Terdaftar' }}
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            <span>{{ $c->formattedPhone() }}</span>
                                            @if ($c->last_visit_at)
                                                <span class="text-[10px] px-1.5 py-0.2 rounded bg-slate-100 dark:bg-white/5 text-slate-400">
                                                    {{ $c->lastVisitForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Tier Badge --}}
                                    @if ($isLoyalty)
                                        <td class="px-4 py-3 text-center">
                                            @php
                                                $tier = strtolower($c->tier);
                                                $tierClass = match($tier) {
                                                    'vip' => 'bg-purple-500/15 text-purple-600 dark:text-purple-400 border-purple-500/25',
                                                    'gold' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/25',
                                                    'silver' => 'bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border-cyan-500/25',
                                                    default => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $tierClass }}">
                                                {{ $c->tierLabel() }}
                                            </span>
                                        </td>
                                    @endif

                                    {{-- Total Belanja (LTV) --}}
                                    <td class="px-4 py-3 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($c->total_spent, 0, ',', '.') }}
                                    </td>

                                    {{-- Kunjungan --}}
                                    <td class="px-4 py-3 text-right">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-extrabold bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10">
                                            {{ number_format($c->total_orders) }}x
                                        </span>
                                    </td>

                                    {{-- Rata-rata per Order (AOV) --}}
                                    <td class="px-4 py-3 text-right font-mono text-xs text-slate-600 dark:text-slate-300">
                                        Rp {{ number_format($c->averageSpendPerOrder(), 0, ',', '.') }}
                                    </td>

                                    {{-- Saldo Poin --}}
                                    @if ($isLoyalty)
                                        <td class="px-4 py-3 text-right font-mono font-bold text-amber-600 dark:text-amber-400 text-xs">
                                            {{ number_format($c->points_balance) }}
                                        </td>
                                    @endif

                                    {{-- Aksi Direct WA --}}
                                    <td class="px-4 py-3 text-center">
                                        @if ($c->waLink())
                                            <a href="{{ $c->waLink('Halo Kak ' . ($c->name ?: '') . ', terima kasih atas kepercayaan dan kunjungan Anda di resto kami!') }}"
                                                target="_blank"
                                                title="Sapa via WhatsApp"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/25 transition-all">
                                                <x-heroicon-o-chat-bubble-left-ellipsis class="h-3.5 w-3.5 text-emerald-500" />
                                                <span>WA</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isLoyalty ? 8 : 6 }}" class="py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 mb-2">
                                                <x-heroicon-o-trophy class="h-5 w-5 text-slate-400" />
                                            </div>
                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada kontribusi omset</p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Top spender akan diperbarui otomatis saat transaksi kasir terselesaikan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Card --}}
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs">
                <span class="text-slate-400 dark:text-slate-500">Menampilkan 10 kontributor omset tertinggi</span>
                <a href="{{ \App\Filament\Resources\Customers\CustomerResource::getUrl('index') }}"
                    class="font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors inline-flex items-center gap-1">
                    Buka Master Data Pelanggan CRM →
                </a>
            </div>
        </div>

        {{-- Row 4: Actionable CRM Business Insights (Vision UI Cards Sesuai Dashboard) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
            <div class="vision-card p-4 sm:p-5 flex items-start gap-3.5">
                <div class="vision-icon-box vision-icon-box-purple shrink-0 mt-0.5">
                    <x-heroicon-o-star class="h-5 w-5 text-white" />
                </div>
                <div class="text-xs">
                    <p class="font-bold text-slate-900 dark:text-white text-sm">Apresiasi Tamu VIP & Loyal</p>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Tamu dengan frekuensi atau nominal belanja tinggi memberikan kontribusi terbesar bagi kestabilan omset resto. Berikan sambutan hangat dan perhatian personal saat mereka berkunjung kembali.
                    </p>
                </div>
            </div>

            <div class="vision-card p-4 sm:p-5 flex items-start gap-3.5">
                <div class="vision-icon-box vision-icon-box-orange shrink-0 mt-0.5">
                    <x-heroicon-o-chat-bubble-bottom-center-text class="h-5 w-5 text-white" />
                </div>
                <div class="text-xs">
                    <p class="font-bold text-slate-900 dark:text-white text-sm">Re-engagement Tamu Dormant</p>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Terdapat <span class="font-bold text-amber-600 dark:text-amber-400">{{ number_format($stats['dormant_customers']) }} tamu</span> yang belum berkunjung dalam 30 hari terakhir. Gunakan tombol WhatsApp langsung pada tabel untuk mengirimkan sapaan ramah atau info menu baru.
                    </p>
                </div>
            </div>

            <div class="vision-card p-4 sm:p-5 flex items-start gap-3.5">
                <div class="vision-icon-box vision-icon-box-green shrink-0 mt-0.5">
                    <x-heroicon-o-arrow-trending-up class="h-5 w-5 text-white" />
                </div>
                <div class="text-xs">
                    <p class="font-bold text-slate-900 dark:text-white text-sm">Optimasi Nilai Keranjang (AOV)</p>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Rata-rata belanja per kunjungan saat ini adalah <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($stats['average_order_value'], 0, ',', '.') }}</span>. Terapkan bundling menu favorit kasir atau menu penutup untuk mendongkrak rata-rata belanja per transaksi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
