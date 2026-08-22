<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_profiles', function (Blueprint $table) {
            $table->string('how_to_image_path')->nullable()->after('hero_image_path');
            $table->string('about_image_path')->nullable()->after('how_to_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('cms_profiles', function (Blueprint $table) {
            $table->dropColumn(['how_to_image_path', 'about_image_path']);
        });
    }
};
