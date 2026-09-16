<?php

namespace App\Filament\Blogger\Resources\BlogPosts;

use App\Filament\Blogger\Concerns\HasTranslatableRecordTitle;
use App\Filament\Blogger\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Blogger\Resources\BlogPosts\Pages\EditBlogPost;
use App\Filament\Blogger\Resources\BlogPosts\Pages\ListBlogPosts;
use App\Filament\Blogger\Resources\BlogPosts\Pages\ViewBlogPost;
use App\Filament\Blogger\Resources\BlogPosts\RelationManagers\CommentsRelationManager;
use App\Filament\Blogger\Resources\BlogPosts\Schemas\BlogPostForm;
use App\Filament\Blogger\Resources\BlogPosts\Schemas\BlogPostInfolist;
use App\Filament\Blogger\Resources\BlogPosts\Tables\BlogPostsTable;
use App\Models\BlogPost;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BlogPostResource extends Resource
{
    use HasTranslatableRecordTitle;

    protected static ?string $model = BlogPost::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?string $navigationLabel = 'Artikel';

    protected static ?string $modelLabel = 'Artikel Blog';

    protected static ?string $pluralModelLabel = 'Artikel Blog';

    protected static ?int $navigationSort = 1;

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return false;
        }

        if ($user->isPlatformOperator()) {
            return true;
        }

        return (int) $record->author_id === (int) $user->id;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return false;
        }

        if ($user->isPlatformOperator()) {
            return true;
        }

        return (int) $record->author_id === (int) $user->id;
    }

    public static function canForceDelete(Model $record): bool
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return false;
        }

        return $user->isPlatformOperator();
    }

    public static function form(Schema $schema): Schema
    {
        return BlogPostForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BlogPostInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogPostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'view' => ViewBlogPost::route('/{record}'),
            'edit' => EditBlogPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        BlogPost::publishDueScheduled();

        return parent::getEloquentQuery();
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        BlogPost::publishDueScheduled();

        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
