<?php

namespace App\Services\Export;

use App\Models\ExportFile;
use App\Models\User;
use App\Services\HandleNotification;

class ExportFinishedNotifier
{
    public function __construct(
        private readonly HandleNotification $notifications,
    ) {}

    public function notify(User $user, ExportFile $exportFile, bool $failed = false): void
    {
        $label = $exportFile->module_label;

        if ($failed) {
            $this->notifications->sendWebNotification(
                recipient: $user,
                title: 'Ekspor gagal',
                body: "Laporan {$label} gagal dibuat. Silakan coba lagi.",
                type: 'danger',
            );

            return;
        }

        $this->notifications->sendWebNotification(
            recipient: $user,
            title: 'Ekspor selesai',
            body: "Laporan {$label} siap diunduh.",
            url: $exportFile->isDownloadable() ? $exportFile->downloadUrl() : null,
            type: 'success',
        );
    }
}
