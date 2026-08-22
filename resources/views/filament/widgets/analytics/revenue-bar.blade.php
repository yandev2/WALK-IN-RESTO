<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class(['fi-wi-analytics-revenue'])
    "
>
    @php
        $theme = $this->analyticsTheme();
        $chartData = $this->getCachedChartData();
        $chartOptions = $this->getChartOptions();
    @endphp

    <div
        class="ad-dashboard"
        style="--ad-primary: {{ $theme['primary'] }}; --ad-primary-dark: {{ $theme['primary_dark'] }}; --ad-accent: {{ $theme['accent'] }}; --ad-ink: {{ $theme['ink'] }};"
    >
        @include('filament.widgets.analytics._styles')

        <div class="ad-card ad-card--top-panel">
            @include('filament.widgets.analytics._section-header', [
                'title' => 'Tren omzet ('.$this->analyticsRangeLabel().')',
                'subtitle' => 'Omzet net harian setelah potongan void cut.',
            ])

            <div class="ad-chart-body">
                @include('filament.widgets.analytics._chart-canvas', [
                    'type' => 'bar',
                    'data' => $chartData,
                    'options' => $chartOptions,
                    'wireKey' => $this->analyticsChartWireKey('revenue-bar'),
                ])
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
