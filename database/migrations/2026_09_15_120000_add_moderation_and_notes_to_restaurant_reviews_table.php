<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_reviews', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('comment');
            $table->text('internal_notes')->nullable()->after('is_published');
            $table->index(['restaurant_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_reviews', function (Blueprint $table) {
            $table->dropIndex(['restaurant_id', 'is_published']);
            $table->dropColumn(['is_published', 'internal_notes']);
        });
    }
};
