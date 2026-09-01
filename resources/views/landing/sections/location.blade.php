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
                    <p class="mt-5 text-base leading-relaxed text-body sm:text-lg flex items-start gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1 shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $outlet->address }}</span>
                    </p>
                @endif
                @if ($outlet?->name)
                    <p class="mt-2 text-sm text-muted pl-7.5">{{ $outlet->name }}</p>
                @endif
                @if ($mapsUrl)
                    <x-customer.btn-primary :href="$mapsUrl" class="mt-8 shadow-md" target="_blank" rel="noopener noreferrer">
                        {{ $copy['button'] ?? 'Buka di Google Maps' }}
                    </x-customer.btn-primary>
                @endif
            </div>
            <div class="landing-reveal overflow-hidden rounded-[1.75rem] bg-surface-muted shadow-md ring-1 ring-[color:var(--border-subtle)]">
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
