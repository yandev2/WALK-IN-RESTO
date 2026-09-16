<?php

namespace App\Filament\Blogger\Widgets;

use App\Models\User;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopBloggersWidget extends BaseTableWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 'full';

    public function __lazyLoad(): void
    {
    }

    public static function canView(): bool
    {
        return auth()->user()?->isPlatformOperator() ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'vision-table-card'])
            ->heading('Top 5 Penulis Terpopuler & Paling Berkontribusi')
            ->description('Penulis dengan kontribusi trafik (views) dan karya artikel terbanyak di blog')
            ->query(
                User::query()
                    ->where(function (Builder $q) {
                        $q->whereHas('blogPosts')
                            ->orWhere(fn (Builder $sq) => $sq->whereHasGlobalRole('blogger'));
                    })
                    ->withCount('blogPosts')
                    ->withSum('blogPosts as total_views', 'views_count')
                    ->withSum('blogPosts as total_likes', 'likes_count')
                    ->orderByDesc('total_views')
                    ->orderByDesc('blog_posts_count')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('Peringkat')
                    ->width('sm')
                    ->rowIndex()
                    ->formatStateUsing(fn ($state) => "#{$state}")
                    ->badge()
                    ->color(fn ($state): string => match ((int) $state) {
                        1 => 'warning',
                        2 => 'gray',
                        3 => 'danger',
                        default => 'primary',
                    }),

                ImageColumn::make('avatar_path')
                    ->label('Avatar')
                    ->disk('public')
                    ->circular()
                    ->imageSize(40)
                    ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF'),

                TextColumn::make('name')
                    ->label('Nama Penulis')
                    ->weight('medium')
                    ->description(fn (User $record): string => $record->email),

                TextColumn::make('blog_posts_count')
                    ->label('Total Artikel')
                    ->numeric()
                    ->badge()
                    ->color('info')
                    ->alignEnd(),

                TextColumn::make('total_views')
                    ->label('Total Trafik (Views)')
                    ->numeric()
                    ->badge()
                    ->color('success')
                    ->default(0)
                    ->alignEnd(),

                TextColumn::make('total_likes')
                    ->label('Likes')
                    ->numeric()
                    ->badge()
                    ->color('warning')
                    ->default(0)
                    ->alignEnd(),

                TextColumn::make('avg_views')
                    ->label('Rata-rata Views / Artikel')
                    ->getStateUsing(function (User $record): string {
                        $posts = (int) ($record->blog_posts_count ?? 0);
                        $views = (int) ($record->total_views ?? 0);
                        if ($posts <= 0) {
                            return '0 views';
                        }
                        $avg = round($views / $posts, 1);

                        return "{$avg} views";
                    })
                    ->badge()
                    ->color('gray')
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->emptyStateHeading('Belum ada data penulis blog')
            ->emptyStateDescription('Data kontribusi penulis akan otomatis terkalkulasi saat artikel dipublikasikan.');
    }
}
