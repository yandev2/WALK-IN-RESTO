@props([
    'categories' => null,
    'featuredPost' => null,
    'blogHero' => null,
])

@php
    $locale = app()->getLocale();
    $heroTrans = $blogHero?->translate($locale);

    $badgeText = $heroTrans?->badge_text ?: __('blog.badge_hero');
    $title = $heroTrans?->title ?: __('blog.title');
    $subtitle = $heroTrans?->subtitle ?: __('blog.subtitle');
    $searchPlaceholder = $heroTrans?->search_placeholder ?: __('blog.search_placeholder');
    $searchButton = __('blog.search_button');

    $heroImage = $blogHero?->banner_image ?: $featuredPost?->featured_image;
    $overlayOpacity = $blogHero ? ($blogHero->overlay_opacity ?? 60) : 60;
    $showQuickCategories = $blogHero ? ($blogHero->show_quick_categories ?? true) : true;
@endphp

<section class="blog-hero" aria-label="{{ __('blog.hero_label') }}">
    {{-- Banner Image Background if available --}}
    @if ($heroImage)
        <img
            src="{{ \App\Support\MediaUrl::public($heroImage) }}"
            alt=""
            class="blog-hero-image"
            loading="eager"
            fetchpriority="high"
        >
    @endif

    {{-- Backdrop Overlay with custom opacity --}}
    <div
        class="blog-hero-overlay"
        style="background: linear-gradient(180deg, rgba(11, 15, 25, {{ number_format(($overlayOpacity / 100) * 0.75, 2, '.', '') }}) 0%, rgba(11, 15, 25, {{ number_format($overlayOpacity / 100, 2, '.', '') }}) 60%, var(--surface-base, #0b0f19) 100%);"
    ></div>

    {{-- Ambient Neon Auras & Grid --}}
    <div class="blog-hero-ambient" aria-hidden="true">
        <div class="blog-hero-aura-cyan"></div>
        <div class="blog-hero-aura-indigo"></div>
        <div class="blog-hero-grid-pattern"></div>
    </div>

    <div class="blog-hero-content">
        {{-- Pill Badge --}}
        @if (filled($badgeText))
            <div class="blog-hero-badge-container">
                <span class="blog-hero-badge">
                    <span class="blog-hero-badge-pulse" aria-hidden="true">
                        <span class="blog-hero-badge-dot"></span>
                    </span>
                    <span>{{ $badgeText }}</span>
                </span>
            </div>
        @endif

        {{-- Main Title & Subtitle --}}
        @if (filled($title))
            <h1 class="blog-hero-title font-display">
                {{ $title }}
            </h1>
        @endif

        @if (filled($subtitle))
            <p class="blog-hero-subtitle">
                {{ $subtitle }}
            </p>
        @endif

        {{-- Glassmorphic Search Capsule --}}
        <div class="blog-hero-search-wrapper">
            <form
                action="{{ route('blog.archive') }}"
                method="GET"
                class="blog-hero-search-capsule"
                role="search"
            >
                <div class="blog-hero-search-icon" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <label class="sr-only" for="blog-search-input">{{ $searchPlaceholder }}</label>
                <input
                    id="blog-search-input"
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="{{ $searchPlaceholder }}"
                    class="blog-hero-search-input"
                    autocomplete="off"
                >
                <button type="submit" class="blog-hero-search-btn">
                    <span>{{ $searchButton }}</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- Trending / Quick Category Chips --}}
        @if ($showQuickCategories && $categories && $categories->isNotEmpty())
            <div class="mt-6 flex flex-wrap items-center justify-center gap-2 text-xs">
                <span class="text-white/60 font-medium">{{ __('blog.trending_topics') }}</span>
                @foreach ($categories->take(5) as $cat)
                    @php $catT = $cat->translate($locale); @endphp
                    @if ($catT)
                        <a
                            href="{{ route('blog.category', ['slug' => $catT->slug]) }}"
                            class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium text-white/90 backdrop-blur-md transition hover:bg-primary hover:border-primary hover:text-white"
                        >
                            {{ $catT->name }}
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
