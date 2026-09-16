<x-filament-widgets::widget class="h-full">
    <div class="vision-card p-5 sm:p-6 h-full flex flex-col justify-between">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100 dark:border-white/5">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                    Tren Trafik & Pengunjung
                </h3>
                <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                    Aktivitas harian dalam <strong class="text-slate-800 dark:text-white">{{ $period }} hari</strong> terakhir
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-2.5 py-1 text-xs font-medium text-slate-700 dark:text-gray-200">
                    <span class="h-2 w-2 rounded-full bg-[#0075ff] dark:bg-[#2cd9ff] shadow-[0_0_8px_#2cd9ff]"></span>
                    {{ number_format($totalViews) }} Views
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-2.5 py-1 text-xs font-medium text-slate-700 dark:text-gray-200">
                    <span class="h-2 w-2 rounded-full bg-[#0284c7] dark:bg-[#0075ff] shadow-[0_0_8px_#0075ff]"></span>
                    {{ number_format($totalVisitors) }} Visitors
                </span>
            </div>
        </div>

        <!-- Chart Container -->
        <div
            wire:key="blog-traffic-chart-{{ $period }}-{{ md5(json_encode($labels)) }}"
            x-data="{
                chart: null,
                labels: @js($labels),
                views: @js($views),
                visitors: @js($visitors),
                articles: @js($articles),
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
                        series: [
                            {
                                name: 'Total Views',
                                data: this.views
                            },
                            {
                                name: 'Unique Visitors',
                                data: this.visitors
                            },
                            {
                                name: 'Artikel Dibaca',
                                data: this.articles
                            }
                        ],
                        chart: {
                            type: 'area',
                            height: 285,
                            toolbar: { show: false },
                            zoom: { enabled: false },
                            fontFamily: 'inherit',
                            background: 'transparent',
                            redrawOnParentResize: true,
                            redrawOnWindowResize: true,
                            dropShadow: {
                                enabled: isDark,
                                top: 5,
                                left: 0,
                                blur: 8,
                                color: '#0075ff',
                                opacity: 0.35
                            }
                        },
                        colors: isDark ? ['#2cd9ff', '#0075ff', '#01b574'] : ['#0284c7', '#2563eb', '#059669'],
                        stroke: {
                            curve: 'smooth',
                            width: [3, 2.5, 2]
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                type: 'vertical',
                                shadeIntensity: 0.5,
                                opacityFrom: isDark ? 0.45 : 0.35,
                                opacityTo: 0.02,
                                stops: [0, 95, 100]
                            }
                        },
                        markers: {
                            size: 0,
                            hover: { size: 5 }
                        },
                        dataLabels: { enabled: false },
                        grid: {
                            borderColor: isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(0, 0, 0, 0.06)',
                            strokeDashArray: 4,
                            padding: { left: 10, right: 10, top: 10, bottom: 0 }
                        },
                        xaxis: {
                            categories: this.labels,
                            tickAmount: 6,
                            labels: {
                                rotate: 0,
                                rotateAlways: false,
                                hideOverlappingLabels: true,
                                style: {
                                    colors: isDark ? '#a0aec0' : '#64748b',
                                    fontSize: '11px',
                                    fontFamily: 'inherit',
                                    fontWeight: 500
                                }
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: isDark ? '#a0aec0' : '#64748b',
                                    fontSize: '11px',
                                    fontFamily: 'inherit'
                                },
                                formatter: (val) => Math.round(val)
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'left',
                            fontSize: '11px',
                            fontFamily: 'inherit',
                            fontWeight: 500,
                            itemMargin: {
                                horizontal: 10,
                                vertical: 4
                            },
                            labels: {
                                colors: isDark ? '#cbd5e1' : '#334155'
                            },
                            markers: {
                                radius: 12,
                                offsetX: -2
                            }
                        },
                        responsive: [
                            {
                                breakpoint: 768,
                                options: {
                                    legend: {
                                        position: 'bottom',
                                        horizontalAlign: 'center',
                                        itemMargin: {
                                            horizontal: 8,
                                            vertical: 4
                                        }
                                    },
                                    xaxis: {
                                        tickAmount: 4
                                    }
                                }
                            },
                            {
                                breakpoint: 480,
                                options: {
                                    xaxis: {
                                        tickAmount: 3
                                    }
                                }
                            }
                        ],
                        tooltip: {
                            theme: isDark ? 'dark' : 'light',
                            x: { show: true },
                            y: {
                                formatter: (val) => val + ' hit'
                            }
                        }
                    };

                    this.chart = new ApexCharts(this.$refs.chartContainer, options);
                    this.chart.render();
                }
            }"
            wire:ignore
            class="relative w-full overflow-hidden flex-1 flex flex-col justify-center mt-3"
        >
            <div x-ref="chartContainer" class="w-full min-h-[285px]"></div>
        </div>
    </div>
</x-filament-widgets::widget>
