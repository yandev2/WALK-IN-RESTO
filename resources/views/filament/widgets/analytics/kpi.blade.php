<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class(['fi-wi-analytics-kpi'])
    "
>
    @php
        $theme = $this->analyticsTheme();
        $cards = $this->getStatCards();
    @endphp

    <div
        class="ad-dashboard"
        style="--ad-primary: {{ $theme['primary'] }}; --ad-primary-dark: {{ $theme['primary_dark'] }}; --ad-accent: {{ $theme['accent'] }}; --ad-ink: {{ $theme['ink'] }};"
    >
        @include('filament.widgets.analytics._styles')

        <div class="ad-kpi-grid">
            @foreach ($cards as $card)
                @include('filament.widgets.analytics._kpi-card', [
                    'label' => $card['label'],
                    'value' => $card['value'],
                    'icon' => $card['icon'],
                    'hint' => $card['hint'],
                    'hintTone' => $card['hint_tone'],
                ])
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
