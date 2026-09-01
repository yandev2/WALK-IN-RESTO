<?php

namespace Tests\Feature;

use App\Models\DiningTable;
use App\Models\Outlet;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_http_responses_include_security_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'geolocation=(self), camera=(), microphone=()');
    }

    public function test_dining_table_hides_qr_secret_in_serialization(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Resto Sec Test',
            'slug' => 'resto-sec-test',
            'is_active' => true,
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'OUT-01',
            'name' => 'Cabang Utama',
            'is_active' => true,
        ]);

        $table = DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => 'T01',
            'capacity' => 4,
            'qr_version' => 1,
            'qr_secret' => 'super-secret-token-key',
        ]);

        $array = $table->toArray();
        $json = $table->toJson();

        $this->assertArrayNotHasKey('qr_secret', $array);
        $this->assertStringNotContainsString('super-secret-token-key', $json);
    }
}
