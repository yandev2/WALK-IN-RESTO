<x-filament-widgets::widget :attributes="new \Illuminate\View\ComponentAttributeBag()
    ->merge(
        [
            'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
        ],
        escape: false,
    )
    ->class(['fi-wi-analytics-revenue', 'w-full'])">
    @php
        $vision = $this->getVisionAreaData();
    @endphp

    <div class="vision-card p-5 sm:p-6 w-full">
        {{-- Header & Legend Pills --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-white/5">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                    Trafik Penjualan & Tren omzet
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Aktivitas omzet dan penjualan harian ({{ $vision['range_label'] }})
                </p>
            </div>

            {{-- Legend Status Badges --}}
            <div class="flex flex-wrap items-center gap-2">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ $vision['today_omzet'] }} (Hari Ini)</span>
                </span>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-sky-500/10 text-sky-600 dark:text-cyan-400 border border-sky-500/20">
                    <span class="w-2 h-2 rounded-full bg-sky-500 dark:bg-cyan-400 animate-pulse"></span>
                    <span>{{ $vision['seven_days_omzet'] }} (7 Hari)</span>
                </span>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    <span>{{ $vision['total_omzet'] }} (Total Periode)</span>
                </span>
            </div>
        </div>

        {{-- ApexCharts Smooth Area Canvas --}}
        <div wire:key="{{ $this->analyticsChartWireKey('revenue-bar') }}" class="relative mt-4 w-full"
            x-data="visionAreaChart({ series: @js($vision['series']), categories: @js($vision['categories']) })" x-init="init()" x-destroy="destroy()" wire:ignore>
            <div x-ref="chart" class="w-full min-h-[280px]"></div>
        </div>
    </div>
</x-filament-widgets::widget>
