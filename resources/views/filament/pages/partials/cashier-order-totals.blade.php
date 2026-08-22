@php
    /** @var array $preview */
    $subtotal = (int) ($preview['subtotal'] ?? 0);
    $isEmpty = $subtotal === 0;
    $isQris = (bool) ($preview['is_qris'] ?? false);
    $qrisImageUrl = $preview['qris_image_url'] ?? null;
    $paymentLabel = $isQris ? 'QRIS' : 'Tunai';
    $lineCount = (int) ($preview['line_count'] ?? 0);
    $totalQty = (int) ($preview['total_qty'] ?? 0);

    $rowStyle = 'display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: baseline; gap: 0.75rem; font-size: 0.875rem; line-height: 1.4;';
    $labelStyle = 'color: rgb(100 116 139);';
    $valueStyle = 'font-variant-numeric: tabular-nums; font-weight: 500; color: rgb(51 65 85); text-align: right; white-space: nowrap;';

    $formatPct = static function (float $pct): string {
        return rtrim(rtrim(number_format($pct, 2, ',', '.'), '0'), ',');
    };
@endphp

<style>
    .cashier-pay-summary {
        border-radius: 0.75rem;
        border: 1px solid rgb(226 232 240);
        background: linear-gradient(180deg, rgb(255 255 255) 0%, rgb(248 250 252) 100%);
        box-shadow: 0 1px 2px rgb(15 23 42 / 0.04);
        overflow: hidden;
    }

    .dark .cashier-pay-summary {
        border-color: rgb(55 65 81);
        background: linear-gradient(180deg, rgb(17 24 39) 0%, rgb(15 23 42) 100%);
        box-shadow: none;
    }

    .cashier-pay-summary__badge--cash {
        background: rgb(220 252 231);
        color: rgb(21 128 61);
        border: 1px solid rgb(187 247 208);
    }

    .dark .cashier-pay-summary__badge--cash {
        background: rgb(21 128 61 / 0.15);
        color: rgb(134 239 172);
        border-color: rgb(34 197 94 / 0.25);
    }

    .cashier-pay-summary__badge--qris {
        background: rgb(219 234 254);
        color: rgb(29 78 216);
        border: 1px solid rgb(191 219 254);
    }

    .dark .cashier-pay-summary__badge--qris {
        background: rgb(29 78 216 / 0.15);
        color: rgb(147 197 253);
        border-color: rgb(59 130 246 / 0.25);
    }

    .cashier-pay-summary__total-box {
        background: rgb(15 23 42);
        color: rgb(248 250 252);
    }

    .dark .cashier-pay-summary__total-box {
        background: rgb(248 250 252);
        color: rgb(15 23 42);
    }

    .cashier-pay-summary__note {
        border-radius: 0.5rem;
        border: 1px solid rgb(191 219 254);
        background: rgb(239 246 255);
        color: rgb(29 78 216);
        font-size: 0.75rem;
        line-height: 1.45;
    }

    .dark .cashier-pay-summary__note {
        border-color: rgb(59 130 246 / 0.3);
        background: rgb(29 78 216 / 0.12);
        color: rgb(147 197 253);
    }

    .cashier-pay-summary__empty {
        color: rgb(100 116 139);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .dark .cashier-pay-summary__empty {
        color: rgb(148 163 184);
    }

    .cashier-pay-summary__qris {
        margin-top: 1rem;
        border-radius: 0.625rem;
        border: 1px solid rgb(226 232 240);
        background: rgb(255 255 255);
        padding: 0.875rem;
        text-align: center;
    }

    .dark .cashier-pay-summary__qris {
        border-color: rgb(55 65 81);
        background: rgb(15 23 42 / 0.6);
    }

    .cashier-pay-summary__qris-label {
        margin-bottom: 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: rgb(100 116 139);
    }

    .dark .cashier-pay-summary__qris-label {
        color: rgb(148 163 184);
    }

    .cashier-pay-summary__qris-image {
        display: block;
        width: 100%;
        max-width: 11rem;
        margin: 0 auto;
        border-radius: 0.5rem;
        border: 1px solid rgb(241 245 249);
        background: rgb(248 250 252);
    }

    .dark .cashier-pay-summary__qris-image {
        border-color: rgb(55 65 81);
        background: rgb(255 255 255);
    }

    .cashier-pay-summary__qris-missing {
        font-size: 0.8125rem;
        line-height: 1.45;
        color: rgb(100 116 139);
    }

    .dark .cashier-pay-summary__qris-missing {
        color: rgb(148 163 184);
    }
</style>

<div class="cashier-pay-summary">
    <div style="padding: 1rem 1.25rem 0;">
        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;">
            <div style="display: flex; align-items: flex-start; gap: 0.75rem; min-width: 0;">
                <div
                    style="display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; border-radius: 0.625rem; background: rgb(241 245 249); color: rgb(71 85 105); flex-shrink: 0;"
                    aria-hidden="true"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 1.25rem; height: 1.25rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h-15m15 0h.008v.008H21v-.008Zm0 3h.008v.008H21V9Zm0 3h.008v.008H21v-.008Zm0 3h.008v.008H21v-.008Z" />
                    </svg>
                </div>

                <div style="min-width: 0;">
                    <div style="font-size: 0.9375rem; font-weight: 600; color: rgb(15 23 42); line-height: 1.3;">
                        Ringkasan pembayaran
                    </div>
                    <div style="margin-top: 0.2rem; font-size: 0.8125rem; color: rgb(100 116 139); line-height: 1.45;">
                        Perkiraan total dihitung otomatis dari item yang dipilih.
                    </div>
                </div>
            </div>

            <span
                class="cashier-pay-summary__badge--{{ $isQris ? 'qris' : 'cash' }}"
                style="display: inline-flex; align-items: center; padding: 0.2rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; flex-shrink: 0;"
            >
                {{ $paymentLabel }}
            </span>
        </div>
    </div>

    <div style="padding: 1rem 1.25rem 1.25rem;">
        @if ($isEmpty)
            <p class="cashier-pay-summary__empty">
                Belum ada item — tambahkan menu di bawah untuk melihat perkiraan total.
            </p>
        @else
            @if ($lineCount > 0)
                <div style="margin-bottom: 0.75rem; font-size: 0.75rem; color: rgb(100 116 139);">
                    {{ $lineCount }} {{ $lineCount === 1 ? 'baris' : 'baris' }} · {{ $totalQty }} {{ $totalQty === 1 ? 'porsi' : 'porsi' }}
                </div>
            @endif

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="{{ $rowStyle }}">
                    <span style="{{ $labelStyle }}">Subtotal</span>
                    <span style="{{ $valueStyle }}">{{ \App\Support\CmsMedia::formatIdr($subtotal) }}</span>
                </div>

                @if (($preview['service_amount'] ?? 0) > 0)
                    <div style="{{ $rowStyle }}">
                        <span style="{{ $labelStyle }}">Service ({{ $formatPct($preview['service_pct']) }}%)</span>
                        <span style="{{ $valueStyle }}">{{ \App\Support\CmsMedia::formatIdr($preview['service_amount']) }}</span>
                    </div>
                @endif

                @if (($preview['pb1_amount'] ?? 0) > 0)
                    <div style="{{ $rowStyle }}">
                        <span style="{{ $labelStyle }}">PB1 ({{ $formatPct($preview['pb1_pct']) }}%)</span>
                        <span style="{{ $valueStyle }}">{{ \App\Support\CmsMedia::formatIdr($preview['pb1_amount']) }}</span>
                    </div>
                @endif
            </div>

            @if ($isQris)
                <div class="cashier-pay-summary__qris">
                    <p class="cashier-pay-summary__qris-label">Scan QRIS outlet</p>

                    @if ($qrisImageUrl)
                        <img
                            src="{{ $qrisImageUrl }}"
                            alt="QRIS outlet"
                            class="cashier-pay-summary__qris-image"
                        >
                    @else
                        <p class="cashier-pay-summary__qris-missing">
                            QRIS outlet belum diunggah. Atur di menu Outlet agar kasir bisa scan di sini.
                        </p>
                    @endif
                </div>
            @endif

            <div
                class="cashier-pay-summary__total-box"
                style="margin-top: 1rem; padding: 0.875rem 1rem; border-radius: 0.625rem; display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: baseline; gap: 0.75rem;"
            >
                <span style="font-size: 0.875rem; font-weight: 600; opacity: 0.88;">Total bayar</span>
                <span style="font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; font-variant-numeric: tabular-nums; text-align: right; white-space: nowrap;">
                    {{ \App\Support\CmsMedia::formatIdr($preview['grand_payable']) }}
                </span>
            </div>

            @if ($isQris)
                <p class="cashier-pay-summary__note" style="margin-top: 0.75rem; padding: 0.625rem 0.75rem;">
                    QRIS: kode unik 1–999 ditambahkan saat pembayaran diproses.
                </p>
            @endif
        @endif
    </div>
</div>
