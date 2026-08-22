<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->text('footer_about')->nullable()->after('location_cta_label');
            $table->string('footer_email')->nullable()->after('footer_about');
            $table->string('footer_phone')->nullable()->after('footer_email');
            $table->string('footer_address')->nullable()->after('footer_phone');
            $table->string('footer_instagram')->nullable()->after('footer_address');
            $table->string('footer_copyright')->nullable()->after('footer_instagram');
            $table->string('footer_privacy_url')->nullable()->after('footer_copyright');
            $table->string('footer_terms_url')->nullable()->after('footer_privacy_url');
        });
    }

    public function down(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropColumn([
                'footer_about',
                'footer_email',
                'footer_phone',
                'footer_address',
                'footer_instagram',
                'footer_copyright',
                'footer_privacy_url',
                'footer_terms_url',
            ]);
        });
    }
};
