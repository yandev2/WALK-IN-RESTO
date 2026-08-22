<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struk #{{ $order->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        .muted { color: #555; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { text-align: left; padding: 4px 0; border-bottom: 1px solid #ddd; }
        th { font-size: 11px; text-transform: uppercase; color: #555; }
        .right { text-align: right; }
        .totals td { border-bottom: none; }
        .totals .grand { font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <h1>{{ $order->restaurant?->name }}</h1>
    <div class="muted">
        {{ $order->outlet?->name }}
        · Meja {{ $order->visit?->diningTable?->code ?: '-' }}
        · Order #{{ $order->number }}
        · {{ $order->paid_at?->timezone($order->restaurant?->timezone ?: 'Asia/Jakarta')->format('d/m/Y H:i') }}
    </div>
    <div>Metode: {{ strtoupper($order->payment_method) }} · Sumber: {{ $order->source }}</div>
    <div>WA: {{ $order->receipt_wa_snapshot ?: $order->visit?->customer_wa }}</div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        {{ $item->displayName() }}
                        @if ($item->kds_status === 'voided')
                            (void {{ $item->void_omzet_policy }})
                        @endif
                    </td>
                    <td class="right">{{ $item->qty }}</td>
                    <td class="right">{{ number_format((int) $item->unit_price, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format((int) $item->unit_price * (int) $item->qty, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">Rp {{ number_format((int) $order->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Service</td>
            <td class="right">Rp {{ number_format((int) $order->service_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>PB1</td>
            <td class="right">Rp {{ number_format((int) $order->pb1_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand">
            <td>Total dibayar</td>
            <td class="right">Rp {{ number_format((int) $order->grand_payable, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p class="muted">Bukan faktur pajak resmi.</p>
</body>
</html>
