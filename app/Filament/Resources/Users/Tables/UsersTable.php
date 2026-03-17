<?php

namespace App\Filament\Resources\Users\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Image;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->size(40)
                    ->getStateUsing(function ($record) {
                        if ($record->avatar) {
                            return asset('storage/' . $record->avatar);
                        }

                        return 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=random';
                    }),

                TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ultima Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                                 
                
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'super_admin' => 'danger',
                        'Administrador' => 'warning',
                        'Usuario' => 'success',
                        default => 'gray',
                    })
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Rol')
                    ->native(false)
                    ->relationship('roles', 'name')
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
