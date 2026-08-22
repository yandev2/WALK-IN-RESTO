@php
    use App\Support\CmsMedia;

    $copy = $layout->copyFor('gallery');
    $images = $restaurant->cmsGalleryImages
        ->map(fn ($image) => ['model' => $image, 'src' => CmsMedia::url($image->image_path)])
        ->filter(fn ($row) => filled($row['src']))
        ->values();
@endphp

<section id="galeri" class="scroll-mt-20 bg-surface-base">
    <div class="landing-container landing-section">
        <div class="landing-reveal mx-auto max-w-2xl text-center">
            <x-customer.section-heading
                :label="$copy['label'] ?? null"
                :title="$copy['title'] ?? 'Galeri'"
                :highlight="$copy['highlight'] ?? null"
                class="text-center md:text-center"
            />
        </div>

        <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach ($images as $row)
                @php
                    $image = $row['model'];
                    $src = $row['src'];
                @endphp
                <figure class="landing-reveal group relative aspect-[4/3] overflow-hidden rounded-2xl bg-surface-muted">
                    <img
                        src="{{ $src }}"
                        alt="{{ $image->caption ?: 'Galeri '.$restaurant->name }}"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                        loading="lazy"
                        onerror="this.closest('figure')?.remove()"
                    >
                    @if ($image->caption)
                        <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/65 via-black/25 to-transparent px-3 pb-3 pt-8 text-left text-sm font-medium text-white">
                            {{ $image->caption }}
                        </figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </div>
</section>
