<?php

namespace App\Filament\Blogger\Resources\Bloggers\RelationManagers;

use App\Filament\Blogger\Resources\BlogPosts\BlogPostResource;
use App\Models\BlogPost;
use App\Support\FilamentTranslatable;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlogPostsRelationManager extends RelationManager
{
    protected static string $relationship = 'blogPosts';

    protected static ?string $title = 'Daftar Artikel Penulis';

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $pluralModelLabel = 'Artikel';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['translations', 'category.translations']))
            ->defaultSort('created_at', 'desc')
            ->recordTitle(fn (BlogPost $record): string => FilamentTranslatable::label($record, 'title'))
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
                    ->weight('medium')
                    ->wrap()
                    ->limit(50),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->getStateUsing(fn (BlogPost $record): string => $record->category ? FilamentTranslatable::label($record->category, 'name') : '-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('published_at')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('Belum Terbit'),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->default(0),

                TextColumn::make('comments_count')
                    ->label('Komentar')
                    ->numeric()
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->default(0),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon(Heroicon::OutlinedEye)
                    ->url(fn (BlogPost $record): string => BlogPostResource::getUrl('view', ['record' => $record])),

                Action::make('edit')
                    ->label('Ubah')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->color('warning')
                    ->url(fn (BlogPost $record): string => BlogPostResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('Belum Ada Artikel')
            ->emptyStateDescription('Penulis ini belum mempublikasikan artikel apa pun di blog.')
            ->emptyStateIcon(Heroicon::OutlinedDocumentText);
    }
}
