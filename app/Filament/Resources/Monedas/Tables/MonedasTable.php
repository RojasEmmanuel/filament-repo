<?php

namespace App\Filament\Resources\Monedas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MonedasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->size('lg')
                    ->weight('bold'),

                TextColumn::make('codigo_iso')
                    ->badge()
                    ->color('info'),

                TextColumn::make('Simbolo')
                    ->badge()
                    ->color('success')
                    ->size('lg'),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated(false)
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
