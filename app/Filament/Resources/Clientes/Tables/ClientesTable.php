<?php

namespace App\Filament\Resources\Clientes\Tables;

use App\Filament\Exports\ClientesExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;

class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Columna principal con avatar/nombre completo
                TextColumn::make('nombre')
                    ->label('Cliente')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('nombre', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%");
                    })
                    ->sortable(['nombre', 'apellidos'])
                    ->formatStateUsing(fn($record) => $record->nombre . ' ' . $record->apellidos)
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->weight('bold')
                    ->description(fn($record) => $record->curp ?? 'CURP no registrado'),

                // Edad con indicador visual
                TextColumn::make('edad')
                    ->label('Edad')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->iconColor(fn($state) => $state < 30 ? 'success' : ($state < 50 ? 'warning' : 'gray'))
                    ->badge()
                    ->color(fn($state) => $state < 30 ? 'success' : ($state < 50 ? 'warning' : 'gray'))
                    ->suffix(' años'),

                // Ciudad con ícono de ubicación
                TextColumn::make('ciudad')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('info')
                    ->wrap(),

                // Tipo de registro con badges mejorados
                TextColumn::make('tipo')
                    ->badge()
                    ->label('Tipo')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'cliente' => 'Cliente',
                        'prospecto' => 'Prospecto',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'cliente' => 'success',
                        'prospecto' => 'warning',
                    })
                    ->icon(fn(string $state) => match ($state) {
                        'cliente' => Heroicon::OutlinedCheckBadge,
                        'prospecto' => Heroicon::OutlinedUserPlus,
                    })
                    ->icons([
                        'cliente' => Heroicon::OutlinedCheckBadge,
                        'prospecto' => Heroicon::OutlinedUserPlus,
                    ])
                    ->alignCenter(),

                // Estado civil con ícono
                TextColumn::make('estado_civil')
                    ->label('Estado Civil')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'soltero' => 'Soltero(a)',
                        'casado' => 'Casado(a)',
                        'otro' => 'Otro',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'soltero' => 'info',
                        'casado' => 'success',
                        'otro' => 'gray',
                    })
                    ->icon(fn(string $state) => match ($state) {
                        'soltero' => 'heroicon-o-user',
                        'casado' => 'heroicon-o-heart',
                        'otro' => 'heroicon-o-users',
                    })
                    ->toggleable(),

                // Ocupación con ícono
                TextColumn::make('ocupacion')
                    ->label('Ocupación')
                    ->icon('heroicon-o-briefcase')
                    ->iconColor('gray')
                    ->limit(20)
                    ->tooltip(fn($state) => $state)
                    ->toggleable(isToggledHiddenByDefault: true),

                // Teléfono con acción de llamada
                TextColumn::make('telefono')
                    ->label('Contacto')
                    ->icon('heroicon-o-phone')
                    ->iconColor('success')
                    ->copyable()
                    ->copyMessage('Teléfono copiado')
                    ->copyMessageDuration(1500)
                    ->tooltip('Click para copiar')
                    ->url(fn($state) => $state ? 'tel:' . $state : null)
                    ->toggleable(),

                // Documentos fiscales (compacto)
                TextColumn::make('rfc')
                    ->label('RFC')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('gray')
                    ->limit(10)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable(),

                // Fechas con formato mejorado
                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->iconColor('gray')
                    ->description(fn($record) => $record->created_at->diffForHumans())
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->icon('heroicon-o-arrow-path')
                    ->iconColor('gray')
                    ->description(fn($record) => $record->updated_at->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: true),

                
            ])
            ->filters([
                // Filtro por tipo con íconos
                SelectFilter::make('tipo')
                    ->label('Tipo de registro')
                    ->options([
                        'cliente' => 'Clientes',
                        'prospecto' => 'Prospectos'
                    ])
                    ->placeholder('Todos los tipos')
                    ->native(false),

                // Filtro por estado civil
                SelectFilter::make('estado_civil')
                    ->label('Estado Civil')
                    ->options([
                        'soltero' => 'Soltero(a)',
                        'casado' => 'Casado(a)',
                        'otro' => 'Otro',
                    ])
                    ->native(false),

                // Filtro por rango de edad
                Filter::make('edad')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('edad_desde')
                            ->label('Edad desde')
                            ->numeric()
                            ->minValue(18)
                            ->maxValue(100),
                        \Filament\Forms\Components\TextInput::make('edad_hasta')
                            ->label('Edad hasta')
                            ->numeric()
                            ->minValue(18)
                            ->maxValue(100),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['edad_desde'], fn($q) => $q->where('edad', '>=', $data['edad_desde']))
                            ->when($data['edad_hasta'], fn($q) => $q->where('edad', '<=', $data['edad_hasta']));
                    }),

                // Filtro por fecha de registro
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('desde')
                            ->label('Registrado desde'),
                        DatePicker::make('hasta')
                            ->label('Registrado hasta'),
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
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->label('Eliminar')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
                ExportBulkAction::make()
                    ->exporter(ClientesExporter::class)
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->label('Exportar selección'),
            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->label(''),
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->label('')
                    ->requiresConfirmation(),
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('No hay clientes registrados')
            ->emptyStateDescription('Crea tu primer cliente o prospecto para comenzar.')
            ->defaultSort('created_at', 'desc')
            ->poll('60s')
            ->deferLoading()
            ->persistFiltersInSession()
            ->persistSearchInSession();
    }
}