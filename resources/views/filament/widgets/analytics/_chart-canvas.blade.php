@props([
    'type',
    'data',
    'options',
    'wireKey' => null,
])

<div
    class="fi-wi-chart"
    @if (filled($wireKey))
        wire:key="{{ $wireKey }}"
    @endif
>
    <div
        x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('chart', 'filament/widgets') }}"
        wire:ignore
        data-chart-type="{{ $type }}"
        x-data="chart({
            cachedData: @js($data),
            options: @js($options),
            type: @js($type),
        })"
        class="fi-wi-chart-canvas-ctn fi-wi-chart-canvas-ctn-no-aspect-ratio"
        {{ $attributes }}
    >
        <canvas x-ref="canvas"></canvas>

        <span x-ref="backgroundColorElement" class="fi-wi-chart-bg-color"></span>
        <span x-ref="borderColorElement" class="fi-wi-chart-border-color"></span>
        <span x-ref="gridColorElement" class="fi-wi-chart-grid-color"></span>
        <span x-ref="textColorElement" class="fi-wi-chart-text-color"></span>
    </div>
</div>
