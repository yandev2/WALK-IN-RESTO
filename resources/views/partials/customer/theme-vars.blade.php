@php
    $theme = $theme ?? \App\Support\RestaurantTheme::for(null);
@endphp
<style>
    :root {
        color-scheme: light;
        --brand-primary: {{ $theme['primary'] }};
        --brand-primary-dark: {{ $theme['primary_dark'] }};
        --brand-accent: {{ $theme['accent'] }};

        --surface-base: #ffffff;
        --surface-raised: #ffffff;
        --surface-muted: color-mix(in srgb, var(--brand-primary) 6%, white);
        --surface-section: color-mix(in srgb, var(--brand-primary) 4%, white);
        --border-subtle: color-mix(in srgb, var(--brand-primary) 8%, #f3f1ef);
        --text-body: #1c1410;
        --text-muted: color-mix(in srgb, var(--text-body) 55%, white);
        --glow-primary: transparent;
        --card-shadow: 0 1px 3px rgba(15, 23, 42, 0.08), 0 8px 18px -4px rgba(15, 23, 42, 0.14);
        --card-shadow-hover: 0 2px 6px rgba(15, 23, 42, 0.1), 0 14px 28px -6px rgba(15, 23, 42, 0.18);
    }

    html.dark {
        color-scheme: dark;
        --surface-base: #0a0a0a;
        --surface-raised: #141414;
        --surface-muted: #1a1a1a;
        --surface-section: #111111;
        --border-subtle: #2a2a2a;
        --text-body: #f5f5f5;
        --text-muted: #a3a3a3;
        --glow-primary: transparent;
        --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.5), 0 8px 18px -4px rgba(0, 0, 0, 0.62);
        --card-shadow-hover: 0 2px 8px rgba(0, 0, 0, 0.55), 0 14px 28px -6px rgba(0, 0, 0, 0.72);
    }
</style>
