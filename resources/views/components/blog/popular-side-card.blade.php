@props(['post'])

@php
    $locale = app()->getLocale();
    $t = $post->translate($locale) ?? $post->translations->first();
    $image = $post->featured_image ?? $post->og_image;
    $url = $t ? route('blog.show', ['slug' => $t->slug]) : '#';
@endphp

@if ($t)
    <article {{ $attributes->merge(['class' => 'blog-card blog-popular-side-card group']) }}>
        @if ($image)
            <a href="{{ $url }}" class="blog-popular-side-thumb blog-card-media" tabindex="-1" aria-hidden="true">
                <img
                    src="{{ \App\Support\MediaUrl::public($image) }}"
                    alt="{{ $t->featured_image_alt ?? $t->title }}"
                    loading="lazy"
                    decoding="async"
                >
            </a>
        @endif

        <div class="blog-popular-side-body">
            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-muted">
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->toIso8601String() }}">
                        {{ $post->published_at->translatedFormat('d M Y') }}
                    </time>
                @endif

                @if ($post->reading_time_minutes)
                    <span>• {{ $post->reading_time_minutes }} {{ __('blog.min_read') }}</span>
                @endif
            </div>

            <h3 class="blog-card-title mt-1.5 text-base leading-snug line-clamp-2">
                <a href="{{ $url }}">{{ $t->title }}</a>
            </h3>

            @if ($post->relationLoaded('category') && $post->category)
                @php $categoryT = $post->category->translate($locale); @endphp
                @if ($categoryT)
                    <a
                        href="{{ route('blog.category', ['slug' => $categoryT->slug]) }}"
                        class="mt-1 inline-block text-xs font-medium text-primary hover:underline"
                    >
                        {{ $categoryT->name }}
                    </a>
                @endif
            @endif

            <div class="mt-2 flex items-center gap-2 text-xs text-muted">
                @if ($post->author)
                    <span class="truncate">{{ $post->author->name }}</span>
                    <span>•</span>
                @endif
                <span>👁 {{ number_format($post->views_count) }}</span>
                <span>•</span>
                <span>❤️ {{ number_format($post->likes_count) }}</span>
            </div>
        </div>
    </article>
@endif
