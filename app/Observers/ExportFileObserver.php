<?php

namespace App\Observers;

use App\Models\ExportFile;
use Illuminate\Support\Facades\Storage;

class ExportFileObserver
{
    public function forceDeleted(ExportFile $exportFile): void
    {
        if (blank($exportFile->file_path)) {
            return;
        }

        $disk = Storage::disk($exportFile->disk ?: 'public');

        if ($disk->exists($exportFile->file_path)) {
            $disk->delete($exportFile->file_path);
        }
    }
}
