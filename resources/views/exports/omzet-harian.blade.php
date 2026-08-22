@extends('exports.layouts.report', [
    'colspan' => 9,
    'subtitle' => 'Omzet, jumlah order, dan metode bayar per hari.',
])

@section('headers')
    <th style="{{ $styles['th'] }} width:4%;">No</th>
    <th style="{{ $styles['th'] }} width:14%;">Tanggal</th>
    <th style="{{ $styles['th'] }} width:14%;">Zona</th>
    <th style="{{ $styles['th'] }} width:14%;">Omzet</th>
    <th style="{{ $styles['th'] }} width:10%;">Order</th>
    <th style="{{ $styles['th'] }} width:12%;">QRIS</th>
    <th style="{{ $styles['th'] }} width:12%;">Tunai</th>
    <th style="{{ $styles['th'] }} width:8%;">Void</th>
    <th style="{{ $styles['th'] }} width:12%;">Waste</th>
@endsection

@section('body')
    @forelse ($rows as $row)
        <tr @class(['row-alt' => $loop->even])>
            <td style="{{ $styles['td_center'] }}">{{ $loop->iteration }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['date'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['timezone'] }}</td>
            <td style="{{ $styles['td_right'] }} font-weight:700;">{{ $money($row['omzet']) }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['count'] }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['qris']) }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['cash']) }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['voided'] }}</td>
            <td style="{{ $styles['td_right'] }} color:#B91C1C;">{{ $money($row['waste']) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9" style="{{ $styles['td_center'] }} padding:18px; color: {{ $styles['muted'] }};">Tidak ada omzet pada periode ini.</td>
        </tr>
    @endforelse
@endsection

@section('footer')
    <tr>
        <td colspan="3" style="{{ $styles['foot'] }}">Total periode</td>
        <td style="{{ $styles['foot'] }} text-align:right;">{{ $money($total_omzet) }}</td>
        <td style="{{ $styles['foot'] }} text-align:center;">{{ $total_orders }}</td>
        <td colspan="3" style="{{ $styles['foot'] }}"></td>
        <td style="{{ $styles['foot'] }} text-align:right; color:#B91C1C;">{{ $money($total_waste) }}</td>
    </tr>
@endsection
