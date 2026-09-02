@php
    use App\Support\CmsMedia;
    use Illuminate\Support\Carbon;

    $formatTime = function ($time): string {
        if (blank($time)) {
            return '—';
        }

        return Carbon::parse($time)->format('H.i');
    };
@endphp

@extends('layouts.landing')

@section('title', ($profile?->headline ?: $restaurant->name).' · Walk-in')
@section('description', $layout->copyFor('hero')['subtitle'] ?? 'Datang, duduk di meja pilihan Anda, lalu scan QR untuk memesan.')

@section('body')
    <div class="relative min-h-screen text-zinc-900 dark:text-white overflow-x-hidden selection:bg-primary/30 selection:text-white transition-colors duration-500 font-sans">
        
        {{-- Base Backdrop Color & Floating Frosted Spheres Background --}}
        @include('landing.templates.glassmorphism.partials.background')


        {{-- Floating iOS Glass Capsule Header --}}
        @include('landing.templates.glassmorphism.sections.header')

        {{-- Main Sections Loop in Configured Dynamic Order --}}
        <main id="atas" class="relative z-10 space-y-16 sm:space-y-24 lg:space-y-32 pt-4">
            {{-- Hero Section --}}
            @if (in_array('hero', $visibleSections, true))
                @include('landing.templates.glassmorphism.sections.hero')
            @endif

            {{-- Core Sections in Dynamic CMS Order --}}
            @foreach ($visibleSections as $sectionId)
                @if ($sectionId !== 'hero')
                    @if (view()->exists('landing.templates.glassmorphism.sections.'.$sectionId))
                        @include('landing.templates.glassmorphism.sections.'.$sectionId)
                    @elseif (view()->exists('landing.sections.'.$sectionId))
                        @include('landing.sections.'.$sectionId)
                    @endif
                @endif
            @endforeach
        </main>

        {{-- Glassmorphism Footer --}}
        @include('landing.templates.glassmorphism.sections.footer')
    </div>
@endsection
