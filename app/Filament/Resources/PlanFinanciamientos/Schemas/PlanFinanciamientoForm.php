<?php

namespace App\Filament\Resources\PlanFinanciamientos\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlanFinanciamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            // SECCIÓN 1: INFORMACIÓN GENERAL
            Section::make('Información General')
                ->description('Datos básicos del plan de financiamiento')
                ->icon('heroicon-o-document-text')
                ->schema([
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Ej: Plan Premium, Financiamiento Básico, etc.')
                        ->columnSpan(1),
                        
                    Textarea::make('descripcion')
                        ->maxLength(65535)
                        ->rows(3)
                        ->placeholder('Describe los detalles y condiciones del plan...')
                        ->columnSpanFull(),
                        
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
                        ->placeholder('Selecciona la frecuencia de pago')
                        ->columnSpan(1),
                        
                    Toggle::make('activo')
                        ->label('Plan Activo')
                        ->default(true)
                        ->helperText('Los planes inactivos no estarán disponibles para nuevos financiamientos')
                        ->columnSpan(1),
                ]),

            // SECCIÓN 2: CONFIGURACIÓN DE ENGANCHE
            Section::make('Configuración de Enganche')
                ->description('Define las condiciones del pago inicial')
                ->icon('heroicon-o-currency-dollar')
                ->columns(3)
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
                        })
                        ->columnSpan(1),
                        
                    Select::make('modo_enganche')
                        ->label('Modo de Enganche')
                        ->required()
                        ->options([
                            'porcentaje' => 'Porcentaje (%)',
                            'monto' => 'Monto Fijo ($)',
                        ])
                        ->default('porcentaje')
                        ->disabled(fn($get) => $get('tipo_enganche') === 'fijo')
                        ->native(false)
                        ->columnSpan(1),
                        
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
                        })
                        ->columnSpan(1),
                ]),

            // SECCIÓN 3: PLAZOS E INTERESES
            Section::make('Plazos e Intereses')
                ->description('Configuración de pagos y tasas de interés')
                ->icon('heroicon-o-calculator')
                ->columns(3)
                ->schema([
                    TextInput::make('plazo_pagos')
                        ->label('Número de Pagos')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(360)
                        ->step(1)
                        ->helperText('Cantidad de períodos de pago según la frecuencia seleccionada')
                        ->columnSpan(1),
                        
                    Select::make('tipo_interes')
                        ->label('Tipo de Interés')
                        ->required()
                        ->options([
                            'porcentaje' => 'Porcentaje (%)',
                            'fijo' => 'Monto Fijo ($)',
                        ])
                        ->default('porcentaje')
                        ->native(false)
                        ->live()
                        ->columnSpan(1),
                        
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
                        })
                        ->columnSpan(1),
                        
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
                        ->helperText('Período al que se aplica la tasa de interés')
                        ->columnSpan(1),
                ]),

            // SECCIÓN 4: PENALIZACIONES POR RETRASO
            Section::make('Penalizaciones por Retraso')
                ->description('Configuración de cargos por pagos tardíos')
                ->icon('heroicon-o-exclamation-triangle')
                ->columns(3)
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
                        ->live()
                        ->columnSpan(1),
                        
                    Select::make('aplicacion_penalizacion')
                        ->label('Aplicación')
                        ->required()
                        ->options([
                            'unica' => 'Pago Único',
                            'diaria' => 'Diaria (por día de retraso)',
                        ])
                        ->default('unica')
                        ->native(false)
                        ->columnSpan(1),
                        
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
                        })
                        ->columnSpan(1),
                        
                    TextInput::make('dias_gracia')
                        ->label('Días de Gracia')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(30)
                        ->step(1)
                        ->default(0)
                        ->helperText('Días después del vencimiento sin aplicar penalizaciones')
                        ->columnSpan(1),
                ]),

            // SECCIÓN 5: RESUMEN DEL PLAN (colapsable)
            Section::make('Resumen del Plan')
                ->description('Vista previa de la configuración seleccionada')
                ->icon('heroicon-o-eye')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Grid::make(3)
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
                                        return '📊 Mínimo: ' . number_format($valor, 2) . '%';
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
                                    
                                    return '💰 $' . number_format($valor, 2) . ' fijo';
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
                                })
                                ->columnSpan(2),
                        ]),
                ]),
        ]);
    }
}
