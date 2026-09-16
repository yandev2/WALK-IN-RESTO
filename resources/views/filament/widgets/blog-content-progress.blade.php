<x-filament-widgets::widget class="h-full">
    <div class="vision-card p-5 sm:p-6 flex flex-col justify-between h-full">
        <!-- Header -->
        <div class="pb-3 border-b border-slate-100 dark:border-white/5">
            <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                Target Rilis Konten
            </h3>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                Produktivitas publikasi bulan ini
            </p>
        </div>

        <!-- Radial Semi-Circle Gauge (Vision UI Satisfaction Rate Style) -->
        <div
            x-data="{
                chart: null,
                percent: @js(min(100, $progress['monthly_target_percent'])),
                published: @js($progress['published_this_month']),
                target: @js($progress['monthly_target']),
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

                    const options = {
                        series: [this.percent],
                        chart: {
                            type: 'radialBar',
                            height: 180,
                            sparkline: { enabled: true },
                            fontFamily: 'inherit'
                        },
                        plotOptions: {
                            radialBar: {
                                startAngle: -125,
                                endAngle: 125,
                                hollow: {
                                    margin: 0,
                                    size: '68%',
                                    background: 'transparent'
                                },
                                track: {
                                    background: isDark ? 'rgba(255, 255, 255, 0.08)' : '#e2e8f0',
                                    strokeWidth: '100%',
                                    margin: 0
                                },
                                dataLabels: {
                                    name: {
                                        show: true,
                                        fontSize: '11px',
                                        color: isDark ? '#a0aec0' : '#64748b',
                                        offsetY: -8
                                    },
                                    value: {
                                        offsetY: 6,
                                        fontSize: '22px',
                                        fontWeight: 700,
                                        color: isDark ? '#ffffff' : '#0f172a',
                                        formatter: (val) => val + '%'
                                    }
                                }
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: isDark ? 'dark' : 'light',
                                type: 'horizontal',
                                shadeIntensity: 0.5,
                                gradientToColors: isDark ? ['#2cd9ff'] : ['#0284c7'],
                                inverseColors: true,
                                opacityFrom: 1,
                                opacityTo: 1,
                                stops: [0, 100]
                            }
                        },
                        colors: ['#0075ff'],
                        stroke: {
                            lineCap: 'round'
                        },
                        labels: [this.published + ' / ' + this.target + ' Target']
                    };

                    this.chart = new ApexCharts(this.$refs.radialContainer, options);
                    this.chart.render();
                }
            }"
            wire:ignore
            class="relative flex flex-col items-center justify-center my-1"
        >
            <div x-ref="radialContainer" class="w-full flex justify-center"></div>
            <div class="flex items-center justify-between w-full px-6 -mt-3 text-[11px] font-semibold text-slate-400 dark:text-gray-500">
                <span>0%</span>
                <span class="text-xs text-slate-600 dark:text-gray-300">{{ $progress['monthly_target_percent'] >= 100 ? 'Target Tercapai 🎉' : ($progress['monthly_target'] - $progress['published_this_month']) . ' artikel lagi' }}</span>
                <span>100%</span>
            </div>
        </div>

        <!-- Status Counters Grid -->
        <div class="grid grid-cols-3 gap-2 text-center mt-3">
            <div class="vision-pill-card p-2.5">
                <span class="block text-lg font-bold text-[#01b574]">{{ $progress['total_published'] }}</span>
                <span class="text-[11px] font-medium text-slate-500 dark:text-gray-400">Published</span>
            </div>
            <div class="vision-pill-card p-2.5">
                <span class="block text-lg font-bold text-[#d97706] dark:text-[#ffb547]">{{ $progress['draft_count'] }}</span>
                <span class="text-[11px] font-medium text-slate-500 dark:text-gray-400">Draft</span>
            </div>
            <div class="vision-pill-card p-2.5">
                <span class="block text-lg font-bold text-[#7551ff]">{{ $progress['scheduled_count'] }}</span>
                <span class="text-[11px] font-medium text-slate-500 dark:text-gray-400">Terjadwal</span>
            </div>
        </div>

        <!-- Durasi Baca -->
        <div class="mt-3 border-t border-slate-100 dark:border-white/5 pt-2.5 flex items-center justify-between text-xs text-slate-500 dark:text-gray-400">
            <span class="flex items-center gap-1.5">
                <x-filament::icon icon="heroicon-o-clock" class="h-4 w-4 text-slate-400 dark:text-gray-400" />
                Rata-rata Waktu Baca:
            </span>
            <span class="font-semibold text-slate-800 dark:text-white">{{ $progress['avg_reading_time'] }} Menit / artikel</span>
        </div>
    </div>
</x-filament-widgets::widget>
