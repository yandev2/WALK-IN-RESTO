<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('number');
            $table->string('status', 32);
            $table->string('source', 16);
            $table->string('payment_method', 16);
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('idempotency_key', 64);
            $table->char('currency', 3)->default('IDR');
            $table->decimal('pb1_pct_snapshot', 5, 2);
            $table->decimal('service_pct_snapshot', 5, 2);
            $table->string('tax_mode_snapshot', 16);
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('service_amount');
            $table->unsignedBigInteger('pb1_amount');
            $table->unsignedBigInteger('grand_before');
            $table->unsignedBigInteger('grand_payable');
            $table->boolean('send_receipt')->default(true);
            $table->string('receipt_wa_snapshot', 20)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamps();
            $table->unique(['outlet_id', 'number']);
            $table->unique(['outlet_id', 'idempotency_key']);
            $table->index(['outlet_id', 'status', 'created_at']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('method', 16);
            $table->string('provider', 16)->default('manual');
            $table->string('status', 32);
            $table->unsignedBigInteger('amount');
            $table->unsignedSmallInteger('unique_add')->default(0);
            $table->unsignedBigInteger('qris_hold_amount')->nullable();
            $table->string('qris_image_path_snapshot')->nullable();
            $table->string('provider_ref', 191)->nullable();
            $table->json('provider_payload')->nullable();
            $table->string('proof_image_path')->nullable();
            $table->string('gps_status', 32)->default('not_required');
            $table->decimal('gps_latitude', 10, 7)->nullable();
            $table->decimal('gps_longitude', 10, 7)->nullable();
            $table->decimal('gps_accuracy_m', 8, 2)->nullable();
            $table->decimal('gps_distance_m', 8, 2)->nullable();
            $table->foreignId('gps_override_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('gps_override_reason')->nullable();
            $table->timestamp('gps_overridden_at')->nullable();
            $table->timestamp('awaiting_expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('paid_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->string('reject_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            $table->unique(['outlet_id', 'qris_hold_amount']);
            $table->index(['outlet_id', 'status', 'created_at']);
            $table->index('awaiting_expires_at');
        });

        Schema::create('order_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamp('generated_at');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('receipt_id')->nullable()->constrained('order_receipts')->nullOnDelete();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kind', 32);
            $table->string('provider', 16)->default('fonnte');
            $table->string('to_wa', 20);
            $table->text('body')->nullable();
            $table->string('media_path')->nullable();
            $table->string('status', 16)->default('queued');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('provider_ref', 191)->nullable();
            $table->json('provider_payload')->nullable();
            $table->string('last_error', 500)->nullable();
            $table->timestamp('queued_at');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
            $table->index(['outlet_id', 'status', 'queued_at']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('menu_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('station_id')->constrained('kds_stations')->restrictOnDelete();
            $table->string('name_snapshot', 120);
            $table->string('variant_name_snapshot', 80)->nullable();
            $table->unsignedBigInteger('unit_price');
            $table->unsignedSmallInteger('qty');
            $table->string('notes')->nullable();
            $table->string('kds_status', 16)->default('queued');
            $table->string('void_omzet_policy', 16)->nullable();
            $table->string('void_reason')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('preparing_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('served_reverted_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamps();
            $table->index(['outlet_id', 'station_id', 'kds_status']);
        });

        Schema::create('order_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modifier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name_snapshot', 80);
            $table->unsignedBigInteger('price_snapshot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_modifiers');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('whatsapp_messages');
        Schema::dropIfExists('order_receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
    }
};
