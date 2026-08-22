@extends('exports.layouts.report', [
    'colspan' => 10,
    'subtitle' => 'Pesanan yang di-void beserta dampak omzet.',
])

@section('headers')
    <th style="{{ $styles['th'] }}">No</th>
    <th style="{{ $styles['th'] }}">Waktu void</th>
    <th style="{{ $styles['th'] }}">No order</th>
    <th style="{{ $styles['th'] }}">Meja</th>
    <th style="{{ $styles['th'] }}">Grand</th>
    <th style="{{ $styles['th'] }}">Omzet net</th>
    <th style="{{ $styles['th'] }}">Cut</th>
    <th style="{{ $styles['th'] }}">Waste</th>
    <th style="{{ $styles['th'] }}">Item</th>
    <th style="{{ $styles['th'] }}">Alasan</th>
@endsection

@section('body')
    @forelse ($rows as $row)
        <tr @class(['row-alt' => $loop->even])>
            <td style="{{ $styles['td_center'] }}">{{ $loop->iteration }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['voided_at'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['order_number'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['table'] }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['grand_before']) }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['omzet_net']) }}</td>
            <td style="{{ $styles['td_right'] }} color:#B45309; font-weight:700;">{{ $money($row['cut_total']) }}</td>
            <td style="{{ $styles['td_right'] }} color:#B91C1C; font-weight:700;">{{ $money($row['waste_total']) }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['item_count'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['void_reason'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10" style="{{ $styles['td_center'] }} padding:18px; color: {{ $styles['muted'] }};">Tidak ada pesanan void pada periode ini.</td>
        </tr>
    @endforelse
@endsection

@section('footer')
    <tr>
        <td colspan="6" style="{{ $styles['foot'] }} text-align:right;">Total {{ $order_count }} order void</td>
        <td style="{{ $styles['foot'] }} text-align:right; color:#B45309;">{{ $money($total_cut) }}</td>
        <td style="{{ $styles['foot'] }} text-align:right; color:#B91C1C;">{{ $money($total_waste) }}</td>
        <td colspan="2" style="{{ $styles['foot'] }}"></td>
    </tr>
@endsection
