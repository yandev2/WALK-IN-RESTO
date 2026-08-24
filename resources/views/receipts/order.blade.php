<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struk #{{ $order->number }}</title>
    <style>
        @@page { margin: 8px; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .wrap { width: 100%; }
        .center { text-align: center; }
        .logo { margin: 0 auto 6px; }
        .logo img { width: 52px; height: 52px; border-radius: 26px; }
        .brand {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            margin: 0 0 2px;
        }
        .contact { font-size: 9px; color: #222; margin: 0 0 8px; }
        .dash {
            border: none;
            border-top: 1px dashed #333;
            margin: 6px 0;
        }
        .meta { width: 100%; }
        .meta td { padding: 1px 0; vertical-align: top; }
        .meta .label { width: 72px; }
        .items { width: 100%; }
        .items td { padding: 2px 0; vertical-align: top; }
        .items .name { width: 58%; }
        .items .qty { width: 14%; text-align: right; }
        .items .price { width: 28%; text-align: right; }
        .totals { width: 100%; }
        .totals td { padding: 2px 0; }
        .totals .label { text-align: left; }
        .totals .value { text-align: right; }
        .totals .grand td { font-weight: bold; font-size: 11px; padding-top: 4px; }
        .pay { font-weight: bold; }
        .footer { margin-top: 10px; font-size: 10px; }
        .legal { font-size: 8px; color: #555; margin-top: 4px; }
    </style>
</head>
<body>
    @php
        $timezone = $order->restaurant?->timezone ?: 'Asia/Jakarta';
        $phone = $order->outlet?->phone;
        $items = $order->items->where('kds_status', '!=', 'voided');
        $method = strtoupper((string) ($payment?->method ?? $order->payment_method ?? ''));
        $methodLabel = match ($method) {
            'CASH' => 'TUNAI',
            default => $method,
        };
    @endphp

    <div class="wrap">
        <div class="center">
            @if (filled($logoDataUri ?? null))
                <div class="logo">
                    <img src="{{ $logoDataUri }}" alt="">
                </div>
            @endif
            <div class="brand">{{ $order->restaurant?->name }}</div>
            @if (filled($phone))
                <div class="contact">WHATSAPP: {{ $phone }}</div>
            @endif
        </div>

        <hr class="dash">

        <table class="meta">
            <tr>
                <td class="label">Kode Struk</td>
                <td>: {{ $order->public_id }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td>: {{ $order->paid_at?->timezone($timezone)->format('Y-m-d H:i:s') ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kasir</td>
                <td>: {{ $payment?->paidByUser?->name ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pelanggan</td>
                <td>: {{ $order->visit?->customer_name ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Meja</td>
                <td>: {{ $order->visit?->diningTable?->code ?: '-' }}</td>
            </tr>
        </table>

        <hr class="dash">

        <table class="items">
            @forelse ($items as $item)
                <tr>
                    <td class="name">{{ $item->displayName() }}</td>
                    <td class="qty">x{{ $item->qty }}</td>
                    <td class="price">{{ number_format((int) $item->unit_price * (int) $item->qty, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Tidak ada item</td>
                </tr>
            @endforelse
        </table>

        <hr class="dash">

        <table class="totals">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">{{ number_format((int) $order->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if ((int) $order->discount_amount > 0)
                <tr>
                    <td class="label">Diskon</td>
                    <td class="value">-{{ number_format((int) $order->discount_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td class="label">Service</td>
                <td class="value">{{ number_format((int) $order->service_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">PB1</td>
                <td class="value">{{ number_format((int) $order->pb1_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand">
                <td class="label">Total</td>
                <td class="value">{{ number_format((int) $order->grand_payable, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if (filled($methodLabel))
            <hr class="dash">
            <div class="pay">{{ $methodLabel }}</div>
        @endif

        <hr class="dash">

        <div class="center footer">Terima kasih telah berkunjung.</div>
        <div class="center legal">Bukan faktur pajak resmi.</div>
    </div>
</body>
</html>
