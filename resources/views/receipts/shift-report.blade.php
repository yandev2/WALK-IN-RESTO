<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Shift #{{ $shift->id }}</title>
    <style>
        @page { margin: 8px; }
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
        .contact { font-size: 9px; color: #222; margin: 0 0 6px; }
        .title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 4px 0;
        }
        .dash {
            border: none;
            border-top: 1px dashed #333;
            margin: 5px 0;
        }
        .meta, .table-data { width: 100%; }
        .meta td, .table-data td { padding: 1.5px 0; vertical-align: top; }
        .meta .label { width: 75px; color: #444; }
        .meta .val { font-weight: 500; }
        .section-heading {
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 4px 0 2px;
            color: #222;
        }
        .table-data .label { text-align: left; }
        .table-data .value { text-align: right; font-weight: bold; }
        .grand td {
            font-weight: bold;
            font-size: 10.5px;
            padding-top: 3px;
            border-top: 1px dotted #555;
        }
        .diff-row td {
            font-size: 10.5px;
            font-weight: bold;
            padding: 3px 0;
        }
        .diff-pass { color: #047857; }
        .diff-warn { color: #b91c1c; }
        .diff-surplus { color: #1d4ed8; }
        .notes-box {
            background-color: #f3f4f6;
            padding: 4px;
            margin-top: 4px;
            font-size: 8.5px;
            border-radius: 3px;
        }
        .movements-table {
            width: 100%;
            font-size: 8.5px;
            margin-top: 3px;
        }
        .movements-table th {
            text-align: left;
            border-bottom: 1px solid #777;
            padding-bottom: 2px;
        }
        .movements-table td {
            padding: 2px 0;
            border-bottom: 1px dotted #ccc;
        }
        .footer-signatures {
            margin-top: 14px;
            width: 100%;
        }
        .footer-signatures td {
            width: 50%;
            text-align: center;
            font-size: 8.5px;
        }
        .sign-space { height: 35px; }
        .printed-at {
            margin-top: 12px;
            font-size: 7.5px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $timezone = $shift->restaurant?->timezone ?: 'Asia/Jakarta';
        $phone = $shift->outlet?->phone;
        $openTime = $shift->opened_at->timezone($timezone)->format('d/m/Y H:i');
        $closeTime = $shift->closed_at ? $shift->closed_at->timezone($timezone)->format('d/m/Y H:i') : 'MASIH AKTIF';
        $diff = $shift->cash_difference ?? 0;
    @endphp

    <div class="wrap">
        <div class="center">
            @if (!empty($logoDataUri))
                <div class="logo">
                    <img src="{{ $logoDataUri }}" alt="Logo">
                </div>
            @endif
            <div class="brand">{{ $shift->restaurant?->name ?? 'RESTO' }}</div>
            <div class="contact">
                {{ $shift->outlet?->name }}<br>
                @if (filled($phone)) Telp/WA: {{ $phone }}<br> @endif
            </div>
            <hr class="dash">
            <div class="title">REKAP SHIFT KASIR</div>
            <hr class="dash">
        </div>

        <table class="meta">
            <tr>
                <td class="label">ID Shift</td>
                <td class="val">#{{ $shift->id }}</td>
            </tr>
            <tr>
                <td class="label">Kasir</td>
                <td class="val">{{ $shift->user?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="val">{{ $shift->isOpen() ? 'AKTIF (OPEN)' : 'DITUTUP (CLOSED)' }}</td>
            </tr>
            <tr>
                <td class="label">Jam Buka</td>
                <td class="val">{{ $openTime }}</td>
            </tr>
            @if ($shift->closed_at)
                <tr>
                    <td class="label">Jam Tutup</td>
                    <td class="val">{{ $closeTime }}</td>
                </tr>
                @if ($shift->closedByUser && $shift->closedByUser->id !== $shift->user_id)
                    <tr>
                        <td class="label">Ditutup Oleh</td>
                        <td class="val">{{ $shift->closedByUser->name }}</td>
                    </tr>
                @endif
            @endif
        </table>

        <hr class="dash">
        <div class="section-heading">Arus Kas Laci (Cash Flow)</div>
        <table class="table-data">
            <tr>
                <td class="label">Modal Awal Kasir</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $shift->starting_cash) }}</td>
            </tr>
            <tr>
                <td class="label">(+) Penjualan Tunai</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $shift->cash_sales) }}</td>
            </tr>
            <tr>
                <td class="label">(+) Kas Masuk</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $shift->cash_in) }}</td>
            </tr>
            <tr>
                <td class="label">(-) Kas Keluar</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $shift->cash_out) }}</td>
            </tr>
            <tr class="grand">
                <td class="label">(=) Total Kas Sistem</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $calc['expected_cash']) }}</td>
            </tr>

            @if ($shift->closed_at)
                <tr>
                    <td class="label">Uang Fisik Kasir</td>
                    <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $shift->actual_ending_cash) }}</td>
                </tr>
                <tr class="diff-row">
                    <td class="label">Selisih Kas</td>
                    <td class="value @if($diff == 0) diff-pass @elseif($diff < 0) diff-warn @else diff-surplus @endif">
                        @if ($diff == 0)
                            PAS (Rp 0)
                        @elseif ($diff < 0)
                            MINUS {{ \App\Support\CmsMedia::formatIdr(abs($diff)) }}
                        @else
                            LEBIH +{{ \App\Support\CmsMedia::formatIdr($diff) }}
                        @endif
                    </td>
                </tr>
            @endif
        </table>

        @if (filled($shift->difference_reason))
            <div class="notes-box">
                <strong>Catatan Selisih:</strong> {{ $shift->difference_reason }}
            </div>
        @endif

        <hr class="dash">
        <div class="section-heading">Penjualan, Poin & Omset Shift</div>
        <table class="table-data">
            <tr>
                <td class="label">Penjualan Kotor (Katalog)</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) ($calc['gross_sales'] ?? $calc['total_sales'])) }}</td>
            </tr>
            @if (($calc['points_redeemed'] ?? 0) > 0)
                <tr>
                    <td class="label">(-) Diskon Poin Member</td>
                    <td class="value" style="color: #b91c1c;">-{{ \App\Support\CmsMedia::formatIdr((int) $calc['points_discount_amount']) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 8px; color: #555; padding-bottom: 2px;">
                        * Rp {{ number_format((int) $calc['points_discount_amount'], 0, ',', '.') }} ditukar via {{ $calc['points_redeemed'] }} Poin Loyalty (Sah program CRM, bukan selisih laci kasir).
                    </td>
                </tr>
            @endif
            <tr>
                <td class="label">(+) Penerimaan Tunai (Laci)</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $shift->cash_sales) }}</td>
            </tr>
            <tr>
                <td class="label">(+) Penerimaan QRIS/Online</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $shift->non_cash_sales) }}</td>
            </tr>
            <tr class="grand">
                <td class="label">(=) Total Omset Riil Diterima</td>
                <td class="value">{{ \App\Support\CmsMedia::formatIdr((int) $calc['total_sales']) }}</td>
            </tr>
        </table>

        @if ($shift->movements->isNotEmpty())
            <hr class="dash">
            <div class="section-heading">Rincian Kas Masuk / Keluar</div>
            <table class="movements-table">
                <thead>
                    <tr>
                        <th style="width: 22%;">Tipe</th>
                        <th style="width: 48%;">Kategori & Catatan</th>
                        <th style="width: 30%; text-align: right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shift->movements as $m)
                        <tr>
                            <td>{{ $m->type === 'cash_in' ? 'MASUK' : 'KELUAR' }}</td>
                            <td>
                                <strong>{{ ucfirst($m->category) }}</strong>
                                @if (filled($m->notes)) <br>{{ $m->notes }} @endif
                            </td>
                            <td style="text-align: right;">
                                {{ $m->type === 'cash_in' ? '+' : '-' }}{{ \App\Support\CmsMedia::formatIdr((int) $m->amount) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if (filled($shift->notes))
            <div class="notes-box" style="margin-top: 6px;">
                <strong>Catatan Shift:</strong> {{ $shift->notes }}
            </div>
        @endif

        <table class="footer-signatures">
            <tr>
                <td>
                    Kasir
                    <div class="sign-space"></div>
                    ( {{ $shift->user?->name ?? 'Kasir' }} )
                </td>
                <td>
                    Supervisor / Owner
                    <div class="sign-space"></div>
                    ( {{ $shift->closedByUser?->name ?? 'Manager' }} )
                </td>
            </tr>
        </table>

        <div class="printed-at">
            Dicetak: {{ now()->timezone($timezone)->format('d/m/Y H:i:s') }}<br>
            Walk-in Resto Shift Management
        </div>
    </div>
</body>
</html>
