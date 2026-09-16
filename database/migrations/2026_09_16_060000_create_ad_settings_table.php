<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_settings', function (Blueprint $table) {
            $table->id();

            // Master switch
            $table->boolean('is_enabled')->default(false);

            // ads.txt content
            $table->text('ads_txt_content')->nullable();

            // Google AdSense
            $table->boolean('adsense_enabled')->default(false);
            $table->string('adsense_client_id', 64)->nullable(); // e.g. ca-pub-XXXXXXXXXX
            $table->boolean('adsense_auto_ads')->default(false);

            // Adsterra
            $table->boolean('adsterra_enabled')->default(false);
            $table->boolean('adsterra_social_bar_enabled')->default(false);
            $table->text('adsterra_social_bar_code')->nullable();
            $table->boolean('adsterra_native_enabled')->default(false);
            $table->text('adsterra_native_code')->nullable();

            // Slot configurations (JSON: {provider, code, is_active})
            $table->json('slot_blog_article_top')->nullable();
            $table->json('slot_blog_article_middle')->nullable();
            $table->json('slot_blog_article_bottom')->nullable();
            $table->json('slot_blog_sidebar')->nullable();
            $table->json('slot_blog_feed')->nullable();
            $table->json('slot_directory_native')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_settings');
    }
};
