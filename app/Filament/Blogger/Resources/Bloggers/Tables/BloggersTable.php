<?php

namespace App\Filament\Blogger\Resources\Bloggers\Tables;

use App\Filament\Support\TableRightClick;
use App\Models\User;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BloggersTable
{
    public static function configure(Table $table): Table
    {
        $table = $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),

                ImageColumn::make('avatar_path')
                    ->label('Avatar')
                    ->disk('public')
                    ->circular()
                    ->imageSize(40)
                    ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF'),

                TextColumn::make('name')
                    ->label('Nama Penulis')
                    ->weight('semibold')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('username')
                    ->label('Username')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Username berhasil disalin'),

                TextColumn::make('email')
                    ->label('Email')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email berhasil disalin'),

                TextColumn::make('blog_posts_count')
                    ->label('Total Artikel')
                    ->numeric()
                    ->badge()
                    ->color('info')
                    ->alignEnd()
                    ->sortable()
                    ->default(0),

                TextColumn::make('total_views_count')
                    ->label('Total Views')
                    ->numeric()
                    ->badge()
                    ->color('success')
                    ->default(0)
                    ->alignEnd()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Status Akun')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Nonaktif',
                    ]),

                SelectFilter::make('has_posts')
                    ->label('Status Konten')
                    ->options([
                        'has_posts' => 'Sudah Menulis Artikel',
                        'no_posts' => 'Belum Ada Artikel',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (($data['value'] ?? null) === 'has_posts') {
                            return $query->has('blogPosts');
                        }

                        if (($data['value'] ?? null) === 'no_posts') {
                            return $query->doesntHave('blogPosts');
                        }

                        return $query;
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Aktifkan Penulis Terpilih')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(fn (User $user) => $user->update(['is_active' => true]));
                            Notification::make()
                                ->title('Penulis berhasil diaktifkan')
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('deactivate')
                        ->label('Nonaktifkan Penulis Terpilih')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $currentUserId = auth()->id();
                            $filtered = $records->reject(fn (User $user) => $user->id === $currentUserId);
                            $filtered->each(fn (User $user) => $user->update(['is_active' => false]));

                            Notification::make()
                                ->title('Penulis terpilih telah dinonaktifkan')
                                ->success()
                                ->send();
                        }),

                    DeleteBulkAction::make()
                        ->action(function (Collection $records): void {
                            $currentUserId = auth()->id();
                            $records
                                ->reject(fn (User $user) => $user->id === $currentUserId)
                                ->each(fn (User $user) => $user->delete());

                            Notification::make()
                                ->title('Penulis terpilih telah dihapus')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Penulis Blog')
            ->emptyStateDescription('Tambahkan akun penulis untuk mulai mempublikasikan artikel di blog.')
            ->emptyStateIcon(Heroicon::OutlinedUsers);

        return TableRightClick::apply($table, fn (): array => [
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make()
                ->visible(fn (User $record): bool => auth()->id() !== $record->id && (auth()->user()?->isPlatformOperator() ?? false)),
        ]);
    }
}
