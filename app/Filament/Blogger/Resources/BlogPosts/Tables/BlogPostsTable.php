<?php

namespace App\Filament\Blogger\Resources\BlogPosts\Tables;

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Support\FilamentTranslatable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['translations', 'author']))
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->getStateUsing(fn (BlogPost $record): string => FilamentTranslatable::label($record, 'title'))
                    ->searchable(query: FilamentTranslatable::searchableQuery('title'))
                    ->wrap()
                    ->limit(45),
                TextColumn::make('locales')
                    ->label('Bahasa')
                    ->badge()
                    ->getStateUsing(function (BlogPost $record): array {
                        return $record->translations
                            ->filter(fn ($t) => filled($t->title))
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
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->searchable(),
                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->disk('public'),
                TextColumn::make('published_at')
                    ->label('Tanggal Terbit')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Pilihan')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('comments_count')
                    ->label('Komentar')
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('status')
                    ->label('Status Publikasi')
                    ->options(BlogPostStatus::class),
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
                                    ->whereNotNull('title')
                                    ->where('title', '!=', '');
                            });
                        }

                        return $query;
                    }),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
