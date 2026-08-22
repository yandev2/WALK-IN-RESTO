<?php

use App\Models\DiningTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->decimal('floor_x_pct', 5, 2)->nullable()->after('area');
            $table->decimal('floor_y_pct', 5, 2)->nullable()->after('floor_x_pct');
        });

        DiningTable::withoutGlobalScopes()
            ->orderBy('outlet_id')
            ->orderBy('area')
            ->orderBy('code')
            ->get()
            ->groupBy(fn (DiningTable $table): string => $table->outlet_id.'|'.DiningTable::normalizeArea($table->area))
            ->each(function (Collection $tables): void {
                $tables->values()->each(function (DiningTable $table, int $index): void {
                    if ($table->hasFloorPosition()) {
                        return;
                    }

                    $position = DiningTable::autoGridPosition($index);

                    $table->update([
                        'floor_x_pct' => $position['x'],
                        'floor_y_pct' => $position['y'],
                    ]);
                });
            });
    }

    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn(['floor_x_pct', 'floor_y_pct']);
        });
    }
};
