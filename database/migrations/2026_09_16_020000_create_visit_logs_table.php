<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('page_key');
            $table->string('visitable_type')->nullable();
            $table->unsignedBigInteger('visitable_id')->nullable();
            $table->string('locale', 10)->nullable();
            $table->string('path');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('visitor_hash', 64)->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['page_key', 'created_at']);
            $table->index(['visitable_type', 'visitable_id', 'created_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
    }
};
