<?php

namespace App\Filament\Resources\Bancos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section as ComponentsSection;

class BancosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Información de la Cuenta Bancaria')
                    ->description('Completa los datos de la cuenta bancaria')
                    ->icon('heroicon-o-building-library')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                ComponentsGrid::make(1)
                                    ->schema([
                                        TextInput::make('nombre_banco')
                                            ->label('Nombre del Banco')
                                            ->required()
                                            ->placeholder('Ej: BBVA, Santander, Banamex...')
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-o-building-library')
                                            ->helperText('Nombre oficial de la institución bancaria'),
                                        
                                        Select::make('tipo_cuenta')
                                            ->label('Tipo de Cuenta')
                                            ->options([
                                                'ahorros' => 'Ahorros',
                                                'corriente' => 'Corriente',
                                                'recaudadora' => 'Recaudadora'
                                            ])
                                            ->default('corriente')
                                            ->required()
                                            ->native(false)
                                            ->prefixIcon('heroicon-o-credit-card')
                                            ->helperText('Selecciona el tipo de cuenta bancaria'),
                                        
                                        Select::make('moneda_id')
                                            ->label('Moneda')
                                            ->relationship('moneda', 'nombre')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->prefixIcon('heroicon-o-currency-dollar')
                                            ->helperText('Moneda en la que opera la cuenta')
                                            ->createOptionForm([
                                                TextInput::make('nombre')
                                                    ->label('Nombre de la moneda')
                                                    ->required(),
                                                TextInput::make('codigo_iso')
                                                    ->label('Código ISO')
                                                    ->required()
                                                    ->maxLength(3),
                                                TextInput::make('simbolo')
                                                    ->label('Símbolo')
                                                    ->required()
                                                    ->maxLength(3),
                                            ]),
                                    ]),

                                // Columna 2
                                ComponentsGrid::make(1)
                                    ->schema([
                                        TextInput::make('numero_cuenta')
                                            ->label('Número de Cuenta')
                                            ->required()
                                            ->placeholder('Ej: 1234567890')
                                            ->maxLength(20)
                                            ->prefixIcon('heroicon-o-hashtag')
                                            ->helperText('Número de cuenta bancaria (sin espacios)')
                                            ->extraInputAttributes(['oninput' => 'this.value = this.value.replace(/[^0-9]/g, "")'])
                                            ->numeric(),
                                        
                                        TextInput::make('codigo_interbancario')
                                            ->label('CLABE / Código Interbancario')
                                            ->required()
                                            ->placeholder('Ej: 123456789012345678')
                                            ->maxLength(20)
                                            ->prefixIcon('heroicon-o-document-text')
                                            ->helperText('CLABE interbancaria para transferencias (18 dígitos)')
                                            ->extraInputAttributes(['oninput' => 'this.value = this.value.replace(/[^0-9]/g, "")'])
                                            ->numeric(),
                                        
                                        TextInput::make('representante')
                                            ->label('Representante Legal')
                                            ->required()
                                            ->placeholder('Ej: Juan Pérez González')
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-o-user-circle')
                                            ->helperText('Nombre del representante o titular de la cuenta'),
                                    ]),
                            ]),


                        
                    ])
                    ->collapsible(false)
                    ->compact(false)
                    ->columnSpanFull(),
            ]);
    }
}