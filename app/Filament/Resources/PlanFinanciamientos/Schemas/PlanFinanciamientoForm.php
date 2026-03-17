<?php

namespace App\Filament\Resources\PlanFinanciamientos\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PlanFinanciamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Configuración del Plan de Financiamiento')
                    ->contained(false) // Esto hace que los tabs no estén dentro de un card
                    ->tabs([
                        Tab::make('Información General')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Plan Premium, Financiamiento Básico, etc.'),
                                    
                                Textarea::make('descripcion')
                                    ->maxLength(65535)
                                    ->placeholder('Describe los detalles y condiciones del plan...'),
                                    
                                Select::make('frecuencia_pago')
                                    ->label('Frecuencia de Pago')
                                    ->required()
                                    ->options([
                                        'semanal' => 'Semanal',
                                        'quincenal' => 'Quincenal',
                                        'mensual' => 'Mensual',
                                        'bimestral' => 'Bimestral',
                                        'trimestral' => 'Trimestral',
                                        'semestral' => 'Semestral',
                                        'anual' => 'Anual',
                                    ])
                                    ->default('mensual')
                                    ->native(false)
                                    ->placeholder('Selecciona la frecuencia de pago'),
                                    
                                Toggle::make('activo')
                                    ->label('Plan Activo')
                                    ->default(true)
                                    ->helperText('Los planes inactivos no estarán disponibles para nuevos financiamientos'),
                            ]),

                        Tab::make('Configuración de Enganche')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Select::make('tipo_enganche')
                                    ->label('Tipo de Enganche')
                                    ->required()
                                    ->options([
                                        'fijo' => 'Fijo (Monto específico)',
                                        'minimo' => 'Mínimo (Porcentaje mínimo requerido)',
                                    ])
                                    ->default('fijo')
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state === 'fijo') {
                                            $set('modo_enganche', 'monto');
                                        }
                                    }),
                                    
                                Select::make('modo_enganche')
                                    ->label('Modo de Enganche')
                                    ->required()
                                    ->options([
                                        'porcentaje' => 'Porcentaje (%)',
                                        'monto' => 'Monto Fijo ($)',
                                    ])
                                    ->default('porcentaje')
                                    ->disabled(fn($get) => $get('tipo_enganche') === 'fijo')
                                    ->native(false),
                                    
                                TextInput::make('enganche')
                                    ->label(function ($get) {
                                        $tipo = $get('tipo_enganche');
                                        $modo = $get('modo_enganche');
                                        
                                        if ($tipo === 'fijo') {
                                            return 'Monto de Enganche';
                                        }
                                        
                                        return $modo === 'porcentaje' 
                                            ? 'Porcentaje Mínimo (%)' 
                                            : 'Monto Mínimo ($)';
                                    })
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(function ($get) {
                                        $modo = $get('modo_enganche');
                                        return $modo === 'porcentaje' ? 100 : null;
                                    })
                                    ->step(0.01)
                                    ->suffix(function ($get) {
                                        $tipo = $get('tipo_enganche');
                                        $modo = $get('modo_enganche');
                                        
                                        if ($tipo === 'fijo' || $modo === 'monto') {
                                            return null;
                                        }
                                        return '%';
                                    })
                                    ->prefix(function ($get) {
                                        $tipo = $get('tipo_enganche');
                                        $modo = $get('modo_enganche');
                                        
                                        if ($tipo === 'fijo' || $modo === 'monto') {
                                            return '$';
                                        }
                                        return null;
                                    })
                                    ->helperText(function ($get) {
                                        $tipo = $get('tipo_enganche');
                                        $modo = $get('modo_enganche');
                                        
                                        if ($tipo === 'fijo') {
                                            return 'Monto exacto requerido como enganche';
                                        }
                                        
                                        return $modo === 'porcentaje' 
                                            ? 'Porcentaje mínimo requerido del total a financiar'
                                            : 'Monto mínimo requerido como enganche';
                                    }),
                            ]),

                        Tab::make('Plazos e Intereses')
                            ->icon('heroicon-o-calculator')
                            ->schema([
                                TextInput::make('plazo_pagos')
                                    ->label('Número de Pagos')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(360)
                                    ->step(1)
                                    ->helperText('Cantidad de períodos de pago según la frecuencia seleccionada'),
                                    
                                Select::make('tipo_interes')
                                    ->label('Tipo de Interés')
                                    ->required()
                                    ->options([
                                        'porcentaje' => 'Porcentaje (%)',
                                        'fijo' => 'Monto Fijo ($)',
                                    ])
                                    ->default('porcentaje')
                                    ->native(false)
                                    ->live(),
                                    
                                TextInput::make('valor_interes')
                                    ->label(function ($get) {
                                        return $get('tipo_interes') === 'porcentaje' 
                                            ? 'Tasa de Interés (%)' 
                                            : 'Monto de Interés ($)';
                                    })
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(function ($get) {
                                        return $get('tipo_interes') === 'porcentaje' ? 100 : null;
                                    })
                                    ->step(0.01)
                                    ->suffix(function ($get) {
                                        return $get('tipo_interes') === 'porcentaje' ? '%' : null;
                                    })
                                    ->prefix(function ($get) {
                                        return $get('tipo_interes') === 'fijo' ? '$' : null;
                                    }),
                                    
                                Select::make('periodo_interes')
                                    ->label('Período del Interés')
                                    ->required()
                                    ->options([
                                        'mensual' => 'Mensual',
                                        'anual' => 'Anual',
                                    ])
                                    ->default('mensual')
                                    ->native(false)
                                    ->visible(fn($get) => $get('tipo_interes') === 'porcentaje')
                                    ->helperText('Período al que se aplica la tasa de interés'),
                            ]),

                        Tab::make('Penalizaciones por Retraso')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->schema([
                                Select::make('tipo_penalizacion')
                                    ->label('Tipo de Penalización')
                                    ->required()
                                    ->options([
                                        'porcentaje' => 'Porcentaje (%)',
                                        'fijo' => 'Monto Fijo ($)',
                                    ])
                                    ->default('porcentaje')
                                    ->native(false)
                                    ->live(),
                                    
                                Select::make('aplicacion_penalizacion')
                                    ->label('Aplicación')
                                    ->required()
                                    ->options([
                                        'unica' => 'Pago Único',
                                        'diaria' => 'Diaria (por día de retraso)',
                                    ])
                                    ->default('unica')
                                    ->native(false),
                                    
                                TextInput::make('penalizacion')
                                    ->label(function ($get) {
                                        return $get('tipo_penalizacion') === 'porcentaje' 
                                            ? 'Valor de Penalización (%)' 
                                            : 'Monto de Penalización ($)';
                                    })
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(function ($get) {
                                        return $get('tipo_penalizacion') === 'porcentaje' ? 100 : null;
                                    })
                                    ->step(0.01)
                                    ->suffix(function ($get) {
                                        return $get('tipo_penalizacion') === 'porcentaje' ? '%' : null;
                                    })
                                    ->prefix(function ($get) {
                                        return $get('tipo_penalizacion') === 'fijo' ? '$' : null;
                                    }),
                                    
                                TextInput::make('dias_gracia')
                                    ->label('Días de Gracia')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(30)
                                    ->step(1)
                                    ->default(0)
                                    ->helperText('Días después del vencimiento sin aplicar penalizaciones'),
                            ]),

                        Tab::make('Resumen del Plan')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Placeholder::make('resumen_enganche')
                                    ->label('Enganche')
                                    ->content(function ($get) {
                                        $tipo = $get('tipo_enganche');
                                        $modo = $get('modo_enganche');
                                        $valor = $get('enganche') ?? 0;
                                        
                                        if ($tipo === 'fijo') {
                                            return 'Fijo: $' . number_format($valor, 2);
                                        }
                                        
                                        if ($modo === 'porcentaje') {
                                            return 'Mínimo: ' . number_format($valor, 2) . '%';
                                        }
                                        
                                        return 'Mínimo: $' . number_format($valor, 2);
                                    }),
                                    
                                Placeholder::make('resumen_pagos')
                                    ->label('Pagos')
                                    ->content(function ($get) {
                                        $frecuencia = $get('frecuencia_pago') ?? 'mensual';
                                        $plazo = $get('plazo_pagos') ?? 0;
                                        
                                        $frecuenciaMap = [
                                            'semanal' => 'semanales',
                                            'quincenal' => 'quincenales',
                                            'mensual' => 'mensuales',
                                            'bimestral' => 'bimestrales',
                                            'trimestral' => 'trimestrales',
                                            'semestral' => 'semestrales',
                                            'anual' => 'anuales',
                                        ];
                                        
                                        return  $plazo . ' pagos ' . ($frecuenciaMap[$frecuencia] ?? $frecuencia);
                                    }),
                                    
                                Placeholder::make('resumen_interes')
                                    ->label('Interés')
                                    ->content(function ($get) {
                                        $tipo = $get('tipo_interes') ?? 'porcentaje';
                                        $valor = $get('valor_interes') ?? 0;
                                        $periodo = $get('periodo_interes') ?? 'mensual';
                                        
                                        if ($tipo === 'porcentaje') {
                                            return number_format($valor, 2) . '% ' . $periodo;
                                        }
                                        
                                        return '$' . number_format($valor, 2) . ' fijo';
                                    }),
                                    
                                Placeholder::make('resumen_penalizacion')
                                    ->label('Penalización')
                                    ->content(function ($get) {
                                        $tipo = $get('tipo_penalizacion') ?? 'porcentaje';
                                        $aplicacion = $get('aplicacion_penalizacion') ?? 'unica';
                                        $valor = $get('penalizacion') ?? 0;
                                        $dias = $get('dias_gracia') ?? 0;
                                        
                                        $valorStr = $tipo === 'porcentaje' 
                                            ? number_format($valor, 2) . '%' 
                                            : '$' . number_format($valor, 2);
                                        
                                        $aplicacionStr = $aplicacion === 'unica' ? 'única' : 'diaria';
                                        
                                        return  $valorStr . ' (' . $aplicacionStr . ')' . "\n" . $dias . ' días de gracia';
                                    }),
                            ]),
                    ])
                    ->persistTabInQueryString() // Opcional: guarda la pestaña activa en la URL
                    ->columnSpanFull() // Esto asegura que ocupe todo el ancho disponible
                    ->contained(true)
            ]);
    }
}