<?php

namespace App\Filament\Resources\CustomerReviews;

use App\Filament\Resources\CustomerReviews\Pages\ListCustomerReviews;
use App\Models\Order;
use App\Models\RestaurantReview;
use App\Models\User;
use App\Support\CmsMedia;
use App\Support\SubscriptionAccess;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use UnitEnum;

class CustomerReviewResource extends Resource
{
    protected static ?string $model = RestaurantReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|UnitEnum|null $navigationGroup = 'CRM & Pelanggan';

    protected static ?string $navigationLabel = 'Ulasan & Rating';

    protected static ?string $pluralModelLabel = 'ulasan pelanggan';

    protected static ?string $modelLabel = 'ulasan';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isPlatformOperator() || $user->isRestaurantOwner() || $user->can('order.verify_payment'))
            && SubscriptionAccess::allows('crm');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('submitted_at')
                    ->label('Waktu Ulasan')
                    ->dateTime('d M Y, H:i')
                    ->description(fn (RestaurantReview $record): string => $record->submitted_at ? $record->submitted_at->diffForHumans() : '-')
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->formatStateUsing(fn (RestaurantReview $record): string => $record->displayName())
                    ->description(fn (RestaurantReview $record): ?string => $record->customerPhone())
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (RestaurantReview $record): string => $record->starsString().' ('.$record->rating.'/5)')
                    ->badge()
                    ->color(fn (RestaurantReview $record): string => $record->sentimentColor())
                    ->sortable(),

                TextColumn::make('sentiment')
                    ->label('Sentimen')
                    ->state(fn (RestaurantReview $record): string => $record->sentimentLabel())
                    ->badge()
                    ->color(fn (RestaurantReview $record): string => $record->sentimentColor()),

                TextColumn::make('comment')
                    ->label('Ulasan / Masukan')
                    ->limit(65)
                    ->tooltip(fn (RestaurantReview $record): ?string => strlen($record->comment) > 65 ? $record->comment : null)
                    ->searchable()
                    ->wrap(),

                TextColumn::make('visit.diningTable.name')
                    ->label('Meja')
                    ->default('-')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('order.grand_payable')
                    ->label('Belanja')
                    ->formatStateUsing(fn ($state): string => $state ? CmsMedia::formatIdr((int) $state) : '-')
                    ->sortable()
                    ->alignEnd(),

                ToggleColumn::make('is_published')
                    ->label('Tayang di Web')
                    ->tooltip('Aktifkan untuk menampilkan ulasan ini pada landing page publik resto'),

                IconColumn::make('has_notes')
                    ->label('Catatan')
                    ->state(fn (RestaurantReview $record): bool => filled($record->internal_notes))
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedClipboardDocumentCheck)
                    ->falseIcon(Heroicon::OutlinedMinus)
                    ->trueColor('info')
                    ->falseColor('gray')
                    ->tooltip(fn (RestaurantReview $record): ?string => $record->internal_notes ?: 'Belum ada catatan internal'),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->label('Bintang Rating')
                    ->options([
                        5 => '⭐⭐⭐⭐⭐ (5 Bintang)',
                        4 => '⭐⭐⭐⭐ (4 Bintang)',
                        3 => '⭐⭐⭐ (3 Bintang)',
                        2 => '⭐⭐ (2 Bintang)',
                        1 => '⭐ (1 Bintang)',
                    ]),

                SelectFilter::make('sentiment')
                    ->label('Klasifikasi Sentimen')
                    ->options([
                        'positive' => 'Puas (⭐ 4–5)',
                        'neutral' => 'Netral (⭐ 3)',
                        'critical' => 'Perlu Perhatian (⭐ 1–2)',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'positive' => $query->where('rating', '>=', 4),
                            'neutral' => $query->where('rating', 3),
                            'critical' => $query->where('rating', '<=', 2),
                            default => $query,
                        };
                    }),

                SelectFilter::make('is_published')
                    ->label('Status Publikasi Web')
                    ->options([
                        '1' => 'Ditayangkan di Landing Page',
                        '0' => 'Disembunyikan dari Publik',
                    ]),
            ])
            ->recordActions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('info')
                    ->modalHeading(fn (RestaurantReview $record): string => 'Detail Ulasan & Sesi Meja — '.$record->displayName())
                    ->modalWidth('2xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->form(fn (RestaurantReview $record): array => [
                        Placeholder::make('summary')
                            ->label('Informasi Ulasan')
                            ->content(function () use ($record) {
                                $stars = $record->starsString();
                                $sentiment = $record->sentimentLabel();
                                $color = $record->sentimentColor();
                                $phone = $record->customerPhone() ?: '-';
                                $table = $record->visit?->diningTable?->name ?? '-';
                                $orderTotal = $record->order?->grand_payable ? CmsMedia::formatIdr((int) $record->order->grand_payable) : '-';
                                $date = $record->submitted_at?->translatedFormat('d F Y, H:i') ?? '-';

                                return new HtmlString("
                                    <div class='space-y-3 text-sm'>
                                        <div class='flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60'>
                                            <div>
                                                <div class='text-base font-bold text-slate-900 dark:text-white'>{$record->displayName()}</div>
                                                <div class='text-xs text-slate-500'>WhatsApp: <span class='font-mono font-medium text-slate-700 dark:text-slate-300'>{$phone}</span></div>
                                            </div>
                                            <div class='text-right'>
                                                <div class='text-lg'>{$stars}</div>
                                                <div class='text-xs font-semibold text-slate-600 dark:text-slate-400'>Kategori: <span class='uppercase font-bold text-{$color}-600'>{$sentiment}</span></div>
                                            </div>
                                        </div>

                                        <div class='grid grid-cols-3 gap-2 text-center text-xs'>
                                            <div class='p-2.5 rounded-lg bg-slate-100 dark:bg-slate-800'>
                                                <div class='text-slate-400'>Meja</div>
                                                <div class='font-bold text-slate-800 dark:text-slate-200 mt-0.5'>{$table}</div>
                                            </div>
                                            <div class='p-2.5 rounded-lg bg-slate-100 dark:bg-slate-800'>
                                                <div class='text-slate-400'>Total Belanja</div>
                                                <div class='font-bold text-slate-800 dark:text-slate-200 mt-0.5'>{$orderTotal}</div>
                                            </div>
                                            <div class='p-2.5 rounded-lg bg-slate-100 dark:bg-slate-800'>
                                                <div class='text-slate-400'>Waktu Kunjungan</div>
                                                <div class='font-bold text-slate-800 dark:text-slate-200 mt-0.5'>{$date}</div>
                                            </div>
                                        </div>

                                        <div class='p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900'>
                                            <div class='text-xs font-bold text-slate-500 uppercase tracking-wider mb-1'>Komentar / Masukan Tamu:</div>
                                            <p class='text-slate-700 dark:text-slate-300 text-sm leading-relaxed italic'>
                                                \"{$record->comment}\"
                                            </p>
                                        </div>

                                        ".(filled($record->internal_notes) ? "
                                        <div class='p-3 rounded-xl border border-amber-200 dark:border-amber-800/60 bg-amber-50/60 dark:bg-amber-950/20'>
                                            <div class='text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider mb-1'>Catatan Internal Staf:</div>
                                            <p class='text-slate-700 dark:text-slate-300 text-xs leading-relaxed'>
                                                {$record->internal_notes}
                                            </p>
                                        </div>
                                        " : '').'
                                    </div>
                                ');
                            }),
                    ]),

                Action::make('chatWa')
                    ->label('WhatsApp')
                    ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                    ->color('success')
                    ->modalHeading(fn (RestaurantReview $record): string => 'Kirim Pesan WhatsApp ke '.$record->displayName())
                    ->modalDescription('Anda dapat mengedit dan menyesuaikan isi pesan WhatsApp di bawah ini sebelum aplikasi WhatsApp dibuka.')
                    ->modalSubmitActionLabel('Buka WhatsApp Sekarang')
                    ->visible(fn (RestaurantReview $record): bool => filled($record->customerPhone()))
                    ->fillForm(fn (RestaurantReview $record): array => [
                        'phone' => $record->customerPhone(),
                        'message' => $record->defaultWaFollowUpMessage(),
                    ])
                    ->form([
                        Placeholder::make('info')
                            ->label('Nomor Tujuan')
                            ->content(fn (RestaurantReview $record): string => $record->displayName().' ('.$record->customerPhone().') — Rating: '.$record->starsString()),

                        Textarea::make('message')
                            ->label('Pesan WhatsApp (Bisa Disesuaikan)')
                            ->rows(5)
                            ->required()
                            ->helperText('Draf otomatis di atas telah disesuaikan berdasarkan rating ulasan pelanggan. Anda bebas mengubah pesan sesuai kebutuhan.'),
                    ])
                    ->action(function (RestaurantReview $record, array $data, Component $livewire): void {
                        $url = $record->waLink((string) $data['message']);

                        if ($url) {
                            $livewire->js("window.open('".addslashes($url)."', '_blank')");

                            Notification::make()
                                ->title('Membuka WhatsApp...')
                                ->body('Tautan chat telah dibuka di tab baru.')
                                ->actions([
                                    Action::make('openAgain')
                                        ->label('Buka Ulang Chat')
                                        ->url($url)
                                        ->openUrlInNewTab(),
                                ])
                                ->success()
                                ->send();
                        }
                    }),

                Action::make('editNotes')
                    ->label('Catatan')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->color('warning')
                    ->modalHeading(fn (RestaurantReview $record): string => 'Catatan Internal Tindak Lanjut — '.$record->displayName())
                    ->fillForm(fn (RestaurantReview $record): array => [
                        'internal_notes' => $record->internal_notes,
                    ])
                    ->form([
                        Textarea::make('internal_notes')
                            ->label('Catatan Staf / Resolusi Keluhan')
                            ->rows(4)
                            ->placeholder('Tuliskan langkah tindak lanjut yang telah dilakukan (misal: telah dihubungi via WA, keluhan telah diselesaikan oleh Chef, dsb)...')
                            ->helperText('Catatan ini hanya terlihat oleh manajemen internal restoran dan tidak ditampilkan ke pelanggan.'),
                    ])
                    ->action(function (RestaurantReview $record, array $data): void {
                        $record->update([
                            'internal_notes' => $data['internal_notes'],
                        ]);

                        Notification::make()
                            ->title('Catatan internal disimpan')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('submitted_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerReviews::route('/'),
        ];
    }
}
