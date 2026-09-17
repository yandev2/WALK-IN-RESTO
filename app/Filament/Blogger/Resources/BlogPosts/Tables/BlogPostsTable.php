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
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['translations', 'author', 'blogCategory.translations']))
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),
                ImageColumn::make('featured_image')
                    ->label('Cover')
                    ->disk('public')
                    ->size(36)
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->getStateUsing(fn (BlogPost $record): string => FilamentTranslatable::label($record, 'title'))
                    ->description(function (BlogPost $record): ?string {
                        $cat = $record->blogCategory ? FilamentTranslatable::label($record->blogCategory, 'name') : null;
                        return $cat ? "Kategori: {$cat}" : null;
                    })
                    ->searchable(query: FilamentTranslatable::searchableQuery('title'))
                    ->weight('medium')
                    ->wrap(),
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
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->visible(fn (): bool => auth()->user()?->isPlatformOperator() ?? false),
                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->date('d M Y')
                    ->description(fn (BlogPost $record): ?string => $record->published_at?->format('H:i'))
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Pilihan')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->numeric()
                    ->sortable()
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comments_count')
                    ->label('Komentar')
                    ->numeric()
                    ->sortable()
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
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
                ViewAction::make()
                    ->iconButton()
                    ->tooltip('Lihat Artikel'),
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Ubah Artikel')
                    ->visible(fn (BlogPost $record): bool => auth()->user()?->isPlatformOperator() || (int) $record->author_id === (int) auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->isPlatformOperator() ?? false),
                ]),
            ]);
    }
}
