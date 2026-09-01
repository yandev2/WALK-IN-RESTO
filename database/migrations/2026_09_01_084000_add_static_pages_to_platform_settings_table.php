<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->string('about_title')->nullable()->after('footer_terms_url');
            $table->longText('about_content')->nullable()->after('about_title');
            $table->string('terms_title')->nullable()->after('about_content');
            $table->longText('terms_content')->nullable()->after('terms_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropColumn([
                'about_title',
                'about_content',
                'terms_title',
                'terms_content',
            ]);
        });
    }
};
