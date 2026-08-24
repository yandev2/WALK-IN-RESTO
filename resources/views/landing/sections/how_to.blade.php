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
                    <div class="landing-img-hover relative overflow-hidden rounded-3xl bg-surface-muted shadow-[var(--card-shadow)]">
                        <img src="{{ $howToImage }}" alt="" class="aspect-[4/5] w-full object-cover sm:aspect-square" loading="lazy">
                    </div>
                </div>
            @endif

            <div class="landing-reveal">
                <x-customer.section-heading
                    :label="$copy['label'] ?? null"
                    :title="$copy['title'] ?? 'Cara pesan'"
                    :highlight="$copy['highlight'] ?? null"
                />
                @if (filled($copy['subtitle'] ?? null))
                    <p class="mt-4 max-w-lg text-sm leading-relaxed text-muted sm:text-base">{{ $copy['subtitle'] }}</p>
                @endif

                <ol class="landing-timeline">
                    @foreach ($steps as $index => $step)
                        <li class="landing-timeline-item">
                            <span class="landing-timeline-marker" aria-hidden="true">{{ $index + 1 }}</span>
                            <div class="landing-timeline-body">
                                <h3 class="font-display text-lg font-bold text-body">{{ $step['title'] ?? '' }}</h3>
                                <p class="mt-1 text-sm leading-relaxed text-muted">{{ $step['description'] ?? '' }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>
