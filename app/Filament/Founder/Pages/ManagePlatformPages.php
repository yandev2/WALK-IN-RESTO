<?php

namespace App\Filament\Founder\Pages;

use App\Models\PlatformSetting;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManagePlatformPages extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Halaman statis';

    protected static ?string $title = 'Halaman Informasi (Tentang & Ketentuan)';

    protected static ?int $navigationSort = 6;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->isPlatformOperator();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewAbout')
                ->label('Lihat halaman Tentang')
                ->icon(Heroicon::OutlinedEye)
                ->url(fn (): string => route('page.about'))
                ->openUrlInNewTab(),
            Action::make('viewTerms')
                ->label('Lihat Syarat & Ketentuan')
                ->icon(Heroicon::OutlinedEye)
                ->url(fn (): string => route('page.terms'))
                ->openUrlInNewTab(),
        ];
    }

    public function mount(): void
    {
        $setting = PlatformSetting::current();

        $this->form->fill($setting->only([
            'about_title',
            'about_content',
            'terms_title',
            'terms_content',
        ]));
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Halaman Tentang Kami')
                    ->description('Dapat diakses pengunjung di alamat /tentang')
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->schema([
                        TextInput::make('about_title')
                            ->label('Judul Halaman')
                            ->placeholder('Tentang RestoTerdekat')
                            ->required()
                            ->maxLength(150),
                        RichEditor::make('about_content')
                            ->label('Isi Konten')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('platform/pages')
                            ->fileAttachmentsVisibility('public')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
                    ]),
                Section::make('Halaman Syarat & Ketentuan')
                    ->description('Dapat diakses pengunjung di alamat /syarat-dan-ketentuan')
                    ->icon(Heroicon::OutlinedDocumentCheck)
                    ->schema([
                        TextInput::make('terms_title')
                            ->label('Judul Halaman')
                            ->placeholder('Syarat & Ketentuan Layanan')
                            ->required()
                            ->maxLength(150),
                        RichEditor::make('terms_content')
                            ->label('Isi Konten')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('platform/pages')
                            ->fileAttachmentsVisibility('public')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function save(): void
    {
        $setting = PlatformSetting::current();
        $setting->update($this->form->getState());

        Notification::make()
            ->title('Halaman statis berhasil disimpan')
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
                                ->label('Simpan Perubahan')
                                ->submit('save'),
                        ]),
                    ]),
            ]);
    }
}
