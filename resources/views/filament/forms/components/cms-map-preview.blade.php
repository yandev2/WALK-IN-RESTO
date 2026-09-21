@php
    $embedUrl = $get('map_embed_url');
    $cleanedUrl = filled($embedUrl) ? \App\Support\CmsMedia::mapsEmbedUrl($embedUrl, null, null) : null;
@endphp

<div class="rounded-xl border border-zinc-200 bg-zinc-50/70 p-3.5 dark:border-zinc-800 dark:bg-zinc-900/50">
    <div class="mb-2 flex items-center justify-between">
        <span class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">
            Pratinjau Peta Lokasi
        </span>
        @if (filled($cleanedUrl))
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Peta Kustom Aktif
            </span>
        @else
            <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-2 py-0.5 text-[11px] font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                Peta Otomatis Outlet
            </span>
        @endif
    </div>

    @if (filled($cleanedUrl))
        <div class="relative aspect-16/9 w-full overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <iframe
                src="{{ $cleanedUrl }}"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="absolute inset-0 h-full w-full"
            ></iframe>
        </div>
    @else
        <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-zinc-300 py-5 px-4 text-center dark:border-zinc-700">
            <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                <svg class="h-4 w-4 text-zinc-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <p class="text-xs font-medium text-zinc-700 dark:text-zinc-300">
                Kolom URL embed belum diisi
            </p>
            <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400 max-w-sm">
                Peta di halaman landing publik akan <strong>otomatis menggunakan titik GPS outlet utama</strong>. Anda juga dapat menekan tombol <strong>"Gunakan Lokasi Outlet"</strong> di samping kolom untuk mengisi otomatis.
            </p>
        </div>
    @endif
</div>
