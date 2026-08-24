<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ExportFiles\ExportFileResource;
use App\Models\ExportFile;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\Export\ReportExportDispatcher;
use App\Support\RestaurantAnalyticsPeriod;
use App\Support\SubscriptionAccess;
use App\Support\TenantContext;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class GenerateReport extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Buat laporan';

    protected static ?string $title = 'Buat laporan';

    protected static ?string $slug = 'laporan';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin() || $user->can('analytics.view'))
            && SubscriptionAccess::allows('analytics');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('history')
                ->label('Riwayat ekspor')
                ->icon(Heroicon::OutlinedClock)
                ->url(ExportFileResource::getUrl('index')),
        ];
    }

    public function mount(): void
    {
        $restaurant = $this->restaurant();
        abort_unless($restaurant instanceof Restaurant, 404);

        $range = RestaurantAnalyticsPeriod::defaultLocalDateRange($restaurant);

        $this->form->fill([
            'module' => ExportFile::MODULE_OMZET_HARIAN,
            'format' => ExportFile::FORMAT_EXCEL,
            'date_from' => $range['from']->toDateString(),
            'date_to' => $range['to']->toDateString(),
            'category_id' => null,
            'is_active' => null,
            'is_out_of_stock' => null,
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Jenis laporan')
                    ->description('File diproses di antrean. Unduh dari Riwayat ekspor setelah selesai.')
                    ->columns(4)
                    ->schema([
                        Select::make('module')
                            ->label('Modul')
                            ->options(ExportFile::moduleLabels())
                            ->required()
                            ->live()
                            ->columnSpan(3)
                            ->native(false),
                        ToggleButtons::make('format')
                            ->label('Format')
                            ->options([
                                ExportFile::FORMAT_EXCEL => 'Excel',
                                ExportFile::FORMAT_PDF => 'PDF',
                            ])
                            ->inline()
                            ->required(),
                    ]),
                Section::make('Periode')
                    ->visible(fn (Get $get): bool => $get('module') !== ExportFile::MODULE_KATALOG_MENU)
                    ->columns(2)
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Dari')
                            ->native(false)
                            ->format('Y-m-d')
                            ->displayFormat('d M Y')
                            ->required()
                            ->maxDate(fn () => $this->maxSelectableDate()),
                        DatePicker::make('date_to')
                            ->label('Sampai')
                            ->native(false)
                            ->format('Y-m-d')
                            ->displayFormat('d M Y')
                            ->required()
                            ->maxDate(fn () => $this->maxSelectableDate())
                            ->afterOrEqual('date_from'),
                    ]),
                Section::make('Filter katalog menu')
                    ->visible(fn (Get $get): bool => $get('module') === ExportFile::MODULE_KATALOG_MENU)
                    ->columns(3)
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->placeholder('Semua kategori')
                            ->native(false)
                            ->options(fn (): array => MenuCategory::query()
                                ->forRestaurant(TenantContext::restaurantId() ?? 0)
                                ->orderBy('sort_order')
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable(),
                        Select::make('is_active')
                            ->label('Status aktif')
                            ->placeholder('Semua')
                            ->native(false)
                            ->options([
                                '1' => 'Aktif',
                                '0' => 'Nonaktif',
                            ]),
                        Select::make('is_out_of_stock')
                            ->label('Stok')
                            ->placeholder('Semua')
                            ->native(false)
                            ->options([
                                '0' => 'Ready',
                                '1' => 'Habis',
                            ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('generate')
                    ->footer([
                        Actions::make([
                            Action::make('generate')
                                ->label('Antrikan ekspor')
                                ->icon(Heroicon::OutlinedArrowDownTray)
                                ->submit('generate'),
                        ]),
                    ]),
            ]);
    }

    public function generate(): void
    {
        $restaurant = $this->restaurant();
        $user = auth()->user();

        abort_unless($restaurant instanceof Restaurant, 404);
        abort_unless($user instanceof User, 403);

        $state = $this->form->getState();

        try {
            $export = app(ReportExportDispatcher::class)->dispatch(
                $restaurant,
                $user,
                (string) $state['module'],
                (string) $state['format'],
                [
                    'date_from' => $state['date_from'] ?? null,
                    'date_to' => $state['date_to'] ?? null,
                    'category_id' => $state['category_id'] ?? null,
                    'is_active' => $state['is_active'] ?? null,
                    'is_out_of_stock' => $state['is_out_of_stock'] ?? null,
                ],
            );
        } catch (ValidationException $exception) {
            Notification::make()
                ->title('Tidak bisa membuat laporan')
                ->body(collect($exception->errors())->flatten()->first() ?: 'Validasi gagal.')
                ->danger()
                ->send();

            return;
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Gagal membuat laporan')
                ->body('Ekspor gagal diproses. Coba lagi atau cek Riwayat ekspor.')
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Ekspor diantrikan')
            ->body('File akan muncul di Riwayat ekspor setelah selesai diproses.')
            ->success()
            ->actions([
                Action::make('openHistory')
                    ->label('Buka riwayat')
                    ->url(ExportFileResource::getUrl('index')),
            ])
            ->send();

        unset($export);
    }

    protected function restaurant(): ?Restaurant
    {
        $tenant = Filament::getTenant();

        return $tenant instanceof Restaurant ? $tenant : null;
    }

    /**
     * Use end-of-day so "today" passes before_or_equal (date-only max is midnight and rejects same day).
     */
    protected function maxSelectableDate(): Carbon
    {
        $restaurant = $this->restaurant();

        if ($restaurant instanceof Restaurant) {
            return RestaurantAnalyticsPeriod::localToday($restaurant)->endOfDay();
        }

        return now('Asia/Jakarta')->endOfDay();
    }
}
