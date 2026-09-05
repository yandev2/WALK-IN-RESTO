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
                ->avatar()
                ->imageAspectRatio('1:1')
                ->automaticallyCropImagesToAspectRatio()
                ->automaticallyResizeImagesMode('cover')
                ->automaticallyResizeImagesToWidth('500')
                ->automaticallyResizeImagesToHeight('500')
                ->automaticallyUpscaleImagesWhenResizing(false)
                ->imageEditor()
                ->imageEditorAspectRatios(['1:1'])
                ->imagePreviewHeight('160')
                ->directory('users/avatars')
                ->disk('public')
                ->maxSize(15360)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->helperText('Format: JPG, PNG, WEBP. Rasio 1:1, otomatis dipotong & dikompres (maks. 15 MB).'),
            $page->getCurrentPasswordFormComponent(),
        ];
    }
}
