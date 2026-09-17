<?php

namespace App\Http\Controllers;

use App\Models\ExportFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportFileDownloadController extends Controller
{
    public function __invoke(Request $request, int $exportFile): StreamedResponse
    {
        $record = ExportFile::query()
            ->withoutRestaurantScope()
            ->whereKey($exportFile)
            ->firstOrFail();

        // Spatie teams: permission checks must use the export's restaurant.
        app(PermissionRegistrar::class)->setPermissionsTeamId($record->restaurant_id);

        Gate::authorize('view', $record);

        abort_unless($record->isDownloadable(), 404);

        // Path must stay under the owning restaurant prefix (tenant isolation on disk).
        $expectedPrefix = 'restaurants/'.$record->restaurant_id.'/export/';
        abort_unless(str_starts_with($record->file_path, $expectedPrefix), 404);

        $disk = Storage::disk($record->disk ?: 'local');

        abort_unless($disk->exists($record->file_path), 404);

        return $disk->download($record->file_path, $record->filename);
    }
}
