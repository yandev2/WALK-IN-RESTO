<?php

namespace App\Filament\Blogger\Resources\BlogTags;

use App\Filament\Blogger\Concerns\HasTranslatableRecordTitle;
use App\Filament\Blogger\Resources\BlogTags\Pages\CreateBlogTag;
use App\Filament\Blogger\Resources\BlogTags\Pages\EditBlogTag;
use App\Filament\Blogger\Resources\BlogTags\Pages\ListBlogTags;
use App\Filament\Blogger\Resources\BlogTags\Pages\ViewBlogTag;
use App\Filament\Blogger\Resources\BlogTags\Schemas\BlogTagForm;
use App\Filament\Blogger\Resources\BlogTags\Schemas\BlogTagInfolist;
use App\Filament\Blogger\Resources\BlogTags\Tables\BlogTagsTable;
use App\Models\BlogTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BlogTagResource extends Resource
{
    use HasTranslatableRecordTitle;

    protected static ?string $model = BlogTag::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?string $navigationLabel = 'Tag';

    protected static ?string $modelLabel = 'Tag Blog';

    protected static ?string $pluralModelLabel = 'Tag Blog';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return BlogTagForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BlogTagInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogTagsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogTags::route('/'),
            'create' => CreateBlogTag::route('/create'),
            'view' => ViewBlogTag::route('/{record}'),
            'edit' => EditBlogTag::route('/{record}/edit'),
        ];
    }
}
