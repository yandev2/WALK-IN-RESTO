import ApexCharts from 'apexcharts';

window.ApexCharts = ApexCharts;

export function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

export function visionGaugeChart(config = {}) {
    return {
        chart: null,
        value: Number(config.value ?? 100),
        label: config.label ?? 'Kesiapan Resto',
        observer: null,

        init() {
            this.$nextTick(() => {
                this.renderChart();
                this.bindThemeObserver();
            });
        },

        destroy() {
            if (this.observer) {
                this.observer.disconnect();
            }
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }
        },

        getOptions() {
            const dark = isDarkMode();
            return {
                series: [this.value],
                chart: {
                    type: 'radialBar',
                    height: 220,
                    offsetY: -10,
                    sparkline: {
                        enabled: true,
                    },
                    animations: {
                        enabled: true,
                        speed: 800,
                        animateGradually: { enabled: true, delay: 150 },
                        dynamicAnimation: { enabled: true, speed: 350 },
                    },
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -125,
                        endAngle: 125,
                        hollow: {
                            margin: 0,
                            size: '68%',
                            background: 'transparent',
                        },
                        track: {
                            background: dark ? 'rgba(255, 255, 255, 0.08)' : '#e2e8f0',
                            strokeWidth: '100%',
                            margin: 0,
                        },
                        dataLabels: {
                            show: true,
                            name: {
                                offsetY: 26,
                                show: true,
                                color: dark ? '#94a3b8' : '#64748b',
                                fontSize: '11px',
                                fontWeight: '600',
                            },
                            value: {
                                offsetY: -12,
                                color: dark ? '#ffffff' : '#0f172a',
                                fontSize: '28px',
                                fontWeight: '800',
                                show: true,
                                formatter(val) {
                                    return `${val}%`;
                                },
                            },
                        },
                    },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        gradientToColors: ['#2cd9ff'],
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        colorStops: [
                            { offset: 0, color: '#0075ff', opacity: 1 },
                            { offset: 100, color: '#2cd9ff', opacity: 1 },
                        ],
                    },
                },
                stroke: {
                    lineCap: 'round',
                },
                labels: [this.label],
            };
        },

        renderChart() {
            const el = this.$refs.chart;
            if (!el) return;

            if (this.chart) {
                this.chart.destroy();
            }

            this.chart = new ApexCharts(el, this.getOptions());
            this.chart.render();
        },

        updateTheme() {
            if (!this.chart) return;
            this.chart.updateOptions(this.getOptions(), false, true);
        },

        bindThemeObserver() {
            this.observer = new MutationObserver(() => {
                this.updateTheme();
            });
            this.observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
        },
    };
}

export function visionAreaChart(config = {}) {
    return {
        chart: null,
        seriesData: config.series ?? [],
        categories: config.categories ?? [],
        idr: config.idr ?? true,
        observer: null,

        init() {
            this.$nextTick(() => {
                this.renderChart();
                this.bindThemeObserver();
            });
        },

        destroy() {
            if (this.observer) {
                this.observer.disconnect();
            }
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }
        },

        getOptions() {
            const dark = isDarkMode();
            const formatIdr = (val) => {
                if (typeof val !== 'number') return val;
                return 'Rp ' + val.toLocaleString('id-ID');
            };

            return {
                series: [{
                    name: 'Omzet Penjualan',
                    data: this.seriesData,
                }],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    animations: {
                        enabled: true,
                        speed: 700,
                    },
                },
                colors: ['#2cd9ff'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: dark ? 0.45 : 0.35,
                        opacityTo: 0.02,
                        stops: [0, 95, 100],
                        colorStops: [
                            { offset: 0, color: '#0075ff', opacity: dark ? 0.45 : 0.35 },
                            { offset: 100, color: '#2cd9ff', opacity: 0.02 },
                        ],
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: 3.5,
                    colors: ['#2cd9ff'],
                },
                markers: {
                    size: 0,
                    hover: {
                        size: 6,
                        sizeOffset: 3,
                    },
                },
                dataLabels: { enabled: false },
                grid: {
                    borderColor: dark ? 'rgba(255, 255, 255, 0.06)' : '#f1f5f9',
                    strokeDashArray: 4,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                    padding: { top: 10, right: 10, bottom: 0, left: 10 },
                },
                xaxis: {
                    categories: this.categories,
                    tickAmount: 6,
                    hideOverlappingLabels: true,
                    rotate: 0,
                    labels: {
                        style: {
                            colors: dark ? '#94a3b8' : '#64748b',
                            fontSize: '11px',
                            fontWeight: 500,
                        },
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: dark ? '#94a3b8' : '#64748b',
                            fontSize: '11px',
                            fontWeight: 500,
                        },
                        formatter: (val) => {
                            if (val >= 1000000) return `${(val / 1000000).toFixed(1)}jt`;
                            if (val >= 1000) return `${(val / 1000).toFixed(0)}k`;
                            return val;
                        },
                    },
                },
                tooltip: {
                    theme: dark ? 'dark' : 'light',
                    x: { show: true },
                    y: {
                        formatter: (val) => this.idr ? formatIdr(val) : val,
                    },
                    style: {
                        fontSize: '12px',
                        fontFamily: 'inherit',
                    },
                },
            };
        },

        renderChart() {
            const el = this.$refs.chart;
            if (!el) return;

            if (this.chart) {
                this.chart.destroy();
            }

            this.chart = new ApexCharts(el, this.getOptions());
            this.chart.render();
        },

        updateTheme() {
            if (!this.chart) return;
            this.chart.updateOptions(this.getOptions(), false, true);
        },

        bindThemeObserver() {
            this.observer = new MutationObserver(() => {
                this.updateTheme();
            });
            this.observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
        },
    };
}

window.visionGaugeChart = visionGaugeChart;
window.visionAreaChart = visionAreaChart;
