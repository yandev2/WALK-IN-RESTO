<template x-teleport="body">
    <div
        x-show="previewOpen"
        x-cloak
        x-transition.opacity
        class="image-preview-modal fixed inset-0 z-[90] flex items-center justify-center bg-black/80 p-4"
        role="dialog"
        aria-modal="true"
        aria-label="Pratinjau gambar"
        @click.self="closePreview()"
        @keydown.escape.window="previewOpen && closePreview()"
        @keydown.arrow-right.window="previewOpen && previewNext()"
        @keydown.arrow-left.window="previewOpen && previewPrev()"
    >
        <button
            type="button"
            class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white transition hover:bg-white/25"
            aria-label="Tutup"
            @click="closePreview()"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <button
            type="button"
            class="absolute left-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-white transition hover:bg-white/25 sm:left-3"
            aria-label="Sebelumnya"
            x-show="images.length > 1"
            x-cloak
            @click="previewPrev()"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>

        <figure class="relative max-h-[90vh] max-w-5xl px-12">
            <img
                :src="current.src"
                :alt="current.caption || ''"
                class="mx-auto max-h-[80vh] w-auto max-w-full rounded-2xl object-contain"
            >
            <figcaption
                class="mt-3 text-center text-sm font-medium text-white/90"
                x-show="current.caption"
                x-text="current.caption"
            ></figcaption>
        </figure>

        <button
            type="button"
            class="absolute right-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-white transition hover:bg-white/25 sm:right-3"
            aria-label="Berikutnya"
            x-show="images.length > 1"
            x-cloak
            @click="previewNext()"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>
</template>
