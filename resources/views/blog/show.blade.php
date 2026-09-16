@extends('layouts.blog')

@php
    $locale = app()->getLocale();
    $t = $post->translate($locale) ?? $post->translations->first();
    $image = $post->featured_image ?? $post->og_image;
    $postTitle = $t?->meta_title ?: ($t?->title ?? 'Artikel Blog');
    $postDescription = $t?->meta_description ?: ($t?->excerpt ?: 'Baca artikel selengkapnya di blog kuliner.');
    $postOgImage = $image ? \App\Support\MediaUrl::public($image) : null;
    $canonicalUrl = $t?->canonical_url ?: route('blog.show', ['slug' => $t?->slug ?? $post->slug]);
    $robotsDirective = $t?->robots ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $postKeywords = $t?->meta_keywords ?: ($t?->focus_keyword ?: '');

    $catT = ($post->relationLoaded('category') && $post->category) ? $post->category->translate($locale) : null;
    $categoryName = $catT?->name ?? 'Kuliner';
    $categoryUrl = $catT ? route('blog.category', ['slug' => $catT->slug]) : null;

    $homeData = \App\Models\PlatformSetting::homeViewData();
@endphp

@section('title', $postTitle . ' · ' . (config('app.name', 'Cita Rasa Kita')))
@section('description', $postDescription)
@section('canonical', $canonicalUrl)
@section('robots', $robotsDirective)
@section('og_type', 'article')

@if (filled($postKeywords))
    @section('keywords', $postKeywords)
@endif

@if ($postOgImage)
    @section('og_image', $postOgImage)
@endif

@section('hreflang')
    @foreach (['id', 'en'] as $langLoc)
        @php $altT = $post->translate($langLoc); @endphp
        @if ($altT && filled($altT->slug))
            <link rel="alternate" hreflang="{{ $langLoc }}" href="{{ route('blog.show', ['slug' => $altT->slug]) }}" />
        @endif
    @endforeach
    @php $idSlug = $post->translate('id')?->slug ?? $post->translations->first()?->slug; @endphp
    @if ($idSlug)
        <link rel="alternate" hreflang="x-default" href="{{ route('blog.show', ['slug' => $idSlug]) }}" />
    @endif
@endsection

@section('article_meta')
    @if ($post->published_at)
        <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    @endif
    @if ($post->updated_at)
        <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    @endif
    @if ($post->author)
        <meta property="article:author" content="{{ $post->author->name }}">
    @endif
    @if ($categoryName)
        <meta property="article:section" content="{{ $categoryName }}">
    @endif
    @if ($post->relationLoaded('tags'))
        @foreach ($post->tags as $tagItem)
            @php $tagT = $tagItem->translate($locale); @endphp
            @if ($tagT)
                <meta property="article:tag" content="{{ $tagT->name }}">
            @endif
        @endforeach
    @endif
@endsection

@push('schema')
@php
    $breadcrumbItems = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => __('portfolio.nav.home') ?? 'Beranda',
            'item' => route('home'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Blog',
            'item' => route('blog.index'),
        ],
    ];

    if ($catT && $categoryUrl) {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => count($breadcrumbItems) + 1,
            'name' => $catT->name,
            'item' => $categoryUrl,
        ];
    }

    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => count($breadcrumbItems) + 1,
        'name' => $t?->title ?? 'Artikel',
        'item' => $canonicalUrl,
    ];

    $schemaBreadcrumbs = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbItems,
    ];

    $schemaArticle = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $canonicalUrl,
        ],
        'headline' => $t?->title ?? $postTitle,
        'description' => $postDescription,
        'inLanguage' => $locale,
        'datePublished' => $post->published_at ? $post->published_at->toIso8601String() : null,
        'dateModified' => $post->updated_at ? $post->updated_at->toIso8601String() : ($post->published_at ? $post->published_at->toIso8601String() : null),
        'author' => [
            '@type' => 'Person',
            'name' => $post->author?->name ?? 'Redaksi',
            'url' => route('blog.index'),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $homeData['site_name'] ?? config('app.name', 'Cita Rasa Kita'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $homeData['logo_url'] ?? asset('favicon.ico'),
            ],
        ],
    ];

    if ($postOgImage) {
        $schemaArticle['image'] = [$postOgImage];
    }

    if ($categoryName) {
        $schemaArticle['articleSection'] = $categoryName;
    }

    if (filled($postKeywords)) {
        $schemaArticle['keywords'] = $postKeywords;
    }
@endphp
<script type="application/ld+json">
{!! json_encode($schemaBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($schemaArticle, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    <div class="py-10 md:py-14">
        <div class="landing-container">
            <div class="blog-article-layout">
                <article class="blog-article-main min-w-0">
                    {{-- Breadcrumbs --}}
                    <nav aria-label="Breadcrumb" class="mb-6 flex flex-wrap items-center gap-2 text-xs font-semibold text-muted">
                        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">
                            {{ __('portfolio.nav.home') ?? 'Beranda' }}
                        </a>
                        <span class="text-border-subtle" aria-hidden="true">/</span>
                        <a href="{{ route('blog.index') }}" class="hover:text-primary transition-colors">
                            Blog
                        </a>
                        @if ($post->relationLoaded('category') && $post->category)
                            @php $catT = $post->category->translate($locale); @endphp
                            @if ($catT)
                                <span class="text-border-subtle" aria-hidden="true">/</span>
                                <a href="{{ route('blog.category', ['slug' => $catT->slug]) }}" class="hover:text-primary transition-colors">
                                    {{ $catT->name }}
                                </a>
                            @endif
                        @endif
                        <span class="text-border-subtle" aria-hidden="true">/</span>
                        <span class="max-w-[14rem] sm:max-w-md truncate text-body" aria-current="page">{{ $t?->title }}</span>
                    </nav>

                    {{-- Header --}}
                    <header>
                        <h1 class="font-display text-3xl font-extrabold tracking-tight text-body sm:text-4xl lg:text-5xl leading-tight">
                            {{ $t?->title }}
                        </h1>

                        {{-- Metadata --}}
                        <x-blog.post-meta :post="$post" />

                        {{-- Top Like & Share Bar --}}
                        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-y border-border-subtle/70 py-3">
                            <x-blog.like-button :post="$post" :has-liked="$hasLiked" />
                            <x-blog.share-buttons :post="$post" />
                        </div>

                        {{-- Excerpt Subtitle --}}
                        @if ($t?->excerpt)
                            <p class="mt-6 text-lg sm:text-xl font-medium text-body/80 italic leading-relaxed pl-4 border-l-4 border-primary">
                                {{ $t->excerpt }}
                            </p>
                        @endif
                    </header>

                    {{-- Featured Image --}}
                    @if ($image)
                        <div class="mt-8 overflow-hidden rounded-2xl border border-border-subtle shadow-md">
                            <img
                                src="{{ \App\Support\MediaUrl::public($image) }}"
                                alt="{{ $t->featured_image_alt ?? $t->title }}"
                                class="aspect-[16/9] w-full max-h-[28rem] object-cover"
                                width="1200"
                                height="675"
                                loading="eager"
                                fetchpriority="high"
                                decoding="async"
                            >
                        </div>
                    @endif

                    {{-- Top Article Ad Slot --}}
                    <x-ads.slot name="blog_article_top" class="my-6" />

                    {{-- Rich Article Content with In-Article Ad Injection --}}
                    @if ($t?->content)
                        <div class="blog-prose mt-8">
                            {!! app(\App\Services\AdPlacementService::class)->injectInArticleAd($t->content, 3, 'blog_article_middle') !!}
                        </div>
                    @endif

                    {{-- Bottom Article Ad Slot --}}
                    <x-ads.slot name="blog_article_bottom" class="my-8" />

                    {{-- Bottom Like & Share Bar --}}
                    <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-border-subtle pt-6">
                        <x-blog.like-button :post="$post" :has-liked="$hasLiked" />
                        <x-blog.share-buttons :post="$post" />
                    </div>

                    {{-- Previous / Next Navigation --}}
                    @if ((isset($prevPost) && $prevPost) || (isset($nextPost) && $nextPost))
                        <div class="mt-10 grid gap-4 border-y border-border-subtle py-6 sm:grid-cols-2">
                            @if (isset($prevPost) && $prevPost && ($prevT = $prevPost->translate($locale) ?? $prevPost->translations->first()))
                                <a
                                    href="{{ route('blog.show', ['slug' => $prevT->slug]) }}"
                                    class="blog-card group flex flex-col justify-between !p-4 transition hover:border-primary"
                                    rel="prev"
                                >
                                    <span class="text-xs font-semibold uppercase tracking-wider text-muted">
                                        ← {{ __('blog.previous_post') }}
                                    </span>
                                    <span class="mt-2 text-sm font-bold text-body line-clamp-2 transition-colors group-hover:text-primary">
                                        {{ $prevT->title }}
                                    </span>
                                </a>
                            @else
                                <div class="hidden sm:block"></div>
                            @endif

                            @if (isset($nextPost) && $nextPost && ($nextT = $nextPost->translate($locale) ?? $nextPost->translations->first()))
                                <a
                                    href="{{ route('blog.show', ['slug' => $nextT->slug]) }}"
                                    class="blog-card group flex flex-col justify-between !p-4 transition hover:border-primary sm:text-right"
                                    rel="next"
                                >
                                    <span class="text-xs font-semibold uppercase tracking-wider text-muted">
                                        {{ __('blog.next_post') }} →
                                    </span>
                                    <span class="mt-2 text-sm font-bold text-body line-clamp-2 transition-colors group-hover:text-primary">
                                        {{ $nextT->title }}
                                    </span>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Mobile Related Posts (shown below article on smaller screens) --}}
                    @if ($relatedPosts->isNotEmpty())
                        <div class="mt-12 lg:hidden">
                            <x-blog.related-posts :posts="$relatedPosts" />
                        </div>
                    @endif

                    {{-- Comments Section --}}
                    <x-blog.comments :post="$post" :comments="$comments" />
                </article>

                {{-- Desktop Sidebar --}}
                <aside class="blog-article-sidebar hidden lg:block">
                    <x-ads.slot name="blog_sidebar" class="mb-6" />
                    @if ($relatedPosts->isNotEmpty())
                        <div class="blog-related-sticky">
                            <x-blog.related-posts :posts="$relatedPosts" />
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
@endsection
