<?php

namespace App\Filament\Founder\Pages;

use App\Models\PlatformSetting;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class ManageSoundNotifications extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSpeakerWave;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Audio notifikasi';

    protected static ?string $title = 'Audio Notifikasi (Kasir & Dapur)';

    protected static ?int $navigationSort = 7;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->isPlatformOperator();
    }

    public function mount(): void
    {
        $setting = PlatformSetting::current();

        $this->form->fill($setting->only([
            'cashier_sound_path',
            'kitchen_sound_path',
        ]));
    }

    public const ACCEPTED_AUDIO_TYPES = [
        // WAV
        'audio/wav',
        'audio/x-wav',
        'audio/wave',
        'audio/vnd.wave',
        'audio/x-pn-wav',
        // MP3 / MPEG
        'audio/mpeg',
        'audio/mp3',
        'audio/x-mp3',
        'audio/x-mpeg',
        'audio/mpeg3',
        'audio/x-mpeg-3',
        // OGG / Opus
        'audio/ogg',
        'audio/x-ogg',
        'application/ogg',
        'audio/opus',
        // M4A / MP4 / AAC
        'audio/x-m4a',
        'audio/m4a',
        'audio/mp4',
        'audio/x-mp4',
        'audio/aac',
        'audio/x-aac',
        // WebM / FLAC
        'audio/webm',
        'audio/flac',
        'audio/x-flac',
    ];

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Notifikasi Pesanan Masuk (Kasir)')
                    ->description('File audio bel kasir saat tamu checkout atau ada pesanan baru berstatus "Menunggu Kasir".')
                    ->icon(Heroicon::OutlinedBellAlert)
                    ->schema([
                        FileUpload::make('cashier_sound_path')
                            ->label('File Audio Bel Kasir')
                            ->helperText('Format: MP3, WAV, OGG, atau M4A (maksimal 5 MB). Jika dikosongkan, sistem otomatis menggunakan suara bel synthesizer bawaan.')
                            ->disk('public')
                            ->directory('platform/sounds')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->acceptedFileTypes(self::ACCEPTED_AUDIO_TYPES)
                            ->validationMessages([
                                'mimetypes' => 'Format berkas audio tidak didukung. Harap gunakan file berekstensi MP3, WAV, OGG, atau M4A.',
                            ])
                            ->downloadable()
                            ->openable(),

                        Placeholder::make('cashier_preview')
                            ->label('Pratinjau / Status Audio Kasir')
                            ->content(function (): HtmlString {
                                $url = PlatformSetting::cashierSoundUrl();

                                if (filled($url)) {
                                    return new HtmlString('
                                        <div class="space-y-2">
                                            <audio controls preload="none" src="'.e($url).'" class="w-full max-w-md h-10"></audio>
                                            <p class="text-xs text-success-600 dark:text-success-400 font-medium">✓ File audio kustom aktif digunakan untuk kasir.</p>
                                        </div>
                                    ');
                                }

                                return new HtmlString('
                                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400">
                                        ℹ️ Belum ada file audio yang diunggah. Kasir menggunakan <strong>nada bel synthesizer bawaan sistem (Two-Tone Chime D5-A5)</strong>.
                                    </div>
                                ');
                            }),
                    ]),

                Section::make('Notifikasi Dapur KDS (Pesanan Siap Dimasak)')
                    ->description('File audio bel dapur saat kasir mengonfirmasi pembayaran di mode bukan simple (item masuk antrian dapur).')
                    ->icon(Heroicon::OutlinedFire)
                    ->schema([
                        FileUpload::make('kitchen_sound_path')
                            ->label('File Audio Bel Dapur')
                            ->helperText('Format: MP3, WAV, OGG, atau M4A (maksimal 5 MB). Jika dikosongkan, sistem otomatis menggunakan suara bel dapur synthesizer bawaan.')
                            ->disk('public')
                            ->directory('platform/sounds')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->acceptedFileTypes(self::ACCEPTED_AUDIO_TYPES)
                            ->validationMessages([
                                'mimetypes' => 'Format berkas audio tidak didukung. Harap gunakan file berekstensi MP3, WAV, OGG, atau M4A.',
                            ])
                            ->downloadable()
                            ->openable(),

                        Placeholder::make('kitchen_preview')
                            ->label('Pratinjau / Status Audio Dapur')
                            ->content(function (): HtmlString {
                                $url = PlatformSetting::kitchenSoundUrl();

                                if (filled($url)) {
                                    return new HtmlString('
                                        <div class="space-y-2">
                                            <audio controls preload="none" src="'.e($url).'" class="w-full max-w-md h-10"></audio>
                                            <p class="text-xs text-success-600 dark:text-success-400 font-medium">✓ File audio kustom aktif digunakan untuk dapur.</p>
                                        </div>
                                    ');
                                }

                                return new HtmlString('
                                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400">
                                        ℹ️ Belum ada file audio yang diunggah. Dapur menggunakan <strong>nada bel dapur synthesizer bawaan sistem (Triple Harmonic Service Bell C5-G5-C6)</strong>.
                                    </div>
                                ');
                            }),
                    ]),
            ]);
    }

    public function save(): void
    {
        $setting = PlatformSetting::current();
        $state = $this->form->getState();

        foreach (['cashier_sound_path', 'kitchen_sound_path'] as $field) {
            $path = $state[$field] ?? null;
            if (is_array($path)) {
                $first = reset($path);
                $state[$field] = is_string($first) && filled($first) ? $first : null;
            } elseif (! is_string($path) || blank($path)) {
                $state[$field] = null;
            }
        }

        $setting->update($state);
        PlatformSetting::forgetCache();

        Notification::make()
            ->title('Pengaturan audio notifikasi berhasil disimpan')
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
