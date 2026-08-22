@php
    use App\Support\CmsMedia;
@endphp

@extends('layouts.landing')

@section('title', 'Restoran walk-in')
@section('description', 'Datang, duduk di meja pilihan Anda, lalu scan QR untuk memesan.')

@section('body')
    <main class="relative min-h-screen overflow-hidden bg-surface-base">
        <div class="relative mx-auto flex min-h-screen max-w-3xl flex-col px-5 py-8 sm:px-6 sm:py-12 md:justify-center md:py-16">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="customer-section-label">Walk-in</p>
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

            <ul class="mt-8 space-y-3 sm:mt-10 sm:space-y-4">
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
                            class="group flex items-center gap-3 rounded-2xl border border-border-subtle bg-surface-raised p-3.5 shadow-[var(--card-shadow)] transition hover:-translate-y-0.5 hover:shadow-[var(--card-shadow-hover)] sm:gap-4 sm:rounded-3xl sm:p-4"
                            style="--brand-primary: {{ $cardTheme['primary'] }}; --brand-accent: {{ $cardTheme['accent'] }};"
                        >
                            @if ($logoUrl)
                                <img
                                    src="{{ $logoUrl }}"
                                    alt=""
                                    class="h-12 w-12 shrink-0 rounded-full object-cover ring-2 ring-primary/20 sm:h-14 sm:w-14"
                                    loading="lazy"
                                >
                            @else
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary text-sm font-bold text-white shadow-[var(--card-shadow)] sm:h-14 sm:w-14 sm:text-base">
                                    {{ mb_substr($restaurant->name, 0, 1) }}
                                </span>
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-base font-bold text-body sm:text-lg md:text-xl">{{ $restaurant->name }}</p>
                                <p class="mt-0.5 line-clamp-2 text-sm text-muted">{{ $headline }}</p>
                                <p class="mt-1 truncate text-xs text-muted">{{ $address }}</p>
                            </div>

                            <span class="hidden shrink-0 text-sm font-semibold text-primary transition group-hover:translate-x-0.5 sm:inline">
                                Buka →
                            </span>
                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary transition group-hover:bg-primary group-hover:text-white sm:hidden" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L10.94 10 7.23 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="rounded-2xl border border-dashed border-border-subtle px-5 py-8 text-center text-sm text-muted sm:rounded-3xl">
                        Belum ada restoran aktif.
                    </li>
                @endforelse
            </ul>
        </div>
    </main>
@endsection
