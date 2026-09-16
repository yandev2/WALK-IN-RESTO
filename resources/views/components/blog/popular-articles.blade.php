@props(['posts'])

@if ($posts && $posts->isNotEmpty())
    <section class="mt-14" aria-labelledby="blog-popular-heading">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between border-b border-border-subtle pb-4">
            <div>
                <h2 id="blog-popular-heading" class="font-display text-2xl font-bold text-body md:text-3xl">
                    {{ __('blog.popular_title') }}
                </h2>
                <p class="text-sm text-muted mt-1">
                    {{ __('blog.popular_subtitle') }}
                </p>
            </div>
            <a href="{{ route('blog.archive') }}" class="text-sm font-semibold text-primary hover:underline self-start sm:self-end">
                {{ __('blog.view_all') }} →
            </a>
        </div>

        <div class="blog-popular-grid mt-6">
            {{-- Big featured first card --}}
            @if ($posts->first())
                <div class="blog-popular-featured">
                    <x-blog.post-card :post="$posts->first()" class="h-full !p-6" />
                </div>
            @endif

            {{-- Side stacked cards --}}
            @if ($posts->count() > 1)
                <div class="blog-popular-side">
                    @foreach ($posts->slice(1) as $post)
                        <x-blog.popular-side-card :post="$post" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
