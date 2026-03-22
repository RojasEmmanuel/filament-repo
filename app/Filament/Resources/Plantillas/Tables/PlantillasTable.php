<?php

namespace App\Filament\Resources\Plantillas\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class PlantillasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Nombre de la plantilla
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('primary')
                    ->wrap()
                    ->description(fn ($record) => $record->descripcion)
                    ->extraAttributes(['class' => 'max-w-md']),

                // Clave
                TextColumn::make('clave')
                    ->label('Clave')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Clave copiada')
                    ->copyMessageDuration(1500)
                    ->badge()
                    ->color('gray')
                    ->extraAttributes(['class' => 'font-mono text-xs']),

                // Ruta del archivo
                TextColumn::make('ruta')
                    ->label('Archivo')
                    ->searchable()
                    ->icon('heroicon-m-document-arrow-down')
                    ->iconColor('gray')
                    ->url(fn ($record) => asset('storage/' . $record->ruta), true)
                    ->openUrlInNewTab()
                    ->extraAttributes(['class' => 'text-primary-600 hover:underline cursor-pointer'])
                    ->tooltip('Haz clic para descargar'),

                // Tipo de plantilla - CORREGIDO PARA ENUM
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn ($record): string => match ($record->tipo?->value ?? $record->tipo) {
                        'pdf' => 'danger',
                        'docx', 'doc' => 'info',
                        'xlsx', 'xls' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn ($record): string => match ($record->tipo?->value ?? $record->tipo) {
                        'pdf' => 'heroicon-m-document',
                        'docx', 'doc' => 'heroicon-m-document-text',
                        'xlsx', 'xls' => 'heroicon-m-table-cells',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn ($state): string => strtoupper($state instanceof \UnitEnum ? $state->value : $state))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('tipo', 'like', "%{$search}%");
                    }),

                // Fraccionamiento asociado
                TextColumn::make('fraccionamiento.nombre')
                    ->label('Fraccionamiento')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-building-office')
                    ->iconColor('gray')
                    ->badge()
                    ->color('warning')
                    ->tooltip('Fraccionamiento al que pertenece'),

                // Fecha de creación
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar')
                    ->iconColor('gray')
                    ->description(fn ($record) => $record->created_at?->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: true),

                // Última actualización
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-m-arrow-path')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por tipo de plantilla
                SelectFilter::make('tipo')
                    ->label('Tipo de plantilla')
                    ->native(false)
                    ->options([
                        'pdf' => 'PDF',
                        'docx' => 'DOCX',
                        'doc' => 'DOC',
                        'xlsx' => 'XLSX',
                        'xls' => 'XLS',
                    ])
                    ->placeholder('Todos los tipos')
                    ->searchable(),

                // Filtro por fraccionamiento
                SelectFilter::make('fraccionamiento_id')
                    ->label('Fraccionamiento')
                    ->native(false)
                    ->relationship('fraccionamiento', 'nombre')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todos los fraccionamientos'),

                // Filtro por rango de fechas
                Filter::make('created_at')
                    ->form([
                        TextInput::make('desde')
                            ->label('Desde')
                            ->type('date'),
                        TextInput::make('hasta')
                            ->label('Hasta')
                            ->type('date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    })
                    ->columns(2)
                    ->columnSpan(2),

                // Filtro por nombre/clave
                Filter::make('busqueda')
                    ->form([
                        TextInput::make('nombre')
                            ->label('Nombre o clave')
                            ->placeholder('Buscar por nombre o clave'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['nombre'],
                                fn (Builder $query, $search): Builder => $query->where(function ($q) use ($search) {
                                    $q->where('nombre', 'like', "%{$search}%")
                                      ->orWhere('clave', 'like', "%{$search}%");
                                })
                            );
                    }),
            ])
            ->filtersFormColumns(4)
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label('Filtros avanzados')
                    ->icon('heroicon-m-funnel')
            )
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->poll('60s')
            ->actions([
                ViewAction::make()
                    ->iconButton()
                    ->icon('heroicon-m-eye')
                    ->color('gray')
                    ->tooltip('Ver detalles'),
                EditAction::make()
                    ->iconButton()
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->tooltip('Editar plantilla'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar plantillas seleccionadas')
                        ->modalDescription('¿Estás seguro de eliminar estas plantillas? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Eliminar'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('No hay plantillas registradas')
            ->emptyStateDescription('Comienza creando tu primera plantilla para gestionar documentos.')
            ->emptyStateActions([
                Action::make('crear')
                    ->label('Crear plantilla')
                    ->icon('heroicon-m-plus')
                    ->button()
                    ->color('primary')
                    ->url(route('filament.admin.resources.plantillas.create')),
            ]);
    }
}