<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\DiningTable;
use App\Models\User;
use App\Services\TableOpsService;
use App\Support\TableQrToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class TableOpsServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_reset_pin_clears_lock_and_keeps_visit_open(): void
    {
        $world = $this->createGuestRestaurant();
        $this->claimTable($world);
        $visit = $world['table']->fresh()->openVisit;
        $oldPin = $visit->join_pin;

        $visit->forceFill([
            'pin_fail_count' => 5,
            'join_locked_until' => now()->addMinutes(10),
        ])->save();

        $pin = app(TableOpsService::class)->resetPin($visit->refresh(), User::factory()->create());

        $visit->refresh();
        $this->assertSame(4, strlen($pin));
        $this->assertSame($pin, $visit->join_pin);
        $this->assertNotSame($oldPin, $pin);
        $this->assertSame(0, $visit->pin_fail_count);
        $this->assertNull($visit->join_locked_until);
        $this->assertSame('open', $visit->status);
        $this->assertTrue(Activity::query()->where('event', 'visit.reset_pin')->exists());
    }

    public function test_regenerate_qr_kills_old_token_and_keeps_visit(): void
    {
        $world = $this->createGuestRestaurant();
        $this->claimTable($world);
        $oldToken = $world['token'];
        $table = $world['table']->fresh();

        app(TableOpsService::class)->regenerateQr($table, User::factory()->create());
        $table->refresh();

        $this->assertNull(TableQrToken::resolve($oldToken));
        $newUrl = TableQrToken::url($table);
        $this->assertNotNull(TableQrToken::resolve(TableQrToken::make($table)));
        $this->assertStringNotContainsString('/order/t/'.$table->id, $newUrl);
        $this->assertSame($world['table']->fresh()->open_visit_id, $table->open_visit_id);

        $png = TableQrToken::png($table);
        $this->assertSame("\x89PNG", substr($png, 0, 4));
    }

    public function test_move_visit_to_available_table(): void
    {
        $world = $this->createGuestRestaurant();
        $this->claimTable($world);
        $visit = $world['table']->fresh()->openVisit;
        $dest = $world['otherTable'];

        app(TableOpsService::class)->moveVisit($visit, $dest, User::factory()->create());

        $this->assertNull($world['table']->fresh()->open_visit_id);
        $this->assertSame($visit->id, $dest->fresh()->open_visit_id);
        $this->assertSame($dest->id, $visit->fresh()->table_id);
        $this->assertTrue(Activity::query()->where('event', 'visit.move')->exists());
    }

    public function test_cashier_can_update_visit_whatsapp(): void
    {
        $world = $this->createGuestRestaurant();
        $this->claimTable($world);
        $visit = $world['table']->fresh()->openVisit;

        app(TableOpsService::class)->updateCustomerWa($visit, User::factory()->create(), '081298765432');

        $this->assertSame('6281298765432', $visit->fresh()->customer_wa);
        $this->assertTrue(Activity::query()->where('event', 'visit.update_wa')->exists());
    }

    public function test_qr_pdf_is_a_pdf_and_png_is_a_png(): void
    {
        $world = $this->createGuestRestaurant();
        $pdf = TableQrToken::pdf($world['table']);

        $this->assertSame('%PDF', substr($pdf, 0, 4));
        $this->assertSame("\x89PNG", substr(TableQrToken::png($world['table']), 0, 4));
    }

    public function test_floor_status_is_ordering_until_paid(): void
    {
        $world = $this->createGuestRestaurant();
        $this->claimTable($world);

        $this->assertSame('ordering', $world['table']->fresh(['openVisit.orders'])->floorStatus());
    }

    public function test_floor_status_is_occupied_after_paid_order(): void
    {
        $world = $this->createGuestRestaurant();
        $this->paidGuestOrder($world, 'floor-occupied-1');

        $this->assertSame('occupied', $world['table']->fresh(['openVisit.orders'])->floorStatus());
    }

    public function test_move_to_occupied_table_is_rejected(): void
    {
        $world = $this->createGuestRestaurant();
        $this->claimTable($world);

        $device = $this->newDeviceToken();
        $this->withHeaders($this->deviceHeaders($device))
            ->postJson('/api/v1/guest/tables/'.TableQrToken::make($world['otherTable']).'/claim', [
                'customer_wa' => '081298765432',
            ])
            ->assertCreated();

        $visit = $world['table']->fresh()->openVisit;

        $this->expectException(ValidationException::class);
        app(TableOpsService::class)->moveVisit($visit, $world['otherTable']->fresh(), User::factory()->create());
    }

    public function test_cannot_mark_oos_or_delete_table_with_open_visit(): void
    {
        $world = $this->createGuestRestaurant();
        $this->claimTable($world);
        $table = $world['table']->fresh();

        try {
            $table->update(['is_out_of_service' => true]);
            $this->fail('OOS should be rejected while visit is open');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('is_out_of_service', $e->errors());
        }

        $this->assertFalse($table->fresh()->is_out_of_service);

        try {
            $table->delete();
            $this->fail('Delete should be rejected while visit is open');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('table', $e->errors());
        }

        $this->assertNotNull($table->fresh());
    }

    /**
     * @param  array{table: DiningTable, token: string}  $world
     */
    private function claimTable(array $world): void
    {
        $device = $this->newDeviceToken();
        $this->withHeaders($this->deviceHeaders($device))
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();
    }
}
