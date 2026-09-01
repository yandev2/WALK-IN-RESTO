@extends('layouts.directory')

@section('title', $title)
@section('description', $pageTitle)

@section('body')
    <div class="flex min-h-screen flex-col bg-surface-base">
        {{-- Header Direktori Publik --}}
        <x-directory.header :home="$home" />

        {{-- Hero Banner Halaman Statis --}}
        <div class="relative overflow-hidden border-b border-border-subtle/80 bg-surface-section py-10 sm:py-14">
            <div class="landing-container relative z-10">
                {{-- Breadcrumbs --}}
                <nav class="flex items-center gap-2 text-xs font-semibold text-muted">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                    <span>/</span>
                    <span class="text-primary">{{ $breadcrumb }}</span>
                </nav>

                <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-body sm:text-4xl lg:text-5xl">
                    {{ $pageTitle }}
                </h1>

                @if ($updatedAt)
                    <p class="mt-2 text-xs text-muted">
                        Terakhir diperbarui: {{ $updatedAt->translatedFormat('d F Y') }}
                    </p>
                @endif
            </div>

            {{-- Subtle Background Mesh Glow --}}
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-primary/5 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-primary/5 blur-3xl" aria-hidden="true"></div>
        </div>

        {{-- Isi Konten Dokumen --}}
        <main class="flex-1 py-10 sm:py-14">
            <div class="landing-container max-w-4xl">
                <article class="rounded-3xl border border-border-subtle/80 bg-surface-raised p-6 sm:p-10 md:p-12 shadow-xs transition-colors">
                    <div class="landing-prose">
                        {!! $content !!}
                    </div>
                </article>
            </div>
        </main>

        {{-- Footer Direktori Publik --}}
        <x-directory.footer :home="$home" />
    </div>
@endsection
