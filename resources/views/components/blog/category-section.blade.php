@props(['category', 'posts'])

@php
    $locale = app()->getLocale();
    $categoryT = $category->translate($locale);
@endphp

@if ($categoryT && $posts->isNotEmpty())
    <section class="mt-16 scroll-mt-28" aria-labelledby="blog-category-{{ $category->id }}">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between border-b border-border-subtle pb-4">
            <div>
                <h2 id="blog-category-{{ $category->id }}" class="font-display text-2xl font-bold text-body md:text-3xl">
                    {{ $categoryT->name }}
                </h2>
                <p class="mt-1 text-sm text-muted">
                    {{ __('blog.category_latest', ['category' => $categoryT->name]) }}
                </p>
            </div>

            <a
                href="{{ route('blog.category', ['slug' => $categoryT->slug]) }}"
                class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline self-start sm:self-end"
            >
                <span>{{ __('blog.view_category') }}</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <x-blog.post-card :post="$post" />
            @endforeach
        </div>
    </section>
@endif
