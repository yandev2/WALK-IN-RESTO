<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Stiker meja {{ $table->code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; color: #1c1410; margin: 18px; }
        h1 { font-size: 14px; margin: 0 0 4px; }
        .table { font-size: 22px; font-weight: bold; margin: 0 0 8px; }
        img { width: 220px; height: 220px; }
        .hint { font-size: 11px; color: #555; margin-top: 8px; }
    </style>
</head>
<body>
    <h1>{{ $table->outlet?->restaurant?->name ?: 'Restoran' }}</h1>
    <p class="table">Meja {{ $table->code }}</p>
    <img src="data:image/png;base64,{{ $png }}" alt="QR meja {{ $table->code }}">
    <p class="hint">Scan untuk memesan dari HP.</p>
</body>
</html>
