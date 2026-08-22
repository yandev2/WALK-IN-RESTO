<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class(['fi-wi-analytics-sidebar'])
    "
>
    @php
        $theme = $this->analyticsTheme();
        $mix = $this->paymentMixSummary();
        $chartData = $this->getCachedChartData();
        $chartOptions = $this->getChartOptions();
    @endphp

    <div
        class="ad-dashboard ad-sidebar-stack"
        style="--ad-primary: {{ $theme['primary'] }}; --ad-primary-dark: {{ $theme['primary_dark'] }}; --ad-accent: {{ $theme['accent'] }}; --ad-ink: {{ $theme['ink'] }};"
    >
        @include('filament.widgets.analytics._styles')

        <div class="ad-card ad-card--top-panel">
            @include('filament.widgets.analytics._section-header', [
                'title' => 'Metode bayar',
                'subtitle' => $this->analyticsRangeLabel(),
            ])

            <div class="ad-donut-wrap">
                @include('filament.widgets.analytics._chart-canvas', [
                    'type' => 'doughnut',
                    'data' => $chartData,
                    'options' => $chartOptions,
                    'wireKey' => $this->analyticsChartWireKey('payment-mix'),
                ])

                <div class="ad-donut-center">
                    <p class="ad-donut-center__value">
                        {{ $mix['total'] > 0 ? rtrim(rtrim(number_format($mix['qris_pct'], 1, '.', ''), '0'), '.').'%' : '—' }}
                    </p>
                    <p class="ad-donut-center__label">QRIS</p>
                </div>
            </div>

            <div class="ad-legend">
                <span class="ad-legend__item">
                    <span class="ad-legend__swatch" style="background: {{ $theme['primary'] }};"></span>
                    QRIS · {{ $mix['qris_formatted'] }}
                </span>
                <span class="ad-legend__item">
                    <span class="ad-legend__swatch" style="background: {{ $theme['ink'] }};"></span>
                    Tunai · {{ $mix['cash_formatted'] }}
                </span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
