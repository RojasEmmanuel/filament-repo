<?php

namespace App\Filament\Resources\Monedas\Tables;

use App\Models\Monedas;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MonedasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Nombre de la moneda con ícono
                TextColumn::make('nombre')
                    ->label('Moneda')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-banknotes')
                    ->iconColor('primary')
                    ->weight('bold')
                    ->description(fn($record) => $record->codigo_iso),

                // Código ISO como badge
                TextColumn::make('codigo_iso')
                    ->label('Código ISO')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-document-text')
                    ->alignCenter(),

                // Símbolo como badge destacado
                TextColumn::make('Simbolo')
                    ->label('Símbolo')
                    ->badge()
                    ->color('success')
                    ->size('lg')
                    ->alignCenter()
                    ->searchable()
                    ->icon('heroicon-o-currency-dollar'),

             

                // Fecha de creación
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Fecha de actualización
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-arrow-path')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por código ISO
                SelectFilter::make('codigo_iso')
                    ->label('Código ISO')
                    ->options(fn() => Monedas::query()
                        ->pluck('codigo_iso', 'codigo_iso')
                        ->toArray())
                    ->searchable()
                    ->native(false)
                    ->placeholder('Filtrar por código'),

                // Filtro por estado
                SelectFilter::make('activo')
                    ->label('Estado')
                    ->options([
                        true => 'Activas',
                        false => 'Inactivas',
                    ])
                    ->native(false)
                    ->placeholder('Todas las monedas'),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated(false)
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->label('Ver'),
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->label('Editar'),
            ])
            ->emptyStateIcon('heroicon-o-banknotes')
            ->emptyStateHeading('No hay monedas registradas')
            ->emptyStateDescription('Agrega tu primera moneda para comenzar a gestionar divisas.')
            ->defaultSort('nombre', 'asc')
            ->striped()
            ->persistFiltersInSession()
            ->persistSearchInSession();
    }
}