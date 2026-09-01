@php
    use App\Support\LandingLayout;

    $landingBase = $landingUrl ?? null;
    $anchor = fn (string $id): string => $landingBase ? $landingBase.'#'.$id : '#'.$id;
    $layout = $layout ?? null;
    $visibleSections = $visibleSections ?? [];
    $showMenuNav = (($menuItems ?? collect())->isNotEmpty() || request()->routeIs('landing.menu'))
        && ($layout === null || $layout->isVisible('menu', $visibleSections) || request()->routeIs('landing.menu'));

    $navCopy = function (string $id) use ($layout): string {
        if (! $layout instanceof LandingLayout) {
            return LandingLayout::defaultCopy()[$id]['nav'] ?? LandingLayout::ADMIN_LABELS[$id] ?? $id;
        }

        return $layout->copyFor($id)['nav'] ?? LandingLayout::ADMIN_LABELS[$id] ?? $id;
    };
@endphp

<header
    class="sticky top-0 z-50 border-b border-border-subtle/80 bg-surface-base/85 backdrop-blur-md transition-colors duration-200"
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
>
    <div class="landing-container flex items-center justify-between gap-3 py-3.5">
        <a href="{{ $homeUrl ?? $anchor('atas') }}" class="group flex min-w-0 items-center gap-3">
            @if ($logoUrl ?? null)
                <img src="{{ $logoUrl }}" alt="" class="h-11 w-11 rounded-2xl object-cover ring-2 ring-primary/25 shadow-sm transition duration-200 group-hover:scale-105">
            @else
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-primary-dark text-sm font-bold text-white shadow-sm ring-2 ring-white/20 transition duration-200 group-hover:scale-105">
                    {{ mb_substr($restaurant->name, 0, 1) }}
                </span>
            @endif
            <span class="truncate font-display text-xl font-bold tracking-tight text-body group-hover:text-primary transition-colors">{{ $restaurant->name }}</span>
        </a>

        <nav class="hidden items-center gap-7 text-sm font-semibold text-muted lg:flex">
            @if ($showMenuNav)
                <a href="{{ route('landing.menu', $restaurant) }}" @class(['landing-nav-link', 'text-primary' => request()->routeIs('landing.menu')])>{{ $navCopy('menu') }}</a>
            @endif
            @if ($layout === null || $layout->isVisible('how_to', $visibleSections))
                <a href="{{ $anchor('cara-pesan') }}" class="landing-nav-link">{{ $navCopy('how_to') }}</a>
            @endif
            @if ($layout === null || $layout->isVisible('hours', $visibleSections))
                <a href="{{ $anchor('jam') }}" class="landing-nav-link">{{ $navCopy('hours') }}</a>
            @endif
            @if (($layout === null && $restaurant->cmsGalleryImages->isNotEmpty()) || ($layout && $layout->isVisible('gallery', $visibleSections)))
                <a href="{{ $anchor('galeri') }}" class="landing-nav-link">{{ $navCopy('gallery') }}</a>
            @endif
            @if ($layout === null || $layout->isVisible('location', $visibleSections))
                <a href="{{ $anchor('lokasi') }}" class="landing-nav-link">{{ $navCopy('location') }}</a>
            @endif
            @if (($layout === null && $restaurant->cmsFaqs->isNotEmpty()) || ($layout && $layout->isVisible('faq', $visibleSections)))
                <a href="{{ $anchor('faq') }}" class="landing-nav-link">{{ $navCopy('faq') }}</a>
            @endif
        </nav>

        <div class="flex shrink-0 items-center gap-2">
            <x-customer.theme-toggle />
            @if ($ctaUrl ?? null)
                <div class="hidden sm:inline-flex">
                    <x-customer.btn-primary :href="$ctaUrl">
                        {{ $ctaLabel ?? 'Lihat lokasi' }}
                    </x-customer.btn-primary>
                </div>
            @endif
            <button
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-border-subtle bg-surface-raised text-body lg:hidden"
                @click="open = ! open"
                :aria-expanded="open.toString()"
                aria-label="Menu navigasi"
            >
                <svg x-show="! open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                <svg x-cloak x-show="open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div
        x-cloak
        x-show="open"
        x-transition
        class="border-t border-border-subtle bg-surface-raised lg:hidden"
        @click.outside="open = false"
    >
        <nav class="landing-container flex flex-col gap-1 py-3 text-sm font-semibold text-body">
            @if ($showMenuNav)
                <a href="{{ route('landing.menu', $restaurant) }}" class="rounded-xl px-3 py-2.5 hover:bg-surface-muted" @click="open = false">{{ $navCopy('menu') }}</a>
            @endif
            @if ($layout === null || $layout->isVisible('how_to', $visibleSections))
                <a href="{{ $anchor('cara-pesan') }}" class="rounded-xl px-3 py-2.5 hover:bg-surface-muted" @click="open = false">{{ $navCopy('how_to') }}</a>
            @endif
            @if ($layout === null || $layout->isVisible('hours', $visibleSections))
                <a href="{{ $anchor('jam') }}" class="rounded-xl px-3 py-2.5 hover:bg-surface-muted" @click="open = false">{{ $navCopy('hours') }}</a>
            @endif
            @if (($layout === null && $restaurant->cmsGalleryImages->isNotEmpty()) || ($layout && $layout->isVisible('gallery', $visibleSections)))
                <a href="{{ $anchor('galeri') }}" class="rounded-xl px-3 py-2.5 hover:bg-surface-muted" @click="open = false">{{ $navCopy('gallery') }}</a>
            @endif
            @if ($layout === null || $layout->isVisible('location', $visibleSections))
                <a href="{{ $anchor('lokasi') }}" class="rounded-xl px-3 py-2.5 hover:bg-surface-muted" @click="open = false">{{ $navCopy('location') }}</a>
            @endif
            @if (($layout === null && $restaurant->cmsFaqs->isNotEmpty()) || ($layout && $layout->isVisible('faq', $visibleSections)))
                <a href="{{ $anchor('faq') }}" class="rounded-xl px-3 py-2.5 hover:bg-surface-muted" @click="open = false">{{ $navCopy('faq') }}</a>
            @endif
            @if ($ctaUrl ?? null)
                <a href="{{ $ctaUrl }}" class="mt-1 rounded-full bg-primary px-4 py-2.5 text-center text-white" @click="open = false">{{ $ctaLabel ?? 'Lihat lokasi' }}</a>
            @endif
        </nav>
    </div>
</header>
