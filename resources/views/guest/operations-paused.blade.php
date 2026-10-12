<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        @include('partials.customer.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pemesanan di Meja — {{ $restaurant->name ?? 'Restoran' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:600,700|dm-sans:400,500,600,700" rel="stylesheet" />
        @include('partials.customer.theme-vars', ['theme' => $theme ?? \App\Support\RestaurantTheme::for($restaurant)])
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-surface-base text-body antialiased transition-colors duration-300">
        <main class="relative mx-auto flex min-h-screen max-w-md flex-col justify-center overflow-hidden px-5 py-12 text-center">
            {{-- Background decorative ambient glow --}}
            <div class="pointer-events-none absolute -right-12 top-16 h-48 w-48 rounded-full blur-3xl" style="background: color-mix(in srgb, var(--brand-accent) 25%, transparent);"></div>
            <div class="pointer-events-none absolute -left-12 bottom-16 h-48 w-48 rounded-full blur-3xl" style="background: color-mix(in srgb, var(--brand-primary) 20%, transparent);"></div>

            <div class="relative customer-card mx-auto w-full max-w-sm rounded-3xl p-7 sm:p-8 shadow-xl border border-border-subtle bg-surface-raised/90 backdrop-blur-md">
                {{-- Resto Logo or Warm Friendly Icon --}}
                @if (filled($restaurant->logo_path ?? null))
                    <div class="mx-auto mb-4 h-16 w-16 overflow-hidden rounded-2xl border border-border-subtle shadow-sm bg-surface-muted">
                        <img src="{{ \App\Support\CmsMedia::url($restaurant->logo_path) }}" alt="{{ $restaurant->name }}" class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10 text-primary ring-8 ring-primary/5">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 17.25h.008v.008H15v-.008zm-6 0h.008v.008H9v-.008zm12-4.5c0 3.314-4.03 6-9 6s-9-2.686-9-6m18 0c0-1.125-.494-2.148-1.328-2.923M3 12.75c0-1.125.494-2.148 1.328-2.923" />
                        </svg>
                    </div>
                @endif

                <span class="inline-flex items-center rounded-full bg-amber-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 border border-amber-500/20">
                    Pemesanan di Meja
                </span>

                <h1 class="mt-3 font-display text-xl sm:text-2xl font-bold text-body leading-snug">
                    Layanan Meja Sedang Diistirahatkan Sejenak
                </h1>

                <div class="mt-4 rounded-2xl bg-surface-muted/80 p-4 text-xs leading-relaxed text-muted text-left border border-border-subtle/50">
                    <p class="font-medium text-body">
                        Halo Kak! Selamat datang di <strong>{{ $restaurant->name }}</strong>. 😊
                    </p>
                    <p class="mt-2">
                        Saat ini layanan pemesanan mandiri lewat scan QR meja sedang kami istirahatkan sejenak. Silakan langsung melakukan pemesanan dan pembayaran ke <strong>meja kasir</strong> atau staf pelayan kami ya.
                    </p>
                    <p class="mt-2">
                        Staf kami siap melayani pesanan Anda dengan senang hati. Terima kasih banyak atas pengertiannya! 🙏
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-6 flex flex-col gap-2.5">
                    @if (\Illuminate\Support\Facades\Route::has('landing.menu'))
                        <a
                            href="{{ route('landing.menu', $restaurant) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-xs font-bold text-white shadow-md hover:bg-primary-hover hover:shadow-lg transition duration-200"
                        >
                            <span>Lihat Daftar Menu Restoran</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    @endif

                    @if (\Illuminate\Support\Facades\Route::has('landing.show'))
                        <a
                            href="{{ route('landing.show', $restaurant) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-border-subtle bg-surface-raised px-4 py-2.5 text-xs font-semibold text-body hover:bg-surface-muted transition"
                        >
                            Kembali ke Profil Restoran
                        </a>
                    @endif
                </div>
            </div>
        </main>
    </body>
</html>
