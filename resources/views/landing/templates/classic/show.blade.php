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
    @include('partials.customer.landing-header', [
        'restaurant' => $restaurant,
        'logoUrl' => $logoUrl,
        'ctaUrl' => $ctaUrl,
        'ctaLabel' => $ctaLabel,
        'menuItems' => $menuItems,
        'landingUrl' => route('landing.show', $restaurant),
        'layout' => $layout,
        'visibleSections' => $visibleSections,
    ])

    <main id="atas">
        @foreach ($visibleSections as $sectionId)
            @include('landing.sections.'.$sectionId)
        @endforeach
    </main>

    @include('partials.customer.landing-footer', [
        'restaurant' => $restaurant,
        'outlet' => $outlet,
        'whatsappUrl' => $whatsappUrl,
        'mapsUrl' => $mapsUrl,
        'menuItems' => $menuItems,
        'layout' => $layout,
        'visibleSections' => $visibleSections,
    ])
@endsection
