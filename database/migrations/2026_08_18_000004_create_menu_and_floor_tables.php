<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('menu_categories')->cascadeOnDelete();
            $table->foreignId('station_id')->constrained('kds_stations')->restrictOnDelete();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price');
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_out_of_stock')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->bigInteger('price_delta')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        Schema::create('modifier_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->unsignedTinyInteger('min_select')->default(0);
            $table->unsignedTinyInteger('max_select')->default(1);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        Schema::create('modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modifier_group_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->unsignedBigInteger('price')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        Schema::create('menu_item_modifier_groups', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modifier_group_id')->constrained()->cascadeOnDelete();
            $table->primary(['menu_item_id', 'modifier_group_id']);
        });

        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('code', 32);
            $table->unsignedTinyInteger('capacity')->default(2);
            $table->string('area', 64)->nullable();
            $table->boolean('is_out_of_service')->default(false);
            $table->boolean('needs_cleaning')->default(false);
            $table->unsignedInteger('qr_version')->default(1);
            $table->string('qr_secret', 64);
            $table->unsignedBigInteger('open_visit_id')->nullable()->unique();
            $table->timestamps();
            $table->unique(['outlet_id', 'code']);
        });

        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('table_id')->constrained()->restrictOnDelete();
            $table->string('status', 16)->default('open');
            $table->char('join_pin', 4);
            $table->unsignedTinyInteger('pin_fail_count')->default(0);
            $table->timestamp('join_locked_until')->nullable();
            $table->foreignId('opened_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name', 120)->nullable();
            $table->string('customer_wa', 20);
            $table->timestamp('claimed_at');
            $table->timestamp('claim_expires_at');
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['restaurant_id', 'outlet_id', 'status']);
            $table->index('claim_expires_at');
            $table->index('customer_wa');
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->foreign('open_visit_id')->references('id')->on('visits')->nullOnDelete();
        });

        Schema::create('visit_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->string('device_token', 64)->unique();
            $table->boolean('is_host')->default(false);
            $table->string('user_agent')->nullable();
            $table->timestamp('joined_at');
            $table->timestamp('last_seen_at')->nullable();
        });

        Schema::create('visit_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->restrictOnDelete();
            $table->foreignId('menu_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('qty');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('visit_cart_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_cart_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modifier_id')->constrained()->restrictOnDelete();
            $table->unique(['visit_cart_item_id', 'modifier_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_cart_item_modifiers');
        Schema::dropIfExists('visit_cart_items');
        Schema::dropIfExists('visit_devices');
        Schema::table('tables', function (Blueprint $table) {
            $table->dropForeign(['open_visit_id']);
        });
        Schema::dropIfExists('visits');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('menu_item_modifier_groups');
        Schema::dropIfExists('modifiers');
        Schema::dropIfExists('modifier_groups');
        Schema::dropIfExists('menu_variants');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menu_categories');
    }
};
