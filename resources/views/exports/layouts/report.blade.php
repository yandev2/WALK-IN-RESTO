@php
    $theme = $theme ?? \App\Support\RestaurantTheme::for($restaurant ?? null);
    $primary = $theme['primary'] ?? '#F97316';
    $primaryDark = $theme['primary_dark'] ?? '#D97706';
    $primaryLight = '#FFF7ED';
    $border = '#CBD5E1';
    $muted = '#64748B';
    $rowAlt = '#F8FAFC';
    $colspan = (int) ($colspan ?? 8);
    $subtitle = $subtitle ?? 'Laporan operasional restoran.';
    $summary = $summary ?? [];
    $money = fn (int|float $amount): string => 'Rp '.number_format((int) $amount, 0, ',', '.');
    $th = $styles['th'] ?? '';
    $td = $styles['td'] ?? '';
    $tdRight = $styles['td_right'] ?? '';
    $tdCenter = $styles['td_center'] ?? '';
    $foot = $styles['foot'] ?? '';
    $muted = $styles['muted'] ?? '#64748B';
    $money = fn (int|float $amount): string => 'Rp '.number_format((int) $amount, 0, ',', '.');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} — {{ $restaurant->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 8.5px;
            color: #1e293b;
            margin: 0;
            padding: 14px 16px 12px;
            line-height: 1.35;
        }
        table.data-table { width: 100%; border-collapse: collapse; }
        table.data-table thead { display: table-header-group; }
        table.data-table tbody { display: table-row-group; }
        table.data-table tr.row-alt td { background-color: {{ $rowAlt }}; }
        .report-footer { margin-top: 10px; font-size: 7.5px; color: {{ $muted }}; text-align: right; }
    </style>
</head>
<body>
<table class="data-table">
    <thead>
        <tr>
            <th colspan="{{ $colspan }}" style="text-align:left; background-color: {{ $primaryDark }}; color:#ffffff; font-size:15px; font-weight:800; letter-spacing:0.4px; padding:12px 14px; border:none; text-transform:uppercase;">
                {{ $title }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ $colspan }}" style="text-align:left; background-color: {{ $primaryLight }}; color: {{ $primaryDark }}; font-size:10px; font-weight:600; padding:8px 14px; border:none;">
                {{ $subtitle }} · {{ $restaurant->name }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ $colspan }}" style="text-align:left; background-color:#F8FAFC; color:#334155; font-size:8.5px; font-weight:normal; padding:8px 14px 10px; border:none;">
                Restoran: {{ $restaurant->name }}
                @if (! empty($outlet_label))
                    · Outlet: {{ $outlet_label }}
                @endif
                @if (! empty($period_label))
                    · Periode: {{ $period_label }}
                @endif
                @if (! empty($timezone))
                    · Zona: {{ $timezone }}
                @endif
                @if (! empty($filters_label))
                    · Filter: {{ $filters_label }}
                @endif
                · Diekspor: {{ $generated_at }}
                @if ($summary !== [])
                    <br>
                    @foreach ($summary as $label => $value)
                        <strong>{{ $label }}:</strong> {{ $value }}@if (! $loop->last) · @endif
                    @endforeach
                @endif
            </th>
        </tr>
        <tr>
            @yield('headers')
        </tr>
    </thead>
    <tbody>
        @yield('body')
    </tbody>
    <tfoot>
        @yield('footer')
    </tfoot>
</table>
<div class="report-footer">
    Dokumen ini digenerate otomatis oleh Resto Admin · {{ $restaurant->name }} · {{ $generated_at }}
</div>
</body>
</html>
