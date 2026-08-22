<?php

namespace App\Filament\Support;

use Filament\Actions\Action;
use Filament\Tables\Table;

final class TableRightClick
{
    /**
     * Register the same row actions for the visible action column and the right-click menu.
     * Pass a factory so each side gets fresh Action instances.
     *
     * @param  callable(): list<Action>  $actions
     */
    public static function apply(Table $table, callable $actions): Table
    {
        return $table
            ->recordActions($actions())
            ->contextMenuActions($actions());
    }
}
