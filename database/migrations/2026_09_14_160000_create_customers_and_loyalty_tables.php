<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('public_id', 32)->unique();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('phone', 30);
            $table->string('name')->nullable();
            $table->string('tier', 20)->default('reguler');
            $table->integer('points_balance')->default(0);
            $table->unsignedBigInteger('total_spent')->default(0);
            $table->unsignedInteger('total_orders')->default(0);
            $table->timestamp('last_visit_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['restaurant_id', 'phone']);
            $table->index(['restaurant_id', 'tier']);
            $table->index(['restaurant_id', 'total_spent']);
            $table->index(['restaurant_id', 'total_orders']);
        });

        Schema::create('customer_loyalty_points', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20); // earn, redeem, adjustment
            $table->integer('points');
            $table->integer('balance_after');
            $table->string('description')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_loyalty_points');
        Schema::dropIfExists('customers');
    }
};
