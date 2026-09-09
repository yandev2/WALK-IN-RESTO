<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class(['fi-wi-analytics-period-summary', 'h-full'])
    "
>
    @php
        $period = $this->periodSummary();
    @endphp

    <div class="vision-card h-full flex flex-col justify-between p-5 sm:p-6">
        <div>
            {{-- Header --}}
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-white/5">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                        Ringkasan periode
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ $period['day_count'] }} hari · {{ $period['range_label'] }}
                    </p>
                </div>
                <div class="vision-icon-box vision-icon-box-blue shadow-sm">
                    <x-filament::icon icon="heroicon-o-calendar-days" class="h-5 w-5 text-white" />
                </div>
            </div>

            {{-- 4 Grid Metrics --}}
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                {{-- Total Omzet --}}
                <div class="vision-pill-card p-4 sm:p-5 flex flex-col justify-between">
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Total omzet
                    </p>
                    <p class="mt-2 text-lg sm:text-xl font-black text-slate-900 dark:text-white font-mono">
                        {{ $period['total_omzet_formatted'] }}
                    </p>
                </div>

                {{-- Rata-rata / hari --}}
                <div class="vision-pill-card p-4 sm:p-5 flex flex-col justify-between">
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Rata-rata / hari
                    </p>
                    <p class="mt-2 text-lg sm:text-xl font-black text-slate-900 dark:text-white font-mono">
                        {{ $period['avg_omzet_formatted'] }}
                    </p>
                </div>

                {{-- Total Order --}}
                <div class="vision-pill-card p-4 sm:p-5 flex flex-col justify-between">
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Total order
                    </p>
                    <p class="mt-2 text-lg sm:text-xl font-black text-slate-900 dark:text-white font-mono">
                        {{ number_format($period['total_orders'], 0, ',', '.') }}
                    </p>
                </div>

                {{-- Order / hari --}}
                <div class="vision-pill-card p-4 sm:p-5 flex flex-col justify-between">
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Order / hari
                    </p>
                    <p class="mt-2 text-lg sm:text-xl font-black text-slate-900 dark:text-white font-mono">
                        {{ number_format($period['avg_orders'], 1, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <span>Pemantauan periode</span>
            <span class="inline-flex items-center gap-1 font-bold text-emerald-600 dark:text-emerald-400">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ $period['day_count'] }} Hari Terpantau
            </span>
        </div>
    </div>
</x-filament-widgets::widget>
