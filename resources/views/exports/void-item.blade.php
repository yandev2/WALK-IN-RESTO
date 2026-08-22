@extends('exports.layouts.report', [
    'colspan' => 10,
    'subtitle' => 'Item yang dibatalkan beserta kebijakan omzet.',
])

@section('headers')
    <th style="{{ $styles['th'] }}">No</th>
    <th style="{{ $styles['th'] }}">Waktu void</th>
    <th style="{{ $styles['th'] }}">No order</th>
    <th style="{{ $styles['th'] }}">Meja</th>
    <th style="{{ $styles['th'] }}">Item</th>
    <th style="{{ $styles['th'] }}">Qty</th>
    <th style="{{ $styles['th'] }}">Nilai</th>
    <th style="{{ $styles['th'] }}">Policy</th>
    <th style="{{ $styles['th'] }}">Alasan</th>
    <th style="{{ $styles['th'] }}">Scope</th>
@endsection

@section('body')
    @forelse ($rows as $row)
        @php
            $policyColor = match ($row['void_policy']) {
                'cut' => '#B45309',
                'waste' => '#B91C1C',
                default => '#334155',
            };
        @endphp
        <tr @class(['row-alt' => $loop->even])>
            <td style="{{ $styles['td_center'] }}">{{ $loop->iteration }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['voided_at'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['order_number'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['table'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['item_name'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['qty'] }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['line_total']) }}</td>
            <td style="{{ $styles['td_center'] }} color: {{ $policyColor }}; font-weight:700;">{{ $row['void_policy'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['void_reason'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['void_scope'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10" style="{{ $styles['td_center'] }} padding:18px; color: {{ $styles['muted'] }};">Tidak ada item void pada periode ini.</td>
        </tr>
    @endforelse
@endsection

@section('footer')
    <tr>
        <td colspan="6" style="{{ $styles['foot'] }} text-align:right;">Cut / Waste</td>
        <td colspan="4" style="{{ $styles['foot'] }}">
            <span style="color:#B45309;">Cut {{ $money($total_cut) }}</span>
            ·
            <span style="color:#B91C1C;">Waste {{ $money($total_waste) }}</span>
        </td>
    </tr>
@endsection
