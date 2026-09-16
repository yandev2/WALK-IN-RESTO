<x-filament-widgets::widget class="h-full">
    <div class="vision-card p-5 sm:p-6 h-full flex flex-col justify-between">
        <!-- Header -->
        <div class="pb-3 border-b border-slate-100 dark:border-white/5">
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
                    <x-filament::icon icon="heroicon-m-chart-pie" class="h-4 w-4" />
                </span>
                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                    Kesehatan Langganan
                </h3>
            </div>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">
                Sebaran status operasional {{ number_format($totalTenants) }} restoran
            </p>
        </div>

        <!-- Donut Chart Container -->
        <div
            wire:key="founder-sub-donut-{{ md5(json_encode($series)) }}"
            x-data="{
                chart: null,
                series: @js($series),
                labels: @js($labels),
                total: {{ (int) $totalTenants }},
                init() {
                    this.loadAndRender();
                },
                loadAndRender() {
                    if (typeof window.ApexCharts !== 'undefined') {
                        this.renderChart();
                        return;
                    }
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/apexcharts';
                    script.onload = () => this.renderChart();
                    document.head.appendChild(script);
                },
                renderChart() {
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    const isDark = document.documentElement.classList.contains('dark');
                    const hasData = this.series.some(v => v > 0);

                    const seriesData = hasData ? this.series : [1];
                    const labelsData = hasData ? this.labels : ['Belum Ada Data'];
                    const colorsData = hasData
                        ? ['#10b981', '#0ea5e9', '#f59e0b', '#ef4444']
                        : ['#94a3b8'];

                    const options = {
                        series: seriesData,
                        labels: labelsData,
                        chart: {
                            type: 'donut',
                            height: 230,
                            fontFamily: 'inherit',
                            background: 'transparent'
                        },
                        colors: colorsData,
                        dataLabels: { enabled: false },
                        stroke: {
                            width: 2,
                            colors: [isDark ? '#0a0e23' : '#ffffff']
                        },
                        legend: { show: false },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '12px',
                                            fontWeight: 600,
                                            color: isDark ? '#94a3b8' : '#64748b'
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '22px',
                                            fontWeight: 700,
                                            color: isDark ? '#ffffff' : '#0f172a',
                                            formatter: (val) => hasData ? val + ' Resto' : '0'
                                        },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '11px',
                                            fontWeight: 500,
                                            color: isDark ? '#94a3b8' : '#64748b',
                                            formatter: () => this.total + ' Resto'
                                        }
                                    }
                                }
                            }
                        },
                        tooltip: {
                            theme: isDark ? 'dark' : 'light',
                            y: {
                                formatter: (val) => hasData ? val + ' Restoran' : '-'
                            }
                        }
                    };

                    this.chart = new ApexCharts(this.$refs.chartEl, options);
                    this.chart.render();
                }
            }"
            class="my-auto py-2"
        >
            <div x-ref="chartEl" class="flex justify-center"></div>
        </div>

        <!-- Breakdown List -->
        <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 dark:border-white/5 text-xs">
            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/5">
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-gray-300 font-medium">
                    <span class="h-2 w-2 rounded-full bg-[#10b981]"></span>
                    Aktif
                </span>
                <span class="font-bold text-slate-900 dark:text-white">{{ $statusCounts['active'] }}</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/5">
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-gray-300 font-medium">
                    <span class="h-2 w-2 rounded-full bg-[#0ea5e9]"></span>
                    Trial
                </span>
                <span class="font-bold text-slate-900 dark:text-white">{{ $statusCounts['trial'] }}</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/5">
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-gray-300 font-medium">
                    <span class="h-2 w-2 rounded-full bg-[#f59e0b]"></span>
                    Grace
                </span>
                <span class="font-bold text-amber-600 dark:text-amber-400">{{ $statusCounts['grace'] }}</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/5">
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-gray-300 font-medium">
                    <span class="h-2 w-2 rounded-full bg-[#ef4444]"></span>
                    Expired
                </span>
                <span class="font-bold text-rose-600 dark:text-rose-400">{{ $statusCounts['expired'] }}</span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
