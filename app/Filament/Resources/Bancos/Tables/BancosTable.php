<?php

namespace App\Filament\Resources\Bancos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use App\Models\Banco;
use App\Models\Bancos;

class BancosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Columna principal del banco con ícono
                TextColumn::make('nombre_banco')
                    ->label('Banco')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-building-library')
                    ->weight('bold')
                    ->description(fn($record) => $record->representante ?? 'Sin representante'),

                // Tipo de cuenta con badge e ícono
                TextColumn::make('tipo_cuenta')
                    ->label('Tipo de Cuenta')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'corriente' => 'success',
                        'ahorros' => 'info',
                        'recaudadora' => 'warning',
                        default => 'gray',
                    })
                    
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->alignCenter(),

                // Moneda con bandera/ícono
                TextColumn::make('moneda.nombre')
                    ->label('Moneda')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-currency-dollar')
                    ->badge()
                    ->color('gray')
                    ->prefix(fn($record) => $record->moneda->simbolo ?? '$')
                    ->description(fn($record) => $record->moneda->codigo ?? ''),

                // Número de cuenta enmascarado
                TextColumn::make('numero_cuenta')
                    ->label('N° Cuenta')
                    ->searchable()
                    ->icon('heroicon-o-credit-card')
                    ->iconColor('info')
                    ->copyable()
                    ->copyMessage('Número de cuenta copiado')
                    ->copyMessageDuration(1500)
                    ->tooltip('Click para copiar')
                    ->formatStateUsing(fn($state) => '•••• ' . substr($state, -4))
                    ->description(fn($state) => 'Termina en ' . substr($state, -4)),

                // Código interbancario (CLABE)
                TextColumn::make('codigo_interbancario')
                    ->label('CLABE')
                    ->searchable()
                    ->icon('heroicon-o-document-text')
                    ->iconColor('warning')
                    ->copyable()
                    ->copyMessage('CLABE copiada')
                    ->copyMessageDuration(1500)
                    ->toggleable()
                    ->formatStateUsing(fn($state) => substr($state, 0, 6) . '••••' . substr($state, -4))
                    ->tooltip(fn($state) => $state),

                // Representante legal
                TextColumn::make('representante')
                    ->label('Representante')
                    ->searchable()
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('gray')
                    ->toggleable(),

                // Saldo/Estado (simulado)
                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean()
                    ->getStateUsing(fn($record) => true) // Esto debería venir de un campo real
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip('Cuenta activa'),

                // Fecha de creación con formato mejorado
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->description(fn($record) => $record->created_at->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: true),

                // Fecha de actualización
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->description(fn($record) => $record->updated_at->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por tipo de cuenta
                SelectFilter::make('tipo_cuenta')
                    ->label('Tipo de Cuenta')
                    ->options([
                        'corriente' => 'Corriente',
                        'ahorro' => 'Ahorro',
                        'inversion' => 'Inversión',
                    ])
                    ->native(false)
                    ->placeholder('Todos los tipos'),

                // Filtro por moneda
                SelectFilter::make('moneda_id')
                    ->label('Moneda')
                    ->relationship('moneda', 'nombre')
                    ->searchable()
                    ->preload()
                    ->native(false),

                // Filtro por representante
                SelectFilter::make('representante')
                    ->label('Representante')
                    ->options(fn() => Bancos::query()
                        ->whereNotNull('representante')
                        ->pluck('representante', 'representante')
                        ->toArray())
                    ->searchable()
                    ->native(false),

                // Filtro por rango de fechas
                Filter::make('created_at')
                    ->label('Fecha de creación')
                    ->form([
                        DatePicker::make('desde')
                            ->label('Desde')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('hasta')
                            ->label('Hasta')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn($q) => $q->whereDate('created_at', '>=', $data['desde']))
                            ->when($data['hasta'], fn($q) => $q->whereDate('created_at', '<=', $data['hasta']));
                    })
            ])
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar bancos seleccionados')
                        ->modalDescription('¿Estás seguro de eliminar estos bancos? Esta acción no se puede revertir.'),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-building-library')
            ->emptyStateHeading('No hay bancos registrados')
            ->emptyStateDescription('Agrega tu primer banco para comenzar a gestionar cuentas.')
            ->defaultSort('nombre_banco', 'asc')
            ->striped()
            ->deferLoading()
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->poll('60s');
    }
}