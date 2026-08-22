@props([
    'label',
    'value',
    'icon',
    'hint' => null,
    'hintTone' => 'neutral',
])

<article @class(['ad-kpi-card', 'ad-kpi-card--'.$icon])>
    <div class="ad-kpi-card__head">
        <span class="ad-kpi-card__label">{{ $label }}</span>
        <span class="ad-kpi-card__icon" aria-hidden="true">
            @include('filament.widgets.partials.sales-summary-icon', ['icon' => $icon])
        </span>
    </div>

    <div class="ad-kpi-card__value">{{ $value }}</div>

    @if (filled($hint))
        <p @class(['ad-kpi-card__hint', 'ad-kpi-card__hint--'.$hintTone])>
            @if ($hintTone === 'up')
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .53.22l5 5a.75.75 0 1 1-1.06 1.06L10.75 5.6V16.25a.75.75 0 0 1-1.5 0V5.35L5.53 9.28a.75.75 0 0 1-1.06-1.06l5-5A.75.75 0 0 1 10 3Z" clip-rule="evenodd" />
                </svg>
            @elseif ($hintTone === 'down')
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.53-.22l-5-5a.75.75 0 1 1 1.06-1.06l3.72 3.72V3.75a.75.75 0 0 1 1.5 0v10.65l3.72-3.72a.75.75 0 1 1 1.06 1.06l-5 5A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
                </svg>
            @endif
            <span>{{ $hint }}</span>
        </p>
    @else
        <p class="ad-kpi-card__hint ad-kpi-card__hint--spacer" aria-hidden="true">&nbsp;</p>
    @endif
</article>
