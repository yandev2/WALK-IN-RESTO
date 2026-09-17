<?php

namespace App\Filament\Blogger\Resources\BlogCategories;

use App\Filament\Blogger\Concerns\HasTranslatableRecordTitle;
use App\Filament\Blogger\Resources\BlogCategories\Pages\CreateBlogCategory;
use App\Filament\Blogger\Resources\BlogCategories\Pages\EditBlogCategory;
use App\Filament\Blogger\Resources\BlogCategories\Pages\ListBlogCategories;
use App\Filament\Blogger\Resources\BlogCategories\Pages\ViewBlogCategory;
use App\Filament\Blogger\Resources\BlogCategories\Schemas\BlogCategoryForm;
use App\Filament\Blogger\Resources\BlogCategories\Schemas\BlogCategoryInfolist;
use App\Filament\Blogger\Resources\BlogCategories\Tables\BlogCategoriesTable;
use App\Models\BlogCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class BlogCategoryResource extends Resource
{
    use HasTranslatableRecordTitle;

    protected static ?string $model = BlogCategory::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'Kategori Blog';

    protected static ?string $pluralModelLabel = 'Kategori Blog';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BlogCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BlogCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->isPlatformOperator() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->isPlatformOperator() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->isPlatformOperator() ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogCategories::route('/'),
            'create' => CreateBlogCategory::route('/create'),
            'view' => ViewBlogCategory::route('/{record}'),
            'edit' => EditBlogCategory::route('/{record}/edit'),
        ];
    }
}
