@php
    $theme = $theme ?? [
        'primary' => '#F97316',
        'primary_dark' => '#D66313',
        'accent' => '#FB923C',
        'ink' => '#1E293B',
    ];
@endphp
<style>
    .ad-dashboard {
        --ad-primary: {{ $theme['primary'] }};
        --ad-primary-dark: {{ $theme['primary_dark'] }};
        --ad-accent: {{ $theme['accent'] }};
        --ad-ink: {{ $theme['ink'] }};
        --ad-surface: #ffffff;
        --ad-muted: #f8fafc;
        --ad-border: #e2e8f0;
        --ad-text: #0f172a;
        --ad-text-muted: #64748b;
        --ad-shadow: 0 10px 30px -18px rgb(15 23 42 / 0.28);
    }

    .dark .ad-dashboard {
        --ad-surface: #111827;
        --ad-muted: #1f2937;
        --ad-border: #374151;
        --ad-text: #f8fafc;
        --ad-text-muted: #94a3b8;
        --ad-shadow: 0 10px 30px -18px rgb(0 0 0 / 0.55);
    }
</style>
