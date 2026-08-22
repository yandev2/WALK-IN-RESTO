<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('audit_logs')) {
            Schema::drop('audit_logs');
        }

        if (Schema::hasTable('activity_log') && ! Schema::hasColumn('activity_log', 'restaurant_id')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->foreignId('restaurant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
                $table->index(['restaurant_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        //
    }
};
