<?php

namespace App\Filament\Blogger\Resources\BlogTags\Schemas;

use App\Models\BlogTag;
use App\Support\FilamentTranslatable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BlogTagInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama Tag')
                    ->getStateUsing(fn (BlogTag $record): string => FilamentTranslatable::label($record, 'name')),
                TextEntry::make('sort_order')
                    ->label('Urutan')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime(),
            ]);
    }
}
