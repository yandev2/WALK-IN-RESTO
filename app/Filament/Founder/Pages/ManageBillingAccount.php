<?php

namespace App\Filament\Founder\Pages;

use App\Models\PlatformSetting;
use App\Models\User;
use App\Support\CmsMedia;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageBillingAccount extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Langganan';

    protected static ?string $navigationLabel = 'Rekening pembayaran';

    protected static ?string $title = 'Rekening pembayaran';

    protected static ?int $navigationSort = 5;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->isPlatformOperator();
    }

    public function mount(): void
    {
        $setting = PlatformSetting::current();

        $this->form->fill([
            ...$setting->only([
                'bank_name',
                'bank_holder',
                'bank_account',
                'qr_image_path',
            ]),
            'trial_days' => PlatformSetting::trialDays(),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Masa uji coba')
                    ->description('Berlaku untuk restoran baru saat daftar publik atau dibuat Founder. Tenant yang sudah berjalan tidak berubah.')
                    ->icon(Heroicon::OutlinedClock)
                    ->schema([
                        TextInput::make('trial_days')
                            ->label('Durasi trial')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(365)
                            ->suffix('hari')
                            ->default(fn (): int => PlatformSetting::trialDays()),
                    ]),
                Grid::make(3)
                    ->schema([
                        Section::make('Rekening transfer')
                            ->description('Ditampilkan ke owner saat membuat invoice langganan.')
                            ->icon(Heroicon::OutlinedBuildingLibrary)
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('bank_name')
                                    ->label('Nama bank')
                                    ->placeholder('BCA')
                                    ->required()
                                    ->maxLength(40)
                                    ->live(onBlur: true),
                                TextInput::make('bank_holder')
                                    ->label('Nama rekening')
                                    ->placeholder('PT Resto Terdekat')
                                    ->required()
                                    ->maxLength(120)
                                    ->live(onBlur: true),
                                TextInput::make('bank_account')
                                    ->label('Nomor rekening')
                                    ->placeholder('0000000000')
                                    ->required()
                                    ->maxLength(40)
                                    ->extraInputAttributes(['class' => 'font-mono tracking-wide'])
                                    ->columnSpanFull()
                                    ->live(onBlur: true),
                            ]),
                        Section::make('QR pembayaran')
                            ->description('Scan QR saat transfer. Kosongkan untuk memakai placeholder.')
                            ->icon(Heroicon::OutlinedQrCode)
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('qr_image_path')
                                    ->hiddenLabel()
                                    ->image()
                                    ->imageAspectRatio('1:1')
                                    ->automaticallyCropImagesToAspectRatio()
                                    ->automaticallyResizeImagesMode('cover')
                                    ->automaticallyResizeImagesToWidth('1000')
                                    ->automaticallyResizeImagesToHeight('1000')
                                    ->automaticallyUpscaleImagesWhenResizing(false)
                                    ->imageEditor()
                                    ->imageEditorAspectRatios(['1:1'])
                                    ->imagePreviewHeight('180')
                                    ->panelLayout('compact')
                                    ->directory('platform/qr')
                                    ->disk('public')
                                    ->maxSize(15360)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->helperText('Format: JPG, PNG, WEBP. Kode QR transfer rasio 1:1 persegi (maks. 15 MB).')
                                    ->live(),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $setting = PlatformSetting::current();
        $state = $this->form->getState();
        $qrPath = $state['qr_image_path'] ?? null;

        if (is_array($qrPath)) {
            $state['qr_image_path'] = $qrPath[array_key_first($qrPath)] ?? null;
        }

        $setting->update($state);

        Notification::make()
            ->title('Rekening pembayaran disimpan')
            ->success()
            ->send();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Simpan')
                                ->submit('save'),
                        ]),
                    ]),
                Section::make('Pratinjau widget owner')
                    ->description('Tampilan yang sama dengan kartu transfer di halaman Langganan.')
                    ->icon(Heroicon::OutlinedEye)
                    ->schema([
                        SchemaView::make('filament.pages.partials.subscription-transfer-widget')
                            ->viewData(fn (): array => $this->previewBillingData()),
                    ]),
            ]);
    }

    /**
     * @return array{bankName: string, bankAccount: string, bankHolder: string, contactEmail: string|null, qrUrl: string}
     */
    private function previewBillingData(): array
    {
        $saved = PlatformSetting::billingViewData();
        $qrPath = $this->data['qr_image_path'] ?? null;

        if (is_array($qrPath)) {
            $qrPath = $qrPath[array_key_first($qrPath)] ?? null;
        }

        return [
            'bankName' => filled($this->data['bank_name'] ?? null) ? (string) $this->data['bank_name'] : $saved['bankName'],
            'bankHolder' => filled($this->data['bank_holder'] ?? null) ? (string) $this->data['bank_holder'] : $saved['bankHolder'],
            'bankAccount' => filled($this->data['bank_account'] ?? null) ? (string) $this->data['bank_account'] : $saved['bankAccount'],
            'contactEmail' => $saved['contactEmail'],
            'qrUrl' => CmsMedia::url(is_string($qrPath) ? $qrPath : null) ?: $saved['qrUrl'],
        ];
    }
}
