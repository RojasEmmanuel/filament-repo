<?php

namespace App\Filament\Breezy;

use Filament\Forms;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

class MyProfile extends MyProfile
{
    protected function getProfileFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('avatar')
                ->image()
                ->directory('avatars')
                ->disk('public')
                ->imageCropAspectRatio('1:1')
                ->imageResizeTargetWidth('300')
                ->imageResizeTargetHeight('300')
                ->circleCropper(),

            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('Correo electrónico')
                ->email()
                ->required(),

            Forms\Components\TextInput::make('telefono')
                ->label('Teléfono'),

            Forms\Components\TextInput::make('rol')
                ->label('Rol'),
        ];
    }
}