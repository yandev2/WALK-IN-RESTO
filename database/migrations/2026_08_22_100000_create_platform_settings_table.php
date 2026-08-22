<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color', 7)->default('#F97316');
            $table->string('site_name')->default('RestoTerdekat');
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_highlight')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->string('cta_register_label')->nullable();
            $table->string('search_placeholder')->nullable();
            $table->string('search_button_label')->nullable();
            $table->string('location_cta_label')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_holder')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('qr_image_path')->nullable();
            $table->timestamps();
        });

        DB::table('platform_settings')->insert([
            'primary_color' => '#F97316',
            'site_name' => 'RestoTerdekat',
            'hero_eyebrow' => 'Walk-in',
            'hero_title' => 'Temukan restoran terdekat & terbaik',
            'hero_highlight' => 'terdekat',
            'hero_subtitle' => 'Jelajahi restoran di sekitar Anda, lihat menu, jam buka, dan langsung datang ke meja pilihan.',
            'cta_register_label' => 'Daftarkan restoran',
            'search_placeholder' => 'Cari restoran, masakan, atau menu...',
            'search_button_label' => 'Cari',
            'location_cta_label' => 'Izinkan lokasi',
            'bank_name' => null,
            'bank_holder' => null,
            'bank_account' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
