<?php

namespace App\Filament\Resources\Ventas\Schemas;

use App\Models\Lotes;
use App\Models\PlanFinanciamiento;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VentasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // 🔷 FRACCIONAMIENTO (PRIMERO)
            Select::make('fraccionamiento_id')
                ->label('Fraccionamiento')
                ->relationship('fraccionamiento', 'nombre')
                ->searchable()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($set) {
                    // Resetear lotes cuando cambia fraccionamiento
                    $set('lotes', null);
                    $set('subtotal', 0);
                    $set('total', 0);
                }),

            // 🔷 LOTES (FILTRADOS)
            Select::make('lotes')
                ->label('Lotes')
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
                                $lote->id => "{$lote->nombre} - $" . number_format($lote->precio, 2)
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
                ->required(),

            // 🔷 CLIENTE
            Select::make('cliente_id')
                ->relationship('cliente', 'nombre')
                ->searchable()
                ->required(),

            // 🔷 PLAN
            Select::make('plan_financiamiento_id')
                ->relationship('planFinanciamiento', 'nombre')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, $set, $get) {
                    $plan = PlanFinanciamiento::find($state);
                    $subtotal = $get('subtotal') ?? 0;

                    if (!$plan) return;

                    $enganche = $plan->modo_enganche === 'porcentaje'
                        ? ($subtotal * $plan->enganche) / 100
                        : $plan->enganche;

                    $set('enganche_aplicado', $enganche);
                }),

            // SUBTOTAL
            TextInput::make('subtotal')
                ->prefix('$')
                ->formatStateUsing(fn ($state) => number_format($state, 2))
                ->disabled(),

            // DESCUENTO
            TextInput::make('descuento')
                ->prefix('$')
                ->numeric()
                ->default(0)
                ->reactive()
                ->afterStateUpdated(function ($state, $set, $get) {
                    $subtotal = $get('subtotal') ?? 0;
                    $set('total', $subtotal - $state);
                }),

            //  TOTAL
            TextInput::make('total')
                ->prefix('$')
                ->formatStateUsing(fn ($state) => number_format($state, 2))
                ->disabled()
                ->dehydrated(),

            //  ENGANCHE
            TextInput::make('enganche_aplicado')
                ->prefix('$')
                ->numeric()
                ->dehydrated()
                ->required(),

            //  FECHA
            DatePicker::make('fecha_venta')
                ->default(now())
                ->required(),

            //  MÉTODO
            Select::make('metodo_pago')
                ->options([
                    'efectivo' => 'Efectivo',
                    'transferencia' => 'Transferencia',
                    'tarjeta' => 'Tarjeta',
                ]),

            //  COMPROBANTE
            FileUpload::make('comprobante_pago')
                ->directory('comprobantes'),

            //  OBS
            Textarea::make('observaciones')
                ->columnSpanFull(),


        ]);
    }
}
