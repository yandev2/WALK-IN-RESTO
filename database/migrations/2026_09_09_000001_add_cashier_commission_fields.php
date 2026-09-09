<?php

use App\Enums\BillingType;
use App\Enums\InvoiceType;
use App\Enums\PlanCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->decimal('cashier_commission_percentage', 5, 2)->default(10.00)->after('trial_days');
        });

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->string('billing_type', 32)->default(BillingType::FixedMonthly->value)->after('price_monthly');
            $table->decimal('commission_percentage', 5, 2)->nullable()->after('billing_type');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('commission_percentage', 5, 2)->nullable()->after('plan_code');
        });

        Schema::table('subscription_invoices', function (Blueprint $table) {
            $table->string('invoice_type', 32)->default(InvoiceType::MonthlyFlat->value)->after('plan_code');
            $table->unsignedBigInteger('total_omzet')->nullable()->after('amount');
            $table->decimal('commission_percentage', 5, 2)->nullable()->after('total_omzet');
            $table->string('period_month', 7)->nullable()->after('period_key');

            $table->index(['restaurant_id', 'invoice_type', 'period_month']);
        });

        DB::table('subscription_plans')
            ->where('code', PlanCode::LandingOnly->value)
            ->update([
                'billing_type' => BillingType::FixedMonthly->value,
                'commission_percentage' => null,
                'is_active' => false,
            ]);

        DB::table('subscription_plans')
            ->where('code', PlanCode::ManagementKds->value)
            ->update([
                'billing_type' => BillingType::Commission->value,
                'commission_percentage' => 10.00,
            ]);
    }

    public function down(): void
    {
        Schema::table('subscription_invoices', function (Blueprint $table) {
            $table->dropIndex(['restaurant_id', 'invoice_type', 'period_month']);
            $table->dropColumn(['invoice_type', 'total_omzet', 'commission_percentage', 'period_month']);
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('commission_percentage');
        });

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn(['billing_type', 'commission_percentage']);
        });

        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropColumn('cashier_commission_percentage');
        });
    }
};
