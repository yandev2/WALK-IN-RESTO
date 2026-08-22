<?php

namespace App\Filament\Profile;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Component;
use Ipatco\FilamentProfile\Forms\ProfileInformationForm as BaseProfileInformationForm;
use Ipatco\FilamentProfile\Pages\EditProfile;

class ProfileInformationForm extends BaseProfileInformationForm
{
    /**
     * @return array<Component>
     */
    public static function configure(EditProfile $page): array
    {
        return [
            $page->getNameFormComponent()
                ->label('Nama'),
            $page->getEmailFormComponent()
                ->label('Email'),
            FileUpload::make('avatar_path')
                ->label('Foto profil')
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
            $page->getCurrentPasswordFormComponent(),
        ];
    }
}
