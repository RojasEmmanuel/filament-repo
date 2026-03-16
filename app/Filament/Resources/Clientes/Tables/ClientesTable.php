<?php

namespace App\Filament\Resources\Clientes\Tables;

use App\Filament\Exports\ClientesExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Actions;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('apellidos')
                    ->searchable(),
                TextColumn::make('edad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ciudad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cliente' => 'success',
                        'prospecto' => 'warning',
                    })
                    ->icons([
                        'cliente' => Heroicon::OutlinedUser,
                        'prospecto' => Heroicon::OutlinedUserPlus,
                    ])
                    ->label('Tipo'),

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
                SelectFilter::make('tipo')
                ->label('Tipo de registro')
                ->options([
                    'cliente'=>'Clientes',
                    'prospecto'=>'Prospectos'
                ])
                ->placeholder('Todos los tipos')
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),

                ExportBulkAction::make()->exporter(ClientesExporter::class)
            ])
            
            ->actions([
                // 👇 Acción de editar en modal
                EditAction::make(),
               
                
                DeleteAction::make(),
            ]);

    }
}
