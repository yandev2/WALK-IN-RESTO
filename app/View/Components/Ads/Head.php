<?php

namespace App\View\Components\Ads;

use App\Services\AdPlacementService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Renders AdSense & Adsterra head scripts.
 * Place in <head> of public layouts only.
 *
 * Usage: <x-ads.head />
 */
class Head extends Component
{
    public string $adsenseScript = '';

    public string $adsterraSocialBar = '';

    public function __construct()
    {
        $service = app(AdPlacementService::class);

        if ($service->isAdAllowedForCurrentRequest()) {
            $this->adsenseScript = $service->getAdSenseHeadScript();
            $this->adsterraSocialBar = $service->getAdsterraSocialBarScript();
        }
    }

    public function shouldRender(): bool
    {
        return filled($this->adsenseScript) || filled($this->adsterraSocialBar);
    }

    public function render(): View
    {
        return view('components.ads.head');
    }
}
