<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Rekonsiliasi Penjualan &amp; Komisi</title>
</head>
<body>
<table>
    <thead>
        <tr>
            <th colspan="11" style="font-size: 15px; font-weight: bold; text-align: center;">LAPORAN REKONSILIASI PENJUALAN &amp; KOMISI</th>
        </tr>
        <tr>
            <th colspan="11" style="font-size: 12px; text-align: center;">{{ $restaurant->name }}</th>
        </tr>
        <tr>
            <th colspan="11" style="text-align: center;">Periode: {{ $summary['from']->format('d M Y') }} - {{ $summary['to']->format('d M Y') }} (Zona Waktu: {{ $summary['timezone'] }})</th>
        </tr>
        <tr>
            <th colspan="11" style="text-align: center;">Skema: {{ $summary['is_commission_plan'] ? 'Bagi Hasil Kasir (' . $summary['commission_rate'] . '%)' : 'Paket Langganan Tetap' }}</th>
        </tr>
        <tr>
            <th colspan="11"></th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #f1f5f9; font-weight: bold; text-align: left;">RINGKASAN EKSEKUTIF</th>
            <th colspan="7" style="background-color: #f1f5f9;"></th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: left; font-weight: normal;">Total Penjualan Kotor (Gross Sales)</th>
            <th style="font-weight: bold; text-align: right;">{{ $summary['gross_sales'] }}</th>
            <th colspan="7" style="text-align: left; font-weight: normal;">Total nilai transaksi sebelum diskon</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: left; font-weight: normal;">Total Diskon &amp; Promo Toko</th>
            <th style="font-weight: bold; text-align: right;">{{ $summary['discount_amount'] }}</th>
            <th colspan="7" style="text-align: left; font-weight: normal;">Diskon yang ditanggung restoran</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: left; font-weight: normal;">Total Pesanan Batal / Void (Cut)</th>
            <th style="font-weight: bold; text-align: right;">{{ $summary['void_cut_amount'] }}</th>
            <th colspan="7" style="text-align: left; font-weight: normal;">Otomatis bebas komisi platform (Rp 0)</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: left; font-weight: bold; background-color: #fef3c7;">Total Penjualan Bersih (Net Sales)</th>
            <th style="font-weight: bold; text-align: right; background-color: #fef3c7;">{{ $summary['net_sales'] }}</th>
            <th colspan="7" style="text-align: left; font-weight: bold; background-color: #fef3c7;">Dasar pengenaan bagi hasil komisi</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: left; font-weight: bold; background-color: #fee2e2; color: #b91c1c;">Total Komisi Platform ({{ $summary['commission_rate'] }}%)</th>
            <th style="font-weight: bold; text-align: right; background-color: #fee2e2; color: #b91c1c;">{{ $summary['commission_amount'] }}</th>
            <th colspan="7" style="text-align: left; background-color: #fee2e2;">{{ $summary['is_commission_plan'] ? 'Porsi bagi hasil layanan platform' : 'Bebas komisi (Paket langganan tetap)' }}</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: left; font-weight: bold; background-color: #dcfce7; color: #15803d;">Total Hak Bersih Restoran</th>
            <th style="font-weight: bold; text-align: right; background-color: #dcfce7; color: #15803d;">{{ $summary['net_payout'] }}</th>
            <th colspan="7" style="text-align: left; font-weight: bold; background-color: #dcfce7; color: #15803d;">Hasil bersih yang menjadi hak restoran</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: left; font-weight: normal;">Kas Masuk Tunai (Laci Kasir)</th>
            <th style="text-align: right; font-weight: normal;">{{ $summary['cash_collected'] }}</th>
            <th colspan="7" style="text-align: left; font-weight: normal;">Uang fisik yang diterima kasir</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: left; font-weight: normal;">Kas Masuk Digital (QRIS / Transfer)</th>
            <th style="text-align: right; font-weight: normal;">{{ $summary['qris_collected'] }}</th>
            <th colspan="7" style="text-align: left; font-weight: normal;">Uang yang masuk ke rekening settlement</th>
        </tr>
        <tr>
            <th colspan="11"></th>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <th style="font-weight: bold; text-align: left;">No. Pesanan</th>
            <th style="font-weight: bold; text-align: left;">Waktu</th>
            <th style="font-weight: bold; text-align: left;">Meja</th>
            <th style="font-weight: bold; text-align: left;">Metode Bayar</th>
            <th style="font-weight: bold; text-align: right;">Subtotal</th>
            <th style="font-weight: bold; text-align: right;">Diskon</th>
            <th style="font-weight: bold; text-align: right;">Void (Cut)</th>
            <th style="font-weight: bold; text-align: right;">Penjualan Bersih</th>
            <th style="font-weight: bold; text-align: right;">Komisi Platform</th>
            <th style="font-weight: bold; text-align: right;">Hak Bersih Resto</th>
            <th style="font-weight: bold; text-align: left;">Status / Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
            <tr>
                <td style="font-weight: bold;">#{{ $row['number'] }}</td>
                <td>{{ $row['paid_at'] }}</td>
                <td>{{ $row['table'] }}</td>
                <td>{{ strtoupper((string) $row['payment_method']) }}</td>
                <td style="text-align: right;">{{ $row['subtotal'] }}</td>
                <td style="text-align: right;">{{ $row['discount'] }}</td>
                <td style="text-align: right;">{{ $row['void_cut'] }}</td>
                <td style="text-align: right; font-weight: bold;">{{ $row['net_sales'] }}</td>
                <td style="text-align: right; color: #b91c1c;">{{ $row['commission_amount'] }}</td>
                <td style="text-align: right; font-weight: bold; color: #15803d;">{{ $row['net_resto'] }}</td>
                <td>{{ $row['status_note'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="11"></th>
        </tr>
        <tr>
            <td colspan="11" style="font-size: 10px; color: #64748b; font-style: italic;">
                * Disclaimer Transparansi: Laporan ini menyajikan rekonsiliasi Penjualan Bersih kasir dan bagi hasil platform. Laporan ini belum memperhitungkan Harga Pokok Penjualan (HPP bahan baku) dan biaya operasional internal restoran.
            </td>
        </tr>
    </tfoot>
</table>
</body>
</html>
