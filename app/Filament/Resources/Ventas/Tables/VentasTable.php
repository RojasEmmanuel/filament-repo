<?php

namespace App\Filament\Resources\Ventas\Tables;

use App\Services\GenerarReciboVentaService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

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
                    ->icon('heroicon-o-document-text')
                    ->copyable()
                    ->copyMessage('Folio copiado')
                    ->copyMessageDuration(1500)
                    ->extraAttributes(['class' => 'font-mono']),

                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->cliente?->apellidos ?? 'Apellidos')
                    ->icon('heroicon-o-user')
                    ->searchable(['nombre', 'email']),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->alignRight()
                    ->description(fn ($record) => "Enganche: $" . number_format($record->enganche_aplicado, 2))
                    ->extraAttributes(['class' => 'font-mono']),

                TextColumn::make('saldo_restante')
                    ->label('Saldo')
                    ->money('MXN')
                    ->sortable()
                    ->weight('medium')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                    ->badge()
                    ->alignRight()
                    ->extraAttributes(['class' => 'font-mono']),

                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'pendiente' => 'warning',
                        'aprobada' => 'success',
                        'cancelada' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn ($state): string => match ($state) {
                        'pendiente' => 'heroicon-o-clock',
                        'aprobada' => 'heroicon-o-check-circle',
                        'cancelada' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => 'PENDIENTE',
                        'aprobada' => 'APROBADA',
                        'cancelada' => 'CANCELADA',
                        default => strtoupper($state),
                    }),

                TextColumn::make('fecha_venta')
                    ->label('Fecha')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->color('gray')
                    ->description(fn ($record) => $record->created_at->format('H:i')),

                TextColumn::make('tipo_venta')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn ($state): string => $state === 'contado' ? 'success' : 'warning')
                    ->icon(fn ($state): string => $state === 'contado' ? 'heroicon-o-bolt' : 'heroicon-o-calendar-days')
                    ->formatStateUsing(fn ($state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault:true),

                TextColumn::make('metodo_pago')
                    ->label('Método')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'efectivo' => 'success',
                        'tarjeta' => 'info',
                        'transferencia' => 'warning',
                        'cheque' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn ($state): string => match ($state) {
                        'efectivo' => 'heroicon-o-banknotes',
                        'tarjeta' => 'heroicon-o-credit-card',
                        'transferencia' => 'heroicon-o-arrow-path',
                        'cheque' => 'heroicon-o-document',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->toggleable(isToggledHiddenByDefault:true),

                TextColumn::make('fraccionamiento.nombre')
                    ->label('Fraccionamiento')
                    ->icon('heroicon-o-building-office')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: false),

                // Corrección: Usar ventaLotes en lugar de lotes directamente
                TextColumn::make('lotes_count')
                    ->label('Lotes')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-squares-2x2')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state . ' ' . ($state == 1 ? 'lote' : 'lotes'))
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('user.name')
                    ->label('Vendedor')
                    ->icon('heroicon-o-user')
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estatus')
                    ->label('Estado de la venta')
                    ->native(false)
                    ->multiple()
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobada' => 'Aprobada',
                        'cancelada' => 'Cancelada',
                    ])
                    ->placeholder('Todos los estados')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('tipo_venta')
                    ->label('Tipo de venta')
                    ->native(false)
                    ->options([
                        'contado' => 'Contado',
                        'credito' => 'Crédito',
                    ])
                    ->placeholder('Todos los tipos'),

                SelectFilter::make('metodo_pago')
                    ->label('Método de pago')
                    ->native(false)
                    ->multiple()
                    ->options([
                        'efectivo' => 'Efectivo',
                        'tarjeta' => 'Tarjeta',
                        'transferencia' => 'Transferencia',
                        'cheque' => 'Cheque',
                    ])
                    ->placeholder('Todos los métodos'),

                
                    
                Filter::make('saldo_pendiente')
                    ->label('Con saldo pendiente')
                    ->query(fn (Builder $query): Builder => $query->where('saldo_restante', '>', 0))
                    ->toggle(),
                    
                // Nuevo filtro para filtrar por número de lotes
                
                    
            ], )
            ->filtersFormColumns(2)
            ->defaultSort('fecha_venta', 'desc')
            ->poll('60s')
            ->actions([
                ViewAction::make()
                    ->iconButton()
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->tooltip('Ver detalles'),
                EditAction::make()
                    ->iconButton()
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->tooltip('Editar venta'),
                Action::make('recibo')
                    ->iconButton()
                    ->icon('heroicon-o-printer')
                    ->tooltip('Imprimir recibo')
                    ->url(fn ($record) => route('ventas.recibo', $record))
                    ->openUrlInNewTab()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar ventas seleccionadas')
                        ->modalDescription('¿Estás seguro de eliminar estas ventas? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Eliminar'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('No hay ventas registradas')
            ->emptyStateDescription('Comienza registrando tu primera venta para verla reflejada aquí.')
            ->emptyStateActions([
                Action::make('crear')
                    ->label('Nueva venta')
                    ->icon('heroicon-o-plus')
                    ->button()
                    ->color('primary')
                    ->url(route('filament.admin.resources.ventas.create')),
            ]);
    }
}