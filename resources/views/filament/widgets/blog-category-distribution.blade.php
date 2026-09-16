<x-filament-widgets::widget>
    <div class="vision-card p-5 sm:p-6 flex flex-col justify-between h-full">
        <!-- Header -->
        <div class="pb-3 border-b border-slate-100 dark:border-white/5">
            <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                Distribusi Kategori
            </h3>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                Kategori penyumbang pembaca terbanyak ({{ $period }} hari)
            </p>
        </div>

        <!-- Donut Chart -->
        <div
            wire:key="blog-category-donut-{{ $period }}-{{ md5(json_encode($views)) }}"
            x-data="{
                chart: null,
                names: @js($names),
                views: @js($views),
                init() {
                    if (this.views.length > 0) {
                        this.loadAndRender();
                    }
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
                        series: this.views,
                        labels: this.names,
                        chart: {
                            type: 'donut',
                            height: 230,
                            fontFamily: 'inherit',
                            background: 'transparent'
                        },
                        colors: ['#0075ff', '#2cd9ff', '#01b574', '#ffb547', '#7551ff', '#ec4899'],
                        stroke: {
                            show: true,
                            width: 2,
                            colors: [isDark ? '#060b26' : '#ffffff']
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '12px',
                                            color: isDark ? '#a0aec0' : '#64748b'
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '20px',
                                            fontWeight: 700,
                                            color: isDark ? '#ffffff' : '#0f172a',
                                            formatter: (val) => val + ' views'
                                        },
                                        total: {
                                            show: true,
                                            label: 'Total Views',
                                            fontSize: '11px',
                                            color: isDark ? '#a0aec0' : '#64748b',
                                            formatter: (w) => {
                                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: { enabled: false },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '11px',
                            labels: {
                                colors: isDark ? '#cbd5e1' : '#334155'
                            },
                            markers: { radius: 10 }
                        },
                        tooltip: {
                            theme: isDark ? 'dark' : 'light',
                            y: {
                                formatter: (val) => val + ' pembaca'
                            }
                        }
                    };

                    this.chart = new ApexCharts(this.$refs.donutContainer, options);
                    this.chart.render();
                }
            }"
            class="w-full mt-2"
        >
            @if (count($views) > 0)
                <div wire:ignore class="flex items-center justify-center">
                    <div x-ref="donutContainer" class="w-full min-h-[230px]"></div>
                </div>
            @else
                <div class="flex h-[200px] flex-col items-center justify-center text-center text-xs text-slate-400 dark:text-gray-500">
                    <x-filament::icon icon="heroicon-o-chart-pie" class="mb-2 h-8 w-8 text-slate-300 dark:text-gray-600" />
                    Belum ada data kunjungan artikel pada periode ini.
                </div>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
