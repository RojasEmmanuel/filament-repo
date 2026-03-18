<?php

namespace App\Filament\Resources\PlanFinanciamientos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str as SupportStr;

class PlanFinanciamientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Columna principal con ícono
                TextColumn::make('nombre')
                    ->label('Plan')
                    ->searchable()
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->weight('bold')
                    ->description(fn($record) => SupportStr::limit($record->descripcion, 30, '...') ?? 'Sin descripción'),

                // Frecuencia de pago con ícono
                TextColumn::make('frecuencia_pago')
                    ->label('Frecuencia')
                    ->badge()
                    ->icon('heroicon-o-calendar')
                    ->color(fn(string $state): string => match ($state) {
                        'semanal' => 'warning',
                        'quincenal' => 'info',
                        'mensual' => 'success',
                        'bimestral' => 'gray',
                        'trimestral' => 'indigo',
                        'semestral' => 'purple',
                        'anual' => 'violet',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                // Enganche con ícono
                TextColumn::make('enganche')
                    ->label('Enganche')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->icon('heroicon-o-currency-dollar')
                    ->iconColor('success')
                    ->description(fn($record) => $record->tipo_enganche === 'fijo' 
                        ? 'Monto fijo' 
                        : ($record->modo_enganche === 'porcentaje' ? 'Mínimo %' : 'Mínimo $')),

                // Plazo de pagos
                TextColumn::make('plazo_pagos')
                    ->label('Plazo')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconColor('gray')
                    ->suffix(fn($record) => ' ' . $record->frecuencia_pago . '(es)'),

                // Interés
                TextColumn::make('valor_interes')
                    ->label('Interés')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->icon('heroicon-o-chart-pie')
                    ->iconColor('danger')
                    ->description(fn($record) => $record->tipo_interes === 'porcentaje' 
                        ? $record->periodo_interes 
                        : 'Monto fijo'),

                // Penalización
                TextColumn::make('penalizacion')
                    ->label('Penalización')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->icon('heroicon-o-exclamation-triangle')
                    ->iconColor('warning')
                    ->description(fn($record) => $record->tipo_penalizacion === 'porcentaje' 
                        ? $record->aplicacion_penalizacion 
                        : 'Monto fijo'),

                // Días de gracia
                TextColumn::make('dias_gracia')
                    ->label('Días Gracia')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-hand-thumb-up')
                    ->iconColor('success')
                    ->badge()
                    ->color('gray'),

                // Estado
                IconColumn::make('activo')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Fechas
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-arrow-path')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por estado
                SelectFilter::make('activo')
                    ->label('Estado')
                    ->options([
                        true => 'Activo',
                        false => 'Inactivo',
                    ])
                    ->default(true),

                // Filtro por frecuencia de pago
                SelectFilter::make('frecuencia_pago')
                    ->label('Frecuencia de Pago')
                    ->multiple()
                    ->options([
                        'semanal' => 'Semanal',
                        'quincenal' => 'Quincenal',
                        'mensual' => 'Mensual',
                        'bimestral' => 'Bimestral',
                        'trimestral' => 'Trimestral',
                        'semestral' => 'Semestral',
                        'anual' => 'Anual',
                    ]),

                // Filtro por tipo de interés
                SelectFilter::make('tipo_interes')
                    ->label('Tipo de Interés')
                    ->options([
                        'porcentaje' => 'Porcentaje',
                        'fijo' => 'Monto Fijo',
                    ]),

                // Filtro por tipo de penalización
               SelectFilter::make('tipo_penalizacion')
                    ->label('Tipo Penalización')
                    ->options([
                        'porcentaje' => 'Porcentaje',
                        'fijo' => 'Monto Fijo',
                    ]),

                // Filtro por rango de días de gracia
                \Filament\Tables\Filters\Filter::make('dias_gracia')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('dias_gracia_desde')
                            ->label('Días de gracia desde')
                            ->numeric(),
                        \Filament\Forms\Components\TextInput::make('dias_gracia_hasta')
                            ->label('Días de gracia hasta')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dias_gracia_desde'], fn($q) => $q->where('dias_gracia', '>=', $data['dias_gracia_desde']))
                            ->when($data['dias_gracia_hasta'], fn($q) => $q->where('dias_gracia', '<=', $data['dias_gracia_hasta']));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->label(''),
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-document')
            ->emptyStateHeading('No hay planes de financiamiento')
            ->emptyStateDescription('Crea un nuevo plan de financiamiento para comenzar.')
            ->poll('60s');
    }
}