@php
    use App\Support\LandingLayout;

    $copy = $layout->copyFor('cta');
@endphp

<section id="cta" class="landing-cta-mesh relative overflow-hidden bg-surface-section border-t border-border-subtle/80">
    <div class="landing-container landing-section relative z-10 text-center">
        <div class="landing-reveal mx-auto max-w-2xl">
            <h2 class="font-display text-3xl font-bold tracking-tight text-body sm:text-4xl md:text-5xl">{{ LandingLayout::interpolate($copy['title'] ?? '', $restaurant->name) }}</h2>
            @if (filled($copy['subtitle'] ?? null))
                <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted sm:text-base md:text-lg">{{ $copy['subtitle'] }}</p>
            @endif
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3.5">
                @if ($mapsUrl)
                    <x-customer.btn-primary :href="$mapsUrl" class="shadow-md" target="_blank" rel="noopener noreferrer">{{ $copy['button'] ?? 'Lihat lokasi' }}</x-customer.btn-primary>
                @endif
                @if ($whatsappUrl)
                    <x-customer.btn-outline :href="$whatsappUrl" target="_blank" rel="noopener noreferrer">{{ $copy['wa_button'] ?? 'WhatsApp kami' }}</x-customer.btn-outline>
                @endif
            </div>
        </div>
    </div>
</section>
