<?php

namespace App\Support;

use App\Models\DiningTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

final class TableFloorPlan
{
    /**
     * @return array{x: float, y: float}
     */
    public static function clampPosition(float $x, float $y): array
    {
        return [
            'x' => round(max(0, min(100, $x)), 2),
            'y' => round(max(0, min(100, $y)), 2),
        ];
    }

    public static function savePosition(DiningTable $table, float $x, float $y): void
    {
        $position = self::clampPosition($x, $y);

        $table->update([
            'floor_x_pct' => $position['x'],
            'floor_y_pct' => $position['y'],
        ]);
    }

    public static function assertSameOutlet(DiningTable $table, ?int $outletId): void
    {
        if ($outletId === null || (int) $table->outlet_id !== $outletId) {
            throw ValidationException::withMessages([
                'table' => 'Meja tidak ditemukan di outlet ini.',
            ]);
        }
    }

    /**
     * @return Builder<DiningTable>
     */
    public static function areaQuery(?string $area): Builder
    {
        $query = DiningTable::query();

        return self::applyAreaScope($query, $area);
    }

    /**
     * @param  Builder<DiningTable>  $query
     * @return Builder<DiningTable>
     */
    public static function applyAreaScope(Builder $query, ?string $area): Builder
    {
        if ($area === null) {
            return $query->where(function (Builder $inner): void {
                $inner->whereNull('area')->orWhere('area', '');
            });
        }

        return $query->where('area', $area);
    }

    public static function autoLayoutArea(int $outletId, ?string $area): int
    {
        $tables = DiningTable::query()
            ->where('outlet_id', $outletId)
            ->tap(fn (Builder $query) => self::applyAreaScope($query, $area))
            ->orderBy('code')
            ->get();

        return self::autoLayoutCollection($tables);
    }

    /**
     * @param  Collection<int, DiningTable>  $tables
     */
    public static function autoLayoutCollection(Collection $tables): int
    {
        $updated = 0;

        $tables->values()->each(function (DiningTable $table, int $index) use (&$updated): void {
            $position = DiningTable::autoGridPosition($index);

            $table->update([
                'floor_x_pct' => $position['x'],
                'floor_y_pct' => $position['y'],
            ]);

            $updated++;
        });

        return $updated;
    }

    /**
     * @return list<string|null>
     */
    public static function areasForOutlet(?int $outletId): array
    {
        if ($outletId === null) {
            return [];
        }

        $areas = DiningTable::query()
            ->where('outlet_id', $outletId)
            ->select('area')
            ->distinct()
            ->orderBy('area')
            ->pluck('area')
            ->map(fn (?string $area): ?string => DiningTable::normalizeArea($area))
            ->unique()
            ->values()
            ->all();

        $hasBlankArea = DiningTable::query()
            ->where('outlet_id', $outletId)
            ->where(function (Builder $query): void {
                $query->whereNull('area')->orWhere('area', '');
            })
            ->exists();

        if ($hasBlankArea && ! in_array(null, $areas, true)) {
            $areas[] = null;
        }

        usort($areas, function (?string $a, ?string $b): int {
            if ($a === null) {
                return 1;
            }

            if ($b === null) {
                return -1;
            }

            return strcasecmp($a, $b);
        });

        return $areas;
    }

    public static function areaTabKey(?string $area): string
    {
        if ($area === null) {
            return 'area-none';
        }

        return 'area-'.str($area)->slug('_');
    }

    public static function areaTabLabel(?string $area): string
    {
        return $area ?? 'Tanpa area';
    }

    /**
     * Tinggi minimum kanvas (rem) berdasarkan jumlah meja & posisi Y terbawah.
     *
     * @param  Collection<int, DiningTable>  $tables
     */
    public static function canvasMinHeightRem(Collection $tables): float
    {
        $baseMin = 32.0;

        if ($tables->isEmpty()) {
            return $baseMin;
        }

        $rows = (int) ceil($tables->count() / DiningTable::FLOOR_GRID_COLUMNS);
        $rowBasedMin = max($baseMin, 6.0 + ($rows * 10.0));

        $maxY = 0.0;

        foreach ($tables->values() as $index => $table) {
            $maxY = max($maxY, $table->displayPosition($index)['y']);
        }

        $cardBufferRem = 5.5;
        $positionBasedMin = $maxY >= 99
            ? $baseMin + ($rows * 7.5)
            : $cardBufferRem / (1 - ($maxY / 100));

        return min(120.0, max($baseMin, $rowBasedMin, $positionBasedMin));
    }
}
