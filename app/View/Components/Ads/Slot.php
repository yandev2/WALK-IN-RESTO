<?php

namespace App\View\Components\Ads;

use App\Services\AdPlacementService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Renders a specific named ad slot.
 *
 * Usage: <x-ads.slot name="blog_article_top" />
 *        <x-ads.slot name="blog_sidebar" class="my-6" />
 */
class Slot extends Component
{
    public string $adHtml = '';

    public function __construct(
        public string $name,
        public string $class = '',
    ) {
        $this->adHtml = app(AdPlacementService::class)->renderSlot($this->name);
    }

    public function shouldRender(): bool
    {
        return filled($this->adHtml);
    }

    public function render(): View
    {
        return view('components.ads.slot');
    }
}
