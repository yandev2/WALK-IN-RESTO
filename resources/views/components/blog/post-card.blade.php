@props(['post'])

@php
    $locale = app()->getLocale();
    $t = $post->translate($locale) ?? $post->translations->first();
    $image = $post->featured_image ?? $post->og_image;
    $url = $t ? route('blog.show', ['slug' => $t->slug]) : '#';
@endphp

@if ($t)
    <article {{ $attributes->merge(['class' => 'blog-card group flex flex-col justify-between']) }}>
        <div>
            @if ($image)
                <a href="{{ $url }}" class="blog-card-media mb-4 block overflow-hidden rounded-xl">
                    <img
                        src="{{ \App\Support\MediaUrl::public($image) }}"
                        alt="{{ $t->featured_image_alt ?? $t->title }}"
                        class="blog-card-img aspect-[16/10] w-full object-cover"
                        loading="lazy"
                        decoding="async"
                    >
                </a>
            @endif

            <div class="flex flex-wrap items-center gap-2 text-xs text-muted">
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->toIso8601String() }}">
                        {{ $post->published_at->translatedFormat('d M Y') }}
                    </time>
                @endif

                @if ($post->is_featured)
                    <span class="rounded-full bg-primary/15 px-2.5 py-0.5 text-xs font-semibold text-primary">
                        {{ __('blog.featured') }}
                    </span>
                @endif

                @if ($post->reading_time_minutes)
                    <span>• {{ $post->reading_time_minutes }} {{ __('blog.min_read') }}</span>
                @endif
            </div>

            <h3 class="blog-card-title mt-2.5 text-lg leading-snug">
                <a href="{{ $url }}">{{ $t->title }}</a>
            </h3>

            @if ($post->relationLoaded('category') && $post->category)
                @php $categoryT = $post->category->translate($locale); @endphp
                @if ($categoryT)
                    <a
                        href="{{ route('blog.category', ['slug' => $categoryT->slug]) }}"
                        class="mt-2 inline-block text-xs font-medium text-primary hover:underline"
                    >
                        {{ $categoryT->name }}
                    </a>
                @endif
            @endif

            @if ($t->excerpt)
                <p class="mt-2 text-sm text-muted line-clamp-3 leading-relaxed">{{ $t->excerpt }}</p>
            @endif
        </div>

        <div class="mt-4 pt-3 border-t border-border-subtle/70">
            <div class="flex items-center justify-between text-xs text-muted">
                @if ($post->relationLoaded('author') && $post->author)
                    <div class="flex items-center gap-2">
                        <span class="h-6 w-6 rounded-full bg-primary/20 text-primary font-bold flex items-center justify-center text-[10px]">
                            {{ substr($post->author->name, 0, 1) }}
                        </span>
                        <span class="font-medium text-body/90">{{ $post->author->name }}</span>
                    </div>
                @else
                    <div></div>
                @endif

                <div class="flex items-center gap-2.5">
                    @if ($post->views_count)
                        <span title="{{ __('blog.views') }}">👁 {{ number_format($post->views_count) }}</span>
                    @endif
                    @if ($post->likes_count)
                        <span title="{{ __('blog.likes') }}">❤️ {{ number_format($post->likes_count) }}</span>
                    @endif
                    @if ($post->comments_count)
                        <span title="{{ __('blog.comments') }}">💬 {{ number_format($post->comments_count) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </article>
@endif
