@extends('exports.layouts.report', [
    'colspan' => 13,
    'subtitle' => 'Rincian item terjual per pesanan.',
])

@section('headers')
    <th style="{{ $styles['th'] }}">No</th>
    <th style="{{ $styles['th'] }}">Waktu bayar</th>
    <th style="{{ $styles['th'] }}">No order</th>
    <th style="{{ $styles['th'] }}">Meja</th>
    <th style="{{ $styles['th'] }}">Item</th>
    <th style="{{ $styles['th'] }}">Qty</th>
    <th style="{{ $styles['th'] }}">Harga</th>
    <th style="{{ $styles['th'] }}">Line</th>
    <th style="{{ $styles['th'] }}">Efektif</th>
    <th style="{{ $styles['th'] }}">Bayar</th>
    <th style="{{ $styles['th'] }}">KDS</th>
    <th style="{{ $styles['th'] }}">Void</th>
    <th style="{{ $styles['th'] }}">Alasan</th>
@endsection

@section('body')
    @forelse ($rows as $row)
        @php
            $voidColor = match ($row['void_policy']) {
                'cut' => '#B45309',
                'waste' => '#B91C1C',
                default => '#334155',
            };
        @endphp
        <tr @class(['row-alt' => $loop->even])>
            <td style="{{ $styles['td_center'] }}">{{ $loop->iteration }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['paid_at'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['order_number'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['table'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['item_name'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['qty'] }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['unit_price']) }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['line_total']) }}</td>
            <td style="{{ $styles['td_right'] }} font-weight:700;">{{ $money($row['effective_total']) }}</td>
            <td style="{{ $styles['td_center'] }}">{{ strtoupper((string) $row['payment_method']) }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['kds_status'] }}</td>
            <td style="{{ $styles['td_center'] }} color: {{ $voidColor }}; font-weight:700;">{{ $row['void_policy'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['void_reason'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="13" style="{{ $styles['td_center'] }} padding:18px; color: {{ $styles['muted'] }};">Tidak ada item penjualan pada periode ini.</td>
        </tr>
    @endforelse
@endsection

@section('footer')
    <tr>
        <td colspan="5" style="{{ $styles['foot'] }} text-align:right;">Total</td>
        <td style="{{ $styles['foot'] }} text-align:center;">{{ $total_qty }}</td>
        <td style="{{ $styles['foot'] }}"></td>
        <td style="{{ $styles['foot'] }} text-align:right;">{{ $money($total_line) }}</td>
        <td style="{{ $styles['foot'] }} text-align:right;">{{ $money($total_effective) }}</td>
        <td colspan="4" style="{{ $styles['foot'] }}"></td>
    </tr>
@endsection
