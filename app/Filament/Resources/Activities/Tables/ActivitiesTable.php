<?php

namespace App\Filament\Resources\Activities\Tables;

use App\Filament\Support\TableRightClick;
use App\Models\Activity;
use App\Support\ActivityPresenter;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        $table = $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['causer']))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('author')
                    ->label('Pelaku')
                    ->getStateUsing(fn (Activity $record): string => ActivityPresenter::causerName($record))
                    ->badge()
                    ->color('info')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('causer', fn (Builder $q) => $q->where('name', 'like', "%{$search}%"));
                    }),
                TextColumn::make('event')
                    ->label('Aksi')
                    ->formatStateUsing(fn (?string $state): string => ActivityPresenter::eventLabel($state))
                    ->badge()
                    ->color(fn (?string $state): string => ActivityPresenter::eventColor($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('log_name')
                    ->label('Modul')
                    ->formatStateUsing(fn (?string $state): string => Str::headline((string) $state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->label('Data')
                    ->formatStateUsing(fn (Activity $record): string => ActivityPresenter::subjectLabel($record))
                    ->toggleable(),
                TextColumn::make('description')
                    ->label('Catatan')
                    ->limit(40)
                    ->tooltip(fn (Activity $record): string => (string) $record->description)
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Modul')
                    ->options(fn (): array => Activity::query()
                        ->distinct()
                        ->orderBy('log_name')
                        ->pluck('log_name', 'log_name')
                        ->filter()
                        ->mapWithKeys(fn ($name) => [$name => Str::headline((string) $name)])
                        ->all()),
                SelectFilter::make('event')
                    ->label('Aksi')
                    ->searchable()
                    ->options(fn (): array => Activity::query()
                        ->whereNotNull('event')
                        ->distinct()
                        ->orderBy('event')
                        ->pluck('event', 'event')
                        ->mapWithKeys(fn ($event) => [$event => ActivityPresenter::eventLabel($event)])
                        ->all()),
            ]);

        return TableRightClick::apply($table, fn (): array => [
            ViewAction::make(),
        ]);
    }
}
