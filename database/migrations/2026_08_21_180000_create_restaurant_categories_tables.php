<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 80)->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('restaurant_restaurant_category', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('restaurant_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['restaurant_id', 'restaurant_category_id']);
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->unsignedTinyInteger('price_level')->nullable()->after('is_active');
            $table->json('facilities')->nullable()->after('price_level');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['price_level', 'facilities']);
        });

        Schema::dropIfExists('restaurant_restaurant_category');
        Schema::dropIfExists('restaurant_categories');
    }
};
