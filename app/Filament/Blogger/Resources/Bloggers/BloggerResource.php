<?php

namespace App\Filament\Blogger\Resources\Bloggers;

use App\Filament\Blogger\Resources\Bloggers\Pages\CreateBlogger;
use App\Filament\Blogger\Resources\Bloggers\Pages\EditBlogger;
use App\Filament\Blogger\Resources\Bloggers\Pages\ListBloggers;
use App\Filament\Blogger\Resources\Bloggers\Pages\ViewBlogger;
use App\Filament\Blogger\Resources\Bloggers\RelationManagers\BlogPostsRelationManager;
use App\Filament\Blogger\Resources\Bloggers\Schemas\BloggerForm;
use App\Filament\Blogger\Resources\Bloggers\Schemas\BloggerInfolist;
use App\Filament\Blogger\Resources\Bloggers\Tables\BloggersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class BloggerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Penulis & Pengguna';

    protected static ?string $navigationLabel = 'Kelola Penulis';

    protected static ?string $modelLabel = 'Penulis Blog';

    protected static ?string $pluralModelLabel = 'Penulis Blog';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->isPlatformOperator() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isPlatformOperator() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->isPlatformOperator() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();
        if (! $user || ! $user->isPlatformOperator()) {
            return false;
        }

        return $record->getKey() !== $user->getKey();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHasGlobalRole('blogger')
            ->withCount('blogPosts')
            ->withSum('blogPosts as total_views_count', 'views_count');
    }

    public static function form(Schema $schema): Schema
    {
        return BloggerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BloggerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BloggersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            BlogPostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBloggers::route('/'),
            'create' => CreateBlogger::route('/create'),
            'view' => ViewBlogger::route('/{record}'),
            'edit' => EditBlogger::route('/{record}/edit'),
        ];
    }
}
