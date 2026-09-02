<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\TemplateRadioPicker;
use App\Models\CmsProfile;
use App\Models\LandingTemplate;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\LandingLayout;
use App\Support\SubscriptionAccess;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageLandingLayout extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Layout landing';

    protected static ?string $title = 'Layout landing';

    protected static ?int $navigationSort = 11;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin() || $user->can('cms.manage'))
            && SubscriptionAccess::allows('cms');
    }

    protected function getHeaderActions(): array
    {
        $tenant = Filament::getTenant();

        return [
            Action::make('viewLanding')
                ->label('Lihat halaman publik')
                ->url(fn (): string => route('landing.show', $tenant))
                ->openUrlInNewTab()
                ->visible(filled($tenant?->slug)),
        ];
    }

    public function mount(): void
    {
        $restaurant = $this->restaurant();

        abort_unless($restaurant instanceof Restaurant, 404);

        $profile = CmsProfile::query()->firstOrCreate(
            ['restaurant_id' => $restaurant->getKey()],
        );

        $this->form->fill([
            'landing_template' => $profile->landing_template ?: LandingTemplate::DEFAULT_TEMPLATE,
            'sections' => LandingLayout::formItems($profile),
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
                Section::make('Pilihan Template Desain')
                    ->description('Pilih desain tampilan landing page publik untuk restoran Anda. Seluruh menu, ulasan, foto, dan informasi asli Anda akan otomatis terpasang.')
                    ->icon(Heroicon::OutlinedPaintBrush)
                    ->schema([
                        TemplateRadioPicker::make('landing_template')
                            ->hiddenLabel()
                            ->required()
                            ->default(LandingTemplate::DEFAULT_TEMPLATE),
                    ]),

                Section::make('Urutan & teks blok')
                    ->description('Geser kartu untuk mengubah urutan di halaman publik. Buka kartu untuk menyunting teks. Field kosong memakai default.')
                    ->icon(Heroicon::OutlinedSquares2x2)
                    ->schema([
                        Repeater::make('sections')
                            ->hiddenLabel()
                            ->schema($this->sectionFields())
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->addable(false)
                            ->deletable(false)
                            ->collapsed()
                            ->compact()
                            ->itemLabel(function (array $state): string {
                                $label = LandingLayout::ADMIN_LABELS[$state['id'] ?? ''] ?? 'Blok';

                                return ($state['enabled'] ?? true)
                                    ? $label
                                    : $label.' · disembunyikan';
                            })
                            ->columns(1),
                    ]),
            ]);
    }

    public function save(): void
    {
        abort_if(SubscriptionAccess::isReadOnly(), 403);
        $restaurant = $this->restaurant();

        abort_unless($restaurant instanceof Restaurant, 404);

        $data = $this->form->getState();
        $payload = LandingLayout::persistFromForm($data['sections'] ?? []);
        $payload['landing_template'] = $data['landing_template'] ?? LandingTemplate::DEFAULT_TEMPLATE;

        $profile = CmsProfile::query()->firstOrCreate(
            ['restaurant_id' => $restaurant->getKey()],
        );

        $old = $profile->only(['landing_template', 'landing_sections', 'landing_copy']);
        $profile->update($payload);

        ActivityLogger::log('cms.update_landing_layout', [
            'old' => $old,
            'new' => $profile->only(['landing_template', 'landing_sections', 'landing_copy']),
        ]);

        Notification::make()
            ->title('Layout & template landing disimpan')
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
            ]);
    }

    /**
     * @return list<Component>
     */
    private function sectionFields(): array
    {
        return [
            Hidden::make('id'),
            Toggle::make('enabled')
                ->label('Tampilkan di landing')
                ->default(true)
                ->live()
                ->inline(),
            Fieldset::make('Navigasi')
                ->visible($this->whenSection('hero', 'banners', 'menu', 'reviews', 'how_to', 'about', 'gallery', 'hours', 'location', 'faq'))
                ->schema([
                    TextInput::make('nav')
                        ->label('Label menu')
                        ->maxLength(40)
                        ->columnSpanFull(),
                ]),
            Fieldset::make('Heading')
                ->visible($this->whenSection('hero', 'banners', 'menu', 'reviews', 'how_to', 'about', 'gallery', 'hours', 'location', 'faq', 'cta'))
                ->columns(2)
                ->schema([
                    TextInput::make('label')
                        ->label('Label kecil')
                        ->maxLength(80)
                        ->visible($this->whenSection('banners', 'menu', 'reviews', 'how_to', 'about', 'gallery', 'hours', 'location', 'faq')),
                    TextInput::make('highlight')
                        ->label('Kata highlight')
                        ->helperText('Harus muncul di judul.')
                        ->maxLength(80)
                        ->visible($this->whenSection('banners', 'menu', 'reviews', 'how_to', 'about', 'gallery', 'location', 'faq')),
                    TextInput::make('title')
                        ->label('Judul')
                        ->maxLength(191)
                        ->helperText(fn (Get $get): ?string => $get('id') === 'cta' ? 'Gunakan {name} untuk nama restoran.' : null)
                        ->visible($this->whenSection('banners', 'menu', 'reviews', 'how_to', 'about', 'gallery', 'hours', 'location', 'faq', 'cta'))
                        ->columnSpanFull(),
                    Textarea::make('subtitle')
                        ->label('Teks pendukung')
                        ->rows(2)
                        ->visible($this->whenSection('hero', 'menu', 'how_to', 'cta'))
                        ->columnSpanFull(),
                ]),
            Fieldset::make('Hero')
                ->visible($this->whenSection('hero'))
                ->columns(2)
                ->schema([
                    TextInput::make('pill')
                        ->label('Pill')
                        ->maxLength(80),
                    TextInput::make('secondary_cta')
                        ->label('Tombol kedua')
                        ->maxLength(80),
                ]),
            Fieldset::make('Tombol')
                ->visible($this->whenSection('banners', 'menu', 'location', 'cta'))
                ->columns(2)
                ->schema([
                    TextInput::make('button')
                        ->label('Teks tombol')
                        ->maxLength(80),
                    TextInput::make('wa_button')
                        ->label('Tombol WhatsApp')
                        ->maxLength(80)
                        ->visible($this->whenSection('cta')),
                ]),
            Fieldset::make('Langkah cara pesan')
                ->visible($this->whenSection('how_to'))
                ->schema([
                    Repeater::make('steps')
                        ->hiddenLabel()
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul')
                                ->required()
                                ->maxLength(80),
                            Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(2)
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columns(1)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->compact()
                        ->itemLabel(fn (array $state): string => $state['title'] ?? 'Langkah'),
                ]),
            Fieldset::make('Footer')
                ->visible($this->whenSection('cta'))
                ->columns(2)
                ->schema([
                    TextInput::make('footer_tagline')
                        ->label('Tagline')
                        ->maxLength(191)
                        ->columnSpanFull(),
                    TextInput::make('footer_home')
                        ->label('Tautan beranda')
                        ->maxLength(40),
                    TextInput::make('footer_nav')
                        ->label('Heading navigasi')
                        ->maxLength(40),
                    TextInput::make('footer_contact')
                        ->label('Heading kontak')
                        ->maxLength(40),
                    TextInput::make('footer_visit')
                        ->label('Heading lokasi')
                        ->maxLength(40),
                    TextInput::make('footer_maps')
                        ->label('Tombol maps')
                        ->maxLength(80)
                        ->columnSpanFull(),
                ]),
        ];
    }

    /**
     * @return \Closure(Get): bool
     */
    private function whenSection(string ...$ids): \Closure
    {
        return fn (Get $get): bool => in_array($get('id'), $ids, true);
    }

    private function restaurant(): ?Restaurant
    {
        $tenant = Filament::getTenant();

        return $tenant instanceof Restaurant ? $tenant : null;
    }
}
