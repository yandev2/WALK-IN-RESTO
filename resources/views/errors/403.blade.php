<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Dibatasi — {{ config('app.name', 'RestoTerdekat') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-slate-900 text-slate-100 flex items-center justify-center p-4 selection:bg-amber-500 selection:text-white">
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $message = trim((string) ($exception?->getMessage() ?: ''));
        $isOverdue = $tenant instanceof \App\Models\Restaurant && $tenant->hasOverdueCashierInvoice();
        
        $billingUrl = $tenant ? \App\Filament\Pages\SubscriptionStatus::getUrl(tenant: $tenant) : null;
        $dashboardUrl = $tenant ? \App\Filament\Pages\Dashboard::getUrl(tenant: $tenant) : url('/');
    @endphp

    <div class="max-w-lg w-full">
        <div class="relative bg-slate-800/80 border border-slate-700/80 rounded-3xl p-8 sm:p-10 shadow-2xl backdrop-blur-xl text-center overflow-hidden">
            <!-- Decorative gradient accent -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-rose-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Icon Header -->
            <div class="relative mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-amber-500/10 border border-amber-500/20 shadow-inner">
                <svg class="h-10 w-10 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>

            <!-- Status Code Badge -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold tracking-wider uppercase bg-amber-500/10 border border-amber-500/20 text-amber-400 mb-4">
                <span>403</span>
                <span>•</span>
                <span>Akses Dibatasi</span>
            </div>

            <!-- Heading -->
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white mb-3">
                @if ($isOverdue)
                    Layanan Kasir Ditangguhkan
                @else
                    Halaman Dibatasi
                @endif
            </h1>

            <!-- Descriptive Message -->
            <div class="text-sm sm:text-base text-slate-300 leading-relaxed space-y-2 mb-8">
                @if ($message && $message !== 'This action is unauthorized.' && $message !== 'Forbidden')
                    <p class="font-medium text-amber-200 bg-amber-950/40 border border-amber-800/50 rounded-xl px-4 py-3">
                        {{ $message }}
                    </p>
                @elseif ($isOverdue)
                    <p>
                        Fitur layanan kasir & operasional sedang dinonaktifkan sementara karena terdapat <strong>tagihan komisi bulan lalu yang belum diselesaikan</strong>.
                    </p>
                    <p class="text-xs text-slate-400">
                        Halaman profil publik dan landing page Anda tetap aktif. Silakan lunasi tagihan komisi kasir untuk mengaktifkan kembali seluruh menu kasir dan meja.
                    </p>
                @else
                    <p>
                        Anda tidak memiliki izin akses untuk membuka halaman ini atau layanan ini sedang dibatasi untuk paket Anda.
                    </p>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @if ($billingUrl)
                    <a href="{{ $billingUrl }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-amber-600 hover:bg-amber-500 active:bg-amber-700 transition shadow-lg shadow-amber-600/25">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                        Buka Billing & Pembayaran
                    </a>
                @endif

                <a href="{{ $dashboardUrl }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-300 bg-slate-700/60 hover:bg-slate-700 hover:text-white transition border border-slate-600">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            &copy; {{ date('Y') }} {{ config('app.name', 'RestoTerdekat') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
