@props([
    'href' => null,
    'type' => 'button',
])

@php
    $classes = 'landing-interactive inline-flex items-center justify-center rounded-full bg-transparent px-5 py-3 text-sm font-semibold text-primary [border:2px_solid_var(--brand-primary)] transition hover:bg-primary/5 hover:shadow-[var(--card-shadow)] focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30 dark:hover:bg-primary/10';
@endphp

@if ($href)
    <a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>{{ $slot }}</button>
@endif
