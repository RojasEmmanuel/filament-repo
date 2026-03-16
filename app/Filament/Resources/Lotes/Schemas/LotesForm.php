<?php

namespace App\Filament\Resources\Lotes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LotesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('fraccionamiento_id')
                    ->native(false)
                    ->label('Fraccionamiento')
                    ->relationship('fraccionamiento', 'nombre')
                    ->required(),
                TextInput::make('manzana')
                    ->required(),
                TextInput::make('lote')
                    ->required(),
                TextInput::make('area')
                    ->required()
                    ->numeric(),
                TextInput::make('norte')
                    ->numeric()
                    ->default(null),
                TextInput::make('sur')
                    ->numeric()
                    ->default(null),
                TextInput::make('este')
                    ->numeric()
                    ->default(null),
                TextInput::make('oeste')
                    ->numeric()
                    ->default(null),
                TextInput::make('precio')
                    ->required()
                    ->numeric(),
                Select::make('estatus')
                    ->native(false)
                    ->options(['disponible' => 'Disponible', 'vendido' => 'Vendido', 'liquidado' => 'Liquidado'])
                    ->required(),
                Textarea::make('observaciones')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
