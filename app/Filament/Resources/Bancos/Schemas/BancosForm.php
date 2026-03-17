<?php

namespace App\Filament\Resources\Bancos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BancosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_banco')
                    ->required(),
                Select::make('tipo_cuenta')
                    ->options(['ahorros' => 'Ahorros', 'corriente' => 'Corriente', 'recaudadora' => 'Recaudadora'])
                    ->default('corriente')
                    ->required(),
                TextInput::make('moneda')
                    ->required(),
                TextInput::make('numero_cuenta')
                    ->required(),
                TextInput::make('codigo_interbancario')
                    ->required(),
                TextInput::make('representante')
                    ->required(),
            ]);
    }
}
