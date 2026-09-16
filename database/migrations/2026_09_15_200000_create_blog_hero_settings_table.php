<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_hero_settings', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image')->nullable();
            $table->unsignedTinyInteger('overlay_opacity')->default(60);
            $table->boolean('show_quick_categories')->default(true);
            $table->timestamps();
        });

        Schema::create('blog_hero_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_hero_setting_id')->constrained('blog_hero_settings')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('badge_text')->nullable();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('search_placeholder')->nullable();

            $table->unique(['blog_hero_setting_id', 'locale'], 'blog_hero_trans_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_hero_setting_translations');
        Schema::dropIfExists('blog_hero_settings');
    }
};
