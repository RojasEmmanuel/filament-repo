<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                Textarea::make('descripcion')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('precio')
                    ->required()
                    ->numeric(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(1),
                Toggle::make('activo')
                    ->required(),
            ]);
    }
}
