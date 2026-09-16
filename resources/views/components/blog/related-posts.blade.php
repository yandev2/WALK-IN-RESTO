@props(['posts'])

@php $locale = app()->getLocale(); @endphp

@if ($posts && $posts->isNotEmpty())
    <aside class="blog-related-posts">
        <h2 class="text-sm font-bold uppercase tracking-wider text-muted mb-4 border-b border-border-subtle pb-2">
            {{ __('blog.related_title') }}
        </h2>

        <div class="space-y-4">
            @foreach ($posts as $relatedPost)
                @php
                    $t = $relatedPost->translate($locale) ?? $relatedPost->translations->first();
                    $image = $relatedPost->featured_image ?? $relatedPost->og_image;
                @endphp
                @if ($t)
                    <article class="blog-related-item group">
                        <a href="{{ route('blog.show', ['slug' => $t->slug]) }}" class="block p-3">
                            @if ($image)
                                <div class="blog-related-thumb">
                                    <img
                                        src="{{ \App\Support\MediaUrl::public($image) }}"
                                        alt="{{ $t->featured_image_alt ?? $t->title }}"
                                        class="blog-related-cover"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                </div>
                            @else
                                <div class="blog-related-thumb">
                                    <div class="blog-related-cover-fallback">
                                        <span>{{ substr($t->title, 0, 1) }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="blog-related-body mt-2">
                                @if ($relatedPost->published_at)
                                    <time class="text-xs text-muted" datetime="{{ $relatedPost->published_at->toIso8601String() }}">
                                        {{ $relatedPost->published_at->translatedFormat('d M Y') }}
                                    </time>
                                @endif

                                <h3 class="blog-related-title">
                                    {{ $t->title }}
                                </h3>

                                @if ($relatedPost->relationLoaded('category') && $relatedPost->category)
                                    @php $catT = $relatedPost->category->translate($locale); @endphp
                                    @if ($catT)
                                        <p class="mt-1 text-xs font-medium text-primary">
                                            {{ $catT->name }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </a>
                    </article>
                @endif
            @endforeach
        </div>
    </aside>
@endif
