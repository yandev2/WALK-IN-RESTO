<?php

namespace Tests\Unit;

use App\Support\CmsMedia;
use PHPUnit\Framework\TestCase;

class CmsMediaMapEmbedTest extends TestCase
{
    public function test_extract_map_embed_url_from_iframe_html(): void
    {
        $html = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.123!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDgnMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';

        $extracted = CmsMedia::extractMapEmbedUrl($html);

        $this->assertSame(
            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.123!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDgnMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890',
            $extracted
        );
    }

    public function test_extract_map_embed_url_returns_plain_url_as_is(): void
    {
        $plainUrl = 'https://maps.google.com/maps?q=-6.2,106.8&z=16&output=embed';

        $this->assertSame($plainUrl, CmsMedia::extractMapEmbedUrl($plainUrl));
        $this->assertNull(CmsMedia::extractMapEmbedUrl(null));
        $this->assertNull(CmsMedia::extractMapEmbedUrl('   '));
    }

    public function test_maps_embed_url_with_raw_iframe_input(): void
    {
        $html = '<iframe src="https://www.google.com/maps/embed?pb=test" width="600"></iframe>';

        $embedUrl = CmsMedia::mapsEmbedUrl($html, null, null);

        $this->assertSame('https://www.google.com/maps/embed?pb=test', $embedUrl);
    }

    public function test_maps_embed_url_falls_back_to_coordinates_when_input_is_blank(): void
    {
        $embedUrl = CmsMedia::mapsEmbedUrl('', -6.200000, 106.816666);

        $this->assertNotNull($embedUrl);
        $this->assertStringContainsString('-6.2', $embedUrl);
        $this->assertStringContainsString('106.816666', $embedUrl);
        $this->assertStringContainsString('output=embed', $embedUrl);
    }

    public function test_maps_embed_url_rejects_unallowed_domains(): void
    {
        $malicious = 'https://evil-phishing.com/maps?q=test';

        // Disallowed URL with valid coordinates should fallback to coordinates
        $fallback = CmsMedia::mapsEmbedUrl($malicious, -6.2, 106.8);
        $this->assertStringContainsString('maps.google.com', $fallback);

        // Disallowed URL with null coordinates should return null
        $this->assertNull(CmsMedia::mapsEmbedUrl($malicious, null, null));
    }
}
