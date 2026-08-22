@php
    $copy = $layout->copyFor('location');
@endphp

<section id="lokasi" class="scroll-mt-20 bg-surface-section">
    <div class="landing-container landing-section">
        <div class="grid items-center gap-8 md:grid-cols-2 md:gap-12">
            <div class="landing-reveal">
                <x-customer.section-heading
                    :label="$copy['label'] ?? null"
                    :title="$copy['title'] ?? 'Lokasi'"
                    :highlight="$copy['highlight'] ?? null"
                />
                @if ($outlet?->address)
                    <p class="mt-5 text-base leading-relaxed text-muted sm:text-lg">{{ $outlet->address }}</p>
                @endif
                @if ($outlet?->name)
                    <p class="mt-2 text-sm text-muted/70">{{ $outlet->name }}</p>
                @endif
                @if ($mapsUrl)
                    <x-customer.btn-primary :href="$mapsUrl" class="mt-8" target="_blank" rel="noopener noreferrer">
                        {{ $copy['button'] ?? 'Buka di Google Maps' }}
                    </x-customer.btn-primary>
                @endif
            </div>
            <div class="landing-reveal overflow-hidden rounded-[1.75rem] bg-surface-muted shadow-[var(--card-shadow)] ring-1 ring-[color:var(--border-subtle)]">
                @if ($mapEmbedUrl)
                    <iframe
                        title="Peta {{ $restaurant->name }}"
                        src="{{ $mapEmbedUrl }}"
                        class="h-72 w-full border-0 sm:h-80 md:min-h-[22rem]"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                @else
                    <div class="flex h-72 items-center justify-center px-6 text-center text-sm text-muted sm:h-80">
                        Peta belum diatur. Isi koordinat outlet atau URL embed di CMS.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
