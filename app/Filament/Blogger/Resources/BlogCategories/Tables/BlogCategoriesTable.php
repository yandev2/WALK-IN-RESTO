<?php

namespace App\Filament\Blogger\Resources\BlogCategories\Tables;

use App\Models\BlogCategory;
use App\Support\FilamentTranslatable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlogCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('translations')->withCount('blogPosts'))
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->getStateUsing(fn (BlogCategory $record): string => FilamentTranslatable::label($record, 'name'))
                    ->searchable(query: FilamentTranslatable::searchableQuery('name')),
                TextColumn::make('locales')
                    ->label('Bahasa')
                    ->badge()
                    ->getStateUsing(function (BlogCategory $record): array {
                        return $record->translations
                            ->filter(fn ($t) => filled($t->name))
                            ->pluck('locale')
                            ->map(fn ($loc) => strtoupper((string) $loc))
                            ->values()
                            ->all();
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'ID' => 'success',
                        'EN' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('blog_posts_count')
                    ->label('Total Artikel')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('locale')
                    ->label('Filter Bahasa')
                    ->options([
                        'id' => 'Indonesia (ID)',
                        'en' => 'English (EN)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (filled($data['value'] ?? null)) {
                            return $query->whereHas('translations', function (Builder $q) use ($data) {
                                $q->where('locale', $data['value'])
                                    ->whereNotNull('name')
                                    ->where('name', '!=', '');
                            });
                        }

                        return $query;
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
