<?php

namespace App\Filament\Resources\Ventas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //FOLIO
                TextColumn::make('folio')
                    ->label('Folio')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary'),

                // CLIENTE
                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                

                // TOTAL
                TextColumn::make('total')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold'),

                // ENGANCHE
                TextColumn::make('enganche_aplicado')
                    ->label('Enganche')
                    ->money('MXN')
                    ->toggleable(),

                // SALDO
                TextColumn::make('saldo_restante')
                    ->label('Saldo')
                    ->money('MXN')
                    ->color('success')
                    ->toggleable(),

                // ESTATUS
                TextColumn::make('estatus')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pendiente' => 'warning',
                        'aprobada' => 'success',
                        'cancelada' => 'danger',
                    }),

                // FECHA
                TextColumn::make('fecha_venta')
                    ->date()
                    ->sortable(),

                // CAMPOS OCULTOS (toggleable)
                TextColumn::make('metodo_pago')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tipo_venta')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('descuento')
                    ->money('MXN')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('subtotal')
                    ->money('MXN')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                // FRACCIONAMIENTO
                TextColumn::make('fraccionamiento.nombre')
                    ->label('Fraccionamiento')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),   // 👁 VER
                EditAction::make(),   // ✏️ EDITAR
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
