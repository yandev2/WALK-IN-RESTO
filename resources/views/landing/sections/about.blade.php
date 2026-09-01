@php
    $copy = $layout->copyFor('about');
    $aboutImage = $aboutImageUrl ?? null;
@endphp

<section id="tentang" class="scroll-mt-20 bg-surface-section">
    <div class="landing-container landing-section">
        <div @class([
            'grid items-center gap-10 lg:gap-16',
            'lg:grid-cols-2' => $aboutImage,
        ])>
            @if ($aboutImage)
                <div class="landing-reveal order-2 lg:order-1">
                    <div class="landing-img-hover overflow-hidden rounded-3xl shadow-md ring-1 ring-[color:var(--border-subtle)]">
                        <img src="{{ $aboutImage }}" alt="" class="aspect-[4/3] w-full object-cover transition duration-500 hover:scale-105" loading="lazy">
                    </div>
                </div>
            @endif

            <div class="landing-reveal {{ $aboutImage ? 'order-1 lg:order-2' : '' }}">
                <x-customer.section-heading
                    :label="$copy['label'] ?? null"
                    :title="$copy['title'] ?? 'Tentang'"
                    :highlight="$copy['highlight'] ?? null"
                />
                <div class="landing-prose mt-6 text-base leading-relaxed text-muted sm:text-lg">
                    {!! $profile->about_html !!}
                </div>
            </div>
        </div>
    </div>
</section>
