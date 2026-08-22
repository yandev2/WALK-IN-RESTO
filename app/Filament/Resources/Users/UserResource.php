<?php

namespace App\Filament\Resources\Users;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\TrashUsers;
use App\Filament\Support\TableRightClick;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use App\Support\SubscriptionAccess;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class UserResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = User::class;

    protected static string $permission = 'settings.manage';

    protected static string $subscriptionFeature = 'settings_full';

    protected static ?string $tenantOwnershipRelationshipName = 'restaurants';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Staf';

    protected static ?string $modelLabel = 'staf';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function canEdit(Model $record): bool
    {
        if ($record instanceof User && static::isProtectedOwnerStaff($record)) {
            return false;
        }

        return static::userHasPermission(static::$permission)
            && SubscriptionAccess::allows(static::subscriptionFeature(), mutate: true);
    }

    public static function canDelete(Model $record): bool
    {
        if (! $record instanceof User) {
            return false;
        }

        if ($record->getKey() === auth()->id() || static::isProtectedOwnerStaff($record)) {
            return false;
        }

        return static::userHasPermission(static::$permission)
            && SubscriptionAccess::allows(static::subscriptionFeature(), mutate: true);
    }

    public static function canForceDelete(Model $record): bool
    {
        return static::canDelete($record);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Identitas')
                            ->description('Nama dan kredensial login staf.')
                            ->icon(Heroicon::OutlinedUser)
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                TextInput::make('username')
                                    ->label('Username')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->alphaDash(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->helperText(fn (string $operation): ?string => $operation === 'edit'
                                        ? 'Kosongkan jika tidak ingin mengubah password.'
                                        : null)
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Avatar')
                            ->description('Foto profil staf. Rasio 1:1.')
                            ->icon(Heroicon::OutlinedPhoto)
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('avatar_path')
                                    ->hiddenLabel()
                                    ->image()
                                    ->imageEditor()
                                    ->imageCropAspectRatio('1:1')
                                    ->imagePreviewHeight('160')
                                    ->panelLayout('compact')
                                    ->avatar()
                                    ->directory('users/avatars')
                                    ->disk('public')
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                            ]),
                    ]),
                Section::make('Akses & status')
                    ->description('Peran menentukan izin di panel restoran ini.')
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->columns(2)
                    ->schema([
                        Select::make('role_name')
                            ->label('Peran')
                            ->options(fn (): array => Role::query()
                                ->where('restaurant_id', Filament::getTenant()?->getKey())
                                ->assignableToStaff()
                                ->pluck('name', 'name')
                                ->all())
                            ->required()
                            ->native(false)
                            ->dehydrated()
                            ->helperText('Peran owner dikunci dan mengikuti paket langganan.'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Staf nonaktif tidak bisa masuk panel.')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                ImageColumn::make('avatar_path')
                    ->label('Avatar')
                    ->disk('public')
                    ->circular()
                    ->imageSize(40)
                    ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF'),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ]);

        return TableRightClick::apply($table, fn (): array => [
            EditAction::make()
                ->visible(fn (User $record): bool => static::canEdit($record)),
            DeleteAction::make()
                ->visible(fn (User $record): bool => static::canDelete($record)),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
            'trash' => TrashUsers::route('/trash'),
        ];
    }

    protected static function isProtectedOwnerStaff(User $record): bool
    {
        $tenant = Filament::getTenant();

        return $record->isRestaurantOwner($tenant instanceof Restaurant ? $tenant : null);
    }
}
