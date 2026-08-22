<?php

namespace App\Jobs;

use App\Filament\Exports\ExcelExporter;
use App\Filament\Exports\PdfExporter;
use App\Models\ExportFile;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\Export\ExportFinishedNotifier;
use App\Services\Export\ReportExportBuilder;
use App\Services\ExportService;
use App\Support\TenantContext;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ExportReportJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public int $exportFileId,
    ) {}

    public function handle(
        ExportService $exportService,
        ReportExportBuilder $builder,
        ExportFinishedNotifier $notifier,
    ): void {
        $exportFile = ExportFile::query()
            ->withoutRestaurantScope()
            ->findOrFail($this->exportFileId);

        $restaurant = Restaurant::query()->findOrFail($exportFile->restaurant_id);
        $user = $exportFile->user_id
            ? User::query()->find($exportFile->user_id)
            : null;

        TenantContext::set($restaurant);

        try {
            $exportService->markProcessing($exportFile);

            $payload = $builder->build(
                $restaurant,
                $exportFile->module,
                $exportFile->format,
                $exportFile->filters ?? [],
            );

            $disk = $exportFile->disk ?: 'public';

            if ($exportFile->format === ExportFile::FORMAT_PDF) {
                (new PdfExporter($payload['data'], $payload['view'], $exportFile->file_path, $disk))->export();
            } else {
                Excel::store(
                    new ExcelExporter($payload['data'], $payload['view']),
                    $exportFile->file_path,
                    $disk,
                );
            }

            $exportFile = $exportService->markCompleted($exportFile->fresh());

            if ($user) {
                $notifier->notify($user, $exportFile, failed: false);
            }
        } catch (Throwable $exception) {
            Log::error('ExportReportJob failed', [
                'export_file_id' => $this->exportFileId,
                'restaurant_id' => $exportFile->restaurant_id,
                'message' => $exception->getMessage(),
            ]);

            if (filled($exportFile->file_path)) {
                Storage::disk($exportFile->disk ?: 'public')->delete($exportFile->file_path);
            }

            $exportService->markFailed($exportFile->fresh(), $exception->getMessage());

            if ($user) {
                $notifier->notify($user, $exportFile->fresh(), failed: true);
            }

            throw $exception;
        } finally {
            TenantContext::clear();
        }
    }

    public function failed(?Throwable $exception): void
    {
        $exportFile = ExportFile::query()
            ->withoutRestaurantScope()
            ->find($this->exportFileId);

        if (! $exportFile || $exportFile->status === ExportFile::STATUS_FAILED) {
            return;
        }

        app(ExportService::class)->markFailed(
            $exportFile,
            $exception?->getMessage() ?? 'Export gagal.',
        );

        if ($exportFile->user_id) {
            $user = User::query()->find($exportFile->user_id);

            if ($user) {
                app(ExportFinishedNotifier::class)->notify($user, $exportFile->fresh(), failed: true);
            }
        }
    }
}
