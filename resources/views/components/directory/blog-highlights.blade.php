@props([
    'articles' => [],
])

@if (! empty($articles))
    <section aria-label="Artikel & Tips Kuliner" class="directory-blog-highlights mb-12">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-primary">Artikel & Tips Kuliner</span>
                <h2 class="text-lg font-bold text-body font-display sm:text-xl md:text-2xl">Jelajahi Cerita di Balik Rasa</h2>
            </div>
            <a
                href="{{ route('blog.index', ['locale' => app()->getLocale() ?: 'id']) }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline"
            >
                <span>Lihat Semua Artikel</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($articles as $article)
                <article class="blog-card group relative flex flex-col justify-between overflow-hidden !rounded-[1.35rem] !p-3.5 border border-border-subtle hover:border-primary/50 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 ease-out">
                    <a href="{{ $article['url'] }}" class="block">
                        <div class="blog-card-media relative aspect-[16/10] overflow-hidden rounded-xl bg-surface-muted">
                            @if ($article['image_url'])
                                <img
                                    src="{{ $article['image_url'] }}"
                                    alt="{{ $article['title'] }}"
                                    class="blog-card-img h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-surface-muted to-surface-section text-muted/30 transition-all duration-500 group-hover:scale-105 group-hover:text-primary/50 group-hover:from-surface-muted group-hover:to-primary/5">
                                    <svg class="h-8 w-8 transition-transform duration-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                            @endif

                            @if ($article['category_name'])
                                <span class="absolute top-2.5 left-2.5 rounded-lg bg-black/60 backdrop-blur-md px-2.5 py-0.5 text-[10px] font-bold text-white shadow-xs transition-colors duration-300 group-hover:bg-primary group-hover:text-white">
                                    {{ $article['category_name'] }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-3">
                            <h3 class="blog-card-title line-clamp-2 font-display text-sm font-bold text-body group-hover:text-primary transition-colors duration-200 leading-snug">
                                {{ $article['title'] }}
                            </h3>

                            @if ($article['excerpt'])
                                <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-muted">
                                    {{ $article['excerpt'] }}
                                </p>
                            @endif
                        </div>
                    </a>

                    <div class="mt-3 flex items-center justify-between border-t border-border-subtle/50 pt-2.5 text-[11px] text-muted transition-colors duration-300 group-hover:border-primary/20">
                        <span>{{ $article['published_at'] }}</span>
                        <span class="inline-flex items-center gap-1 font-medium transition-colors duration-300 group-hover:text-primary">
                            <span>{{ $article['reading_time'] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transform transition-transform duration-300 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif
