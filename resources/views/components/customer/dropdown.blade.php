@props([
    'property' => null,
    'method' => null,
    'value' => null,
    'options' => [],
    'label' => null,
    'size' => 'default',
])

@php
    $selected = collect($options)->first(fn (array $option) => (string) ($option['value'] ?? '') === (string) ($value ?? ''));
    $label = $label ?? $selected['label'] ?? 'Pilih';
@endphp

<div
    {{ $attributes->except(['property', 'method', 'value', 'options', 'label', 'size'])->class('relative') }}
    x-data="{ open: false }"
    x-on:keydown.escape.window="open = false"
    x-on:click.outside="open = false"
>
    <button
        type="button"
        x-on:click="open = ! open"
        x-bind:aria-expanded="open.toString()"
        @class([
            'flex w-full items-center justify-between gap-3 bg-transparent text-left text-body transition hover:bg-surface-muted focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30',
            'h-12 rounded-2xl px-4 text-sm' => $size !== 'compact',
            'h-9 rounded-full px-3 text-xs font-semibold' => $size === 'compact',
        ])
    >
        <span class="truncate">{{ $label }}</span>
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 shrink-0 text-muted transition"
            x-bind:class="open ? 'rotate-180' : ''"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
            aria-hidden="true"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition.origin.top
        class="absolute left-0 right-0 z-50 mt-2 max-h-72 overflow-y-auto rounded-2xl bg-surface-raised py-1 shadow-[var(--card-shadow-hover)] ring-1 ring-[color:var(--border-subtle)]"
        style="background-color: var(--surface-raised);"
    >
        @foreach ($options as $option)
            @php
                $optionValue = $option['value'];
                $isActive = (string) ($optionValue ?? '') === (string) ($value ?? '');
                $setValue = $optionValue === null || $optionValue === ''
                    ? 'null'
                    : (is_numeric($optionValue) ? (string) $optionValue : "'".addslashes((string) $optionValue)."'");
            @endphp
            <button
                type="button"
                @if (filled($method))
                    wire:click="{{ $method }}({{ $setValue }})"
                @else
                    wire:click="$set('{{ $property }}', {{ $setValue }})"
                @endif
                x-on:click="open = false"
                @class([
                    'flex w-full items-center justify-between gap-3 px-4 py-2.5 text-left text-sm transition hover:bg-surface-muted',
                    'font-semibold text-primary' => $isActive,
                    'text-body' => ! $isActive,
                ])
            >
                <span>{{ $option['label'] }}</span>
                @if ($isActive)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </button>
        @endforeach
    </div>
</div>
