<?php

use App\Enums\InvoiceSource;
use App\Enums\InvoiceStatus;
use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->unsignedInteger('price_monthly');
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('subscription_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 32)->unique();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('plan_code', 32);
            $table->string('requested_plan_code', 32)->nullable();
            $table->unsignedTinyInteger('billing_months')->nullable();
            $table->unsignedInteger('amount');
            $table->string('status', 32)->default(InvoiceStatus::Sent->value);
            $table->string('source', 32)->default(InvoiceSource::Manual->value);
            $table->string('period_key', 40)->nullable();
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->timestamp('payment_submitted_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('payment_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_notes')->nullable();
            $table->timestamps();

            $table->index(['restaurant_id', 'status']);
            $table->unique(['restaurant_id', 'source', 'period_key'], 'subscription_invoices_auto_period_unique');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('subscription_status', 32)->default(SubscriptionStatus::Active->value)->after('plan_code');
            $table->timestamp('trial_ends_at')->nullable()->after('subscription_status');
            $table->timestamp('grace_ends_at')->nullable()->after('trial_ends_at');
            $table->timestamp('subscribed_until')->nullable()->after('grace_ends_at');
            $table->boolean('listed_in_directory')->default(true)->after('is_active');
            $table->boolean('landing_enabled')->default(true)->after('listed_in_directory');
        });

        $now = now();

        DB::table('subscription_plans')->insert([
            [
                'code' => PlanCode::LandingOnly->value,
                'name' => 'Landing Page Only',
                'description' => 'Halaman publik restoran, CMS, dan profil directory. Tanpa pemesanan, KDS, atau operasional.',
                'price_monthly' => 99000,
                'features' => json_encode([
                    'cms' => true,
                    'menu' => false,
                    'operations' => false,
                    'analytics' => false,
                    'settings' => 'limited',
                ]),
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => PlanCode::ManagementKds->value,
                'name' => 'Management KDS',
                'description' => 'Semua fitur: CMS, menu, order, KDS, kasir, meja, dan laporan.',
                'price_monthly' => 249000,
                'features' => json_encode([
                    'cms' => true,
                    'menu' => true,
                    'operations' => true,
                    'analytics' => true,
                    'settings' => 'full',
                ]),
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $subscribedUntil = $now->copy()->addYear();

        DB::table('restaurants')->update([
            'plan_code' => DB::raw("COALESCE(plan_code, '".PlanCode::ManagementKds->value."')"),
            'subscription_status' => SubscriptionStatus::Active->value,
            'subscribed_until' => $subscribedUntil,
            'listed_in_directory' => DB::raw('is_active'),
            'landing_enabled' => DB::raw('is_active'),
        ]);
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'trial_ends_at',
                'grace_ends_at',
                'subscribed_until',
                'listed_in_directory',
                'landing_enabled',
            ]);
        });

        Schema::dropIfExists('subscription_invoices');
        Schema::dropIfExists('subscription_plans');
    }
};
