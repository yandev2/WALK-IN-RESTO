@props([
    'icon' => null,
    'title',
    'description',
])

<div {{ $attributes->merge(['class' => 'customer-card group landing-card-hover p-5']) }}>
    @if ($icon)
        <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary transition group-hover:bg-primary/20">
            {!! $icon !!}
        </div>
    @endif
    <h3 class="font-bold text-body">{{ $title }}</h3>
    <p class="mt-2 text-sm leading-relaxed text-muted">{{ $description }}</p>
</div>
