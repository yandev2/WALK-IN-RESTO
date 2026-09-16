<?php

namespace App\Filament\Blogger\Resources\BlogPosts\Schemas;

use App\Models\BlogPost;
use App\Support\FilamentTranslatable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BlogPostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextEntry::make('title')
                    ->label('Judul Artikel')
                    ->getStateUsing(fn (BlogPost $record): string => FilamentTranslatable::label($record, 'title'))
                    ->columnSpan(2),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge(),
                TextEntry::make('author.name')
                    ->label('Penulis'),
                TextEntry::make('category.name')
                    ->label('Kategori')
                    ->getStateUsing(fn (BlogPost $record): string => $record->category ? FilamentTranslatable::label($record->category, 'name') : '-'),
                TextEntry::make('published_at')
                    ->label('Tanggal Terbit')
                    ->dateTime(),
                ImageEntry::make('featured_image')
                    ->label('Gambar Utama')
                    ->columnSpanFull(),
                TextEntry::make('views_count')
                    ->label('Views'),
                TextEntry::make('likes_count')
                    ->label('Likes'),
                TextEntry::make('comments_count')
                    ->label('Komentar'),
            ]);
    }
}
