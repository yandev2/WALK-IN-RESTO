<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak struk #{{ $order->number }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: system-ui, sans-serif;
            background: #f4f4f5;
            color: #18181b;
        }
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: #fff;
            border-bottom: 1px solid #e4e4e7;
        }
        .toolbar p { margin: 0; font-size: 0.85rem; color: #52525b; }
        button, a.btn {
            appearance: none;
            border: 0;
            border-radius: 0.5rem;
            padding: 0.5rem 0.9rem;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            text-decoration: none;
        }
        button.primary { background: #18181b; color: #fff; }
        a.btn { background: #e4e4e7; color: #18181b; }
        iframe {
            display: block;
            width: 100%;
            height: calc(100vh - 4.5rem);
            border: 0;
            background: #fff;
        }
        @media print {
            .toolbar { display: none; }
            iframe { height: 100vh; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="primary" type="button" id="print-btn">Cetak struk</button>
        @if ($backUrl)
            <a class="btn" href="{{ $backUrl }}">Kembali ke pesanan</a>
        @endif
        <p>File PDF yang sama dengan unduh/WA. Pilih printer, kertas 80mm, skala 100% (jangan fit A4).</p>
    </div>
    <iframe id="receipt-pdf" src="{{ $pdfUrl }}" title="Struk PDF"></iframe>
    <script>
        const frame = document.getElementById('receipt-pdf');
        const auto = {{ $auto ? 'true' : 'false' }};

        function printReceipt() {
            try {
                frame.contentWindow?.focus();
                frame.contentWindow?.print();
            } catch (e) {
                window.print();
            }
        }

        document.getElementById('print-btn').addEventListener('click', printReceipt);

        if (auto) {
            frame.addEventListener('load', function () {
                setTimeout(printReceipt, 500);
            });
        }
    </script>
</body>
</html>
