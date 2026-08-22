@php
    $theme = $this->theme();
    $status = $this->statusEnum();
@endphp

<div
    class="subscription-plan-card"
    style="--spc-primary: {{ $theme['primary'] }}; --spc-primary-dark: {{ $theme['primary_dark'] }}; --spc-accent: {{ $theme['accent'] }};"
>
    <style>
        .subscription-plan-card {
            --spc-surface: #ffffff;
            --spc-muted: #f8fafc;
            --spc-border: #e2e8f0;
            --spc-text: #0f172a;
            --spc-text-muted: #64748b;
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem;
            border: 1px solid var(--spc-border);
            background: var(--spc-surface);
            box-shadow: 0 10px 30px -18px rgb(15 23 42 / 0.28);
        }

        .dark .subscription-plan-card {
            --spc-surface: #111827;
            --spc-muted: #1f2937;
            --spc-border: #374151;
            --spc-text: #f8fafc;
            --spc-text-muted: #94a3b8;
            box-shadow: 0 10px 30px -18px rgb(0 0 0 / 0.55);
        }

        .subscription-plan-card__accent {
            position: absolute;
            inset: 0 auto 0 0;
            width: 0.35rem;
            background: linear-gradient(180deg, var(--spc-primary-dark), var(--spc-primary), var(--spc-accent));
        }

        .subscription-plan-card__inner {
            position: relative;
            padding: 1.35rem 1.5rem 1.35rem 1.75rem;
        }

        .subscription-plan-card__header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .subscription-plan-card__eyebrow {
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--spc-text-muted);
        }

        .subscription-plan-card__title {
            margin: 0.25rem 0 0;
            font-size: clamp(1.25rem, 2.2vw, 1.65rem);
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.03em;
            color: var(--spc-text);
        }

        .subscription-plan-card__badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .subscription-plan-card__badge--success {
            background: color-mix(in srgb, #10b981 16%, white);
            color: #047857;
        }

        .subscription-plan-card__badge--info {
            background: color-mix(in srgb, #0ea5e9 16%, white);
            color: #0369a1;
        }

        .subscription-plan-card__badge--warning {
            background: color-mix(in srgb, #f59e0b 18%, white);
            color: #b45309;
        }

        .subscription-plan-card__badge--danger {
            background: color-mix(in srgb, #f43f5e 16%, white);
            color: #be123c;
        }

        .dark .subscription-plan-card__badge--success {
            background: color-mix(in srgb, #10b981 22%, #1f2937);
            color: #6ee7b7;
        }

        .dark .subscription-plan-card__badge--info {
            background: color-mix(in srgb, #0ea5e9 22%, #1f2937);
            color: #7dd3fc;
        }

        .dark .subscription-plan-card__badge--warning {
            background: color-mix(in srgb, #f59e0b 22%, #1f2937);
            color: #fcd34d;
        }

        .dark .subscription-plan-card__badge--danger {
            background: color-mix(in srgb, #f43f5e 22%, #1f2937);
            color: #fda4af;
        }

        .subscription-plan-card__grid {
            display: grid;
            gap: 0.75rem;
            margin-top: 1.15rem;
            grid-template-columns: 1fr;
        }

        .subscription-plan-card__stat {
            border-radius: 1rem;
            border: 1px solid var(--spc-border);
            background: var(--spc-muted);
            padding: 0.85rem 1rem;
        }

        .subscription-plan-card__stat-label {
            margin: 0;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--spc-text-muted);
        }

        .subscription-plan-card__stat-value {
            margin: 0.35rem 0 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--spc-text);
        }

        .subscription-plan-card__transfer {
            margin-top: 1rem;
        }

        .subscription-plan-card__transfer .transfer-widget {
            margin-top: 0;
        }

        @media (min-width: 640px) {
            .subscription-plan-card__grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
    </style>

    <div class="subscription-plan-card__accent" aria-hidden="true"></div>

    <div class="subscription-plan-card__inner">
        <div class="subscription-plan-card__header">
            <div>
                <p class="subscription-plan-card__eyebrow">Paket aktif</p>
                <h2 class="subscription-plan-card__title">{{ $this->planName() }}</h2>
            </div>
            <span class="subscription-plan-card__badge subscription-plan-card__badge--{{ $status?->color() ?? 'info' }}">
                {{ $this->statusLabel() }}
            </span>
        </div>

        <div class="subscription-plan-card__grid">
            <div class="subscription-plan-card__stat">
                <p class="subscription-plan-card__stat-label">Berlaku sampai</p>
                <p class="subscription-plan-card__stat-value">{{ $this->expiryLabel() ?? '—' }}</p>
            </div>
            <div class="subscription-plan-card__stat">
                <p class="subscription-plan-card__stat-label">Harga / bulan</p>
                <p class="subscription-plan-card__stat-value">{{ $this->monthlyPriceLabel() }}</p>
            </div>
            <div class="subscription-plan-card__stat">
                <p class="subscription-plan-card__stat-label">Sisa masa aktif</p>
                <p class="subscription-plan-card__stat-value">{{ $this->countdownLabel() }}</p>
            </div>
        </div>

        <div class="subscription-plan-card__transfer">
            @include('filament.pages.partials.subscription-transfer-widget', [
                'bankName' => $bankName ?? null,
                'bankAccount' => $bankAccount ?? null,
                'bankHolder' => $bankHolder ?? null,
                'contactEmail' => $contactEmail ?? null,
                'qrUrl' => $qrUrl ?? null,
            ])
        </div>
    </div>
</div>
