<?php

namespace App\Filament\Resources\Ventas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('folio')
                    ->label('Folio')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-document-text')                    ,

                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->cliente->email ?? null),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('enganche_aplicado')
                    ->label('Enganche')
                    ->money('MXN')
                    ->toggleable()
                    ->alignRight(),

                TextColumn::make('saldo_restante')
                    ->label('Saldo')
                    ->money('MXN')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                    ->toggleable()
                    ->alignRight(),

                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pendiente' => 'warning',
                        'aprobada' => 'success',
                        'cancelada' => 'danger',
                    })
                    ->icon(fn ($state) => match ($state) {
                        'pendiente' => 'heroicon-o-clock',
                        'aprobada' => 'heroicon-o-check-circle',
                        'cancelada' => 'heroicon-o-x-circle',
                    }),

                TextColumn::make('fecha_venta')
                    ->label('Fecha')
                    ->dateTime('D M Y')
                    ->sortable(),

                TextColumn::make('metodo_pago')
                    ->label('Método')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tipo_venta')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn ($state) => $state === 'contado' ? 'success' : 'warning')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('fraccionamiento.nombre')
                    ->label('Fraccionamiento')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estatus')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobada' => 'Aprobada',
                        'cancelada' => 'Cancelada',
                    ])
                    ->multiple()
                    ->label('Estatus'),

                SelectFilter::make('tipo_venta')
                    ->options([
                        'contado' => 'Contado',
                        'credito' => 'Crédito',
                    ])
                    ->label('Tipo'),

                Filter::make('fecha_venta')
                    ->form([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['desde'], fn ($q, $date) => $q->whereDate('fecha_venta', '>=', $date))
                            ->when($data['hasta'], fn ($q, $date) => $q->whereDate('fecha_venta', '<=', $date));
                    })
                    ->columns(2)
                    ->columnSpan(2),
            ])
            ->filtersFormColumns(2)
            ->defaultSort('fecha_venta', 'desc')
            ->striped()
            ->poll('60s')
            ->recordActions([
                ViewAction::make()->iconButton()->color('gray'),
                EditAction::make()->iconButton()->color('primary'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation(),
            ]);
    }
}