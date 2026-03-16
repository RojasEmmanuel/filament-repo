<?php

namespace App\Filament\Resources\Lotes\Tables;

use App\Filament\Exports\LotesExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fraccionamiento.nombre')
                    ->label('Fraccionamiento')
                    ->sortable(),
                TextColumn::make('lote')
                    ->searchable(),
                TextColumn::make('manzana')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('area')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('norte')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sur')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('este')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('oeste')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('precio')->money('MXN')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estatus')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disponible' => 'success',
                        'vendido' => 'warning',
                        'liquidado'=>'danger'
                    })
                    ,
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estatus')
                    ->label('Estatus')
                    ->native(false)
                    ->options([
                        'disponible' => 'Disponible',
                        'vendido' => 'Vendido',
                        'liquidado' => 'Liquidado',
                    ]),

                SelectFilter::make('fraccionamiento_id')
                    ->native(false)
                    ->label('Fraccionamiento')
                    ->relationship('fraccionamiento', 'nombre'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                
                ExportBulkAction::make()->exporter(LotesExporter::class)
            ]);
    }
}
