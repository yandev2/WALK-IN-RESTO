<?php

namespace App\Filament\Blogger\Resources\BlogComments\Schemas;

use App\Enums\BlogCommentStatus;
use App\Models\BlogComment;
use App\Support\FilamentTranslatable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BlogCommentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('blogPost.title')
                    ->label('Artikel')
                    ->getStateUsing(fn (BlogComment $record): string => $record->blogPost ? FilamentTranslatable::label($record->blogPost, 'title') : '-'),
                TextEntry::make('author_name')
                    ->label('Nama Pengirim'),
                TextEntry::make('author_email')
                    ->label('Email'),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge(),
                IconEntry::make('is_author_reply')
                    ->label('Balasan Penulis')
                    ->boolean(),
                TextEntry::make('content')
                    ->label('Isi Komentar')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime(),
                TextEntry::make('approved_at')
                    ->label('Disetujui Pada')
                    ->dateTime(),
            ]);
    }
}
