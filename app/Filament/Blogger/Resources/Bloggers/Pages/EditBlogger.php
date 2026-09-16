<?php

namespace App\Filament\Blogger\Resources\Bloggers\Pages;

use App\Filament\Blogger\Resources\Bloggers\BloggerResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditBlogger extends EditRecord
{
    protected static string $resource = BloggerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Lihat Profil')
                ->icon(Heroicon::OutlinedEye),
            DeleteAction::make()
                ->label('Hapus Penulis')
                ->visible(fn (User $record): bool => auth()->id() !== $record->id && (auth()->user()?->isPlatformOperator() ?? false)),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
