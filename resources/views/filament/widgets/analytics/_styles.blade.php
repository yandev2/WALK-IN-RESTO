@once
<style>
    .ad-dashboard {
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

    .ad-dashboard .fi-wi-chart-bg-color {
        color: rgb(241 245 249);
    }

    .dark .ad-dashboard .fi-wi-chart-bg-color {
        color: rgb(31 41 55);
    }

    .ad-dashboard .fi-wi-chart-border-color {
        color: rgb(148 163 184);
    }

    .ad-dashboard .fi-wi-chart-grid-color {
        color: rgb(226 232 240);
    }

    .dark .ad-dashboard .fi-wi-chart-grid-color {
        color: rgb(55 65 81);
    }

    .ad-dashboard .fi-wi-chart-text-color {
        color: rgb(100 116 139);
    }

    .dark .ad-dashboard .fi-wi-chart-text-color {
        color: rgb(148 163 184);
    }

    .ad-card {
        border-radius: 1.25rem;
        border: 1px solid var(--ad-border);
        background: var(--ad-surface);
        box-shadow: var(--ad-shadow);
        overflow: hidden;
    }

    .ad-card--top-panel {
        display: flex;
        flex-direction: column;
        min-height: 26rem;
        overflow: visible;
    }

    @media (min-width: 1280px) {
        .ad-card--top-panel {
            height: 26rem;
            overflow: hidden;
        }
    }

    .ad-section-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.15rem 1.25rem 0.25rem;
    }

    .ad-section-header__title {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--ad-text);
    }

    .ad-section-header__subtitle {
        margin: 0.3rem 0 0;
        font-size: 0.8125rem;
        line-height: 1.45;
        color: var(--ad-text-muted);
    }

    .ad-kpi-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 0.75rem;
    }

    @media (min-width: 768px) {
        .ad-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .ad-kpi-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (min-width: 1536px) {
        .ad-kpi-grid {
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }
    }

    .ad-kpi-card {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        min-height: 7.25rem;
        padding: 1rem 1.05rem 0.95rem;
        border-radius: 1.15rem;
        border: 1px solid color-mix(in srgb, var(--ad-border) 80%, transparent);
        background:
            linear-gradient(
                155deg,
                color-mix(in srgb, var(--ad-kpi-tint, var(--ad-primary)) 10%, white) 0%,
                var(--ad-surface) 48%,
                var(--ad-surface) 100%
            );
        box-shadow: 0 8px 22px -18px rgb(15 23 42 / 0.35);
        overflow: hidden;
        transition: transform 160ms ease, box-shadow 160ms ease;
    }

    .ad-kpi-card::after {
        content: '';
        position: absolute;
        inset: auto -20% -45% auto;
        width: 7rem;
        height: 7rem;
        border-radius: 9999px;
        background: color-mix(in srgb, var(--ad-kpi-tint, var(--ad-primary)) 12%, transparent);
        pointer-events: none;
    }

    .dark .ad-kpi-card {
        background:
            linear-gradient(
                155deg,
                color-mix(in srgb, var(--ad-kpi-tint, var(--ad-primary)) 16%, #111827) 0%,
                var(--ad-surface) 52%,
                var(--ad-surface) 100%
            );
        box-shadow: 0 8px 22px -18px rgb(0 0 0 / 0.55);
    }

    .ad-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px -18px rgb(15 23 42 / 0.4);
    }

    .ad-kpi-card--revenue { --ad-kpi-tint: var(--ad-primary); }
    .ad-kpi-card--orders { --ad-kpi-tint: #0d9488; }
    .ad-kpi-card--average { --ad-kpi-tint: #2563eb; }
    .ad-kpi-card--qris { --ad-kpi-tint: var(--ad-primary-dark); }
    .ad-kpi-card--cash { --ad-kpi-tint: #ca8a04; }
    .ad-kpi-card--void { --ad-kpi-tint: #dc2626; }

    .ad-kpi-card__head {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .ad-kpi-card__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.15rem;
        height: 2.15rem;
        border-radius: 0.75rem;
        flex-shrink: 0;
        background: color-mix(in srgb, var(--ad-kpi-tint, var(--ad-primary)) 16%, white);
        color: var(--ad-kpi-tint, var(--ad-primary-dark));
    }

    .dark .ad-kpi-card__icon {
        background: color-mix(in srgb, var(--ad-kpi-tint, var(--ad-primary)) 22%, #1f2937);
        color: color-mix(in srgb, var(--ad-kpi-tint, var(--ad-accent)) 70%, white);
    }

    .ad-kpi-card__icon svg {
        width: 1.05rem;
        height: 1.05rem;
    }

    .ad-kpi-card__label {
        position: relative;
        z-index: 1;
        font-size: 0.8125rem;
        font-weight: 500;
        line-height: 1.35;
        color: var(--ad-text-muted);
        padding-top: 0.15rem;
    }

    .ad-kpi-card__value {
        position: relative;
        z-index: 1;
        margin-top: 0.15rem;
        font-size: clamp(1.25rem, 1.9vw, 1.55rem);
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -0.03em;
        font-variant-numeric: tabular-nums;
        color: var(--ad-text);
        word-break: break-word;
    }

    .ad-kpi-card__hint {
        position: relative;
        z-index: 1;
        margin: 0.15rem 0 0;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        max-width: 100%;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1.35;
    }

    .ad-kpi-card__hint svg {
        width: 0.85rem;
        height: 0.85rem;
        flex-shrink: 0;
    }

    .ad-kpi-card__hint--spacer {
        visibility: hidden;
        user-select: none;
    }

    .ad-kpi-card__hint--up {
        color: rgb(21 128 61);
    }

    .dark .ad-kpi-card__hint--up {
        color: rgb(134 239 172);
    }

    .ad-kpi-card__hint--down {
        color: rgb(185 28 28);
    }

    .dark .ad-kpi-card__hint--down {
        color: rgb(252 165 165);
    }

    .ad-kpi-card__hint--new {
        color: rgb(29 78 216);
    }

    .dark .ad-kpi-card__hint--new {
        color: rgb(147 197 253);
    }

    .ad-kpi-card__hint--neutral {
        color: var(--ad-text-muted);
        font-weight: 500;
    }

    .ad-chart-body {
        flex: 1;
        display: flex;
        min-height: 0;
        padding: 0.25rem 1rem 1.35rem 0.85rem;
    }

    .ad-chart-body .fi-wi-chart {
        flex: 1;
        width: 100%;
        min-height: 0;
    }

    .ad-chart-body .fi-wi-chart-canvas-ctn {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 17rem;
        max-height: none;
        overflow: visible;
    }

    .ad-chart-body .fi-wi-chart-canvas-ctn canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .ad-donut-wrap {
        flex: 1 1 auto;
        position: relative;
        min-height: 12.5rem;
        max-height: 15rem;
        margin: 0.5rem 1.15rem 0.35rem;
    }

    .ad-donut-wrap > .fi-wi-chart {
        position: absolute;
        inset: 0;
    }

    .ad-donut-wrap .fi-wi-chart-canvas-ctn {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .ad-donut-wrap .fi-wi-chart-canvas-ctn canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .ad-donut-center {
        pointer-events: none;
        position: absolute;
        inset: 0;
        display: grid;
        place-content: center;
        text-align: center;
    }

    .ad-donut-center__value {
        margin: 0;
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--ad-text);
    }

    .ad-donut-center__label {
        margin: 0.15rem 0 0;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--ad-text-muted);
    }

    .ad-legend {
        display: flex;
        flex-wrap: wrap;
        flex-shrink: 0;
        gap: 0.75rem 1.25rem;
        margin-top: auto;
        padding: 0.85rem 1.35rem 1.65rem;
    }

    .ad-sidebar-stack {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .ad-sidebar-stack > .ad-card--top-panel {
        flex: 1;
        min-height: 26rem;
    }

    .ad-legend__item {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.8125rem;
        color: var(--ad-text-muted);
    }

    .ad-legend__swatch {
        width: 0.65rem;
        height: 0.65rem;
        border-radius: 9999px;
        flex-shrink: 0;
    }

    .ad-period-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
        padding: 0.25rem 1.15rem 1.15rem;
    }

    .ad-card--period-summary {
        height: 100%;
    }

    .ad-period-stat {
        padding: 0.85rem;
        border-radius: 0.9rem;
        background: var(--ad-muted);
        border: 1px solid var(--ad-border);
    }

    .ad-period-stat__label {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--ad-text-muted);
    }

    .ad-period-stat__value {
        margin: 0.35rem 0 0;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--ad-text);
        font-variant-numeric: tabular-nums;
    }

    .ad-table-wrap {
        padding: 0.35rem 0.5rem 0.75rem;
        overflow-x: auto;
    }

    .ad-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .ad-table th {
        padding: 0.65rem 0.85rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--ad-text-muted);
        border-bottom: 1px solid var(--ad-border);
    }

    .ad-table td {
        padding: 0.85rem;
        font-size: 0.875rem;
        color: var(--ad-text);
        border-bottom: 1px solid var(--ad-border);
        vertical-align: middle;
    }

    .ad-table tr:last-child td {
        border-bottom: 0;
    }

    .ad-rank {
        display: inline-grid;
        place-items: center;
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        background: var(--ad-muted);
        color: var(--ad-text-muted);
    }

    .ad-rank--top {
        background: var(--ad-ink);
        color: #fff;
    }

    .ad-qty-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.25rem;
        padding: 0.2rem 0.55rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 700;
        background: color-mix(in srgb, var(--ad-primary) 14%, white);
        color: var(--ad-primary-dark);
    }

    .dark .ad-qty-badge {
        background: color-mix(in srgb, var(--ad-primary) 22%, #111827);
        color: var(--ad-accent);
    }

    .ad-empty {
        min-height: 13.5rem;
        padding: 2rem 1.25rem;
        text-align: center;
        display: grid;
        place-content: center;
        color: var(--ad-text-muted);
    }

    .ad-empty__title {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--ad-text);
    }

    .ad-empty__desc {
        margin: 0.35rem 0 0;
        font-size: 0.8125rem;
    }
</style>
@endonce
