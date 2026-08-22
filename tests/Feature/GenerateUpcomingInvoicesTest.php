<?php

namespace Tests\Feature;

use App\Enums\InvoiceSource;
use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Models\SubscriptionInvoice;
use App\Services\SubscriptionInvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class GenerateUpcomingInvoicesTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    public function test_command_creates_one_invoice_in_h_minus_7_window(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(5),
        ]);

        $this->artisan('subscription:generate-invoices')
            ->assertSuccessful();

        $this->assertSame(1, SubscriptionInvoice::query()->where('restaurant_id', $restaurant->id)->count());

        $invoice = SubscriptionInvoice::query()->first();
        $this->assertSame(InvoiceStatus::Sent, $invoice->status);
        $this->assertSame(InvoiceSource::AutoRenewal, $invoice->source);
    }

    public function test_command_does_not_duplicate_open_invoice(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(5),
        ]);

        $this->artisan('subscription:generate-invoices')->assertSuccessful();
        $this->artisan('subscription:generate-invoices')->assertSuccessful();

        $this->assertSame(1, SubscriptionInvoice::query()->where('restaurant_id', $restaurant->id)->count());
    }

    public function test_generate_upcoming_is_idempotent_via_service(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Active,
            'subscribed_until' => now()->addDays(2),
            'trial_ends_at' => null,
        ]);

        $service = app(SubscriptionInvoiceService::class);
        $first = $service->generateUpcoming($restaurant);
        $second = $service->generateUpcoming($restaurant);

        $this->assertNotNull($first);
        $this->assertTrue($first->is($second));
    }
}
