<?php

namespace App\Filament\Resources\Monedas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MonedasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('codigo_iso')
                    ->required(),
                TextInput::make('Simbolo')
                    ->required(),
            ]);
    }
}
