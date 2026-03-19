<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Avatar con nombre como fallback
                ImageColumn::make('avatar')
                    ->label('')
                    ->circular()
                    ->size(45)
                    ->getStateUsing(function ($record) {
                        if ($record->avatar) {
                            return asset('storage/' . $record->avatar);
                        }
                        return 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=0D6EFD&color=fff&bold=true';
                    })
                    ->extraAttributes(['class' => 'ring-2 ring-gray-200']),

                // Nombre y email combinados
                TextColumn::make('name')
                    ->label('Usuario')
                    ->searchable(['name', 'email'])
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->email)
                    ->icon('heroicon-o-user')
                    ->iconColor('primary'),

                // Teléfono con formato
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->iconColor('gray')
                    ->placeholder('No registrado')
                    ->toggleable(),

                // Roles como badges
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin', 'Administrador' => 'warning',
                        'usuario', 'Usuario' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn ($state): string => match ($state) {
                        'super_admin' => 'heroicon-o-shield-check',
                        'admin', 'Administrador' => 'heroicon-o-cog',
                        'usuario', 'Usuario' => 'heroicon-o-user',
                        default => 'heroicon-o-user-circle',
                    })
                    ->separator(', ')
                    ->tooltip(fn ($record) => $record->roles->pluck('name')->join(', ')),

                // Fecha de registro formateada
                TextColumn::make('created_at')
                    ->label('Registro')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->iconColor('gray')
                    ->description(fn ($record) => $record->created_at?->diffForHumans() ?? '')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Última actualización
                TextColumn::make('updated_at')
                    ->label('Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-arrow-path')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                
            ])
            ->filters([
                // Filtro por roles
                SelectFilter::make('roles')
                    ->label('Filtrar por Rol')
                    ->native(false)
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder('Todos los roles'),

                // Filtro por fecha de registro
                Filter::make('created_at')
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
                    ->columnSpan(2),

                // Filtro por teléfono (con/sin teléfono)
                Filter::make('telefono')
                    ->label('Con teléfono registrado')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('telefono'))
                    ->toggle(),
            ])
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label('Filtros avanzados')
                    ->icon('heroicon-m-funnel')
            )
            ->defaultSort('created_at', 'desc')
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
                    ->tooltip('Editar usuario'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar usuarios seleccionados')
                        ->modalDescription('¿Estás seguro de eliminar estos usuarios? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Eliminar'),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('No hay usuarios registrados')
            ->emptyStateDescription('Crea tu primer usuario para comenzar.')
            ->emptyStateActions([
                Action::make('crear')
                    ->label('Crear usuario')
                    ->icon('heroicon-m-plus')
                    ->button()
                    ->color('primary')
                    ->url(route('filament.admin.resources.users.create')),
            ]);
    }
}