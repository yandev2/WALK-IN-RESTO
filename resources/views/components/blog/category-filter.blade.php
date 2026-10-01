@props(['categories', 'activeCategorySlug' => null])

@php $locale = app()->getLocale(); @endphp

@if ($categories && $categories->isNotEmpty())
    <nav class="blog-category-filter my-8" aria-label="{{ __('blog.filter_categories') }}">
        <a
            href="{{ route('blog.index', ['locale' => $locale]) }}"
            class="blog-pill {{ $activeCategorySlug === null ? 'blog-pill-active' : '' }}"
        >
            {{ __('blog.all_categories') }}
        </a>

        @foreach ($categories as $category)
            @php $categoryT = $category->translate($locale); @endphp
            @if ($categoryT)
                <a
                    href="{{ route('blog.category', ['locale' => $locale, 'slug' => $categoryT->slug]) }}"
                    class="blog-pill {{ $activeCategorySlug === $categoryT->slug ? 'blog-pill-active' : '' }}"
                >
                    {{ $categoryT->name }}
                </a>
            @endif
        @endforeach
    </nav>
@endif
