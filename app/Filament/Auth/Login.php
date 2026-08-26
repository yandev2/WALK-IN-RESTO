<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getHeading();
        }

        return 'Masuk';
    }

    public function getSubheading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getSubheading();
        }

        if (Filament::getCurrentOrDefaultPanel()?->getId() !== 'admin') {
            return null;
        }

        $url = e(route('register.restaurant'));

        return new HtmlString(
            'Belum punya akun? <a href="'.$url.'">Daftarkan restoran</a>'
        );
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email atau username')
            ->placeholder('Email atau username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->suffixIcon(Heroicon::OutlinedUser);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->placeholder('Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->prefixIcon(Heroicon::OutlinedLockClosed);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        $login = $data['email'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $login,
            'password' => $data['password'],
        ];
    }
}
