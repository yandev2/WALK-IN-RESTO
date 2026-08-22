<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class(['fi-wi-analytics-period-summary'])
    "
>
    @php
        $theme = $this->analyticsTheme();
        $period = $this->periodSummary();
    @endphp

    <div
        class="ad-dashboard"
        style="--ad-primary: {{ $theme['primary'] }}; --ad-primary-dark: {{ $theme['primary_dark'] }}; --ad-accent: {{ $theme['accent'] }}; --ad-ink: {{ $theme['ink'] }};"
    >
        @include('filament.widgets.analytics._styles')

        <div class="ad-card ad-card--period-summary">
            @include('filament.widgets.analytics._section-header', [
                'title' => 'Ringkasan periode',
                'subtitle' => $period['day_count'].' hari · '.$period['range_label'],
            ])

            <div class="ad-period-grid">
                <div class="ad-period-stat">
                    <p class="ad-period-stat__label">Total omzet</p>
                    <p class="ad-period-stat__value">{{ $period['total_omzet_formatted'] }}</p>
                </div>
                <div class="ad-period-stat">
                    <p class="ad-period-stat__label">Rata-rata / hari</p>
                    <p class="ad-period-stat__value">{{ $period['avg_omzet_formatted'] }}</p>
                </div>
                <div class="ad-period-stat">
                    <p class="ad-period-stat__label">Total order</p>
                    <p class="ad-period-stat__value">{{ number_format($period['total_orders'], 0, ',', '.') }}</p>
                </div>
                <div class="ad-period-stat">
                    <p class="ad-period-stat__label">Order / hari</p>
                    <p class="ad-period-stat__value">{{ number_format($period['avg_orders'], 1, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
