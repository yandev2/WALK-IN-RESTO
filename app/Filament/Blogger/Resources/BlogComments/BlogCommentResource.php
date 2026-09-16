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
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
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
