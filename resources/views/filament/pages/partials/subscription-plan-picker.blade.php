@php
    $plans = $plans ?? [];
    $selectedPlanCode = $selectedPlanCode ?? null;
    $actionIndex = max(0, count($this->mountedActions ?? []) - 1);
    $statePath = "mountedActions.{$actionIndex}.data.requested_plan_code";
@endphp

<div class="plan-picker">
    <style>
        .plan-picker {
            --pp-bg: #fff;
            --pp-muted: #71717a;
            --pp-text: #18181b;
            --pp-line: #e4e4e7;
            --pp-soft: #f4f4f5;
            --pp-accent: var(--primary-600, #ea580c);
        }

        .dark .plan-picker {
            --pp-bg: #111827;
            --pp-muted: #a1a1aa;
            --pp-text: #f4f4f5;
            --pp-line: #374151;
            --pp-soft: #1f2937;
        }

        .plan-picker__label {
            margin: 0 0 0.55rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--pp-text);
        }

        .plan-picker__grid {
            display: grid;
            gap: 0.75rem;
        }

        .plan-picker__card {
            position: relative;
            display: flex;
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            gap: 0.35rem;
            padding: 0.95rem 1rem;
            border: 1px solid var(--pp-line);
            border-radius: 6px;
            background: var(--pp-bg);
            color: var(--pp-text);
            cursor: pointer;
        }

        .plan-picker__card:hover {
            border-color: color-mix(in srgb, var(--pp-accent) 55%, var(--pp-line));
        }

        .plan-picker__card.is-selected {
            border-color: var(--pp-accent);
            border-width: 2px;
            padding: calc(0.95rem - 1px) calc(1rem - 1px);
            background: color-mix(in srgb, var(--pp-accent) 7%, var(--pp-bg));
        }

        .plan-picker__name {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .plan-picker__price {
            margin: 0;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .plan-picker__desc {
            margin: 0.2rem 0 0;
            font-size: 0.78rem;
            line-height: 1.45;
            color: var(--pp-muted);
        }

        .plan-picker__meta {
            margin-top: 0.45rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--pp-muted);
        }

        .plan-picker__card.is-selected .plan-picker__meta {
            color: var(--pp-accent);
        }

        .plan-picker__error {
            margin: 0.5rem 0 0;
            font-size: 0.75rem;
            color: #b91c1c;
        }

        @media (min-width: 640px) {
            .plan-picker__grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    <p class="plan-picker__label">Paket <span style="color: #dc2626;">*</span></p>

    <div class="plan-picker__grid" role="listbox" aria-label="Pilih paket">
        @foreach ($plans as $plan)
            @php
                $isSelected = (string) $selectedPlanCode === (string) $plan['code'];
            @endphp

            <button
                type="button"
                role="option"
                aria-selected="{{ $isSelected ? 'true' : 'false' }}"
                wire:click="selectInvoicePlan({{ \Illuminate\Support\Js::from($plan['code']) }})"
                @class([
                    'plan-picker__card',
                    'is-selected' => $isSelected,
                ])
            >
                <p class="plan-picker__name">{{ $plan['name'] }}</p>
                <p class="plan-picker__price">{{ $plan['price_label'] }}</p>
                <p class="plan-picker__desc">{{ $plan['description'] }}</p>
                <span class="plan-picker__meta">
                    @if ($isSelected)
                        Dipilih untuk invoice
                    @elseif ($plan['is_current'])
                        Paket aktif saat ini
                    @else
                        Pilih paket ini
                    @endif
                </span>
            </button>
        @endforeach
    </div>

    @error($statePath)
        <p class="plan-picker__error">{{ $message }}</p>
    @enderror
    @error('requested_plan_code')
        <p class="plan-picker__error">{{ $message }}</p>
    @enderror
</div>
