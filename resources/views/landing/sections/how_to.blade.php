@php
    $copy = $layout->copyFor('how_to');
    $steps = $copy['steps'] ?? [];
    $howToImage = $howToImageUrl ?? null;
@endphp

<section id="cara-pesan" class="scroll-mt-20 bg-surface-base">
    <div class="landing-container landing-section">
        <div @class([
            'grid items-center gap-12 lg:gap-16',
            'lg:grid-cols-2' => $howToImage,
        ])>
            @if ($howToImage)
                <div class="landing-reveal relative mx-auto w-full max-w-md lg:mx-0">
                    <div class="landing-img-hover relative overflow-hidden rounded-3xl bg-surface-muted ring-1 ring-[color:var(--border-subtle)] shadow-md">
                        <img src="{{ $howToImage }}" alt="" class="aspect-[4/5] w-full object-cover transition duration-500 hover:scale-105 sm:aspect-square" loading="lazy">
                    </div>
                </div>
            @endif

            <div @class([
                'landing-reveal',
                'mx-auto max-w-4xl text-center' => ! $howToImage,
            ])>
                <x-customer.section-heading
                    :label="$copy['label'] ?? null"
                    :title="$copy['title'] ?? 'Cara pesan'"
                    :highlight="$copy['highlight'] ?? null"
                    class="{{ ! $howToImage ? 'text-center md:text-center' : '' }}"
                />
                @if (filled($copy['subtitle'] ?? null))
                    <p class="mt-3.5 max-w-lg text-sm leading-relaxed text-muted sm:text-base {{ ! $howToImage ? 'mx-auto' : '' }}">{{ $copy['subtitle'] }}</p>
                @endif

                <ol @class([
                    'landing-timeline' => $howToImage,
                    'mt-8 grid gap-4 sm:grid-cols-3 text-left' => ! $howToImage,
                ])>
                    @foreach ($steps as $index => $step)
                        @if ($howToImage)
                            <li class="landing-timeline-item group">
                                <span class="landing-timeline-marker bg-gradient-to-br from-primary to-primary-dark shadow-sm ring-4 ring-primary/10" aria-hidden="true">{{ $index + 1 }}</span>
                                <div class="landing-timeline-body">
                                    <h3 class="font-display text-lg font-bold text-body group-hover:text-primary transition-colors">{{ $step['title'] ?? '' }}</h3>
                                    <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $step['description'] ?? '' }}</p>
                                </div>
                            </li>
                        @else
                            <li class="group relative rounded-2xl border border-border-subtle bg-surface-raised p-5 shadow-xs transition duration-200 hover:shadow-md hover:border-primary/40 flex flex-col justify-between">
                                <div>
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary-dark font-display text-sm font-bold text-white shadow-xs">
                                        {{ $index + 1 }}
                                    </span>
                                    <h3 class="mt-3.5 font-display text-base font-bold text-body group-hover:text-primary transition-colors">{{ $step['title'] ?? '' }}</h3>
                                    <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-muted">{{ $step['description'] ?? '' }}</p>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>
