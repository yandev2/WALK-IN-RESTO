<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $keepIds = DB::table('restaurant_users')
            ->selectRaw('MIN(id) as id')
            ->groupBy('user_id')
            ->pluck('id');

        if ($keepIds->isNotEmpty()) {
            DB::table('restaurant_users')
                ->whereNotIn('id', $keepIds)
                ->delete();
        }

        Schema::table('restaurant_users', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_users', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });
    }
};
