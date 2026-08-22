@props(['item'])

@php
    use App\Support\CmsMedia;
@endphp

@if ($item->hasDiscount())
    <div class="mt-1 flex flex-col gap-0.5">
        <span class="customer-pill w-fit bg-red-500 text-[0.65rem] text-white">Diskon {{ $item->discount_percent }}%</span>
        <p class="text-sm text-muted line-through">{{ CmsMedia::formatIdr($item->price) }}</p>
        <p class="text-sm font-bold text-primary">{{ CmsMedia::formatIdr($item->effectivePrice()) }}</p>
    </div>
@else
    <p class="mt-1 text-sm font-bold text-primary">{{ CmsMedia::formatIdr($item->price) }}</p>
@endif
