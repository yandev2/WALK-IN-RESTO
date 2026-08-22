@php
    $copy = $layout->copyFor('banners');
@endphp

<x-customer.promo-banner-carousel
    :banners="$restaurant->cmsBanners"
    :menu-url="$menuItems->isNotEmpty() ? route('landing.menu', $restaurant) : null"
    :label="$copy['label'] ?? null"
    :title="$copy['title'] ?? null"
    :highlight="$copy['highlight'] ?? null"
    :button="$copy['button'] ?? null"
/>
