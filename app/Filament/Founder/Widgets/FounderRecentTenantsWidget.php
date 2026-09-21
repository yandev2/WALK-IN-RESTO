<?php

namespace App\Filament\Founder\Widgets;

use App\Enums\SubscriptionStatus;
use App\Filament\Founder\Resources\Tenants\TenantResource;
use App\Models\Restaurant;
use App\Services\FounderAnalyticsService;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class FounderRecentTenantsWidget extends BaseTableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'vision-table-card'])
            ->heading('Restoran Baru Terdaftar')
            ->description('Tenant terbaru yang baru bergabung di platform')
            ->query(app(FounderAnalyticsService::class)->getRecentTenantsQuery()->limit(6))
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn (Restaurant $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=F97316&background=FFEDD5'),

                TextColumn::make('name')
                    ->label('Nama Resto')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Restaurant $record): string => '@'.$record->slug)
                    ->url(fn (Restaurant $record): string => TenantResource::getUrl('edit', ['record' => $record])),

                TextColumn::make('subscription_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof SubscriptionStatus ? $state->label() : (string) $state)
                    ->color(fn ($state) => $state instanceof SubscriptionStatus ? $state->color() : 'gray'),

                TextColumn::make('created_at')
                    ->label('Daftar')
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Restaurant $record): string => TenantResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
