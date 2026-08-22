@props([
    'label' => null,
    'title',
    'highlight' => null,
])

<div {{ $attributes->merge(['class' => 'text-center md:text-left']) }}>
    @if ($label)
        <p class="customer-section-label">{{ $label }}</p>
    @endif
    <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-body md:text-4xl">
        @if ($highlight)
            {!! str_replace($highlight, '<span class="text-primary">'.$highlight.'</span>', e($title)) !!}
        @else
            {{ $title }}
        @endif
    </h2>
</div>
