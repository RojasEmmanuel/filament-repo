<?php

namespace App\Filament\Resources\Fraccionamientos\Tables;

use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Grid;  // ← IMPORTAR GRID
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class FraccionamientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    // Imagen con overlay de estado
                    Stack::make([
                        ImageColumn::make('imagen')
                            ->disk('public')
                            ->height(160)
                            ->width('100%')
                            ->alignment('center')
                            ->extraImgAttributes([
                                'class' => 'rounded-t-xl object-cover w-full h-40 shadow-sm',
                            ]),
                        
                        // Badge de estado superpuesto
                        IconColumn::make('activo')
                            ->boolean()
                            ->label('')
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-no-symbol')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->extraAttributes([
                                'class' => 'absolute top-2 right-2 bg-white/90 backdrop-blur-sm rounded-full p-1 shadow-lg',
                            ]),
                    ])->space(0)->extraAttributes(['class' => 'relative']),

                    // Contenido de la tarjeta
                    Stack::make([
                        // Nombre y código postal
                        Stack::make([
                            TextColumn::make('nombre')
                                ->searchable()
                                ->weight('bold')
                                ->wrap()
                                ->color('primary')
                                ->extraAttributes(['class' => 'text-lg font-bold text-primary-600']),
                            
                            TextColumn::make('codigo_postal')
                                ->label('')
                                ->badge()
                                ->color('gray')
                                ->prefix('CP ')
                                ->extraAttributes(['class' => 'text-xs']),
                        ])->space(1)->alignment('start'),

                        // Ubicación con icono
                        TextColumn::make('ubicacion')
                            ->icon('heroicon-m-map-pin')
                            ->color('gray')
                            ->wrap()
                            ->extraAttributes(['class' => 'text-sm text-gray-600']),

                        // ✅ CORREGIDO: Usar Grid en lugar de Stack::columns()
                        Grid::make(3)
                            ->schema([
                                TextColumn::make('total_manzanas')
                                    ->label('Manzanas')
                                    ->numeric()
                                    ->color('info')
                                    ->icon('heroicon-m-squares-2x2')
                                    ->extraAttributes(['class' => 'bg-blue-50/50 rounded-lg px-2 py-1 text-xs font-medium text-center']),

                                TextColumn::make('total_lotes')
                                    ->label('Lotes')
                                    ->numeric()
                                    ->color('success')
                                    ->icon('heroicon-m-cube')
                                    ->extraAttributes(['class' => 'bg-green-50/50 rounded-lg px-2 py-1 text-xs font-medium text-center']),

                                TextColumn::make('area_total')
                                    ->label('Área')
                                    ->numeric(decimalPlaces: 2)
                                    ->suffix(' m²')
                                    ->color('warning')
                                    ->icon('heroicon-m-arrows-pointing-out')
                                    ->extraAttributes(['class' => 'bg-yellow-50/50 rounded-lg px-2 py-1 text-xs font-medium text-center']),
                            ]),

                      

                        // Perímetro (si existe)
                        TextColumn::make('perimetro')
                            ->label('Perímetro')
                            ->suffix(' m')
                            ->color('gray')
                            ->icon('heroicon-m-arrow-path')
                            ->extraAttributes(['class' => 'text-xs'])
                            ->visible(fn ($record) => !empty($record->perimetro)),
                    ])->space(3)->extraAttributes(['class' => 'p-4']),
                ])->space(0),
            ])
            ->filters([
                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos')
                    ->placeholder('Todos los fraccionamientos')
                    ->native(false),

                Filter::make('area_total')
                    ->form([
                        TextInput::make('area_desde')
                            ->label('Área desde')
                            ->numeric()
                            ->suffix('m²')
                            ->placeholder('Mínimo'),
                        TextInput::make('area_hasta')
                            ->label('Área hasta')
                            ->numeric()
                            ->suffix('m²')
                            ->placeholder('Máximo'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['area_desde'],
                                fn (Builder $query, $value): Builder => $query->where('area_total', '>=', $value)
                            )
                            ->when(
                                $data['area_hasta'],
                                fn (Builder $query, $value): Builder => $query->where('area_total', '<=', $value)
                            );
                    }),
            ])
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label('Filtros avanzados')
                    ->icon('heroicon-m-funnel')
            )
            ->defaultSort('nombre')
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
                    ->tooltip('Editar fraccionamiento'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar fraccionamientos seleccionados')
                        ->modalDescription('¿Estás seguro de eliminar estos fraccionamientos? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Eliminar'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-building-office')
            ->emptyStateHeading('No hay fraccionamientos registrados')
            ->emptyStateDescription('Comienza creando tu primer fraccionamiento.')
            ->emptyStateActions([
                Action::make('crear')
                    ->label('Crear fraccionamiento')
                    ->icon('heroicon-m-plus')
                    ->button()
                    ->color('primary')
                    ->url(route('filament.admin.resources.fraccionamientos.create')),
            ]);
    }
}