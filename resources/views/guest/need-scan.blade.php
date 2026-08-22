<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        @include('partials.customer.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Scan QR meja</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:600|dm-sans:400,500,600,700" rel="stylesheet" />
        @include('partials.customer.theme-vars', ['theme' => \App\Support\RestaurantTheme::for(null)])
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-surface-base text-body antialiased transition-colors duration-300">
        <main class="relative mx-auto flex min-h-screen max-w-md flex-col justify-center overflow-hidden px-6 py-16 text-center">
            <div class="pointer-events-none absolute -right-10 top-10 h-40 w-40 rounded-full blur-2xl" style="background: color-mix(in srgb, var(--brand-accent) 25%, transparent);"></div>
            <div class="relative customer-card mx-auto max-w-sm p-8">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-16 0h2m13-9h.01M6 7h.01M12 17a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>
                <p class="customer-section-label">Walk-in</p>
                <h1 class="mt-2 font-display text-3xl font-bold text-body">Scan stiker QR di meja Anda.</h1>
                <p class="mt-3 text-muted">Pemesanan hanya dari meja. Tidak ada pesan dari HP di luar resto.</p>
            </div>
        </main>
    </body>
</html>
