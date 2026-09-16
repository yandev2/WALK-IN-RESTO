@extends('layouts.blog')

@php
    $locale = app()->getLocale();
    $catT = $category->translate($locale) ?? $category->translations->first();
    $catName = $catT?->name ?? 'Kategori';
    $catCanonical = route('blog.category', ['slug' => $catT?->slug ?? $category->slug]);
@endphp

@section('title', ($catT?->meta_title ?: $catName) . ' · Blog · ' . (config('app.name', 'Cita Rasa Kita')))
@section('description', $catT?->meta_description ?: __('blog.category_latest', ['category' => $catName]))
@section('canonical', $catCanonical)
@if ($catT?->robots)
    @section('robots', $catT->robots)
@endif

@section('hreflang')
    @foreach (['id', 'en'] as $langLoc)
        @php $altCat = $category->translate($langLoc); @endphp
        @if ($altCat && filled($altCat->slug))
            <link rel="alternate" hreflang="{{ $langLoc }}" href="{{ route('blog.category', ['slug' => $altCat->slug]) }}" />
        @endif
    @endforeach
    @php $idCatSlug = $category->translate('id')?->slug ?? $category->translations->first()?->slug; @endphp
    @if ($idCatSlug)
        <link rel="alternate" hreflang="x-default" href="{{ route('blog.category', ['slug' => $idCatSlug]) }}" />
    @endif
@endsection

@push('schema')
@php
    $schemaCategoryBreadcrumbs = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
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
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $catName,
                'item' => $catCanonical,
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($schemaCategoryBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    <div class="py-10 md:py-14">
        <div class="landing-container">
            {{-- Breadcrumbs --}}
            <nav aria-label="Breadcrumb" class="mb-6 flex flex-wrap items-center gap-2 text-xs font-semibold text-muted">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">
                    {{ __('portfolio.nav.home') ?? 'Beranda' }}
                </a>
                <span class="text-border-subtle" aria-hidden="true">/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-primary transition-colors">
                    Blog
                </a>
                <span class="text-border-subtle" aria-hidden="true">/</span>
                <span class="text-body" aria-current="page">{{ $catName }}</span>
            </nav>

            <div class="max-w-3xl">
                <h1 class="font-display text-3xl font-bold tracking-tight text-body sm:text-4xl">
                    {{ $catName }}
                </h1>

                @if ($catT?->meta_description)
                    <p class="mt-2 text-sm text-muted">{{ $catT->meta_description }}</p>
                @else
                    <p class="mt-2 text-sm text-muted">{{ __('blog.category_latest', ['category' => $catName]) }}</p>
                @endif
            </div>

            {{-- Category Filter Pills --}}
            <x-blog.category-filter :categories="$categories" :active-category-slug="$activeCategorySlug" />

            {{-- Posts Grid --}}
            @if ($posts->isEmpty())
                <div class="mt-12 text-center py-16 blog-card">
                    <p class="text-base text-muted">{{ __('blog.no_results') }}</p>
                    <a href="{{ route('blog.index') }}" class="mt-4 inline-block text-sm font-semibold text-primary hover:underline">
                        {{ __('blog.view_all') }}
                    </a>
                </div>
            @else
                <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $index => $post)
                        <x-blog.post-card :post="$post" />
                        @if ($index === 2)
                            <div class="md:col-span-2 lg:col-span-3">
                                <x-ads.slot name="blog_feed" class="my-2" />
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
