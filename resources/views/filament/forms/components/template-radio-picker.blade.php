<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            state: $wire.entangle('{{ $getStatePath() }}'),
            previewModalOpen: false,
            previewImage: '',
            previewTitle: '',
            openPreview(img, title) {
                this.previewImage = img;
                this.previewTitle = title;
                this.previewModalOpen = true;
            }
        }"
        class="w-full"
    >
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($getTemplates() as $template)
                @php
                    $thumbUrl = $template->thumbnail_url;
                @endphp
                <div
                    @click="state = '{{ $template->slug }}'"
                    :class="state === '{{ $template->slug }}' ? 'ring-2 ring-primary-500 border-primary-500 bg-primary-50/20 dark:bg-primary-950/20 shadow-md' : 'border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-zinc-300 dark:hover:border-zinc-700'"
                    class="group relative flex flex-col rounded-xl border p-3.5 transition-all duration-200 cursor-pointer select-none"
                >
                    {{-- Preview Thumbnail Container --}}
                    <div class="relative aspect-16/10 w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200/60 dark:border-zinc-700/60">
                        <img
                            src="{{ $thumbUrl }}"
                            alt="{{ $template->name }}"
                            class="h-full w-full object-cover object-top transition-transform duration-300 group-hover:scale-105"
                            loading="lazy"
                        >

                        {{-- Floating Zoom / Preview Overlay --}}
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-2">
                            <button
                                type="button"
                                @click.stop="openPreview('{{ $thumbUrl }}', '{{ addslashes($template->name) }}')"
                                class="inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold text-zinc-900 shadow-lg backdrop-blur-xs hover:bg-white hover:scale-105 transition-transform"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                Zoom Preview
                            </button>
                        </div>

                        {{-- Badge (Populer / Default / Baru) --}}
                        @if (filled($template->badge))
                            <div class="absolute top-2 left-2 pointer-events-none">
                                <span class="inline-flex items-center rounded-md bg-amber-500/90 backdrop-blur-xs px-2 py-0.5 text-[11px] font-bold text-white shadow-xs">
                                    {{ $template->badge }}
                                </span>
                            </div>
                        @endif

                        {{-- Active Checkmark Indicator --}}
                        <div
                            x-show="state === '{{ $template->slug }}'"
                            x-cloak
                            class="absolute top-2 right-2 flex h-6 w-6 items-center justify-center rounded-full bg-primary-600 text-white shadow-md ring-2 ring-white dark:ring-zinc-900"
                        >
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Title & Radio --}}
                    <div class="mt-3 flex items-center justify-between gap-2">
                        <span class="font-semibold text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $template->name }}
                        </span>
                        <input
                            type="radio"
                            name="{{ $getStatePath() }}"
                            value="{{ $template->slug }}"
                            :checked="state === '{{ $template->slug }}'"
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-zinc-300 dark:border-zinc-700 pointer-events-none"
                        >
                    </div>

                    {{-- Description --}}
                    @if (filled($template->description))
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2">
                            {{ $template->description }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Preview Lightbox Modal (Alpine.js) --}}
        <div
            x-show="previewModalOpen"
            x-cloak
            @keydown.escape.window="previewModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-zinc-950/80 backdrop-blur-xs"
        >
            <div
                @click.away="previewModalOpen = false"
                class="relative max-h-[90vh] max-w-4xl w-full flex flex-col rounded-2xl bg-white dark:bg-zinc-900 shadow-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800"
            >
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 px-5 py-3.5">
                    <h3 class="font-semibold text-base text-zinc-900 dark:text-zinc-100" x-text="previewTitle"></h3>
                    <button
                        type="button"
                        @click="previewModalOpen = false"
                        class="rounded-lg p-1.5 text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-zinc-200 transition"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-4 bg-zinc-100 dark:bg-zinc-950/50 flex justify-center items-center">
                    <img :src="previewImage" :alt="previewTitle" class="max-w-full rounded-lg shadow-md object-contain max-h-[75vh]">
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
