@php
    use App\Support\CmsMedia;
@endphp

@extends('layouts.landing')

@section('title', 'Restoran walk-in')
@section('description', 'Datang, duduk di meja pilihan Anda, lalu scan QR untuk memesan.')

@section('body')
    <main class="relative min-h-screen overflow-hidden bg-surface-base">
        <div class="relative mx-auto flex min-h-screen max-w-3xl flex-col px-5 py-8 sm:px-6 sm:py-12 md:justify-center md:py-16">
            <div class="flex items-start justify-between gap-4 border-b border-border-subtle/80 pb-6">
                <div class="min-w-0">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">
                        <span class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span>
                        Walk-in Dining
                    </span>
                    <h1 class="mt-3 font-display text-3xl font-bold leading-tight tracking-tight text-body sm:text-4xl md:text-5xl">
                        Datang, duduk, <span class="text-primary">pesan dari meja</span>.
                    </h1>
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-muted sm:mt-4 sm:text-base md:text-lg">
                        Tidak perlu reservasi. Pilih restoran di bawah, lalu datang langsung.
                    </p>
                </div>
                <div class="shrink-0 pt-1">
                    <x-customer.theme-toggle />
                </div>
            </div>

            <ul class="mt-8 space-y-3.5 sm:mt-10 sm:space-y-4">
                @forelse ($restaurants as $restaurant)
                    @php
                        $cardTheme = \App\Support\RestaurantTheme::for($restaurant);
                        $headline = $restaurant->cmsProfile?->headline ?: $restaurant->name;
                        $logoUrl = CmsMedia::url($restaurant->logo_path);
                        $address = $restaurant->defaultOutlet?->address ?: 'Lihat jam buka dan lokasi';
                    @endphp
                    <li>
                        <a
                            href="{{ route('landing.show', $restaurant) }}"
                            class="group flex items-center gap-3.5 rounded-2xl border border-border-subtle bg-surface-raised p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md hover:border-primary/40 sm:gap-4 sm:rounded-3xl sm:p-5"
                            style="--brand-primary: {{ $cardTheme['primary'] }}; --brand-accent: {{ $cardTheme['accent'] }};"
                        >
                            @if ($logoUrl)
                                <img
                                    src="{{ $logoUrl }}"
                                    alt=""
                                    class="h-12 w-12 shrink-0 rounded-2xl object-cover ring-2 ring-primary/20 transition duration-300 group-hover:scale-105 sm:h-14 sm:w-14"
                                    loading="lazy"
                                >
                            @else
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-primary-dark text-base font-bold text-white shadow-sm transition duration-300 group-hover:scale-105 sm:h-14 sm:w-14 sm:text-lg">
                                    {{ mb_substr($restaurant->name, 0, 1) }}
                                </span>
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="truncate font-display text-base font-bold text-body group-hover:text-primary transition-colors sm:text-lg md:text-xl">{{ $restaurant->name }}</p>
                                <p class="mt-0.5 line-clamp-1 text-xs sm:text-sm text-muted">{{ $headline }}</p>
                                <p class="mt-1 truncate text-xs text-muted/80 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $address }}
                                </p>
                            </div>

                            <span class="hidden shrink-0 items-center gap-1 text-sm font-bold text-primary transition group-hover:translate-x-1 sm:inline-flex">
                                Buka
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </span>
                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary transition group-hover:bg-primary group-hover:text-white sm:hidden" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L10.94 10 7.23 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="rounded-2xl border border-dashed border-border-subtle px-5 py-12 text-center text-sm text-muted sm:rounded-3xl">
                        Belum ada restoran aktif.
                    </li>
                @endforelse
            </ul>
        </div>
    </main>
@endsection
