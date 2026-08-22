<?php

namespace App\Services;

use App\Models\ExportFile;
use Illuminate\Support\Facades\Storage;

class ExportService
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function createQueued(
        int $restaurantId,
        int $userId,
        string $module,
        string $format,
        array $filters,
        string $filename,
        string $filePath,
        string $disk = 'public',
    ): ExportFile {
        return ExportFile::query()->create([
            'restaurant_id' => $restaurantId,
            'user_id' => $userId,
            'filename' => $filename,
            'file_path' => $filePath,
            'disk' => $disk,
            'module' => $module,
            'format' => $format,
            'status' => ExportFile::STATUS_QUEUED,
            'filters' => $filters,
        ]);
    }

    public function markProcessing(ExportFile $exportFile): void
    {
        $exportFile->update([
            'status' => ExportFile::STATUS_PROCESSING,
            'error_message' => null,
        ]);
    }

    public function markCompleted(ExportFile $exportFile): ExportFile
    {
        $disk = Storage::disk($exportFile->disk ?: 'public');
        $fullPath = $disk->path($exportFile->file_path);

        $exportFile->update([
            'status' => ExportFile::STATUS_COMPLETED,
            'mime_type' => is_file($fullPath) ? (mime_content_type($fullPath) ?: 'application/octet-stream') : 'application/octet-stream',
            'file_size' => is_file($fullPath) ? filesize($fullPath) : 0,
            'error_message' => null,
        ]);

        return $exportFile->fresh();
    }

    public function markFailed(ExportFile $exportFile, string $message): void
    {
        $exportFile->update([
            'status' => ExportFile::STATUS_FAILED,
            'error_message' => mb_substr($message, 0, 2000),
        ]);
    }
}
