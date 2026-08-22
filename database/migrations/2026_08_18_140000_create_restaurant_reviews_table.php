<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name', 120)->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->index(['restaurant_id', 'submitted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_reviews');
    }
};
