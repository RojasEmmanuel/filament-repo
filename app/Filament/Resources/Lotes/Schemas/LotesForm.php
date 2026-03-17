<?php

namespace App\Filament\Resources\Lotes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Support\Icons\Heroicon;

class LotesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Información del Lote')
                    ->description('Datos generales y ubicación del lote')
                    ->icon('heroicon-o-rectangle-stack')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                // Columna izquierda - Identificación
                                ComponentsGrid::make(1)
                                
                                    ->schema([
                                        Select::make('fraccionamiento_id')
                                            ->label('Fraccionamiento')
                                            ->relationship('fraccionamiento', 'nombre')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->placeholder('Selecciona un fraccionamiento')
                                            ->prefixIcon('heroicon-o-building-office')
                                            ->helperText('Fraccionamiento al que pertenece el lote')
                                            ->createOptionForm([
                                                TextInput::make('nombre')
                                                    ->label('Nombre del Fraccionamiento')
                                                    ->required(),
                                                TextInput::make('ubicacion')
                                                    ->label('Ubicación')
                                                    ->required(),
                                            ]),
                                        
                                        ComponentsGrid::make(2)
                                            ->schema([
                                                TextInput::make('manzana')
                                                    ->label('Manzana')
                                                    ->required()
                                                    ->placeholder('Ej: 1, A, 2B')
                                                    ->prefixIcon('heroicon-o-squares-2x2')
                                                    ->helperText('Número o identificador de la manzana'),
                                                
                                                TextInput::make('lote')
                                                    ->label('Lote')
                                                    ->required()
                                                    ->placeholder('Ej: 1, 2, 3')
                                                    ->prefixIcon('heroicon-o-hashtag')
                                                    ->helperText('Número o identificador del lote'),
                                            ]),
                                        
                                        TextInput::make('area')
                                            ->label('Área del Lote')
                                            ->required()
                                            ->numeric()
                                            ->step(0.01)
                                            ->minValue(0.01)
                                            ->suffix('m²')
                                            ->prefixIcon('heroicon-o-arrows-pointing-out')
                                            ->helperText('Superficie total en metros cuadrados'),
                                    ]),

                                // Columna derecha - Precio y Estatus
                                ComponentsGrid::make(1)
                                    ->schema([
                                        TextInput::make('precio')
                                            ->label('Precio de Venta')
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->prefixIcon('heroicon-o-currency-dollar')
                                            ->helperText('Precio de venta del lote en pesos mexicanos'),
                                     
                                        
                                        Select::make('estatus')
                                            ->label('Estatus del Lote')
                                            ->options([
                                                'disponible' => 'Disponible',
                                                'vendido' => 'Vendido',
                                                'liquidado' => 'Liquidado'
                                            ])
                                            ->required()
                                            ->native(false)
                                            ->default('disponible')
                                            ->prefixIcon('heroicon-o-tag')
                                            
                                            ->helperText('Estado actual del lote'),
                                        
                                        Placeholder::make('precio_formateado')
                                            ->label('Precio por m²')
                                            ->content(function ($get) {
                                                $precio = $get('precio') ?? 0;
                                                $area = $get('area') ?? 1;
                                                if ($precio > 0 && $area > 0) {
                                                    $precio_m2 = $precio / $area;
                                                    return '$ ' . number_format($precio_m2, 2) . ' / m²';
                                                }
                                                return 'Calculará automáticamente';
                                            }),
                                    ]),
                            ]),


              

                        // Colindancias
                        ComponentsGrid::make(4)
                            ->schema([
                                TextInput::make('norte')
                                    ->label('Norte')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('0.00')
                                    ->suffix('m')
                                    ->prefixIcon('heroicon-o-arrow-up')
                                    ->prefixIconColor('gray')
                                    ->helperText('Medida colindancia norte'),
                                
                                TextInput::make('sur')
                                    ->label('Sur')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('0.00')
                                    ->suffix('m')
                                    ->prefixIcon('heroicon-o-arrow-down')
                                    ->prefixIconColor('gray')
                                    ->helperText('Medida colindancia sur'),
                                
                                TextInput::make('este')
                                    ->label('Este')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('0.00')
                                    ->suffix('m')
                                    ->prefixIcon('heroicon-o-arrow-right')
                                    ->prefixIconColor('gray')
                                    ->helperText('Medida colindancia este'),
                                
                                TextInput::make('oeste')
                                    ->label('Oeste')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('0.00')
                                    ->suffix('m')
                                    ->prefixIcon('heroicon-o-arrow-left')
                                    ->prefixIconColor('gray')
                                    ->helperText('Medida colindancia oeste'),
                            ]),



                        // Observaciones
                        ComponentsGrid::make(1)
                            ->schema([
                                Textarea::make('observaciones')
                                    ->label('Observaciones')
                                    ->placeholder('Notas adicionales sobre el lote, características especiales, restricciones, etc.')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->helperText('Información adicional relevante')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(false)
                    ->columnSpanFull(),

                
            ]);
    }
}