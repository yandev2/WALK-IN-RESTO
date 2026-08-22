@php
    $bankName = $bankName ?? config('subscription.bank_name');
    $bankAccount = $bankAccount ?? config('subscription.bank_account');
    $bankHolder = $bankHolder ?? config('subscription.bank_holder');
    $contactEmail = $contactEmail ?? config('subscription.contact_email');
    $qrUrl = $qrUrl ?? 'https://picsum.photos/seed/founder-billing-qr/200/200';
@endphp

<div class="transfer-widget">
    <style>
        .transfer-widget {
            --tw-bg: #fff7ed;
            --tw-line: #fdba74;
            --tw-text: #1c1917;
            --tw-muted: #78716c;
            --tw-accent: #c2410c;
            --tw-btn: #fff;
            margin-top: 0.15rem;
        }

        .dark .transfer-widget {
            --tw-bg: color-mix(in srgb, #c2410c 12%, #1f2937);
            --tw-line: #9a3412;
            --tw-text: #fafaf9;
            --tw-muted: #d6d3d1;
            --tw-accent: #fdba74;
            --tw-btn: #111827;
        }

        .transfer-widget__box {
            display: flex;
            align-items: stretch;
            gap: 0.95rem;
            padding: 0.9rem;
            border: 1px dashed var(--tw-line);
            border-radius: 6px;
            background: var(--tw-bg);
        }

        .transfer-widget__qr {
            width: 5.5rem;
            height: 5.5rem;
            flex-shrink: 0;
            overflow: hidden;
            border: 1px solid var(--tw-line);
            border-radius: 4px;
            background: #fff;
        }

        .transfer-widget__qr img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .transfer-widget__body {
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.28rem;
        }

        .transfer-widget__kicker {
            margin: 0;
            font-size: 0.72rem;
            color: var(--tw-muted);
        }

        .transfer-widget__holder {
            margin: 0;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--tw-accent);
            line-height: 1.25;
        }

        .transfer-widget__row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.4rem;
        }

        .transfer-widget__account {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--tw-accent);
            font-variant-numeric: tabular-nums;
        }

        .transfer-widget__copy {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.45rem;
            border: 1px solid var(--tw-line);
            border-radius: 4px;
            background: var(--tw-btn);
            color: var(--tw-text);
            font-size: 0.7rem;
            font-weight: 600;
            line-height: 1;
            cursor: pointer;
        }

        .transfer-widget__copy svg {
            width: 0.85rem;
            height: 0.85rem;
        }

        .transfer-widget__copy.is-copied {
            border-color: #047857;
            color: #047857;
        }

        .transfer-widget__contact {
            margin: 0.15rem 0 0;
            font-size: 0.75rem;
            color: var(--tw-muted);
        }
    </style>

    <div
        class="transfer-widget__box"
        x-data="{ copied: false }"
    >
        <div class="transfer-widget__qr">
            <img src="{{ $qrUrl }}" alt="QR pembayaran">
        </div>

        <div class="transfer-widget__body">
            <p class="transfer-widget__kicker">Transfer ke {{ $bankName }}</p>
            <p class="transfer-widget__holder">{{ $bankHolder }}</p>
            <div class="transfer-widget__row">
                <p class="transfer-widget__account">{{ $bankAccount }}</p>
                <button
                    type="button"
                    class="transfer-widget__copy"
                    :class="{ 'is-copied': copied }"
                    @click="
                        navigator.clipboard.writeText(@js((string) $bankAccount)).then(() => {
                            copied = true;
                            setTimeout(() => copied = false, 1600);
                        })
                    "
                >
                    <x-filament::icon icon="heroicon-o-clipboard-document" />
                    <span x-text="copied ? 'Disalin' : 'Salin'"></span>
                </button>
            </div>
            @if (filled($contactEmail))
                <p class="transfer-widget__contact">{{ $contactEmail }}</p>
            @endif
        </div>
    </div>
</div>
