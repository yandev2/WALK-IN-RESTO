<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_profiles', function (Blueprint $table) {
            $table->char('primary_color', 7)->nullable()->after('cta_url');
            $table->char('accent_color', 7)->nullable()->after('primary_color');
        });
    }

    public function down(): void
    {
        Schema::table('cms_profiles', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'accent_color']);
        });
    }
};
