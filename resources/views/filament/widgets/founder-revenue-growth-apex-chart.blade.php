<x-filament-widgets::widget class="h-full">
    <div class="vision-card p-5 sm:p-6 h-full flex flex-col justify-between">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100 dark:border-white/5">
            <div>
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400">
                        <x-filament::icon icon="heroicon-m-banknotes" class="h-4 w-4" />
                    </span>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                        Tren Pendapatan &amp; Pertumbuhan Sewa
                    </h3>
                </div>
                <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">
                    Akumulasi pendapatan sewa flat bulanan dan komisi kasir 6 bulan terakhir
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-gray-200">
                    <span class="h-2 w-2 rounded-full bg-[#10b981] shadow-[0_0_8px_#10b981]"></span>
                    Total: Rp {{ number_format($summaryTotalRev, 0, ',', '.') }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-2.5 py-1 text-xs font-medium text-slate-600 dark:text-gray-300">
                    <span class="h-2 w-2 rounded-full bg-[#3b82f6]"></span>
                    Sewa: Rp {{ number_format($summaryTotalFlat, 0, ',', '.') }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-2.5 py-1 text-xs font-medium text-slate-600 dark:text-gray-300">
                    <span class="h-2 w-2 rounded-full bg-[#f97316]"></span>
                    Komisi: Rp {{ number_format($summaryTotalComm, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Chart Container -->
        <div
            wire:key="founder-revenue-chart-{{ md5(json_encode($labels)) }}"
            x-data="{
                chart: null,
                labels: @js($labels),
                flatRevenue: @js($flatRevenue),
                commissionRevenue: @js($commissionRevenue),
                totalRevenue: @js($totalRevenue),
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
                formatRupiah(val) {
                    return 'Rp ' + Number(val).toLocaleString('id-ID');
                },
                renderChart() {
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    const isDark = document.documentElement.classList.contains('dark');

                    const options = {
                        series: [
                            {
                                name: 'Sewa Flat',
                                data: this.flatRevenue
                            },
                            {
                                name: 'Komisi Kasir',
                                data: this.commissionRevenue
                            },
                            {
                                name: 'Total Pendapatan',
                                data: this.totalRevenue
                            }
                        ],
                        chart: {
                            type: 'area',
                            height: 290,
                            toolbar: { show: false },
                            zoom: { enabled: false },
                            fontFamily: 'inherit',
                            background: 'transparent'
                        },
                        colors: ['#3b82f6', '#f97316', '#10b981'],
                        dataLabels: { enabled: false },
                        stroke: {
                            curve: 'smooth',
                            width: [2, 2, 3]
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.35,
                                opacityTo: 0.05,
                                stops: [0, 90, 100]
                            }
                        },
                        xaxis: {
                            categories: this.labels,
                            labels: {
                                style: {
                                    colors: isDark ? '#94a3b8' : '#64748b',
                                    fontSize: '11px',
                                    fontWeight: 500
                                }
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: isDark ? '#94a3b8' : '#64748b',
                                    fontSize: '11px'
                                },
                                formatter: (val) => {
                                    if (val >= 1000000) {
                                        return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                                    }
                                    if (val >= 1000) {
                                        return 'Rp ' + (val / 1000).toFixed(0) + 'k';
                                    }
                                    return 'Rp ' + val;
                                }
                            }
                        },
                        grid: {
                            borderColor: isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(0, 0, 0, 0.05)',
                            strokeDashArray: 4,
                            padding: { top: 10, right: 10, bottom: 0, left: 10 }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            labels: {
                                colors: isDark ? '#e2e8f0' : '#334155'
                            },
                            markers: {
                                radius: 12
                            }
                        },
                        tooltip: {
                            theme: isDark ? 'dark' : 'light',
                            y: {
                                formatter: (val) => this.formatRupiah(val)
                            }
                        }
                    };

                    this.chart = new ApexCharts(this.$refs.chartEl, options);
                    this.chart.render();
                }
            }"
            class="mt-4 flex-1 min-h-[290px]"
        >
            <div x-ref="chartEl" class="w-full"></div>
        </div>
    </div>
</x-filament-widgets::widget>
