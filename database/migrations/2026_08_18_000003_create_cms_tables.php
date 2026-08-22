<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('headline', 191)->nullable();
            $table->text('about_html')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->string('map_embed_url', 500)->nullable();
            $table->string('cta_label', 80)->nullable();
            $table->string('cta_url', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('cms_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption', 191)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cms_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('question');
            $table->text('answer_html');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cms_banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('title', 120)->nullable();
            $table->string('image_path');
            $table->string('link_url', 500)->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_banners');
        Schema::dropIfExists('cms_faqs');
        Schema::dropIfExists('cms_gallery_images');
        Schema::dropIfExists('cms_profiles');
    }
};
