<?php

namespace App\Filament\Blogger\Resources\BlogPosts\RelationManagers;

use App\Enums\BlogCommentStatus;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Komentar Pembaca';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('author_name')
                    ->label('Nama Pengirim')
                    ->required()
                    ->maxLength(255),
                TextInput::make('author_email')
                    ->label('Email Pengirim')
                    ->email()
                    ->maxLength(255),
                Textarea::make('content')
                    ->label('Isi Komentar')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status Moderasi')
                    ->options(BlogCommentStatus::class)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('author_name')
            ->defaultSort('created_at', 'desc')
            ->columns([
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
                    ->limit(70),
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
                    ->label('Email')
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
                    ->label('Balas Komentar')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->iconButton()
                    ->tooltip('Balas Komentar')
                    ->color('primary')
                    ->visible(fn (BlogComment $record): bool => $record->parent_id === null)
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
                        /** @var BlogPost $post */
                        $post = $this->getOwnerRecord();

                        BlogComment::query()->create([
                            'blog_post_id' => $post->id,
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
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Ubah')
                    ->visible(fn (): bool => auth()->user()?->isPlatformOperator() || (int) $this->getOwnerRecord()?->author_id === (int) auth()->id()),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus')
                    ->visible(fn (): bool => auth()->user()?->isPlatformOperator() || (int) $this->getOwnerRecord()?->author_id === (int) auth()->id()),
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
