<?php

namespace App\Filament\Blogger\Resources\BlogComments\Schemas;

use App\Enums\BlogCommentStatus;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Support\FilamentTranslatable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class BlogCommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Komentar')
                    ->description('Isi komentar pembaca atau balasan penulis.')
                    ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                    ->columns(2)
                    ->schema([
                        Select::make('blog_post_id')
                            ->label('Artikel Blog')
                            ->relationship(
                                name: 'blogPost',
                                titleAttribute: 'id',
                                modifyQueryUsing: function ($query, ?string $search = null) {
                                    if (! auth()->user()?->isPlatformOperator()) {
                                        $query->where('author_id', auth()->id());
                                    }

                                    if (filled($search)) {
                                        FilamentTranslatable::search($query, 'title', $search);
                                    }

                                    return $query;
                                },
                            )
                            ->getOptionLabelFromRecordUsing(fn (BlogPost $record): string => static::postLabel($record))
                            ->searchable([])
                            ->preload()
                            ->required()
                            ->live()
                            ->native(false)
                            ->columnSpanFull(),
                        Select::make('parent_id')
                            ->label('Balas Ke Komentar')
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'author_name',
                                modifyQueryUsing: fn ($query, Get $get) => $query
                                    ->where('blog_post_id', $get('blog_post_id'))
                                    ->whereNull('parent_id')
                                    ->orderByDesc('created_at'),
                            )
                            ->getOptionLabelFromRecordUsing(fn (BlogComment $record): string => sprintf(
                                '#%d — %s (%s)',
                                $record->id,
                                $record->author_name,
                                str($record->content)->limit(40),
                            ))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Komentar utama (bukan balasan)')
                            ->columnSpanFull(),
                        TextInput::make('author_name')
                            ->label('Nama Pengirim')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('author_email')
                            ->label('Email Pengirim')
                            ->email()
                            ->maxLength(255),
                        Toggle::make('is_author_reply')
                            ->label('Balasan Penulis')
                            ->helperText('Menampilkan lencana "Penulis" pada artikel publik.')
                            ->default(false)
                            ->inline(false),
                        Select::make('status')
                            ->label('Status Moderasi')
                            ->options(BlogCommentStatus::class)
                            ->default(BlogCommentStatus::Pending)
                            ->required()
                            ->native(false),
                        Textarea::make('content')
                            ->label('Isi Komentar')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Moderasi')
                    ->description('Metadata persetujuan komentar.')
                    ->icon(Heroicon::OutlinedCheckBadge)
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        DateTimePicker::make('approved_at')
                            ->label('Disetujui Pada')
                            ->seconds(false)
                            ->native(false),
                        Select::make('approved_by')
                            ->label('Disetujui Oleh')
                            ->relationship('approvedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('—'),
                    ]),

                Section::make('Metadata Teknis')
                    ->description('Informasi teknis IP dan user agent.')
                    ->icon(Heroicon::OutlinedServerStack)
                    ->collapsed()
                    ->schema([
                        Fieldset::make('Info Permintaan')
                            ->columns(2)
                            ->schema([
                                TextInput::make('ip_address')
                                    ->label('Alamat IP')
                                    ->required()
                                    ->maxLength(45)
                                    ->default(fn (): string => request()->ip() ?? '0.0.0.0'),
                                Textarea::make('user_agent')
                                    ->label('User Agent')
                                    ->rows(2)
                                    ->columnSpanFull()
                                    ->default(fn (): ?string => request()->userAgent()),
                            ]),
                    ]),
            ]);
    }

    private static function postLabel(BlogPost $post): string
    {
        $title = FilamentTranslatable::label($post, 'title') ?: 'Tanpa Judul';

        return "#{$post->id} — {$title}";
    }
}
