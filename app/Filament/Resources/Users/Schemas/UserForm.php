<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->required(),
                
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->label('Contraseña'),                   

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
            ]);
    }
}
