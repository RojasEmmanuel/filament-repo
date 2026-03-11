<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('apellidos')
                    ->required(),
                TextInput::make('edad')
                    ->required()
                    ->numeric(),
                TextInput::make('ciudad')
                    ->required(),
            ]);
    }
}
