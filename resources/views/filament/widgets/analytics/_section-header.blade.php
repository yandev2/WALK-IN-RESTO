@props([
    'title',
    'subtitle' => null,
])

<div class="ad-section-header">
    <div class="ad-section-header__copy">
        <h3 class="ad-section-header__title">{{ $title }}</h3>
        @if (filled($subtitle))
            <p class="ad-section-header__subtitle">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="ad-section-header__actions">
            {{ $actions }}
        </div>
    @endisset
</div>
