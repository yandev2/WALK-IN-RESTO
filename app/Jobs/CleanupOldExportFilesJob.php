<?php

namespace App\Jobs;

use App\Models\ExportFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanupOldExportFilesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $days = 30,
    ) {}

    public function handle(): void
    {
        $cutoff = Carbon::now()->subDays(max(1, $this->days));

        ExportFile::query()
            ->withoutRestaurantScope()
            ->withTrashed()
            ->where('created_at', '<', $cutoff)
            ->orderBy('id')
            ->chunkById(100, function ($files): void {
                foreach ($files as $file) {
                    try {
                        $file->forceDelete();
                    } catch (\Throwable $exception) {
                        Log::warning('Failed to cleanup export file', [
                            'export_file_id' => $file->id,
                            'message' => $exception->getMessage(),
                        ]);

                        if (filled($file->file_path)) {
                            Storage::disk($file->disk ?: 'local')->delete($file->file_path);
                        }
                    }
                }
            });
    }
}
