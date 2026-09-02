@php
    use App\Support\CmsMedia;
    $galleryCopy = $layout->copyFor('gallery');
    $galleryImages = $restaurant->cmsGalleryImages ?? collect();
    $previewItems = $galleryImages->map(fn ($img) => [
        'src' => CmsMedia::url($img->image_path),
        'caption' => $img->caption ?: 'Suasana '.$restaurant->name,
    ])->values()->all();
@endphp

@if ($galleryImages->isNotEmpty())
    <section id="galeri" class="scroll-mt-24 py-8 sm:py-12 relative" x-data="imagePreview(@js($previewItems))">
        
        {{-- Ambient Light Orb --}}
        <div class="pointer-events-none absolute -left-20 top-1/2 h-80 w-80 rounded-full bg-accent/20 blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $galleryCopy['label'] ?? 'Suasana Resto' }}</span>
                </span>
                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                    {{ $galleryCopy['title'] ?? 'Galeri sudut ruangan & suasana' }}
                </h2>
                <p class="mt-3 text-sm sm:text-base text-zinc-700 dark:text-zinc-300 leading-relaxed">
                    {{ $galleryCopy['subtitle'] ?? 'Tempat yang nyaman untuk bersantap bersama keluarga, teman, atau rekan kerja.' }}
                </p>
            </div>

            {{-- Glass Masonry Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($galleryImages as $index => $img)
                    @php
                        $photoUrl = CmsMedia::url($img->image_path);
                    @endphp
                    <button
                        type="button"
                        class="group relative overflow-hidden rounded-[2.4rem] bg-white/25 dark:bg-white/[0.06] p-3 backdrop-blur-2xl border border-white/50 dark:border-white/15 shadow-[0_12px_40px_rgba(0,0,0,0.06),inset_0_1px_1px_rgba(255,255,255,0.7)] dark:shadow-[0_12px_40px_rgba(0,0,0,0.4),inset_0_1px_1px_rgba(255,255,255,0.15)] transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl text-left"
                        @click="openPreview({{ $index }})"
                    >
                        <div class="relative aspect-4/3 w-full overflow-hidden rounded-[1.8rem]">
                            <img
                                src="{{ $photoUrl }}"
                                alt="{{ $img->caption ?: 'Galeri Resto' }}"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-108"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                                <span class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-white drop-shadow-md">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                    <span>{{ $img->caption ?: 'Lihat foto penuh' }}</span>
                                </span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

        </div>

        <x-customer.image-preview-modal />
    </section>
@endif
