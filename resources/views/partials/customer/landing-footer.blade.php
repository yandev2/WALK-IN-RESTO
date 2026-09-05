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
    $instagramUrl = $instagramUrl ?? ($outlet?->instagram ? \App\Support\CmsMedia::instagramUrl($outlet->instagram) : null);
@endphp

<footer class="bg-zinc-950 px-5 pb-10 pt-14 text-white border-t border-white/10">
    <div class="landing-container grid gap-10 md:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <p class="font-display text-2xl font-bold text-white">{{ $restaurant->name }}</p>
            <p class="mt-3 max-w-xs text-sm leading-relaxed text-white/70">{{ $ctaCopy['footer_tagline'] ?? '' }}</p>
            <div class="mt-5 flex flex-wrap items-center gap-2">
                @if ($whatsappUrl ?? null)
                    <a href="{{ $whatsappUrl }}" class="landing-interactive inline-flex items-center gap-2 rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-primary-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                        </svg>
                        WhatsApp
                    </a>
                @endif
                @if ($instagramUrl)
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="landing-interactive inline-flex items-center gap-2 rounded-full bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">
                        <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                        </svg>
                        Instagram
                    </a>
                @endif
            </div>
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
                @if ($outlet?->instagram && $instagramUrl)
                    <li>
                        <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="landing-interactive inline-flex items-center gap-1.5 hover:text-primary transition-colors">
                            <svg class="h-4 w-4 shrink-0 text-pink-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ str_starts_with($outlet->instagram, 'http') ? '@'.basename($outlet->instagram) : (str_starts_with($outlet->instagram, '@') ? $outlet->instagram : '@'.$outlet->instagram) }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-primary">{{ $ctaCopy['footer_visit'] ?? 'Datang ke sini' }}</p>
            <p class="mt-4 text-sm text-white/70 leading-relaxed">{{ !empty($ctaCopy['footer_visit_text']) ? $ctaCopy['footer_visit_text'] : 'Walk-in saja. Pilih meja kosong, scan QR, pesan dari HP.' }}</p>
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
