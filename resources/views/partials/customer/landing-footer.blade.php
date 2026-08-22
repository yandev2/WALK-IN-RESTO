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

<footer class="bg-zinc-950 px-5 pb-10 pt-14 text-white">
    <div class="landing-container grid gap-10 md:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <p class="font-display text-2xl font-bold text-white">{{ $restaurant->name }}</p>
            <p class="mt-3 max-w-xs text-sm leading-relaxed text-white/60">{{ $ctaCopy['footer_tagline'] ?? '' }}</p>
            @if ($whatsappUrl ?? null)
                <a href="{{ $whatsappUrl }}" class="landing-interactive mt-5 inline-flex rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
                    WhatsApp
                </a>
            @endif
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">{{ $ctaCopy['footer_nav'] ?? 'Navigasi' }}</p>
            <ul class="mt-4 space-y-2.5 text-sm text-white/80">
                <li><a href="#atas" class="landing-interactive hover:text-white">{{ $ctaCopy['footer_home'] ?? 'Beranda' }}</a></li>
                @if ($isVisible('menu') && ($menuItems ?? collect())->isNotEmpty())
                    <li><a href="#menu" class="landing-interactive hover:text-white">{{ $navCopy('menu') }}</a></li>
                @endif
                @if ($isVisible('how_to'))
                    <li><a href="#cara-pesan" class="landing-interactive hover:text-white">{{ $navCopy('how_to') }}</a></li>
                @endif
                @if ($isVisible('hours'))
                    <li><a href="#jam" class="landing-interactive hover:text-white">{{ $navCopy('hours') }}</a></li>
                @endif
                @if ($isVisible('location'))
                    <li><a href="#lokasi" class="landing-interactive hover:text-white">{{ $navCopy('location') }}</a></li>
                @endif
            </ul>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">{{ $ctaCopy['footer_contact'] ?? 'Kontak' }}</p>
            <ul class="mt-4 space-y-2.5 text-sm leading-relaxed text-white/80">
                @if ($outlet?->address)
                    <li>{{ $outlet->address }}</li>
                @endif
                @if ($outlet?->phone)
                    <li><a href="{{ $whatsappUrl }}" class="landing-interactive hover:text-white">{{ $outlet->phone }}</a></li>
                @endif
            </ul>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">{{ $ctaCopy['footer_visit'] ?? 'Datang ke sini' }}</p>
            <p class="mt-4 text-sm text-white/60">Walk-in saja. Pilih meja kosong, scan QR, pesan dari HP.</p>
            @if ($mapsUrl ?? null)
                <a href="{{ $mapsUrl }}" rel="noopener noreferrer" class="landing-interactive mt-4 inline-flex rounded-full border border-white/25 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    {{ $ctaCopy['footer_maps'] ?? 'Buka Google Maps' }}
                </a>
            @endif
        </div>
    </div>
    <p class="landing-container mt-12 border-t border-white/10 pt-6 text-center text-xs text-white/45">
        &copy; {{ now()->year }} {{ $restaurant->name }}
    </p>
</footer>
