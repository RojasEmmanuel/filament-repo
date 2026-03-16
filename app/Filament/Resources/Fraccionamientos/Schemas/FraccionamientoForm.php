<?php

namespace App\Filament\Resources\Fraccionamientos\Schemas;

use Dom\Text;
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
                TextInput::make('Código_postal')
                    ->label('código postal')
                    ->numeric(),
                TextInput::make('perimetro')
                    ->label('Perimetro (km)')
                    ->default('0.0')
                    ->numeric(),
                TextInput::make('area_total')
                    ->label('área total del proyecto (m2)')
                    ->default('0.0')
                    ->numeric(),
                TextInput::make('total_manzanas')
                    ->label('Total de manzanas')
                    ->default('1')
                    ->numeric(),
                TextInput::make('total_lotes')
                    ->label('total_lotes')
                    ->default('1')
                    ->numeric(),
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
