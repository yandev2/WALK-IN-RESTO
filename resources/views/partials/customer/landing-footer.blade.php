@php
    use App\Support\LandingLayout;

    $layout = $layout ?? null;
    $visibleSections = $visibleSections ?? [];
    $ctaCopy = $layout instanceof LandingLayout ? $layout->copyFor('cta') : LandingLayout::defaultCopy()['cta'];
    $navCopy = function (string $id) use ($layout): string {
        if (! $layout instanceof LandingLayout) {
            return LandingLayout::defaultCopy()[$id]['nav'] ?? LandingLayout::ADMIN_LABELS[$id] ?? $id;
        }

        return $layout->copyFor($id)['nav'] ?? LandingLayout::ADMIN_LABELS[$id] ?? $id;
    };
    $isVisible = fn (string $id): bool => $layout === null || $layout->isVisible($id, $visibleSections);
@endphp

<footer class="bg-zinc-950 px-5 pb-10 pt-14 text-white border-t border-white/10">
    <div class="landing-container grid gap-10 md:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <p class="font-display text-2xl font-bold text-white">{{ $restaurant->name }}</p>
            <p class="mt-3 max-w-xs text-sm leading-relaxed text-white/70">{{ $ctaCopy['footer_tagline'] ?? '' }}</p>
            @if ($whatsappUrl ?? null)
                <a href="{{ $whatsappUrl }}" class="landing-interactive mt-5 inline-flex items-center gap-2 rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-primary-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                    </svg>
                    WhatsApp
                </a>
            @endif
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-primary">{{ $ctaCopy['footer_nav'] ?? 'Navigasi' }}</p>
            <ul class="mt-4 space-y-2.5 text-sm text-white/80">
                <li><a href="#atas" class="landing-interactive hover:text-primary transition-colors">{{ $ctaCopy['footer_home'] ?? 'Beranda' }}</a></li>
                @if ($isVisible('menu') && ($menuItems ?? collect())->isNotEmpty())
                    <li><a href="#menu" class="landing-interactive hover:text-primary transition-colors">{{ $navCopy('menu') }}</a></li>
                @endif
                @if ($isVisible('how_to'))
                    <li><a href="#cara-pesan" class="landing-interactive hover:text-primary transition-colors">{{ $navCopy('how_to') }}</a></li>
                @endif
                @if ($isVisible('hours'))
                    <li><a href="#jam" class="landing-interactive hover:text-primary transition-colors">{{ $navCopy('hours') }}</a></li>
                @endif
                @if ($isVisible('location'))
                    <li><a href="#lokasi" class="landing-interactive hover:text-primary transition-colors">{{ $navCopy('location') }}</a></li>
                @endif
            </ul>
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-primary">{{ $ctaCopy['footer_contact'] ?? 'Kontak' }}</p>
            <ul class="mt-4 space-y-2.5 text-sm leading-relaxed text-white/80">
                @if ($outlet?->address)
                    <li class="leading-snug">{{ $outlet->address }}</li>
                @endif
                @if ($outlet?->phone)
                    <li><a href="{{ $whatsappUrl }}" class="landing-interactive hover:text-primary transition-colors">{{ $outlet->phone }}</a></li>
                @endif
            </ul>
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-primary">{{ $ctaCopy['footer_visit'] ?? 'Datang ke sini' }}</p>
            <p class="mt-4 text-sm text-white/70 leading-relaxed">Walk-in saja. Pilih meja kosong, scan QR, pesan dari HP.</p>
            @if ($mapsUrl ?? null)
                <a href="{{ $mapsUrl }}" rel="noopener noreferrer" class="landing-interactive mt-4 inline-flex rounded-full border border-white/25 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10 transition">
                    {{ $ctaCopy['footer_maps'] ?? 'Buka Google Maps' }}
                </a>
            @endif
        </div>
    </div>
    <p class="landing-container mt-12 border-t border-white/10 pt-6 text-center text-xs text-white/50">
        &copy; {{ now()->year }} {{ $restaurant->name }}
    </p>
</footer>
