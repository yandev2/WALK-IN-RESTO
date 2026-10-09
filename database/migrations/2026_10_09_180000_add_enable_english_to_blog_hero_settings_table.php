<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_hero_settings', function (Blueprint $table) {
            $table->boolean('enable_english')->default(false)->after('show_quick_categories');
        });
    }

    public function down(): void
    {
        Schema::table('blog_hero_settings', function (Blueprint $table) {
            $table->dropColumn('enable_english');
        });
    }
};
