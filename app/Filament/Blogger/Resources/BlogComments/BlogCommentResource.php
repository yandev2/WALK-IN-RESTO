<?php

namespace App\Filament\Blogger\Resources\BlogComments;

use App\Filament\Blogger\Resources\BlogComments\Pages\CreateBlogComment;
use App\Filament\Blogger\Resources\BlogComments\Pages\EditBlogComment;
use App\Filament\Blogger\Resources\BlogComments\Pages\ListBlogComments;
use App\Filament\Blogger\Resources\BlogComments\Pages\ViewBlogComment;
use App\Filament\Blogger\Resources\BlogComments\Schemas\BlogCommentForm;
use App\Filament\Blogger\Resources\BlogComments\Schemas\BlogCommentInfolist;
use App\Filament\Blogger\Resources\BlogComments\Tables\BlogCommentsTable;
use App\Models\BlogComment;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class BlogCommentResource extends Resource
{
    protected static ?string $model = BlogComment::class;

    protected static ?string $recordTitleAttribute = 'author_name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?string $navigationLabel = 'Komentar';

    protected static ?string $modelLabel = 'Komentar Blog';

    protected static ?string $pluralModelLabel = 'Komentar Blog';

    protected static ?int $navigationSort = 4;

    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return false;
        }

        if ($user->isPlatformOperator()) {
            return true;
        }

        return (int) $record->blogPost?->author_id === (int) $user->id;
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return false;
        }

        if ($user->isPlatformOperator()) {
            return true;
        }

        return (int) $record->blogPost?->author_id === (int) $user->id;
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

        return (int) $record->blogPost?->author_id === (int) $user->id;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->isPlatformOperator()) {
            $query->whereHas('blogPost', function (Builder $postQuery) use ($user): void {
                $postQuery->where('author_id', $user->id);
            });
        }

        return $query;
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        $query = parent::getRecordRouteBindingEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->isPlatformOperator()) {
            $query->whereHas('blogPost', function (Builder $postQuery) use ($user): void {
                $postQuery->where('author_id', $user->id);
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return BlogCommentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BlogCommentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogCommentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogComments::route('/'),
            'create' => CreateBlogComment::route('/create'),
            'view' => ViewBlogComment::route('/{record}'),
            'edit' => EditBlogComment::route('/{record}/edit'),
        ];
    }
}
