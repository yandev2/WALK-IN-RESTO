<?php

namespace App\Filament\Blogger\Resources\BlogCategories\Schemas;

use App\Models\BlogCategory;
use App\Support\FilamentTranslatable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BlogCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama Kategori')
                    ->getStateUsing(fn (BlogCategory $record): string => FilamentTranslatable::label($record, 'name')),
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
