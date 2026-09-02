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
@section('description', $layout->copyFor('hero')['subtitle'] ?? 'Datang, duduk di meja pilihan Anda, lalu scan QR untuk memesan. Tanpa reservasi.')

@section('body')
    <div class="min-h-screen bg-surface-base text-body transition-colors duration-300">
        {{-- Header Navigation with Theme Toggle --}}
        @include('landing.templates.foodie.sections.header')

        {{-- Main Content Sections Driven by CMS Layout & Visibility --}}
        <main id="atas">
            {{-- Hero Section --}}
            @if (in_array('hero', $visibleSections, true))
                @include('landing.templates.foodie.sections.hero')
            @endif

            {{-- Core Sections in Configured Dynamic Order --}}
            @foreach ($visibleSections as $sectionId)
                @if ($sectionId !== 'hero')
                    @if (view()->exists('landing.templates.foodie.sections.'.$sectionId))
                        @include('landing.templates.foodie.sections.'.$sectionId)
                    @elseif (view()->exists('landing.sections.'.$sectionId))
                        @include('landing.sections.'.$sectionId)
                    @endif
                @endif
            @endforeach
        </main>

        {{-- Footer --}}
        @include('landing.templates.foodie.sections.footer')
    </div>
@endsection
