<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['restaurant_id', 'paid_at']);
            $table->index(['restaurant_id', 'voided_at']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['restaurant_id', 'order_id']);
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['restaurant_id', 'order_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['restaurant_id', 'voided_at']);
            $table->dropIndex(['restaurant_id', 'paid_at']);
        });
    }
};
