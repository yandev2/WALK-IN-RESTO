<?php

namespace App\Services;

use App\Models\AdSetting;

class AdPlacementService
{
    /**
     * URL path prefixes that must NEVER show ads.
     * These protect tenant POS, KDS, menu, panels, and auth pages.
     *
     * @var list<string>
     */
    protected const BLOCKED_PREFIXES = [
        'pos',
        'cashier',
        'kds',
        'menu',
        'order',
        'cart',
        'checkout',
        'admin',
        'founder',
        'blogger',
        'invoice',
        'login',
        'register',
        'daftar',
        'livewire',
        'filament',
        'shifts',
        'export-files',
        'receipts',
    ];

    protected AdSetting $settings;

    public function __construct()
    {
        $this->settings = AdSetting::current();
    }

    /**
     * Determine if ads are allowed for the current HTTP request.
     */
    public function isAdAllowedForCurrentRequest(): bool
    {
        if (! $this->settings->isMasterEnabled()) {
            return false;
        }

        $currentPath = trim(request()->path(), '/');

        foreach (self::BLOCKED_PREFIXES as $prefix) {
            if ($currentPath === $prefix || str_starts_with($currentPath, $prefix.'/')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the AdSense <script> tag for the <head> section.
     */
    public function getAdSenseHeadScript(): string
    {
        if (! $this->isAdAllowedForCurrentRequest()) {
            return '';
        }

        if (! $this->settings->adsense_enabled || blank($this->settings->adsense_client_id)) {
            return '';
        }

        $clientId = e($this->settings->adsense_client_id);

        $script = '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client='.$clientId.'" crossorigin="anonymous"></script>';

        if ($this->settings->adsense_auto_ads) {
            $script .= "\n".'<meta name="google-adsense-account" content="'.$clientId.'">';
        }

        return $script;
    }

    /**
     * Get the Adsterra Social Bar script for <head> or bottom-of-body.
     */
    public function getAdsterraSocialBarScript(): string
    {
        if (! $this->isAdAllowedForCurrentRequest()) {
            return '';
        }

        if (! $this->settings->adsterra_enabled || ! $this->settings->adsterra_social_bar_enabled) {
            return '';
        }

        return $this->settings->adsterra_social_bar_code ?? '';
    }

    /**
     * Get the rendered HTML for a named ad slot.
     */
    public function renderSlot(string $slotName): string
    {
        if (! $this->isAdAllowedForCurrentRequest()) {
            return '';
        }

        if (! $this->settings->isSlotActive($slotName)) {
            return '';
        }

        $slot = $this->settings->getSlot($slotName);
        $code = $slot['code'];

        // Wrap in CLS-safe container with sponsor label
        return '<div class="ad-slot ad-slot--'
            .e($slotName)
            .'" style="min-height:90px;overflow:hidden;">'
            .'<span class="ad-slot__label" style="display:block;text-align:center;font-size:10px;color:#9ca3af;letter-spacing:0.08em;text-transform:uppercase;margin-bottom:4px;">Iklan</span>'
            .$code
            .'</div>';
    }

    /**
     * Inject an ad slot after the Nth paragraph in article HTML content.
     *
     * @param  string  $htmlContent  The article's raw HTML content.
     * @param  int  $afterParagraph  Insert after this paragraph number (1-indexed).
     * @param  string  $slotName  The ad slot name to render.
     * @return string The modified HTML content.
     */
    public function injectInArticleAd(string $htmlContent, int $afterParagraph = 3, string $slotName = 'blog_article_middle'): string
    {
        $adHtml = $this->renderSlot($slotName);

        if ($adHtml === '') {
            return $htmlContent;
        }

        // Count closing </p> tags and inject after the Nth one
        $count = 0;
        $offset = 0;

        while (($pos = stripos($htmlContent, '</p>', $offset)) !== false) {
            $count++;
            $insertPosition = $pos + 4; // right after </p>

            if ($count === $afterParagraph) {
                return substr($htmlContent, 0, $insertPosition)
                    ."\n".$adHtml."\n"
                    .substr($htmlContent, $insertPosition);
            }

            $offset = $insertPosition;
        }

        // Article has fewer paragraphs than threshold — don't inject
        return $htmlContent;
    }
}
