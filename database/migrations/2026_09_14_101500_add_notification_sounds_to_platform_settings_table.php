<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->string('cashier_sound_path')->nullable()->after('qr_image_path');
            $table->string('kitchen_sound_path')->nullable()->after('cashier_sound_path');
        });
    }

    public function down(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropColumn([
                'cashier_sound_path',
                'kitchen_sound_path',
            ]);
        });
    }
};
