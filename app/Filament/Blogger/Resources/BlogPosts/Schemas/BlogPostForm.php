<?php

namespace App\Filament\Blogger\Resources\BlogPosts\Schemas;

use App\Enums\BlogPostStatus;
use App\Filament\Blogger\Forms\SeoFields;
use App\Filament\Blogger\Forms\TranslationTabs;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\BlogTagTranslation;
use App\Models\User;
use App\Support\FilamentTranslatable;
use App\Support\Locales;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Konten Artikel')
                    ->description('Tulis artikel kuliner dalam Bahasa Indonesia dan Bahasa Inggris. Slug digunakan untuk URL artikel.')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->schema([
                        TranslationTabs::make('Konten', function (string $locale) {
                            $isFieldFilled = function (mixed $value, bool $stripTags = false): bool {
                                if (is_null($value)) {
                                    return false;
                                }
                                if (is_string($value)) {
                                    $trimmed = $stripTags ? trim(strip_tags($value)) : trim($value);

                                    return $trimmed !== '';
                                }
                                if (is_scalar($value)) {
                                    return trim((string) $value) !== '';
                                }
                                if (is_array($value)) {
                                    return ! empty(array_filter($value, fn ($v) => ! is_null($v) && $v !== ''));
                                }

                                return false;
                            };

                            $isLocaleTouched = function (Get $get) use ($locale, $isFieldFilled): bool {
                                return $isFieldFilled($get("{$locale}.title"))
                                    || $isFieldFilled($get("{$locale}.slug"))
                                    || $isFieldFilled($get("{$locale}.content"), true)
                                    || $isFieldFilled($get("{$locale}.excerpt"));
                            };

                            $hasAnyLocaleContent = function (Get $get) use ($isFieldFilled): bool {
                                foreach (Locales::all() as $loc) {
                                    if (
                                        $isFieldFilled($get("{$loc}.title"))
                                        || $isFieldFilled($get("{$loc}.slug"))
                                        || $isFieldFilled($get("{$loc}.content"), true)
                                    ) {
                                        return true;
                                    }
                                }

                                return false;
                            };

                            return [
                                Grid::make(2)->schema([
                                    TextInput::make("{$locale}.title")
                                        ->label('Judul Artikel')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (?string $state, Set $set, Get $get) use ($locale) {
                                            if (blank($get("{$locale}.slug"))) {
                                                $set("{$locale}.slug", Str::slug($state ?? ''));
                                            }
                                        })
                                        ->required(function (Get $get) use ($isLocaleTouched, $hasAnyLocaleContent, $locale): bool {
                                            if ($isLocaleTouched($get)) {
                                                return true;
                                            }

                                            return ! $hasAnyLocaleContent($get) && $locale === Locales::default();
                                        })
                                        ->maxLength(255)
                                        ->helperText('Wajib diisi untuk bahasa utama atau bahasa yang sedang ditulis.')
                                        ->columnSpanFull(),
                                    TextInput::make("{$locale}.slug")
                                        ->label('Slug URL')
                                        ->required($isLocaleTouched)
                                        ->maxLength(255)
                                        ->helperText('Bagian URL artikel (otomatis dari judul).'),
                                    TextInput::make("{$locale}.featured_image_alt")
                                        ->label('Teks Alt Gambar Utama')
                                        ->maxLength(255)
                                        ->helperText('Deskripsi gambar untuk aksesibilitas & SEO.'),
                                ]),
                                Textarea::make("{$locale}.excerpt")
                                    ->label('Kutipan / Ringkasan Singkat')
                                    ->rows(3)
                                    ->helperText('Ringkasan pembuka artikel yang muncul di daftar blog.')
                                    ->columnSpanFull(),
                                RichEditor::make("{$locale}.content")
                                    ->label('Isi Lengkap Artikel')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('blog/attachments')
                                    ->required($isLocaleTouched)
                                    ->rules([
                                        fn (Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get, $locale): void {
                                            $isTouched = filled($get("{$locale}.title"))
                                                || filled($get("{$locale}.slug"));

                                            $hasBody = false;
                                            if (is_array($value)) {
                                                $hasBody = filled(array_filter($value));
                                            } elseif (is_string($value)) {
                                                $hasBody = trim(strip_tags($value)) !== '';
                                            } else {
                                                $hasBody = filled($value);
                                            }

                                            if ($isTouched && ! $hasBody) {
                                                $fail('Isi artikel wajib diisi jika judul sudah diisi.');
                                            }
                                        },
                                    ])
                                    ->columnSpanFull(),
                            ];
                        }),
                    ]),

                Section::make('Publikasi & Taksonomi')
                    ->description('Atur status terbit, penulis, kategori, dan tag artikel.')
                    ->icon(Heroicon::OutlinedClock)
                    ->columns(2)
                    ->schema([
                        Fieldset::make('Jadwal & Status')
                            ->columns(2)
                            ->columnSpanFull()
                            ->schema([
                                Select::make('status')
                                    ->label('Status Publikasi')
                                    ->options(BlogPostStatus::class)
                                    ->default(BlogPostStatus::Draft)
                                    ->required()
                                    ->live()
                                    ->native(false),
                                DateTimePicker::make('published_at')
                                    ->label('Tanggal & Waktu Terbit')
                                    ->seconds(false)
                                    ->native(false)
                                    ->helperText(fn (Get $get): ?string => $get('status') === BlogPostStatus::Scheduled->value
                                        ? 'Wajib diisi dengan waktu di masa depan untuk publikasi otomatis.'
                                        : ($get('status') === BlogPostStatus::Published->value
                                            ? 'Biarkan kosong untuk otomatis menggunakan waktu saat ini ketika disimpan.'
                                            : null))
                                    ->required(fn (Get $get): bool => $get('status') === BlogPostStatus::Scheduled->value)
                                    ->rules([
                                        fn (Get $get, ?BlogPost $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get, $record): void {
                                            if ($get('status') !== BlogPostStatus::Scheduled->value) {
                                                return;
                                            }

                                            if (blank($value)) {
                                                $fail('Tanggal & waktu terbit wajib diisi untuk status terjadwal.');

                                                return;
                                            }

                                            if (Carbon::parse($value)->lte(now())) {
                                                if ($record && $record->status === BlogPostStatus::Scheduled && $record->published_at?->equalTo(Carbon::parse($value))) {
                                                    return;
                                                }

                                                $fail('Waktu terbit harus berada di masa depan untuk status terjadwal.');
                                            }
                                        },
                                    ]),
                            ]),

                        Fieldset::make('Penulis & Kategori')
                            ->columns(2)
                            ->columnSpanFull()
                            ->schema([
                                Select::make('blog_category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'id', FilamentTranslatable::relationshipSearch('name'))
                                    ->getOptionLabelFromRecordUsing(fn ($record) => FilamentTranslatable::label($record, 'name'))
                                    ->searchable([])
                                    ->preload()
                                    ->native(false),
                                Select::make('author_id')
                                    ->label('Penulis (Author)')
                                    ->relationship('author', 'name')
                                    ->default(fn () => auth()->id())
                                    ->disabled(fn () => ! (auth()->user()?->isPlatformOperator() ?? false))
                                    ->dehydrated()
                                    ->helperText(fn () => (auth()->user()?->isPlatformOperator() ?? false)
                                        ? 'Founder dapat memilih penulis artikel manapun.'
                                        : 'Nama penulis otomatis dihubungkan ke akun login Anda.')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->native(false),
                                Select::make('tags')
                                    ->label('Tag Artikel')
                                    ->relationship('tags', 'id', FilamentTranslatable::relationshipSearch('name'))
                                    ->multiple()
                                    ->getOptionLabelFromRecordUsing(fn ($record) => FilamentTranslatable::label($record, 'name'))
                                    ->preload()
                                    ->searchable([])
                                    ->columnSpanFull()
                                    ->createOptionAction(fn ($action) => $action->modalHeading('Buat Tag Baru')->modalWidth('md'))
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nama Tag')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (?string $state, Set $set) {
                                                $set('slug', Str::slug($state ?? ''));
                                            }),
                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->helperText('Otomatis dibuat dari nama menggunakan Str::slug()')
                                            ->maxLength(255),
                                        Select::make('locale')
                                            ->label('Bahasa Tag')
                                            ->options(collect(Locales::all())->mapWithKeys(fn ($loc) => [$loc => strtoupper($loc) . ' — ' . Locales::label($loc)]))
                                            ->default(function ($livewire, ?BlogPost $record) {
                                                return static::resolveActiveLocale($livewire, $record);
                                            })
                                            ->required(),
                                    ])
                                    ->createOptionUsing(function (array $data, $livewire, ?BlogPost $record): int {
                                        $locale = $data['locale'] ?? static::resolveActiveLocale($livewire, $record);
                                        $name = trim((string) ($data['name'] ?? ''));
                                        $slug = filled($data['slug'] ?? null)
                                            ? Str::slug($data['slug'])
                                            : Str::slug($name);

                                        if (blank($slug)) {
                                            $slug = 'tag-' . time();
                                        }

                                        $baseSlug = $slug;
                                        $counter = 1;
                                        while (BlogTagTranslation::where('locale', $locale)->where('slug', $slug)->exists()) {
                                            $slug = "{$baseSlug}-{$counter}";
                                            $counter++;
                                        }

                                        $tag = BlogTag::create([
                                            'is_active' => true,
                                            'sort_order' => 0,
                                        ]);

                                        $tag->translations()->create([
                                            'locale' => $locale,
                                            'name' => $name,
                                            'slug' => $slug,
                                        ]);

                                        return $tag->id;
                                    }),
                            ]),

                        Fieldset::make('Opsi Tampilan')
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('reading_time_minutes')
                                    ->label('Estimasi Baca')
                                    ->numeric()
                                    ->suffix('menit')
                                    ->minValue(0),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                                Toggle::make('is_featured')
                                    ->label('Artikel Pilihan (Featured)')
                                    ->default(false)
                                    ->inline(false),
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                    ]),

                Section::make('Media Gambar')
                    ->description('Gambar utama ditampilkan pada kartu artikel. Gambar Open Graph digunakan untuk pratinjau media sosial.')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Gambar Utama (Featured Image)')
                            ->image()
                            ->imageAspectRatio('16:9')
                            ->panelAspectRatio('16:9')
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1200')
                            ->automaticallyResizeImagesToHeight('675')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->imageEditor()
                            ->imageEditorMode(2)
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                            ->directory('blog/featured')
                            ->disk('public')
                            ->maxSize(8192)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Format: JPG, PNG, WEBP. Rasio 16:9 lanskap (maks. 8 MB). Pratinjau gambar akan tampil setelah diunggah.'),
                        FileUpload::make('og_image')
                            ->label('Gambar Open Graph (Medsos)')
                            ->image()
                            ->imageAspectRatio('1200:630')
                            ->panelAspectRatio('1200:630')
                            ->imagePreviewHeight('160')
                            ->panelLayout('integrated')
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1200')
                            ->automaticallyResizeImagesToHeight('630')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->imageEditor()
                            ->imageEditorMode(2)
                            ->imageEditorAspectRatios(['1200:630', '16:9', '1:1'])
                            ->directory('blog/og')
                            ->disk('public')
                            ->maxSize(8192)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Format: JPG, PNG, WEBP. Rasio 1200×630 medsos (maks. 8 MB). Opsional: bila kosong otomatis menggunakan gambar utama.'),
                    ]),

                Section::make('Pengaturan SEO')
                    ->description('Meta tag per bahasa untuk Google dan mesin pencari.')
                    ->icon(Heroicon::OutlinedMagnifyingGlass)
                    ->collapsed()
                    ->schema([
                        TranslationTabs::make('SEO', fn (string $locale) => SeoFields::forLocale($locale)),
                    ]),

                Section::make('Statistik Pembaca')
                    ->description('Data keterlibatan pembaca (hanya baca). Otomatis diperbarui saat artikel dibaca, disukai, atau dikomentari.')
                    ->icon(Heroicon::OutlinedChartBar)
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        TextInput::make('views_count')
                            ->label('Total Views')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('likes_count')
                            ->label('Total Likes')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('comments_count')
                            ->label('Total Komentar')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function resolveActiveLocale(mixed $livewire = null, ?BlogPost $record = null): string
    {
        $queryLocale = request()->query('active_locale') ?? request()->query('tab');
        if (filled($queryLocale) && in_array($queryLocale, Locales::all(), true)) {
            return $queryLocale;
        }

        $data = null;
        if ($livewire instanceof \Livewire\Component && isset($livewire->data) && is_array($livewire->data)) {
            $data = $livewire->data;
        } elseif (is_array($livewire)) {
            $data = $livewire;
        }

        $isFilled = function (mixed $value): bool {
            if (is_null($value)) {
                return false;
            }
            if (is_string($value)) {
                return trim(strip_tags($value)) !== '';
            }
            if (is_scalar($value)) {
                return trim((string) $value) !== '';
            }
            if (is_array($value)) {
                return ! empty(array_filter($value, fn ($v) => ! is_null($v) && $v !== ''));
            }

            return false;
        };

        if (is_array($data)) {
            foreach (Locales::all() as $loc) {
                $localeData = $data[$loc] ?? null;
                if (! is_array($localeData)) {
                    continue;
                }

                $titleFilled = $isFilled($localeData['title'] ?? null);
                $contentFilled = $isFilled($localeData['content'] ?? null);
                $slugFilled = $isFilled($localeData['slug'] ?? null);

                if ($titleFilled || $contentFilled || $slugFilled) {
                    return $loc;
                }
            }
        }

        $targetRecord = $record;
        if (! $targetRecord && $livewire instanceof \Livewire\Component && method_exists($livewire, 'getRecord')) {
            $targetRecord = $livewire->getRecord();
        }

        if ($targetRecord instanceof BlogPost) {
            $firstLocale = $targetRecord->translations()->pluck('locale')->first();
            if ($firstLocale && in_array($firstLocale, Locales::all(), true)) {
                return $firstLocale;
            }
        }

        return Locales::default();
    }
}
