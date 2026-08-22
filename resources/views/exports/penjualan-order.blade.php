@extends('exports.layouts.report', [
    'colspan' => 14,
    'subtitle' => 'Rekap pesanan yang sudah dibayar.',
])

@section('headers')
    <th style="{{ $styles['th'] }}">No</th>
    <th style="{{ $styles['th'] }}">Waktu bayar</th>
    <th style="{{ $styles['th'] }}">No order</th>
    <th style="{{ $styles['th'] }}">Meja</th>
    <th style="{{ $styles['th'] }}">Status</th>
    <th style="{{ $styles['th'] }}">Bayar</th>
    <th style="{{ $styles['th'] }}">Subtotal</th>
    <th style="{{ $styles['th'] }}">Diskon</th>
    <th style="{{ $styles['th'] }}">Service</th>
    <th style="{{ $styles['th'] }}">PB1</th>
    <th style="{{ $styles['th'] }}">Grand</th>
    <th style="{{ $styles['th'] }}">Omzet net</th>
    <th style="{{ $styles['th'] }}">Waste</th>
    <th style="{{ $styles['th'] }}">Kasir</th>
@endsection

@section('body')
    @forelse ($rows as $row)
        @php
            $statusColor = $row['status'] === 'voided' ? '#B91C1C' : '#15803D';
        @endphp
        <tr @class(['row-alt' => $loop->even])>
            <td style="{{ $styles['td_center'] }}">{{ $loop->iteration }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['paid_at'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['order_number'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['table'] }}</td>
            <td style="{{ $styles['td_center'] }} color: {{ $statusColor }}; font-weight:700;">{{ $row['status'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ strtoupper((string) $row['payment_method']) }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['subtotal']) }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['discount']) }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['service']) }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['pb1']) }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['grand_before']) }}</td>
            <td style="{{ $styles['td_right'] }} font-weight:700;">{{ $money($row['omzet_net']) }}</td>
            <td style="{{ $styles['td_right'] }} color:#B91C1C;">{{ $money($row['waste']) }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['kasir'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="14" style="{{ $styles['td_center'] }} padding:18px; color: {{ $styles['muted'] }};">Tidak ada pesanan berbayar pada periode ini.</td>
        </tr>
    @endforelse
@endsection

@section('footer')
    <tr>
        <td colspan="11" style="{{ $styles['foot'] }} text-align:right;">Total {{ $order_count }} order</td>
        <td style="{{ $styles['foot'] }} text-align:right;">{{ $money($total_omzet) }}</td>
        <td style="{{ $styles['foot'] }} text-align:right; color:#B91C1C;">{{ $money($total_waste) }}</td>
        <td style="{{ $styles['foot'] }}"></td>
    </tr>
@endsection
