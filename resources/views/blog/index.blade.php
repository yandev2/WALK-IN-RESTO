@extends('layouts.blog')

@section('title', __('blog.title') . ' · ' . (config('app.name', 'Cita Rasa Kita')))
@section('description', __('blog.subtitle'))

@section('content')
    {{-- Hero Banner --}}
    <x-blog.hero-banner
        :categories="$categories"
        :featured-post="$featuredPost"
        :blog-hero="$blogHero ?? null"
    />

    <div class="py-12 md:py-16">
        <div class="landing-container">
            {{-- Category Filter Pills --}}
            <x-blog.category-filter
                :categories="$categories"
                :active-category-slug="$activeCategorySlug"
            />

            {{-- Popular Articles --}}
            <x-blog.popular-articles :posts="$popularPosts" />

            {{-- In-Feed Ad Slot --}}
            <x-ads.slot name="blog_feed" class="my-10" />

            {{-- Category Sections --}}
            <div class="mt-14 space-y-16">
                @foreach ($postsByCategory as $group)
                    <x-blog.category-section
                        :category="$group['category']"
                        :posts="$group['posts']"
                    />
                @endforeach
            </div>

            {{-- Show All CTA --}}
            <div class="mt-16 flex justify-center border-t border-border-subtle pt-10">
                <a
                    href="{{ route('blog.archive') }}"
                    class="rounded-full bg-primary px-8 py-3.5 text-sm font-bold text-white shadow-md hover:bg-primary-dark hover:shadow-lg transition transform hover:-translate-y-0.5"
                >
                    {{ __('blog.show_all') }}
                </a>
            </div>
        </div>
    </div>
@endsection
