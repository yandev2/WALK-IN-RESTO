@props(['post'])

@php
    $locale = app()->getLocale();
    $t = $post->translate($locale) ?? $post->translations->first();
@endphp

@if ($t)
    <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-muted">
        @if ($post->relationLoaded('author') && $post->author)
            <div class="flex items-center gap-2">
                <span class="h-8 w-8 rounded-full bg-primary/20 text-primary font-bold flex items-center justify-center text-xs">
                    {{ substr($post->author->name, 0, 1) }}
                </span>
                <span class="font-medium text-body">{{ $post->author->name }}</span>
            </div>
            <span class="text-border-subtle">•</span>
        @endif

        @if ($post->published_at)
            <time datetime="{{ $post->published_at->toIso8601String() }}">
                {{ $post->published_at->translatedFormat('d F Y') }}
            </time>
        @endif

        @if ($post->reading_time_minutes)
            <span class="text-border-subtle">•</span>
            <span>{{ $post->reading_time_minutes }} {{ __('blog.min_read') }}</span>
        @endif

        @if ($post->relationLoaded('category') && $post->category)
            @php $categoryT = $post->category->translate($locale); @endphp
            @if ($categoryT)
                <span class="text-border-subtle">•</span>
                <a href="{{ route('blog.category', ['slug' => $categoryT->slug]) }}" class="font-medium text-primary hover:underline">
                    {{ $categoryT->name }}
                </a>
            @endif
        @endif

        @if ($post->is_featured)
            <span class="rounded-full bg-primary/15 px-2.5 py-0.5 text-xs font-semibold text-primary">
                {{ __('blog.featured') }}
            </span>
        @endif

        @if ($post->views_count)
            <span class="text-border-subtle">•</span>
            <span>👁 {{ number_format($post->views_count) }} {{ __('blog.views') }}</span>
        @endif

        @if ($post->comments_count)
            <span class="text-border-subtle">•</span>
            <span>💬 {{ number_format($post->comments_count) }} {{ __('blog.comments') }}</span>
        @endif
    </div>

    @php
        $localeTags = $post->relationLoaded('tags')
            ? $post->tags->filter(fn ($tag) => (bool) $tag->translate($locale))
            : collect();
    @endphp

    @if ($localeTags->isNotEmpty())
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($localeTags as $tag)
                @php $tagT = $tag->translate($locale); @endphp
                <a
                    href="{{ route('blog.tag', ['slug' => $tagT->slug]) }}"
                    class="blog-tag-pill"
                >
                    #{{ $tagT->name }}
                </a>
            @endforeach
        </div>
    @endif
@endif
