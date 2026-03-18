<?php

namespace App\Filament\Resources\Ventas\Schemas;

use App\Models\Lotes;
use App\Models\PlanFinanciamiento;
use App\Models\Clientes;
use App\Models\Fraccionamiento;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Components\Wizard as ComponentsWizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class VentasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsWizard::make([
                // PASO 1: FRACCIONAMIENTO Y LOTES
                Step::make('fraccionamiento_lotes')
                    ->label('Fraccionamiento y Lotes')
                    ->icon('heroicon-m-building-office')
                    ->schema([
                        Select::make('fraccionamiento_id')
                            ->label('Fraccionamiento')
                            ->relationship('fraccionamiento', 'nombre')
                            ->searchable()
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function ($set) {
                                $set('lotes', []);
                                $set('subtotal', 0);
                                $set('total', 0);
                            }),

                        // INFO FRACCIONAMIENTO - SOLO COMPONENTES FILAMENT
                        ComponentsSection::make('Información del Fraccionamiento')
                            ->icon('heroicon-m-map')
                            ->schema([
                                ComponentsGrid::make(3)
                                    ->schema([
                                        Placeholder::make('fracc_nombre')
                                            ->label('Nombre')
                                            ->content(fn ($get) => Fraccionamiento::find($get('fraccionamiento_id'))?->nombre ?? '-'),
                                        
                                        Placeholder::make('fracc_ubicacion')
                                            ->label('Ubicación')
                                            ->content(fn ($get) => Fraccionamiento::find($get('fraccionamiento_id'))?->ubicacion ?? '-'),
                                        
                                        Placeholder::make('fracc_cp')
                                            ->label('Código Postal')
                                            ->content(fn ($get) => Fraccionamiento::find($get('fraccionamiento_id'))?->codigo_postal ?? '-'),
                                        
                                        Placeholder::make('fracc_area')
                                            ->label('Área Total')
                                            ->content(fn ($get) => number_format(floatval(Fraccionamiento::find($get('fraccionamiento_id'))?->area_total ?? 0), 2) . ' m²'),
                                        
                                        Placeholder::make('fracc_manzanas')
                                            ->label('Total Manzanas')
                                            ->content(fn ($get) => Fraccionamiento::find($get('fraccionamiento_id'))?->total_manzanas ?? '-'),
                                        
                                        Placeholder::make('fracc_lotes')
                                            ->label('Lotes Disponibles')
                                            ->content(function ($get) {
                                                $f = Fraccionamiento::withCount('lotes')->find($get('fraccionamiento_id'));
                                                if (!$f) return '-';
                                                
                                                $disponibles = $f->lotes()->where('estatus', 'disponible')->count();
                                                return $disponibles . '/' . ($f->lotes_count ?? 0);
                                            })
                                            ->extraAttributes(['class' => 'text-success-600 font-medium']),
                                    ]),
                                
                                Placeholder::make('fracc_descripcion')
                                    ->label('Descripción')
                                    ->content(fn ($get) => Fraccionamiento::find($get('fraccionamiento_id'))?->descripcion ?? '-')
                                    ->visible(fn ($get) => Fraccionamiento::find($get('fraccionamiento_id'))?->descripcion),
                            ])
                            ->visible(fn ($get) => $get('fraccionamiento_id'))
                            ->collapsible(false),

                        Select::make('lotes')
                            ->label('Selección de Lotes')
                            ->multiple()
                            ->searchable()
                            ->options(function ($get) {
                                $id = $get('fraccionamiento_id');
                                if (!$id) return [];
                                
                                return Lotes::where('fraccionamiento_id', $id)
                                    ->where('estatus', 'disponible')
                                    ->get()
                                    ->mapWithKeys(fn ($l) => [
                                        $l->id => "{$l->nombre} - $" . number_format(floatval($l->precio), 2)
                                    ]);
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                $lotes = Lotes::whereIn('id', $state ?? [])->get();
                                $total = $lotes->sum('precio');
                                $set('subtotal', floatval($total));
                                $set('total', floatval($total));
                            })
                            ->required()
                            ->helperText('Seleccione uno o más lotes disponibles'),

                        // RESUMEN LOTES - USANDO GRID DE FILAMENT (SIN FONDO BLANCO)
                        ComponentsSection::make('Lotes Seleccionados')
                            ->schema(function ($get) {
                                $ids = $get('lotes') ?? [];
                                if (empty($ids)) return [];
                                
                                $lotes = Lotes::whereIn('id', $ids)->get();
                                $total = $lotes->sum('precio');
                                
                                $schema = [];
                                
                                foreach ($lotes as $index => $l) {
                                    $schema[] = ComponentsGrid::make(12)
                                        ->schema([
                                            Placeholder::make("lote_nombre_{$index}")
                                                ->label('')
                                                ->content($l->nombre)
                                                ->columnSpan(3),
                                            
                                            Placeholder::make("lote_manzana_{$index}")
                                                ->label('')
                                                ->content('MZ ' . $l->manzana)
                                                ->columnSpan(2),
                                            
                                            Placeholder::make("lote_area_{$index}")
                                                ->label('')
                                                ->content(number_format(floatval($l->area), 2) . ' m²')
                                                ->columnSpan(2),
                                            
                                            Placeholder::make("lote_precio_{$index}")
                                                ->label('')
                                                ->content('$' . number_format(floatval($l->precio), 2))
                                                ->columnSpan(3)
                                                ->extraAttributes(['class' => 'text-right font-medium']),
                                        ]);
                                }
                                
                                // Total
                                $schema[] = ComponentsGrid::make(2)
                                    ->schema([
                                        Placeholder::make('resumen_total_label')
                                            ->label('')
                                            ->content('TOTAL')
                                            ->extraAttributes(['class' => 'font-bold text-right']),
                                        
                                        Placeholder::make('resumen_total_valor')
                                            ->label('')
                                            ->content('$' . number_format(floatval($total), 2))
                                            ->extraAttributes(['class' => 'font-bold text-right text-success-600']),
                                    ]);
                                
                                return $schema;
                            })
                            ->visible(fn ($get) => !empty($get('lotes')))
                            ->collapsible(true),
                    ]),

                // PASO 2: CLIENTE - IGUAL CON COMPONENTES PUROS
                Step::make('cliente')
                    ->label('Cliente')
                    ->icon('heroicon-m-user')
                    ->schema([
                        Select::make('cliente_id')
                            ->label('Cliente')
                            ->relationship('cliente', 'nombre')
                            ->searchable(['nombre', 'apellidos', 'curp', 'rfc'])
                            ->required()
                            ->reactive()
                            ->createOptionForm([
                                ComponentsGrid::make(2)->schema([
                                    TextInput::make('nombre')->required(),
                                    TextInput::make('apellidos')->required(),
                                    TextInput::make('telefono'),
                                    DatePicker::make('fecha_nacimiento'),
                                    TextInput::make('curp'),
                                    TextInput::make('rfc'),
                                    TextInput::make('ocupacion'),
                                    Select::make('estado_civil')
                                        ->options([
                                            'soltero' => 'Soltero/a',
                                            'casado' => 'Casado/a',
                                            'otro' => 'Otro',
                                        ]),
                                ]),
                            ]),

                        ComponentsSection::make('Información del Cliente')
                            ->icon('heroicon-m-user-circle')
                            ->schema([
                                ComponentsGrid::make(3)
                                    ->schema([
                                        Placeholder::make('cliente_nombre')
                                            ->label('Nombre completo')
                                            ->content(fn ($get) => Clientes::find($get('cliente_id'))?->nombre . ' ' . (Clientes::find($get('cliente_id'))?->apellidos ?? '')),
                                        
                                        Placeholder::make('cliente_edad')
                                            ->label('Edad')
                                            ->content(function ($get) {
                                                $c = Clientes::find($get('cliente_id'));
                                                return $c?->fecha_nacimiento ? \Carbon\Carbon::parse($c->fecha_nacimiento)->age . ' años' : 'N/A';
                                            }),
                                        
                                        Placeholder::make('cliente_telefono')
                                            ->label('Teléfono')
                                            ->content(fn ($get) => Clientes::find($get('cliente_id'))?->telefono ?? 'N/A'),
                                        
                                        Placeholder::make('cliente_curp')
                                            ->label('CURP')
                                            ->content(fn ($get) => Clientes::find($get('cliente_id'))?->curp ?? 'N/A')
                                            ->extraAttributes(['class' => 'font-mono']),
                                        
                                        Placeholder::make('cliente_rfc')
                                            ->label('RFC')
                                            ->content(fn ($get) => Clientes::find($get('cliente_id'))?->rfc ?? 'N/A')
                                            ->extraAttributes(['class' => 'font-mono']),
                                        
                                        Placeholder::make('cliente_ocupacion')
                                            ->label('Ocupación')
                                            ->content(fn ($get) => Clientes::find($get('cliente_id'))?->ocupacion ?? 'N/A'),
                                        
                                        Placeholder::make('cliente_ciudad')
                                            ->label('Ciudad')
                                            ->content(fn ($get) => Clientes::find($get('cliente_id'))?->ciudad ?? 'N/A'),

                                    ]),
                            ])
                            ->visible(fn ($get) => $get('cliente_id'))
                            ->collapsible(false),
                    ]),

                // PASO 3: PLAN DE FINANCIAMIENTO
                Step::make('financiamiento')
                    ->label('Plan de Financiamiento')
                    ->icon('heroicon-m-calculator')
                    ->schema([
                        Select::make('plan_financiamiento_id')
                            ->label('Plan')
                            ->relationship('planFinanciamiento', 'nombre')
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $plan = PlanFinanciamiento::find($state);
                                $total = floatval($get('total') ?? 0);
                                
                                if ($plan) {
                                    $enganche = $plan->modo_enganche === 'porcentaje'
                                        ? ($total * floatval($plan->enganche)) / 100
                                        : floatval($plan->enganche);
                                    $set('enganche_aplicado', $enganche);
                                }
                            }),

                        ComponentsSection::make('Detalles del Plan')
                            ->schema([
                                ComponentsGrid::make(2)
                                    ->schema([
                                        Placeholder::make('plan_nombre')
                                            ->label('Plan')
                                            ->content(fn ($get) => PlanFinanciamiento::find($get('plan_financiamiento_id'))?->nombre ?? '-'),
                                        
                                        Placeholder::make('plan_descripcion')
                                            ->label('Descripción')
                                            ->content(fn ($get) => PlanFinanciamiento::find($get('plan_financiamiento_id'))?->descripcion ?? '-'),
                                        
                                        Placeholder::make('plan_enganche')
                                            ->label('Enganche')
                                            ->content(function ($get) {
                                                $p = PlanFinanciamiento::find($get('plan_financiamiento_id'));
                                                if (!$p) return '-';
                                                
                                                return $p->modo_enganche === 'porcentaje' 
                                                    ? $p->enganche . '%' 
                                                    : '$' . number_format(floatval($p->enganche), 2);
                                            }),
                                        
                                        Placeholder::make('plan_plazo')
                                            ->label('Plazo')
                                            ->content(fn ($get) => PlanFinanciamiento::find($get('plan_financiamiento_id'))?->plazo_pagos . ' pagos' ?? '-'),
                                        
                                        Placeholder::make('plan_frecuencia')
                                            ->label('Frecuencia')
                                            ->content(function ($get) {
                                                $f = PlanFinanciamiento::find($get('plan_financiamiento_id'))?->frecuencia_pago;
                                                return match($f) {
                                                    'semanal' => 'Semanal',
                                                    'quincenal' => 'Quincenal',
                                                    'mensual' => 'Mensual',
                                                    'bimestral' => 'Bimestral',
                                                    default => $f ?? '-'
                                                };
                                            }),
                                        
                                        Placeholder::make('plan_interes')
                                            ->label('Interés')
                                            ->content(function ($get) {
                                                $p = PlanFinanciamiento::find($get('plan_financiamiento_id'));
                                                if (!$p) return '-';
                                                
                                                return $p->tipo_interes === 'porcentaje' 
                                                    ? $p->valor_interes . '%' 
                                                    : '$' . number_format(floatval($p->valor_interes), 2);
                                            }),
                                    ]),
                                
                                ComponentsGrid::make(2)
                                    ->schema([
                                        Placeholder::make('plan_total')
                                            ->label('Total de la venta')
                                            ->content(fn ($get) => '$' . number_format(floatval($get('total') ?? 0), 2))
                                            ->extraAttributes(['class' => 'font-medium']),
                                        
                                        Placeholder::make('plan_enganche_calculado')
                                            ->label('Enganche a pagar')
                                            ->content(function ($get) {
                                                $plan = PlanFinanciamiento::find($get('plan_financiamiento_id'));
                                                $total = floatval($get('total') ?? 0);
                                                
                                                if (!$plan) return '-';
                                                
                                                $enganche = $plan->modo_enganche === 'porcentaje'
                                                    ? ($total * floatval($plan->enganche)) / 100
                                                    : floatval($plan->enganche);
                                                    
                                                return '$' . number_format($enganche, 2);
                                            })
                                            ->extraAttributes(['class' => 'font-bold text-warning-600']),
                                    ])
                                    ->visible(fn ($get) => $get('plan_financiamiento_id')),
                            ])
                            ->visible(fn ($get) => $get('plan_financiamiento_id'))
                            ->collapsible(false),
                    ]),

                // PASO 4: PAGO
                Step::make('pago')
                    ->label('Pago')
                    ->icon('heroicon-m-currency-dollar')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->formatStateUsing(function ($state) {
                                        if (is_numeric($state)) {
                                            return number_format(floatval($state), 2);
                                        }
                                        if (is_string($state)) {
                                            $clean = preg_replace('/[^0-9.-]/', '', $state);
                                            if (is_numeric($clean)) {
                                                return number_format(floatval($clean), 2);
                                            }
                                        }
                                        return '0.00';
                                    }),
                                
                                TextInput::make('descuento')
                                    ->label('Descuento')
                                    ->prefix('$')
                                    ->numeric()
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        // Limpiar el subtotal si viene formateado
                                        $subtotalRaw = $get('subtotal');
                                        if (is_string($subtotalRaw)) {
                                            $subtotalRaw = preg_replace('/[^0-9.-]/', '', $subtotalRaw);
                                        }
                                        $subtotal = floatval($subtotalRaw ?? 0);
                                        $descuento = floatval($state ?? 0);
                                        $total = max(0, $subtotal - $descuento);
                                        $set('total', $total);
                                        
                                        // Recalcular enganche si hay plan
                                        $planId = $get('plan_financiamiento_id');
                                        if ($planId) {
                                            $plan = PlanFinanciamiento::find($planId);
                                            if ($plan) {
                                                $enganche = $plan->modo_enganche === 'porcentaje'
                                                    ? ($total * floatval($plan->enganche)) / 100
                                                    : floatval($plan->enganche);
                                                $set('enganche_aplicado', $enganche);
                                            }
                                        }
                                    }),
                                
                                TextInput::make('total')
                                    ->label('Total')
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->formatStateUsing(function ($state) {
                                        if (is_numeric($state)) {
                                            return number_format(floatval($state), 2);
                                        }
                                        if (is_string($state)) {
                                            $clean = preg_replace('/[^0-9.-]/', '', $state);
                                            if (is_numeric($clean)) {
                                                return number_format(floatval($clean), 2);
                                            }
                                        }
                                        return '0.00';
                                    }),
                                
                                TextInput::make('enganche_aplicado')
                                    ->label('Enganche')
                                    ->prefix('$')
                                    ->numeric()
                                    ->required()
                                    ->formatStateUsing(function ($state) {
                                        if (is_numeric($state)) {
                                            return number_format(floatval($state), 2);
                                        }
                                        if (is_string($state)) {
                                            $clean = preg_replace('/[^0-9.-]/', '', $state);
                                            if (is_numeric($clean)) {
                                                return number_format(floatval($clean), 2);
                                            }
                                        }
                                        return '0.00';
                                    }),
                            ]),
                        
                        ComponentsGrid::make(2)
                            ->schema([
                                DatePicker::make('fecha_venta')
                                    ->label('Fecha de Venta')
                                    ->default(now())
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y'),
                                
                                Select::make('metodo_pago')
                                    ->label('Método de Pago')
                                    ->options([
                                        'efectivo' => 'Efectivo',
                                        'transferencia' => 'Transferencia Bancaria',
                                        'tarjeta' => 'Tarjeta de Crédito/Débito',
                                    ])
                                    ->required()
                                    ->native(false),
                            ]),
                        
                        ComponentsGrid::make(2)
                            ->schema([
                                FileUpload::make('comprobante_pago')
                                    ->label('Comprobante de Pago')
                                    ->directory('comprobantes')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->maxSize(5120)
                                    ->columnSpanFull(),
                                
                                Textarea::make('observaciones')
                                    ->label('Observaciones')
                                    ->placeholder('Notas adicionales sobre la venta...')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                    ]),
            ])
            ->skippable()
            ->persistStepInQueryString()
            ->columnSpanFull(),
        ]);
    }
}