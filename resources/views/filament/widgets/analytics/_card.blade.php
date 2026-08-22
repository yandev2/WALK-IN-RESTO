@props([
    'class' => '',
])

<div {{ $attributes->class(['ad-card', $class]) }}>
    {{ $slot }}
</div>
