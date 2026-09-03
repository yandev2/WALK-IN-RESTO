@php
    use App\Support\CmsMedia;
    $copy = $layout->copyFor('gallery');
    $images = $restaurant->cmsGalleryImages
        ->map(fn ($image) => ['model' => $image, 'src' => CmsMedia::url($image->image_path)])
        ->filter(fn ($row) => filled($row['src']))
        ->values();
    $lightboxImages = $images
        ->map(fn ($row) => [
            'src' => $row['src'],
            'caption' => $row['model']->caption,
        ])
        ->values();
@endphp

@if ($images->isNotEmpty())
    <section
        id="galeri"
        class="scroll-mt-20 py-16 sm:py-24 bg-surface-base border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200"
        x-data="imagePreview(@js($lightboxImages))"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                @if (filled($copy['label'] ?? null))
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-linear-to-r from-primary/15 to-accent/15 px-3.5 py-1.5 rounded-full border border-primary/25 shadow-2xs">
                        {{ $copy['label'] }}
                    </span>
                @endif
                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-body tracking-tight">
                    {{ $copy['title'] ?? 'Galeri Foto' }}
                </h2>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($images as $row)
                    @php
                        $image = $row['model'];
                        $src = $row['src'];
                    @endphp
                    <figure class="group relative aspect-4/3 overflow-hidden rounded-3xl bg-surface-muted border border-border-subtle dark:border-zinc-800 shadow-xs hover:shadow-2xl hover:border-primary/50 hover:-translate-y-1.5 transition-all duration-300">
                        <button
                            type="button"
                            class="absolute inset-0 cursor-zoom-in"
                            aria-label="Perbesar {{ $image->caption ?: 'foto galeri' }}"
                            @click="openPreview({{ $loop->index }})"
                        >
                            <img
                                src="{{ $src }}"
                                alt="{{ $image->caption ?: 'Galeri '.$restaurant->name }}"
                                class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
                                loading="lazy"
                            >
                        </button>
                        @if ($image->caption)
                            <figcaption class="pointer-events-none absolute inset-x-0 bottom-0 bg-linear-to-t from-black/80 via-black/40 to-transparent px-4 pb-3.5 pt-8 text-left text-xs font-medium text-white transition-opacity duration-300">
                                {{ $image->caption }}
                            </figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif
