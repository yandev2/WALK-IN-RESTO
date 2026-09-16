@extends('layouts.blog')

@section('title', ($searchQuery !== '' ? __('blog.search_results', ['count' => $posts->total(), 'query' => $searchQuery]) : __('blog.all_articles_title')) . ' · ' . (config('app.name', 'Cita Rasa Kita')))
@section('description', __('blog.subtitle'))

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
                <span class="text-body" aria-current="page">{{ __('blog.all_articles_title') }}</span>
            </nav>

            <div class="max-w-3xl">
                <h1 class="font-display text-3xl font-bold tracking-tight text-body sm:text-4xl">
                    @if ($searchQuery !== '')
                        {{ __('blog.search_results', ['count' => $posts->total(), 'query' => $searchQuery]) }}
                    @else
                        {{ __('blog.all_articles_title') }}
                    @endif
                </h1>

                <p class="mt-2 text-sm text-muted">
                    {{ __('blog.subtitle') }}
                </p>
            </div>

            {{-- Search Bar --}}
            <form
                action="{{ route('blog.archive') }}"
                method="GET"
                class="mt-6 flex w-full max-w-xl items-center gap-2"
                role="search"
            >
                <input
                    type="search"
                    name="q"
                    value="{{ $searchQuery }}"
                    placeholder="{{ __('blog.search_placeholder') }}"
                    class="w-full rounded-full border border-border-subtle bg-surface-raised px-4 py-2.5 text-sm text-body outline-none focus:border-primary"
                >
                <button type="submit" class="rounded-full bg-primary px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-primary-dark transition shrink-0">
                    {{ __('blog.search_button') }}
                </button>
            </form>

            {{-- Category Filter Pills --}}
            <x-blog.category-filter :categories="$categories" :active-category-slug="$activeCategorySlug" />

            {{-- Posts Grid --}}
            @if ($posts->isEmpty())
                <div class="mt-12 text-center py-16 blog-card">
                    <p class="text-base text-muted">{{ __('blog.no_results') }}</p>
                    <a href="{{ route('blog.archive') }}" class="mt-4 inline-block text-sm font-semibold text-primary hover:underline">
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
