<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashier_shifts', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 20)->default('open'); // 'open', 'closed'
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->unsignedBigInteger('starting_cash')->default(0);
            $table->unsignedBigInteger('cash_sales')->default(0);
            $table->unsignedBigInteger('non_cash_sales')->default(0);
            $table->unsignedBigInteger('cash_in')->default(0);
            $table->unsignedBigInteger('cash_out')->default(0);
            $table->unsignedBigInteger('expected_ending_cash')->nullable();
            $table->unsignedBigInteger('actual_ending_cash')->nullable();
            $table->bigInteger('cash_difference')->nullable();
            $table->text('difference_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('closed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['restaurant_id', 'outlet_id', 'status']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('cashier_shift_movements', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('cashier_shift_id')->constrained('cashier_shifts')->cascadeOnDelete();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 20); // 'cash_in', 'cash_out'
            $table->unsignedBigInteger('amount');
            $table->string('category', 64)->default('lainnya');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index(['cashier_shift_id', 'type']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cashier_shift_id')->nullable()->after('created_by_user_id')->constrained('cashier_shifts')->nullOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('cashier_shift_id')->nullable()->after('order_id')->constrained('cashier_shifts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cashier_shift_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cashier_shift_id');
        });

        Schema::dropIfExists('cashier_shift_movements');
        Schema::dropIfExists('cashier_shifts');
    }
};
