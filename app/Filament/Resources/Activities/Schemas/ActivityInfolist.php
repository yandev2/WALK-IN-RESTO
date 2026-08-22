<?php

namespace App\Filament\Resources\Activities\Schemas;

use App\Models\Activity;
use App\Support\ActivityPresenter;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan')
                    ->icon(Heroicon::OutlinedClipboardDocumentList)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('event')
                            ->label('Aksi')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => ActivityPresenter::eventLabel($state))
                            ->color(fn (?string $state): string => ActivityPresenter::eventColor($state)),
                        TextEntry::make('log_name')
                            ->label('Modul')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => Str::headline((string) $state)),
                        TextEntry::make('causer.name')
                            ->label('Pelaku')
                            ->placeholder('Sistem')
                            ->formatStateUsing(fn (Activity $record): string => ActivityPresenter::causerName($record)),
                        TextEntry::make('created_at')
                            ->label('Waktu')
                            ->dateTime('d M Y H:i:s'),
                        TextEntry::make('subject_summary')
                            ->label('Data terkait')
                            ->getStateUsing(fn (Activity $record): string => ActivityPresenter::subjectLabel($record)),
                        TextEntry::make('subject_type')
                            ->label('Tipe model')
                            ->formatStateUsing(fn (?string $state): string => ActivityPresenter::subjectTypeLabel($state)),
                        TextEntry::make('description')
                            ->label('Catatan')
                            ->columnSpanFull(),
                        TextEntry::make('activity_summary')
                            ->label('Narasi')
                            ->columnSpanFull()
                            ->getStateUsing(fn (Activity $record): string => ActivityPresenter::summary($record)),
                    ]),
                Section::make('Detail perubahan')
                    ->icon(Heroicon::OutlinedArrowsRightLeft)
                    ->visible(fn (Activity $record): bool => filled(ActivityPresenter::resolvePropertyBuckets($record)['old'])
                        || filled(ActivityPresenter::resolvePropertyBuckets($record)['new']))
                    ->schema([
                        Grid::make(2)->schema([
                            KeyValueEntry::make('changes_old')
                                ->label('Sebelumnya')
                                ->getStateUsing(fn (Activity $record): array => ActivityPresenter::resolvePropertyBuckets($record)['old'])
                                ->visible(fn (Activity $record): bool => filled(ActivityPresenter::resolvePropertyBuckets($record)['old'])),
                            KeyValueEntry::make('changes_new')
                                ->label('Sesudahnya')
                                ->getStateUsing(fn (Activity $record): array => ActivityPresenter::resolvePropertyBuckets($record)['new'])
                                ->visible(fn (Activity $record): bool => filled(ActivityPresenter::resolvePropertyBuckets($record)['new'])),
                        ]),
                    ]),
                Section::make('Metadata')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('id')->label('ID'),
                        TextEntry::make('subject_id')->placeholder('-'),
                        TextEntry::make('causer_id')->placeholder('-'),
                        TextEntry::make('restaurant_id')->placeholder('-'),
                    ]),
            ]);
    }
}
