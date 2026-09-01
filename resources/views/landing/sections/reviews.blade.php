@php
    $copy = $layout->copyFor('reviews');
    $reviewItems = $restaurant->reviews
        ->map(fn ($review) => [
            'id' => $review->id,
            'customer_name' => $review->displayName(),
            'rating' => (int) $review->rating,
            'comment' => $review->comment,
        ])
        ->values();
    $reviewLastPage = max(1, (int) ceil(($ratingSummary['count'] ?? $reviewItems->count()) / 4));
@endphp

@push('scripts')
<script>
    window.testimonialCarousel = window.testimonialCarousel || function (config) {
        return {
            endpoint: config.endpoint,
            items: config.items || [],
            lastPage: config.lastPage || 1,
            perPage: config.perPage || 4,
            loading: false,
            fetchedPages: new Set([1]),
            accents: ['var(--brand-primary)', '#F2994A', '#EB5757', '#9B51E0'],

            init() {
                if (this.lastPage > 1) {
                    this.fetchPage(2);
                }
            },

            accentFor(index) {
                return this.accents[index % this.accents.length];
            },

            initialsFor(name) {
                const parts = String(name || 'Tamu').trim().split(/\s+/).filter(Boolean);

                if (! parts.length) {
                    return 'T';
                }

                if (parts.length >= 2) {
                    return (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
                }

                return parts[0].charAt(0).toUpperCase();
            },

            canCycle() {
                return this.lastPage > 1 || this.items.length > 4;
            },

            scrollPage(direction) {
                const track = this.$refs.track;

                if (! track) {
                    return;
                }

                track.scrollBy({
                    left: direction * track.clientWidth,
                    behavior: 'smooth',
                });
            },

            onScroll() {
                const track = this.$refs.track;

                if (! track || this.loading) {
                    return;
                }

                if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 96) {
                    this.fetchNext();
                }
            },

            async fetchNext() {
                const nextPage = Math.max(...this.fetchedPages) + 1;

                if (nextPage > this.lastPage) {
                    return;
                }

                await this.fetchPage(nextPage);
            },

            async fetchPage(page) {
                if (this.loading || page < 2 || page > this.lastPage || this.fetchedPages.has(page)) {
                    return;
                }

                this.loading = true;
                this.fetchedPages.add(page);

                try {
                    const url = new URL(this.endpoint, window.location.origin);
                    url.searchParams.set('page', String(page));
                    url.searchParams.set('per_page', String(this.perPage));

                    const response = await fetch(url.toString(), {
                        headers: { Accept: 'application/json' },
                    });

                    if (! response.ok) {
                        this.fetchedPages.delete(page);
                        return;
                    }

                    const payload = await response.json();
                    const incoming = Array.isArray(payload.data) ? payload.data : [];
                    const seen = new Set(this.items.map((item) => item.id));

                    incoming.forEach((item) => {
                        if (! seen.has(item.id)) {
                            this.items.push(item);
                            seen.add(item.id);
                        }
                    });

                    if (payload.meta && payload.meta.last_page) {
                        this.lastPage = payload.meta.last_page;
                    }
                } catch (error) {
                    this.fetchedPages.delete(page);
                } finally {
                    this.loading = false;
                }
            },
        };
    };
</script>
@endpush

<section id="ulasan" class="scroll-mt-20 bg-surface-section">
    <div class="landing-container landing-section">
        <div class="landing-reveal flex flex-col gap-8 border-b border-border-subtle pb-8 md:flex-row md:items-end md:justify-between">
            <div class="max-w-xl">
                <x-customer.section-heading
                    :label="$copy['label'] ?? null"
                    :title="$copy['title'] ?? 'Ulasan'"
                    :highlight="$copy['highlight'] ?? null"
                    class="text-left"
                />
            </div>
            @if ($ratingSummary['average'])
                <div class="flex items-center gap-4 bg-surface-raised px-5 py-3 rounded-2xl ring-1 ring-border-subtle shadow-sm">
                    <p class="font-display text-4xl sm:text-5xl font-bold leading-none text-body">{{ number_format($ratingSummary['average'], 1) }}</p>
                    <div>
                        <div class="flex gap-0.5" aria-hidden="true">
                            @foreach (range(1, 5) as $star)
                                <svg @class([
                                    'h-4 w-4',
                                    'text-amber-500 fill-amber-500' => $star <= round($ratingSummary['average']),
                                    'text-muted/30' => $star > round($ratingSummary['average']),
                                ]) fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endforeach
                        </div>
                        <p class="mt-1 text-xs sm:text-sm text-muted">Berdasarkan {{ $ratingSummary['count'] }} ulasan</p>
                    </div>
                </div>
            @endif
        </div>

        <div
            class="relative mt-8"
            x-data="testimonialCarousel({
                endpoint: '{{ '/api/v1/restaurants/'.$restaurant->slug.'/reviews' }}',
                items: @js($reviewItems),
                lastPage: {{ $reviewLastPage }},
                perPage: 4,
            })"
        >
            <div
                x-ref="track"
                class="landing-testimonial-track"
                x-on:scroll.debounce.120ms="onScroll()"
            >
                <template x-for="(review, index) in items" :key="review.id">
                    <article
                        class="landing-testimonial landing-testimonial-slide"
                        :style="`--testimonial-accent: ${accentFor(index)}`"
                    >
                        <div class="landing-testimonial-panel">
                            <span class="landing-testimonial-quote" aria-hidden="true">“</span>

                            <div class="flex items-center gap-3">
                                <div class="landing-testimonial-avatar" aria-hidden="true" x-text="initialsFor(review.customer_name)"></div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-base font-bold leading-tight text-body" x-text="review.customer_name"></h3>
                                    <p class="text-xs text-muted">Pengunjung</p>
                                </div>
                            </div>

                            <div class="mt-3 flex gap-0.5" :aria-label="`Rating ${review.rating} dari 5`">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="review.id + '-' + star">
                                    <svg
                                        class="landing-testimonial-star h-3.5 w-3.5"
                                        :class="star <= review.rating && 'is-filled'"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        aria-hidden="true"
                                    >
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </template>
                            </div>

                            <p class="mt-3 line-clamp-4 min-h-[4.5rem] flex-1 text-xs sm:text-sm italic leading-relaxed text-muted" x-text="review.comment"></p>
                        </div>
                    </article>
                </template>
            </div>

            <div class="mt-6 flex items-center justify-center gap-3" x-show="canCycle()" x-cloak>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface-raised text-body shadow-sm ring-1 ring-[color:var(--border-subtle)] transition hover:bg-surface-muted hover:shadow-md"
                    x-on:click="scrollPage(-1)"
                    aria-label="Ulasan sebelumnya"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface-raised text-body shadow-sm ring-1 ring-[color:var(--border-subtle)] transition hover:bg-surface-muted hover:shadow-md"
                    x-on:click="scrollPage(1); fetchNext()"
                    aria-label="Ulasan berikutnya"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L10.94 10 7.23 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>
