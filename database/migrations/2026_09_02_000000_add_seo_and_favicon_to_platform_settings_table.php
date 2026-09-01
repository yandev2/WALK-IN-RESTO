<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->string('favicon_path')->nullable()->after('logo_path');
            $table->string('meta_title')->nullable()->after('footer_terms_url');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image_path')->nullable()->after('meta_keywords');
            $table->string('canonical_url')->nullable()->after('og_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropColumn([
                'favicon_path',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'og_image_path',
                'canonical_url',
            ]);
        });
    }
};
