<?php

namespace App\Filament\Resources\Ventas\Schemas;

use App\Models\Lotes;
use App\Models\PlanFinanciamiento;
use App\Models\Clientes;
use App\Models\Fraccionamiento;
use Filament\Actions\Action;
use Filament\Forms\Components\Wizard;
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
                    ->completedIcon('heroicon-m-check-badge')
                    ->columns(2)
                    ->schema([
                        // Fraccionamiento
                        Select::make('fraccionamiento_id')
                            ->label('Fraccionamiento')
                            ->relationship('fraccionamiento', 'nombre')
                            ->searchable()
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function ($set) {
                                $set('lotes', null);
                                $set('subtotal', 0);
                                $set('total', 0);
                            })
                            ->suffixAction(
                                Action::make('ver_fraccionamiento')
                                    ->icon('heroicon-m-information-circle')
                                    ->color('info')
                                    ->modalHeading('Detalles del Fraccionamiento')
                                    ->modalContent(function ($get) {
                                        $fraccionamientoId = $get('fraccionamiento_id');
                                        if (!$fraccionamientoId) return null;
                                        
                                        $fraccionamiento = Fraccionamiento::find($fraccionamientoId);
                                        if (!$fraccionamiento) return null;
                                        
                                        return new HtmlString("
                                            <div class='space-y-2'>
                                                <h3 class='text-lg font-bold'>{$fraccionamiento->nombre}</h3>
                                                <p><strong>Ubicación:</strong> {$fraccionamiento->ubicacion}</p>
                                                <p><strong>Código Postal:</strong> {$fraccionamiento->codigo_postal}</p>
                                                <p><strong>Área Total:</strong> " . number_format($fraccionamiento->area_total, 2) . " m²</p>
                                                <p><strong>Total Manzanas:</strong> {$fraccionamiento->total_manzanas}</p>
                                                <p><strong>Total Lotes:</strong> {$fraccionamiento->total_lotes}</p>
                                                <p><strong>Descripción:</strong> {$fraccionamiento->descripcion}</p>
                                            </div>
                                        ");
                                    })
                                    ->modalSubmitAction(false)
                                    ->modalCancelActionLabel('Cerrar')
                            ),
                        
                        // Información del Fraccionamiento (Placeholder)
                        Placeholder::make('info_fraccionamiento')
                            ->label('Información del Fraccionamiento')
                            ->content(function ($get) {
                                $fraccionamientoId = $get('fraccionamiento_id');
                                if (!$fraccionamientoId) return 'Seleccione un fraccionamiento';
                                
                                $fraccionamiento = Fraccionamiento::find($fraccionamientoId);
                                if (!$fraccionamiento) return 'No disponible';
                                
                                return new HtmlString("
                                    <div class='bg-gray-50 p-3 rounded-lg'>
                                        <p class='text-sm'><span class='font-medium'>Ubicación:</span> {$fraccionamiento->ubicacion}</p>
                                        <p class='text-sm'><span class='font-medium'>CP:</span> {$fraccionamiento->codigo_postal}</p>
                                        <p class='text-sm'><span class='font-medium'>Lotes disponibles:</span> " . $fraccionamiento->lotes()->where('estatus', 'disponible')->count() . "</p>
                                    </div>
                                ");
                            })
                            ->columnSpan(1),
                        
                        // Lotes (ocupa 2 columnas)
                        Select::make('lotes')
                            ->label('Lotes Disponibles')
                            ->multiple()
                            ->searchable()
                            ->options(function ($get) {
                                $fraccionamientoId = $get('fraccionamiento_id');
                                if (!$fraccionamientoId) return [];
                                
                                return Lotes::where('fraccionamiento_id', $fraccionamientoId)
                                    ->where('estatus', 'disponible')
                                    ->get()
                                    ->mapWithKeys(function ($lote) {
                                        return [
                                            $lote->id => "{$lote->nombre} - $" . number_format($lote->precio, 2) . " MXN"
                                        ];
                                    });
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                $lotes = Lotes::whereIn('id', $state ?? [])->get();
                                $subtotal = $lotes->sum('precio');
                                
                                $set('subtotal', $subtotal);
                                $set('total', $subtotal);
                            })
                            ->required()
                            ->helperText('Seleccione uno o más lotes disponibles')
                            ->columnSpanFull(),
                        
                        // Resumen de lotes seleccionados
                        Placeholder::make('resumen_lotes')
                            ->label('Resumen de Lotes Seleccionados')
                            ->content(function ($get) {
                                $loteIds = $get('lotes') ?? [];
                                if (empty($loteIds)) return 'No hay lotes seleccionados';
                                
                                $lotes = Lotes::whereIn('id', $loteIds)->get();
                                
                                $html = '<div class="space-y-2">';
                                foreach ($lotes as $lote) {
                                    $html .= "
                                        <div class='flex justify-between items-center p-2 bg-gray-50 rounded'>
                                            <div>
                                                <span class='font-medium'>{$lote->nombre}</span>
                                                <span class='text-xs text-gray-500 ml-2'>MZ {$lote->manzana} - LT {$lote->lote}</span>
                                            </div>
                                            <div>
                                                <span class='font-bold text-success-600'>$" . number_format($lote->precio, 2) . "</span>
                                            </div>
                                        </div>
                                    ";
                                }
                                $html .= '</div>';
                                
                                return new HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ]),
                
                // PASO 2: CLIENTE
                Step::make('cliente')
                    ->label('Información del Cliente')
                    ->icon('heroicon-m-user')
                    ->completedIcon('heroicon-m-check-badge')
                    ->columns(2)
                    ->schema([
                        Select::make('cliente_id')
                            ->label('Cliente')
                            ->relationship('cliente', 'nombre')
                            ->searchable(['nombre', 'apellidos', 'curp', 'rfc'])
                            ->required()
                            ->reactive()
                            ->createOptionForm([
                                ComponentsGrid::make(2)
                                    ->schema([
                                        TextInput::make('nombre')->required(),
                                        TextInput::make('apellidos')->required(),
                                        TextInput::make('email')->email(),
                                        TextInput::make('telefono'),
                                        DatePicker::make('fecha_nacimiento'),
                                        TextInput::make('curp'),
                                        TextInput::make('rfc'),
                                        TextInput::make('ocupacion'),
                                        Select::make('estado_civil')
                                            ->options([
                                                'soltero' => 'Soltero/a',
                                                'casado' => 'Casado/a',
                                                'divorciado' => 'Divorciado/a',
                                                'viudo' => 'Viudo/a',
                                            ]),
                                        TextInput::make('ciudad'),
                                    ]),
                            ])
                            ->suffixAction(
                                Action::make('ver_cliente')
                                    ->icon('heroicon-m-information-circle')
                                    ->color('info')
                                    ->modalHeading('Información Completa del Cliente')
                                    ->modalContent(function ($get) {
                                        $clienteId = $get('cliente_id');
                                        if (!$clienteId) return null;
                                        
                                        $cliente = Clientes::find($clienteId);
                                        if (!$cliente) return null;
                                        
                                        $edad = $cliente->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->age : 'N/A';
                                        
                                        return new HtmlString("
                                            <div class='space-y-4'>
                                                <div class='bg-gray-50 p-4 rounded-lg'>
                                                    <h3 class='text-lg font-bold mb-3'>Datos Personales</h3>
                                                    <div class='grid grid-cols-2 gap-4'>
                                                        <div><strong>Nombre:</strong> {$cliente->nombre} {$cliente->apellidos}</div>
                                                        <div><strong>Edad:</strong> {$edad} años</div>
                                                        <div><strong>Estado Civil:</strong> " . ucfirst($cliente->estado_civil ?? 'N/A') . "</div>
                                                        <div><strong>Ocupación:</strong> {$cliente->ocupacion}</div>
                                                    </div>
                                                </div>
                                                
                                                <div class='bg-gray-50 p-4 rounded-lg'>
                                                    <h3 class='text-lg font-bold mb-3'>Documentación</h3>
                                                    <div class='grid grid-cols-2 gap-4'>
                                                        <div><strong>CURP:</strong> {$cliente->curp}</div>
                                                        <div><strong>RFC:</strong> {$cliente->rfc}</div>
                                                    </div>
                                                </div>
                                                
                                                <div class='bg-gray-50 p-4 rounded-lg'>
                                                    <h3 class='text-lg font-bold mb-3'>Contacto</h3>
                                                    <div class='grid grid-cols-2 gap-4'>
                                                        <div><strong>Teléfono:</strong> {$cliente->telefono}</div>
                                                        <div><strong>Ciudad:</strong> {$cliente->ciudad}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        ");
                                    })
                                    ->modalSubmitAction(false)
                                    ->modalCancelActionLabel('Cerrar')
                            ),
                        
                        // Información del Cliente (Placeholder)
                        Placeholder::make('info_cliente')
                            ->label('Información del Cliente')
                            ->content(function ($get) {
                                $clienteId = $get('cliente_id');
                                if (!$clienteId) return 'Seleccione un cliente';
                                
                                $cliente = Clientes::find($clienteId);
                                if (!$cliente) return 'No disponible';
                                
                                $edad = $cliente->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->age : 'N/A';
                                
                                return new HtmlString("
                                    <div class='bg-gray-50 p-3 rounded-lg space-y-2'>
                                        <p><span class='font-medium'>Nombre completo:</span> {$cliente->nombre} {$cliente->apellidos}</p>
                                        <p><span class='font-medium'>Edad:</span> {$edad} años</p>
                                        <p><span class='font-medium'>Teléfono:</span> {$cliente->telefono}</p>
                                        <p><span class='font-medium'>CURP:</span> {$cliente->curp}</p>
                                    </div>
                                ");
                            })
                            ->columnSpan(1),
                    ]),
                
                // PASO 3: PLAN DE FINANCIAMIENTO
                Step::make('financiamiento')
                    ->label('Plan de Financiamiento')
                    ->icon('heroicon-m-calculator')
                    ->completedIcon('heroicon-m-check-badge')
                    ->columns(2)
                    ->schema([
                        Select::make('plan_financiamiento_id')
                            ->label('Plan de Financiamiento')
                            ->relationship('planFinanciamiento', 'nombre')
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $plan = PlanFinanciamiento::find($state);
                                $subtotal = $get('subtotal') ?? 0;
                                
                                if (!$plan) return;
                                
                                $enganche = $plan->modo_enganche === 'porcentaje'
                                    ? ($subtotal * $plan->enganche) / 100
                                    : $plan->enganche;
                                
                                $set('enganche_aplicado', $enganche);
                                
                                // Calcular pago aproximado si es crédito
                                if ($plan->tipo_enganche === 'credito') {
                                    $saldo = $subtotal - $enganche;
                                    $interes = $plan->tipo_interes === 'porcentaje' 
                                        ? ($saldo * $plan->valor_interes) / 100 
                                        : $plan->valor_interes;
                                    
                                    $pagoMensual = ($saldo + $interes) / $plan->plazo_pagos;
                                    $set('pago_mensual_aprox', $pagoMensual);
                                }
                            })
                            ->suffixAction(
                                Action::make('ver_plan')
                                    ->icon('heroicon-m-information-circle')
                                    ->color('info')
                                    ->modalHeading('Detalles del Plan')
                                    ->modalContent(function ($get) {
                                        $planId = $get('plan_financiamiento_id');
                                        if (!$planId) return null;
                                        
                                        $plan = PlanFinanciamiento::find($planId);
                                        if (!$plan) return null;
                                        
                                        $frecuencia = [
                                            'semanal' => 'Semanal',
                                            'quincenal' => 'Quincenal',
                                            'mensual' => 'Mensual',
                                            'bimestral' => 'Bimestral',
                                        ][$plan->frecuencia_pago] ?? $plan->frecuencia_pago;
                                        
                                        return new HtmlString("
                                            <div class='space-y-4'>
                                                <div class='bg-gray-50 p-4 rounded-lg'>
                                                    <h3 class='text-lg font-bold mb-3'>{$plan->nombre}</h3>
                                                    <p class='text-gray-600 mb-3'>{$plan->descripcion}</p>
                                                    
                                                    <div class='grid grid-cols-2 gap-4'>
                                                        <div><strong>Frecuencia:</strong> {$frecuencia}</div>
                                                        <div><strong>Tipo:</strong> " . ucfirst($plan->tipo_enganche) . "</div>
                                                        <div><strong>Enganche:</strong> " . ($plan->modo_enganche === 'porcentaje' ? $plan->enganche . '%' : '$' . number_format($plan->enganche, 2)) . "</div>
                                                        <div><strong>Plazo:</strong> {$plan->plazo_pagos} pagos</div>
                                                    </div>
                                                </div>
                                                
                                                <div class='bg-gray-50 p-4 rounded-lg'>
                                                    <h3 class='text-lg font-bold mb-3'>Intereses</h3>
                                                    <div class='grid grid-cols-2 gap-4'>
                                                        <div><strong>Tipo:</strong> " . ucfirst($plan->tipo_interes) . "</div>
                                                        <div><strong>Valor:</strong> " . ($plan->tipo_interes === 'porcentaje' ? $plan->valor_interes . '%' : '$' . number_format($plan->valor_interes, 2)) . "</div>
                                                        <div><strong>Periodo:</strong> {$plan->periodo_interes}</div>
                                                    </div>
                                                </div>
                                                
                                                <div class='bg-gray-50 p-4 rounded-lg'>
                                                    <h3 class='text-lg font-bold mb-3'>Penalizaciones</h3>
                                                    <div class='grid grid-cols-2 gap-4'>
                                                        <div><strong>Tipo:</strong> " . ucfirst($plan->tipo_penalizacion) . "</div>
                                                        <div><strong>Valor:</strong> " . ($plan->tipo_penalizacion === 'porcentaje' ? $plan->penalizacion . '%' : '$' . number_format($plan->penalizacion, 2)) . "</div>
                                                        <div><strong>Aplicación:</strong> {$plan->aplicacion_penalizacion}</div>
                                                        <div><strong>Días de gracia:</strong> {$plan->dias_gracia}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        ");
                                    })
                                    ->modalSubmitAction(false)
                                    ->modalCancelActionLabel('Cerrar')
                            ),
                        
                        Placeholder::make('info_plan')
                            ->label('Resumen del Plan')
                            ->content(function ($get) {
                                $planId = $get('plan_financiamiento_id');
                                if (!$planId) return 'Seleccione un plan';
                                
                                $plan = PlanFinanciamiento::find($planId);
                                if (!$plan) return 'No disponible';
                                
                                $subtotal = $get('subtotal') ?? 0;
                                $enganche = $get('enganche_aplicado') ?? 0;
                                
                                $engancheTexto = $plan->modo_enganche === 'porcentaje' 
                                    ? $plan->enganche . '% (≈ $' . number_format($enganche, 2) . ')'
                                    : '$' . number_format($plan->enganche, 2);
                                
                                return new HtmlString("
                                    <div class='bg-gray-50 p-3 rounded-lg space-y-2'>
                                        <p><span class='font-medium'>Enganche:</span> {$engancheTexto}</p>
                                        <p><span class='font-medium'>Plazo:</span> {$plan->plazo_pagos} pagos</p>
                                        <p><span class='font-medium'>Interés:</span> " . ($plan->tipo_interes === 'porcentaje' ? $plan->valor_interes . '%' : '$' . number_format($plan->valor_interes, 2)) . "</p>
                                        " . ($get('pago_mensual_aprox') ? "<p><span class='font-medium'>Pago aprox.:</span> $" . number_format($get('pago_mensual_aprox'), 2) . "</p>" : '') . "
                                    </div>
                                ");
                            })
                            ->columnSpan(1),
                    ]),
                
                // PASO 4: DETALLES DE PAGO
                Step::make('pago')
                    ->label('Detalles de Pago')
                    ->icon('heroicon-m-currency-dollar')
                    ->completedIcon('heroicon-m-check-badge')
                    ->columns(2)
                    ->schema([
                        // Subtotal (solo lectura)
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('$')
                            ->formatStateUsing(fn ($state) => number_format($state, 2))
                            ->disabled()
                            ->extraAttributes(['class' => 'bg-gray-50']),
                        
                        // Descuento
                        TextInput::make('descuento')
                            ->label('Descuento')
                            ->prefix('$')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $subtotal = $get('subtotal') ?? 0;
                                $set('total', $subtotal - ($state ?? 0));
                                
                                // Recalcular enganche si hay plan seleccionado
                                $planId = $get('plan_financiamiento_id');
                                if ($planId) {
                                    $plan = PlanFinanciamiento::find($planId);
                                    if ($plan) {
                                        $total = $subtotal - ($state ?? 0);
                                        $enganche = $plan->modo_enganche === 'porcentaje'
                                            ? ($total * $plan->enganche) / 100
                                            : $plan->enganche;
                                        $set('enganche_aplicado', $enganche);
                                    }
                                }
                            }),
                        
                        // Total
                        TextInput::make('total')
                            ->label('Total')
                            ->prefix('$')
                            ->formatStateUsing(fn ($state) => number_format($state, 2))
                            ->disabled()
                            ->extraAttributes(['class' => 'bg-gray-50 font-bold text-success-600']),
                        
                        // Enganche aplicado
                        TextInput::make('enganche_aplicado')
                            ->label('Enganche a Pagar')
                            ->prefix('$')
                            ->numeric()
                            ->dehydrated()
                            ->required()
                            ->extraAttributes(['class' => 'font-bold']),
                        
                        // Fecha de venta
                        DatePicker::make('fecha_venta')
                            ->label('Fecha de Venta')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        
                        // Método de pago
                        Select::make('metodo_pago')
                            ->label('Método de Pago')
                            ->options([
                                'efectivo' => '💰 Efectivo',
                                'transferencia' => '🏦 Transferencia',
                                'tarjeta' => '💳 Tarjeta',
                            ])
                            ->required()
                            ->native(false)
                            ->columnSpan(1),
                        
                        // Comprobante
                        FileUpload::make('comprobante_pago')
                            ->label('Comprobante de Pago')
                            ->directory('comprobantes')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->maxSize(5120)
                            ->columnSpanFull(),
                        
                        // Observaciones
                        Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->placeholder('Notas adicionales sobre la venta...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ])
            ->skippable()
            ->persistStepInQueryString()
            ->columnSpanFull(),
        ]);
    }
}