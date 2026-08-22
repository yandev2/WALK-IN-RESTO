<?php

namespace App\Services\Export;

use App\Jobs\ExportReportJob;
use App\Models\ExportFile;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\ExportService;
use App\Support\Export\ExportStoragePath;
use App\Support\RestaurantAnalyticsPeriod;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReportExportDispatcher
{
    public function __construct(
        private readonly ExportService $exportService,
    ) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function dispatch(
        Restaurant $restaurant,
        User $user,
        string $module,
        string $format,
        array $filters = [],
    ): ExportFile {
        if (! isset(ExportFile::moduleLabels()[$module])) {
            throw ValidationException::withMessages([
                'module' => 'Jenis laporan tidak dikenal.',
            ]);
        }

        if (! in_array($format, [ExportFile::FORMAT_EXCEL, ExportFile::FORMAT_PDF], true)) {
            throw ValidationException::withMessages([
                'format' => 'Format ekspor tidak valid.',
            ]);
        }

        if (! $user->isSuperAdmin() && ! $user->canAccessTenant($restaurant)) {
            throw ValidationException::withMessages([
                'restaurant' => 'Anda tidak memiliki akses ke restoran ini.',
            ]);
        }

        $normalized = $this->normalizeFilters($restaurant, $module, $filters);
        $extension = $format === ExportFile::FORMAT_PDF ? 'pdf' : 'xlsx';
        $slug = Str::slug(ExportFile::moduleLabels()[$module]);
        $stamp = now()->format('Ymd_His');
        $filename = "{$slug}_{$stamp}.{$extension}";
        $filePath = ExportStoragePath::forRestaurant($restaurant->id, $filename);

        $exportFile = $this->exportService->createQueued(
            restaurantId: $restaurant->id,
            userId: $user->id,
            module: $module,
            format: $format,
            filters: $normalized,
            filename: $filename,
            filePath: $filePath,
        );

        ExportReportJob::dispatch($exportFile->id);

        return $exportFile;
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    private function normalizeFilters(Restaurant $restaurant, string $module, array $filters): array
    {
        if ($module === ExportFile::MODULE_KATALOG_MENU) {
            return $this->normalizeMenuFilters($restaurant, $filters);
        }

        $range = RestaurantAnalyticsPeriod::normalizeLocalDateRange(
            $restaurant,
            isset($filters['date_from']) ? (string) $filters['date_from'] : null,
            isset($filters['date_to']) ? (string) $filters['date_to'] : null,
        );

        return [
            'date_from' => $range['from']->toDateString(),
            'date_to' => $range['to']->toDateString(),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    private function normalizeMenuFilters(Restaurant $restaurant, array $filters): array
    {
        $normalized = [
            'category_id' => null,
            'is_active' => null,
            'is_out_of_stock' => null,
        ];

        if (filled($filters['category_id'] ?? null)) {
            $categoryId = (int) $filters['category_id'];
            $belongs = MenuCategory::query()
                ->forRestaurant($restaurant->id)
                ->whereKey($categoryId)
                ->exists();

            if (! $belongs) {
                throw ValidationException::withMessages([
                    'category_id' => 'Kategori tidak ditemukan di restoran ini.',
                ]);
            }

            $normalized['category_id'] = $categoryId;
        }

        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null && $filters['is_active'] !== '') {
            $normalized['is_active'] = filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN);
        }

        if (array_key_exists('is_out_of_stock', $filters) && $filters['is_out_of_stock'] !== null && $filters['is_out_of_stock'] !== '') {
            $normalized['is_out_of_stock'] = filter_var($filters['is_out_of_stock'], FILTER_VALIDATE_BOOLEAN);
        }

        return $normalized;
    }
}
