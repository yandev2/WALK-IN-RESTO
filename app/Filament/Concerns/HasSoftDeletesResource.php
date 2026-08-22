<?php

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

trait HasSoftDeletesResource
{
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function canRestore(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canForceDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function trashPageAction(): Action
    {
        return Action::make('trash')
            ->label('Trash')
            ->icon(Heroicon::OutlinedTrash)
            ->color('gray')
            ->url(static::getUrl('trash'));
    }
}
