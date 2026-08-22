<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Filament\Founder\Resources\SubscriptionInvoices\SubscriptionInvoiceResource;
use App\Models\SubscriptionInvoice;
use App\Models\User;
use App\Services\SubscriptionInvoiceService;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class InvoicePaymentTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_owner_submit_then_founder_approve_activates_and_extends(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(2),
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $this->assertSame(99000, $invoice->amount);

        $submitted = $service->submitProof(
            $invoice,
            PlanCode::ManagementKds->value,
            3,
            'subscription-proofs/bukti.jpg',
            'Sudah transfer BCA',
        );

        $this->assertSame(InvoiceStatus::AwaitingVerification, $submitted->status);
        $this->assertSame(PlanCode::ManagementKds->value, $submitted->requested_plan_code);
        $this->assertSame(3 * 249000, $submitted->amount);

        $paid = $service->approve($submitted, $founder);
        $this->assertSame(InvoiceStatus::Paid, $paid->status);

        $restaurant->refresh();
        $this->assertSame(SubscriptionStatus::Active, $restaurant->subscription_status);
        $this->assertSame(PlanCode::ManagementKds->value, $restaurant->plan_code);
        $this->assertTrue($restaurant->subscribed_until->gt(now()->addMonths(2)));
    }

    public function test_reject_returns_invoice_to_sent(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);
        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');

        $rejected = $service->reject($invoice->fresh(), 'Nominal kurang', $founder);

        $this->assertSame(InvoiceStatus::Sent, $rejected->status);
        $this->assertSame('Nominal kurang', $rejected->rejection_notes);
        $this->assertNull($rejected->payment_proof_path);
    }

    public function test_approve_requires_uploaded_proof(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);
        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);

        try {
            $service->approve($invoice, $founder);
            $this->fail('Expected ValidationException when approving without payment proof.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('invoice', $exception->errors());
            $this->assertStringContainsString('bukti transfer', $exception->errors()['invoice'][0]);
        }

        $this->assertSame(InvoiceStatus::Sent, $invoice->fresh()->status);
        $this->assertSame(SubscriptionStatus::Trial, $restaurant->fresh()->subscription_status);
    }

    public function test_submit_proof_rejected_after_invoice_is_paid(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(2),
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');
        $paid = $service->approve($invoice->fresh(), $founder);

        try {
            $service->submitProof($paid, PlanCode::LandingOnly->value, 1, 'subscription-proofs/retry.jpg');
            $this->fail('Expected ValidationException when submitting proof on a paid invoice.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('invoice', $exception->errors());
        }

        $fresh = $paid->fresh();
        $this->assertSame(InvoiceStatus::Paid, $fresh->status);
        $this->assertSame('subscription-proofs/bukti.jpg', $fresh->payment_proof_path);
    }

    public function test_reject_notifies_owner_and_deletes_proof_file(): void
    {
        Storage::fake('local');

        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $owner = $this->makeOwner($restaurant);
        $staff = $this->makeStaff($restaurant, [], 'kasir');
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $path = 'subscription-proofs/bukti.jpg';
        Storage::disk('local')->put($path, 'fake-image');

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, $path);

        Filament::setCurrentPanel('founder');
        $rejected = $service->reject($invoice->fresh(), 'Nominal kurang', $founder);

        $this->assertSame(InvoiceStatus::Sent, $rejected->status);
        $this->assertNull($rejected->payment_proof_path);
        Storage::disk('local')->assertMissing($path);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $owner->id,
        ]);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $staff->id,
        ]);

        $payload = json_decode(
            (string) DB::table('notifications')
                ->where('notifiable_id', $owner->id)
                ->latest('id')
                ->value('data'),
            true,
        );

        $this->assertSame('Bukti transfer ditolak', $payload['title'] ?? null);
        $this->assertStringContainsString((string) $rejected->invoice_number, (string) ($payload['body'] ?? ''));
        $this->assertStringContainsString('Nominal kurang', (string) ($payload['body'] ?? ''));
        $this->assertStringContainsString('/admin/', (string) ($payload['actions'][0]['url'] ?? $payload['url'] ?? ''));
        $this->assertStringNotContainsString('/founder/', (string) ($payload['actions'][0]['url'] ?? $payload['url'] ?? ''));
    }

    public function test_same_plan_approval_extends_remaining_period(): void
    {
        $this->travelTo('2026-08-22 10:00:00');
        $expiry = now()->addDays(12);

        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Active,
            'subscribed_until' => $expiry,
            'trial_ends_at' => null,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::ManagementKds->value, $founder, 2);
        $service->submitProof($invoice, PlanCode::ManagementKds->value, 2, 'subscription-proofs/bukti.jpg');
        $paid = $service->approve($invoice->fresh(), $founder);

        $expectedUntil = $expiry->copy()->addMonths(2);
        $restaurant->refresh();

        $this->assertSame(PlanCode::ManagementKds->value, $restaurant->plan_code);
        $this->assertTrue($restaurant->subscribed_until->equalTo($expectedUntil));
        $this->assertTrue($paid->period_start->equalTo($expiry));
        $this->assertTrue($paid->period_end->equalTo($expectedUntil));
    }

    public function test_different_plan_approval_resets_from_approval_day(): void
    {
        $this->travelTo('2026-08-22 10:00:00');
        $expiry = now()->addDays(12);

        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Active,
            'subscribed_until' => $expiry,
            'trial_ends_at' => null,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');
        $paid = $service->approve($invoice->fresh(), $founder);

        $expectedUntil = now()->addMonth();
        $restaurant->refresh();

        $this->assertSame(PlanCode::LandingOnly->value, $restaurant->plan_code);
        $this->assertTrue($restaurant->subscribed_until->equalTo($expectedUntil));
        $this->assertTrue($paid->period_start->equalTo(now()));
        $this->assertTrue($paid->period_end->equalTo($expectedUntil));
        $this->assertTrue($restaurant->subscribed_until->lt($expiry->copy()->addMonth()));
    }

    public function test_expired_same_plan_approval_starts_from_approval_day(): void
    {
        $this->travelTo('2026-08-22 10:00:00');

        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Expired,
            'subscribed_until' => now()->subDay(),
            'trial_ends_at' => null,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');
        $service->approve($invoice->fresh(), $founder);

        $restaurant->refresh();
        $this->assertTrue($restaurant->subscribed_until->equalTo(now()->addMonth()));
        $this->assertSame(SubscriptionStatus::Active, $restaurant->subscription_status);
    }

    public function test_create_manual_rejected_when_open_invoice_exists(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $first = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);

        try {
            $service->createManual($restaurant, PlanCode::ManagementKds->value, $founder, 1);
            $this->fail('Expected ValidationException when an unpaid invoice already exists.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('invoice', $exception->errors());
        }

        $this->assertSame(1, SubscriptionInvoice::query()->where('restaurant_id', $restaurant->id)->count());
        $this->assertTrue($first->fresh()->isOpen());
    }

    public function test_delete_unpaid_allows_creating_another_invoice(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->deleteUnpaid($invoice);

        $this->assertDatabaseMissing('subscription_invoices', ['id' => $invoice->id]);

        $next = $service->createManual($restaurant, PlanCode::ManagementKds->value, $founder, 3);
        $this->assertTrue($next->isOpen());
        $this->assertSame(PlanCode::ManagementKds->value, $next->requested_plan_code);
        $this->assertSame(3 * 249000, $next->amount);
    }

    public function test_paid_invoice_cannot_be_deleted(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(2),
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');
        $paid = $service->approve($invoice->fresh(), $founder);

        try {
            $service->deleteUnpaid($paid);
            $this->fail('Expected ValidationException when deleting a paid invoice.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('invoice', $exception->errors());
        }

        $this->assertDatabaseHas('subscription_invoices', [
            'id' => $paid->id,
            'status' => InvoiceStatus::Paid->value,
        ]);
    }

    public function test_founder_approve_notifies_owner_not_staff_once(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(2),
        ]);
        $owner = $this->makeOwner($restaurant);
        $staff = $this->makeStaff($restaurant, [], 'kasir');
        $inactiveOwner = $this->makeOwner($restaurant);
        $restaurant->users()->updateExistingPivot($inactiveOwner->id, ['is_active' => false]);

        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');

        Filament::setCurrentPanel('founder');
        $paid = $service->approve($invoice->fresh(), $founder);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $owner->id,
        ]);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $staff->id,
        ]);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $inactiveOwner->id,
        ]);

        $payload = json_decode(
            (string) DB::table('notifications')
                ->where('notifiable_id', $owner->id)
                ->latest('id')
                ->value('data'),
            true,
        );

        $this->assertSame('Pembayaran berhasil', $payload['title'] ?? null);
        $this->assertStringContainsString((string) $paid->invoice_number, (string) ($payload['body'] ?? ''));
        $this->assertStringContainsString('Langganan aktif sampai', (string) ($payload['body'] ?? ''));
        $this->assertStringContainsString('/admin/', (string) ($payload['actions'][0]['url'] ?? $payload['url'] ?? ''));

        $count = DB::table('notifications')->where('notifiable_id', $owner->id)->count();
        $service->approve($paid, $founder);
        $this->assertSame(
            $count,
            DB::table('notifications')->where('notifiable_id', $owner->id)->count(),
        );
    }

    public function test_submit_proof_notifies_founder_and_super_admin_not_owner(): void
    {
        $restaurant = $this->makeRestaurant([
            'name' => 'Warung Bukti',
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $owner = $this->makeOwner($restaurant);
        $staff = $this->makeStaff($restaurant, [], 'kasir');
        $founder = $this->makeFounder();
        $superAdmin = $this->makeSuperAdmin();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);

        $this->assertDatabaseCount('notifications', 0);

        $submitted = $service->submitProof(
            $invoice,
            PlanCode::LandingOnly->value,
            1,
            'subscription-proofs/bukti.jpg',
        );

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $founder->id,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $superAdmin->id,
        ]);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $owner->id,
        ]);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $staff->id,
        ]);

        $payload = json_decode(
            (string) DB::table('notifications')
                ->where('notifiable_id', $founder->id)
                ->latest('id')
                ->value('data'),
            true,
        );

        $this->assertSame('Invoice perlu ditinjau', $payload['title'] ?? null);
        $this->assertStringContainsString((string) $submitted->invoice_number, (string) ($payload['body'] ?? ''));
        $this->assertStringContainsString('Warung Bukti', (string) ($payload['body'] ?? ''));
        $this->assertStringContainsString('/founder/', (string) ($payload['actions'][0]['url'] ?? $payload['url'] ?? ''));
    }

    public function test_submit_proof_notifies_platform_operators_when_tenant_permission_team_is_set(): void
    {
        $restaurant = $this->makeRestaurant([
            'name' => 'Warung Tenant Team',
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);

        $registrar = app(PermissionRegistrar::class);
        $previousTeamId = $registrar->getPermissionsTeamId();
        $registrar->setPermissionsTeamId($restaurant->id);

        try {
            $service->submitProof(
                $invoice,
                PlanCode::LandingOnly->value,
                1,
                'subscription-proofs/bukti.jpg',
            );
        } finally {
            $registrar->setPermissionsTeamId($previousTeamId);
        }

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $founder->id,
        ]);
    }

    public function test_approve_notifies_owner_when_permission_team_is_not_the_restaurant(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(2),
        ]);
        $other = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $owner = $this->makeOwner($restaurant);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');

        $registrar = app(PermissionRegistrar::class);
        $previousTeamId = $registrar->getPermissionsTeamId();
        $registrar->setPermissionsTeamId($other->id);

        try {
            $service->approve($invoice->fresh(), $founder);
        } finally {
            $registrar->setPermissionsTeamId($previousTeamId);
        }

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $owner->id,
        ]);
    }

    public function test_reject_does_not_fail_when_restaurant_has_no_owner_role(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');

        $rejected = $service->reject($invoice->fresh(), 'Nominal kurang', $founder);

        $this->assertSame(InvoiceStatus::Sent, $rejected->status);
        $this->assertFalse(
            DB::table('notifications')->pluck('data')->contains(
                fn (string $data): bool => str_contains($data, 'Bukti transfer ditolak'),
            ),
        );
    }

    public function test_invoice_navigation_badge_counts_awaiting_verification(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);

        $this->assertNull(SubscriptionInvoiceResource::getNavigationBadge());

        $invoice = $service->createManual($restaurant, PlanCode::LandingOnly->value, $founder, 1);
        $this->assertNull(SubscriptionInvoiceResource::getNavigationBadge());

        $service->submitProof($invoice, PlanCode::LandingOnly->value, 1, 'subscription-proofs/bukti.jpg');
        $this->assertSame('1', SubscriptionInvoiceResource::getNavigationBadge());
        $this->assertSame('warning', SubscriptionInvoiceResource::getNavigationBadgeColor());

        $service->approve($invoice->fresh(), $founder);
        $this->assertNull(SubscriptionInvoiceResource::getNavigationBadge());
    }
}
