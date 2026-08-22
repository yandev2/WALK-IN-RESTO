<?php

namespace Tests\Unit;

use App\Support\GeoDistance;
use PHPUnit\Framework\TestCase;

class GeoDistanceTest extends TestCase
{
    public function test_kilometers_between_jakarta_reference_points(): void
    {
        $kemang = GeoDistance::kilometers(-6.2615, 106.8108, -6.2448, 106.7995);

        $this->assertGreaterThan(1.5, $kemang);
        $this->assertLessThan(3.5, $kemang);
    }

    public function test_meters_matches_kilometers(): void
    {
        $meters = GeoDistance::meters(-6.2615, 106.8108, -6.2448, 106.7995);
        $kilometers = GeoDistance::kilometers(-6.2615, 106.8108, -6.2448, 106.7995);

        $this->assertEqualsWithDelta($kilometers * 1000, $meters, 1);
    }

    public function test_label_formats_kilometers_and_meters(): void
    {
        $this->assertSame('1,2 km', GeoDistance::label(1.2));
        $this->assertSame('450 m', GeoDistance::label(0.45));
        $this->assertNull(GeoDistance::label(null));
    }
}
