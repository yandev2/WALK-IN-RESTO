@extends('exports.layouts.report', [
    'colspan' => 9,
    'subtitle' => 'Daftar item menu restoran.',
])

@section('headers')
    <th style="{{ $styles['th'] }} width:4%;">No</th>
    <th style="{{ $styles['th'] }} width:22%;">Nama</th>
    <th style="{{ $styles['th'] }} width:14%;">Kategori</th>
    <th style="{{ $styles['th'] }} width:12%;">Stasiun</th>
    <th style="{{ $styles['th'] }} width:12%;">Harga</th>
    <th style="{{ $styles['th'] }} width:8%;">Diskon</th>
    <th style="{{ $styles['th'] }} width:10%;">Status</th>
    <th style="{{ $styles['th'] }} width:10%;">Stok</th>
    <th style="{{ $styles['th'] }} width:8%;">Urutan</th>
@endsection

@section('body')
    @forelse ($rows as $row)
        @php
            $activeColor = $row['is_active'] === 'Aktif' ? '#15803D' : '#64748B';
            $stockColor = $row['stock'] === 'Ready' ? '#15803D' : '#B91C1C';
        @endphp
        <tr @class(['row-alt' => $loop->even])>
            <td style="{{ $styles['td_center'] }}">{{ $loop->iteration }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['name'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['category'] }}</td>
            <td style="{{ $styles['td'] }}">{{ $row['station'] }}</td>
            <td style="{{ $styles['td_right'] }}">{{ $money($row['price']) }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['discount_percent'] }}%</td>
            <td style="{{ $styles['td_center'] }} color: {{ $activeColor }}; font-weight:700;">{{ $row['is_active'] }}</td>
            <td style="{{ $styles['td_center'] }} color: {{ $stockColor }}; font-weight:700;">{{ $row['stock'] }}</td>
            <td style="{{ $styles['td_center'] }}">{{ $row['sort_order'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9" style="{{ $styles['td_center'] }} padding:18px; color: {{ $styles['muted'] }};">Tidak ada item menu untuk filter ini.</td>
        </tr>
    @endforelse
@endsection

@section('footer')
    <tr>
        <td colspan="9" style="{{ $styles['foot'] }} text-align:right;">
            Ringkasan katalog — {{ $total_items }} item diekspor
        </td>
    </tr>
@endsection
