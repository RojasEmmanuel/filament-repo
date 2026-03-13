<?php

namespace App\Filament\Resources\Fraccionamientos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FraccionamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('ubicacion')
                    ->required(),
                Textarea::make('descripcion')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('activo')
                    ->required(),
                FileUpload::make('imagen')
                    ->image()
                    ->disk('public')
                    ->directory('fraccionamientos')
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('center')
            ]);
    }
}
