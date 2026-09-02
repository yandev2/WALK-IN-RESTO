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
        
        {{-- Base Backdrop Color (Behind Everything) --}}
        <div class="pointer-events-none fixed inset-0 -z-20 bg-gradient-to-b from-slate-100 via-slate-50 to-slate-100 dark:from-[#060913] dark:via-[#090d1c] dark:to-[#060913] transition-colors duration-500" aria-hidden="true"></div>

        {{-- Organic 3D Floating Frosted Glass Spheres & Ambient Fluid Mesh Glows --}}
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
            
            {{-- Vibrant Fluid Mesh Glows (Light & Dark Mode) --}}
            <div class="absolute -top-40 -left-40 h-[650px] w-[650px] rounded-full bg-gradient-to-br from-primary/40 via-accent/30 to-purple-600/30 dark:from-primary/60 dark:via-purple-600/50 dark:to-accent/40 blur-[110px] opacity-80 dark:opacity-85"></div>
            <div class="absolute top-1/4 -right-40 h-[600px] w-[600px] rounded-full bg-gradient-to-bl from-accent/35 via-primary/25 to-sky-500/25 dark:from-accent/50 dark:via-primary/45 dark:to-sky-500/40 blur-[120px] opacity-75 dark:opacity-80"></div>
            <div class="absolute top-2/3 -left-32 h-[550px] w-[550px] rounded-full bg-gradient-to-tr from-primary/35 via-pink-500/25 to-accent/30 dark:from-primary/55 dark:via-pink-500/45 dark:to-indigo-500/45 blur-[130px] opacity-75 dark:opacity-80"></div>
            <div class="absolute -bottom-40 right-1/4 h-[650px] w-[650px] rounded-full bg-gradient-to-tl from-accent/40 via-primary/30 to-indigo-500/25 dark:from-accent/55 dark:via-primary/50 dark:to-purple-600/45 blur-[120px] opacity-80 dark:opacity-85"></div>

            {{-- 3D Organic Glass Spheres (High Contrast Specular Reflections in Dark Mode) --}}
            
            {{-- Sphere 1: Top Right Sphere --}}
            <div class="absolute top-16 right-6 sm:right-24 h-36 w-36 sm:h-56 sm:w-56 rounded-full bg-gradient-to-br from-white/75 via-white/15 to-primary/30 dark:from-white/40 dark:via-primary/35 dark:to-purple-600/50 backdrop-blur-2xl border border-white/70 dark:border-white/40 shadow-[0_20px_50px_rgba(0,0,0,0.1),inset_0_4px_14px_rgba(255,255,255,0.9)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.8),inset_0_3px_14px_rgba(255,255,255,0.5),0_0_40px_rgba(255,255,255,0.15)]"></div>
            
            {{-- Sphere 2: Mid Left Sphere --}}
            <div class="absolute top-1/3 left-2 sm:left-12 h-28 w-28 sm:h-48 sm:w-48 rounded-full bg-gradient-to-tr from-white/70 via-accent/30 to-white/15 dark:from-white/35 dark:via-accent/40 dark:to-primary/45 backdrop-blur-2xl border border-white/60 dark:border-white/35 shadow-[0_15px_40px_rgba(0,0,0,0.08),inset_0_3px_10px_rgba(255,255,255,0.8)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.8),inset_0_3px_12px_rgba(255,255,255,0.45),0_0_35px_rgba(255,255,255,0.12)]"></div>

            {{-- Sphere 3: Center Right Floating Bubble --}}
            <div class="absolute top-1/2 right-3 sm:right-16 h-20 w-20 sm:h-32 sm:w-32 rounded-full bg-gradient-to-br from-white/80 to-primary/40 dark:from-white/45 dark:via-sky-500/40 dark:to-primary/50 backdrop-blur-xl border border-white/70 dark:border-white/40 shadow-lg dark:shadow-[0_15px_35px_rgba(0,0,0,0.8),inset_0_2px_10px_rgba(255,255,255,0.5),0_0_30px_rgba(255,255,255,0.15)]"></div>

            {{-- Sphere 4: Lower Left Sphere --}}
            <div class="absolute top-2/3 left-3 sm:left-20 h-32 w-32 sm:h-52 sm:w-52 rounded-full bg-gradient-to-tl from-white/70 via-primary/30 to-accent/25 dark:from-white/35 dark:via-pink-500/40 dark:to-primary/50 backdrop-blur-2xl border border-white/60 dark:border-white/35 shadow-[0_20px_45px_rgba(0,0,0,0.08),inset_0_3px_12px_rgba(255,255,255,0.8)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.8),inset_0_3px_12px_rgba(255,255,255,0.45),0_0_35px_rgba(255,255,255,0.12)]"></div>

            {{-- Sphere 5: Center Ambient Glass Sphere --}}
            <div class="absolute top-[40%] left-1/2 -translate-x-1/2 h-24 w-24 sm:h-40 sm:w-40 rounded-full bg-gradient-to-tr from-white/60 via-primary/30 to-accent/25 dark:from-white/35 dark:via-purple-600/45 dark:to-accent/40 backdrop-blur-xl border border-white/50 dark:border-white/35 shadow-md dark:shadow-[0_15px_35px_rgba(0,0,0,0.8),inset_0_2px_10px_rgba(255,255,255,0.4)]"></div>

            {{-- Sphere 6: Bottom Right Sphere --}}
            <div class="absolute bottom-20 right-4 sm:right-28 h-24 w-24 sm:h-44 sm:w-44 rounded-full bg-gradient-to-bl from-white/75 via-white/20 to-accent/35 dark:from-white/40 dark:via-accent/45 dark:to-primary/50 backdrop-blur-xl border border-white/60 dark:border-white/40 shadow-xl dark:shadow-[0_20px_45px_rgba(0,0,0,0.8),inset_0_3px_12px_rgba(255,255,255,0.5),0_0_35px_rgba(255,255,255,0.15)]"></div>

            {{-- Radiant Light Flares --}}
            <div class="absolute top-36 left-1/4 h-3.5 w-3.5 rounded-full bg-white shadow-[0_0_25px_12px_rgba(255,255,255,0.9)] dark:shadow-[0_0_30px_14px_rgba(255,255,255,0.7)]"></div>
            <div class="absolute top-3/4 right-1/3 h-3.5 w-3.5 rounded-full bg-primary shadow-[0_0_30px_14px_rgba(255,255,255,0.8)] dark:shadow-[0_0_35px_16px_rgba(255,255,255,0.6)]"></div>
        </div>

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
