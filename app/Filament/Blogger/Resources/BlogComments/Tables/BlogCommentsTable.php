<?php

namespace App\Filament\Blogger\Resources\BlogComments\Tables;

use App\Enums\BlogCommentStatus;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Support\FilamentTranslatable;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BlogCommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['blogPost.translations']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),
                TextColumn::make('blogPost.title')
                    ->label('Artikel')
                    ->getStateUsing(fn (BlogComment $record): string => $record->blogPost ? FilamentTranslatable::label($record->blogPost, 'title') : "Post #{$record->blog_post_id}")
                    ->weight('medium')
                    ->wrap()
                    ->tooltip(fn (BlogComment $record): string => $record->blogPost ? FilamentTranslatable::label($record->blogPost, 'title') : ''),
                TextColumn::make('author_name')
                    ->label('Pengirim')
                    ->weight('medium')
                    ->description(fn (BlogComment $record): ?string => $record->author_email)
                    ->icon(fn (BlogComment $record): ?string => $record->is_author_reply ? 'heroicon-m-check-badge' : null)
                    ->iconColor('primary')
                    ->searchable(['author_name', 'author_email']),
                TextColumn::make('content')
                    ->label('Isi Komentar')
                    ->wrap()
                    ->limit(80),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->description(fn (BlogComment $record): ?string => $record->created_at?->format('H:i'))
                    ->sortable(),
                TextColumn::make('parent_id')
                    ->label('Balasan Dari')
                    ->formatStateUsing(fn (?int $state): string => $state ? '#'.$state : 'Utama')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author_email')
                    ->label('Email Pengirim')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_author_reply')
                    ->label('Penulis')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Moderasi')
                    ->options(BlogCommentStatus::class),
            ])
            ->recordActions([
                Action::make('reply')
                    ->label('Balas')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->iconButton()
                    ->tooltip('Balas Komentar')
                    ->color('primary')
                    ->visible(fn (BlogComment $record): bool => $record->parent_id === null && (auth()->user()?->isPlatformOperator() || (int) $record->blogPost?->author_id === (int) auth()->id()))
                    ->schema([
                        TextInput::make('author_name')
                            ->label('Nama Penulis Balasan')
                            ->default(fn (): string => auth()->user()?->name ?? 'Penulis')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('content')
                            ->label('Isi Balasan')
                            ->required()
                            ->rows(4),
                    ])
                    ->action(function (BlogComment $record, array $data): void {
                        BlogComment::query()->create([
                            'blog_post_id' => $record->blog_post_id,
                            'parent_id' => $record->id,
                            'is_author_reply' => true,
                            'author_name' => $data['author_name'],
                            'author_email' => auth()->user()?->email,
                            'content' => $data['content'],
                            'status' => BlogCommentStatus::Approved,
                            'ip_address' => request()->ip() ?? '127.0.0.1',
                            'user_agent' => request()->userAgent() ?? 'Filament Blogger',
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                        ]);
                    }),
                ViewAction::make()
                    ->iconButton()
                    ->tooltip('Lihat Komentar'),
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Ubah Komentar')
                    ->visible(fn (BlogComment $record): bool => auth()->user()?->isPlatformOperator() || (int) $record->blogPost?->author_id === (int) auth()->id()),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus Komentar')
                    ->visible(fn (BlogComment $record): bool => auth()->user()?->isPlatformOperator() || (int) $record->blogPost?->author_id === (int) auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn ($record) => $record->update([
                            'status' => BlogCommentStatus::Approved,
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                        ]))),
                    BulkAction::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn ($record) => $record->update([
                            'status' => BlogCommentStatus::Rejected,
                            'approved_at' => null,
                            'approved_by' => null,
                        ]))),
                    BulkAction::make('spam')
                        ->label('Tandai Spam')
                        ->icon('heroicon-o-no-symbol')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn ($record) => $record->update([
                            'status' => BlogCommentStatus::Spam,
                            'approved_at' => null,
                            'approved_by' => null,
                        ]))),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
