<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->string('name', 120);
            $table->string('slug', 80)->unique();
            $table->string('custom_domain', 191)->nullable()->unique();
            $table->string('legal_name', 191)->nullable();
            $table->string('npwp', 32)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('timezone', 64)->default('Asia/Jakarta');
            $table->char('currency', 3)->default('IDR');
            $table->boolean('is_active')->default(true);
            $table->string('plan_code', 32)->nullable();
            $table->string('fonnte_api_key_encrypted', 512)->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('restaurant_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['restaurant_id', 'user_id']);
        });

        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->char('public_id', 26)->unique();
            $table->string('code', 32);
            $table->string('name', 120);
            $table->text('address')->nullable();
            $table->string('phone', 32)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_open')->default(true);
            $table->boolean('is_active')->default(true);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('geofence_radius_m')->default(30);
            $table->unsignedInteger('gps_accuracy_max_m')->default(50);
            $table->string('qris_image_path')->nullable();
            $table->decimal('pb1_pct', 5, 2)->default(10);
            $table->decimal('service_pct', 5, 2)->default(5);
            $table->string('tax_mode', 16)->default('exclusive');
            $table->unsignedInteger('claim_ttl_minutes')->default(10);
            $table->unsignedInteger('awaiting_cashier_ttl_minutes')->default(20);
            $table->timestamps();
            $table->unique(['restaurant_id', 'code']);
        });

        Schema::create('outlet_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
            $table->unique(['outlet_id', 'user_id']);
        });

        Schema::create('outlet_operating_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week');
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->unique(['outlet_id', 'day_of_week']);
        });

        Schema::create('outlet_closed_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->date('closed_on');
            $table->string('reason', 191)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unique(['outlet_id', 'closed_on']);
        });

        Schema::create('outlet_sequences', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('seq_key', 32);
            $table->unsignedBigInteger('next_value')->default(1);
            $table->primary(['outlet_id', 'seq_key']);
        });

        Schema::create('kds_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 32);
            $table->string('name', 80);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['outlet_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kds_stations');
        Schema::dropIfExists('outlet_sequences');
        Schema::dropIfExists('outlet_closed_dates');
        Schema::dropIfExists('outlet_operating_hours');
        Schema::dropIfExists('outlet_users');
        Schema::dropIfExists('outlets');
        Schema::dropIfExists('restaurant_users');
        Schema::dropIfExists('restaurants');
    }
};
