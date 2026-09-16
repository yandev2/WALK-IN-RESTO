@extends('layouts.directory')

@section('title', $title)
@section('description', $description ?? $pageTitle)
@section('canonical', $canonical ?? url()->current())

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => route('home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $breadcrumb,
                    'item' => $canonical ?? url()->current(),
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('body')
    <div class="relative flex min-h-screen flex-col bg-surface-base">
        {{-- Header Direktori Publik --}}
        <x-directory.header :home="$home" />

        {{-- Subtle Ambient Background Glow --}}
        <div class="pointer-events-none absolute inset-x-0 top-16 -z-0 h-96 overflow-hidden" aria-hidden="true">
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 h-[22rem] w-[50rem] rounded-full bg-primary/6 blur-3xl"></div>
        </div>

        {{-- Konten Utama Halaman Statis --}}
        <main class="relative z-10 flex-1 py-10 sm:py-14 lg:py-16">
            <div class="landing-container">
                <div class="max-w-4xl">
                    {{-- Breadcrumbs --}}
                    <nav class="mb-5 flex items-center gap-2 text-xs font-semibold text-muted" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                        <span class="text-muted/50">/</span>
                        <span class="text-primary">{{ $breadcrumb }}</span>
                    </nav>

                    {{-- Page Header --}}
                    <header class="mb-8 sm:mb-10">
                        <h1 class="font-display text-3xl font-bold tracking-tight text-body sm:text-4xl lg:text-5xl">
                            {{ $pageTitle }}
                        </h1>

                        @if ($updatedAt)
                            <div class="mt-3 flex items-center gap-2 text-xs text-muted">
                                <svg class="h-3.5 w-3.5 text-primary/70 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>Terakhir diperbarui: {{ $updatedAt->translatedFormat('d F Y') }}</span>
                            </div>
                        @endif

                        {{-- Garis Pemisah Halus --}}
                        <div class="mt-6 sm:mt-8 h-px w-full bg-gradient-to-r from-border-subtle via-border-subtle/60 to-transparent"></div>
                    </header>

                    {{-- Isi Konten Dokumen (Sejajar dengan Judul, Card Dihilangkan) --}}
                    <article class="landing-prose text-body/90 [&>p:first-of-type]:text-lg [&>p:first-of-type]:leading-relaxed [&>p:first-of-type]:text-body">
                        {!! $content !!}
                    </article>
                </div>
            </div>
        </main>

        {{-- Footer Direktori Publik --}}
        <x-directory.footer :home="$home" />
    </div>
@endsection
