@php
    $theme = $theme ?? \App\Support\RestaurantTheme::for(null);
@endphp
<style>
    :root {
        color-scheme: light;
        --brand-primary: {{ $theme['primary'] }};
        --brand-primary-dark: {{ $theme['primary_dark'] }};
        --brand-accent: {{ $theme['accent'] }};

        --surface-base: #fafaf9;
        --surface-raised: #ffffff;
        --surface-muted: #f4f3f0;
        --surface-section: #f7f6f3;
        --border-subtle: #e7e5e2;
        --text-body: #1c1917;
        --text-muted: #716c66;
        --glow-primary: color-mix(in srgb, var(--brand-primary) 20%, transparent);
        --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 4px 14px -2px rgba(0, 0, 0, 0.05);
        --card-shadow-hover: 0 8px 24px -4px rgba(0, 0, 0, 0.08), 0 4px 8px -2px rgba(0, 0, 0, 0.03);
    }

    html.dark {
        color-scheme: dark;
        --surface-base: #0f1115;
        --surface-raised: #171920;
        --surface-muted: #1e222b;
        --surface-section: #13151a;
        --border-subtle: #262b36;
        --text-body: #f3f4f6;
        --text-muted: #9ca3af;
        --glow-primary: color-mix(in srgb, var(--brand-primary) 30%, transparent);
        --card-shadow: 0 2px 6px rgba(0, 0, 0, 0.35), 0 10px 24px -4px rgba(0, 0, 0, 0.5);
        --card-shadow-hover: 0 4px 12px rgba(0, 0, 0, 0.45), 0 18px 36px -6px rgba(0, 0, 0, 0.65);
    }
</style>
