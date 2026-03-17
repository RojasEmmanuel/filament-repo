<?php

namespace App\Filament\Resources\Fraccionamientos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Support\Icons\Heroicon;

class FraccionamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Información del Fraccionamiento')
                    ->description('Datos generales y características del fraccionamiento')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                // Columna izquierda
                                ComponentsGrid::make(1)
                                    ->schema([
                                        TextInput::make('nombre')
                                            ->label('Nombre del Fraccionamiento')
                                            ->required()
                                            ->placeholder('Ej: Los Pinos, Villas del Sol, etc.')
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-o-building-office')
                                            ->helperText('Nombre oficial del fraccionamiento'),
                                        
                                        TextInput::make('ubicacion')
                                            ->label('Ubicación')
                                            ->required()
                                            ->placeholder('Ej: Carretera a Chapala km 5, Guadalajara')
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-o-map-pin')
                                            ->helperText('Dirección o referencia de ubicación'),
                                        
                                        Textarea::make('descripcion')
                                            ->label('Descripción')
                                            ->placeholder('Describe las características, amenidades, etc.')
                                            ->rows(4)
                                            ->maxLength(1000)
                                            ->helperText('Información adicional sobre el fraccionamiento')
                                            ->columnSpanFull(),
                                    ]),

                                // Columna derecha - Estadísticas
                                ComponentsGrid::make(2)
                                    ->schema([
                                        TextInput::make('total_manzanas')
                                            ->label('Manzanas')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->prefixIcon('heroicon-o-squares-2x2')
                                            ->helperText('Número total de manzanas'),
                                        
                                        TextInput::make('total_lotes')
                                            ->label('Lotes')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->prefixIcon('heroicon-o-rectangle-stack')
                                            ->helperText('Número total de lotes'),
                                        
                                        TextInput::make('area_total')
                                            ->label('Área Total')
                                            ->numeric()
                                            ->default(0)
                                            ->step(0.01)
                                            ->required()
                                            ->suffix('m²')
                                            ->prefixIcon('heroicon-o-arrows-pointing-out')
                                            ->helperText('Superficie total en metros cuadrados'),
                                        
                                        TextInput::make('perimetro')
                                            ->label('Perímetro')
                                            ->numeric()
                                            ->default(0)
                                            ->step(0.01)
                                            ->required()
                                            ->suffix('km')
                                            ->prefixIcon('heroicon-o-arrow-path-rounded-square')
                                            ->helperText('Perímetro del fraccionamiento en kilómetros'),
                                    ])
                                    ->columnSpan(1),
                            ]),

                        // Código postal y estado
                        ComponentsGrid::make(3)
                            ->schema([
                                TextInput::make('codigo_postal')
                                    ->label('Código Postal')
                                    ->numeric()
                                    ->placeholder('Ej: 45100')
                                    ->maxLength(5)
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->prefixIconColor('purple')
                                    ->helperText('Código postal de 5 dígitos')
                                    ->rules(['digits:5'])
                                    ->validationMessages([
                                        'digits' => 'El código postal debe tener exactamente 5 dígitos',
                                    ]),
                                
                                Toggle::make('activo')
                                    ->label('Fraccionamiento Activo')
                                    ->default(true)
                                    ->helperText('Desactivar si el fraccionamiento no está disponible')
                                    ->onColor('primary')
                                    ->offColor('danger'),

                                FileUpload::make('imagen')
                                    ->label('Imagen del Fraccionamiento')
                                    ->image()
                                    ->disk('public')
                                    ->directory('fraccionamientos')
                                    ->loadingIndicatorPosition('center')
                                    ->panelLayout('integrated')
                                    ->uploadingMessage('Subiendo imagen...')
                                    ->removeUploadedFileButtonPosition('center')
                                    ->imageEditor()
                                    
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->helperText('Formatos: JPG, PNG, WEBP (Máx. 5MB)'),
                                
                              
                            ]),

                    ])
                    ->collapsible(false)
                    ->compact(false)
                    ->columnSpanFull(),

                
            ]);
    }
}