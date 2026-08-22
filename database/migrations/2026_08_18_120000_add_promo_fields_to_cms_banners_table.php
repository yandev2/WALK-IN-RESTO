<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_banners', function (Blueprint $table) {
            $table->string('subtitle', 500)->nullable()->after('title');
            $table->string('badge_text', 40)->nullable()->after('subtitle');
            $table->string('price_label', 60)->nullable()->after('badge_text');
            $table->string('cta_label', 60)->nullable()->after('price_label');
        });
    }

    public function down(): void
    {
        Schema::table('cms_banners', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'badge_text', 'price_label', 'cta_label']);
        });
    }
};
