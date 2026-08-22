<?php

namespace App\Services;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class HandleNotification
{
    public function sendWebNotification(
        User $recipient,
        string $title,
        string $body,
        ?string $url = null,
        string $type = 'success',
        ?string $actionLabel = null,
    ): void {
        $notification = Notification::make()
            ->title($title)
            ->body($body);

        match ($type) {
            'info' => $notification->info(),
            'warning' => $notification->warning(),
            'danger' => $notification->danger(),
            default => $notification->success(),
        };

        $label = $actionLabel ?? ($url ? 'Unduh' : 'Tandai dibaca');
        $icon = $url
            ? ($actionLabel ? 'heroicon-m-arrow-top-right-on-square' : 'heroicon-m-arrow-down-tray')
            : 'heroicon-m-check-circle';

        $action = Action::make('view')
            ->label($label)
            ->icon($icon)
            ->button()
            ->markAsRead();

        if ($url) {
            $action->url($url, shouldOpenInNewTab: true);
        }

        $notification
            ->actions([$action])
            ->sendToDatabase($recipient);
    }
}
