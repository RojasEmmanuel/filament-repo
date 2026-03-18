<?php

namespace App\Filament\Resources\Fraccionamientos\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\View;

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

                    ImageColumn::make('imagen')
                        ->disk('public')
                        ->height(120)
                        ->alignment('center')
                        ->extraImgAttributes([
                            'class' => 'rounded-xl object-cover w-full shadow-sm',
                        ]),

                    TextColumn::make('nombre')
                        ->searchable()
                        ->weight('bold')
                        ->size('lg')
                        ->wrap(),

                    TextColumn::make('ubicacion')
                        ->icon('heroicon-m-map-pin')
                        ->color('gray')
                        ->size('sm')
                        ->searchable(),

                    IconColumn::make('activo')
                        ->boolean()
                        ->label('Disponible')
                        ->trueIcon('heroicon-o-check-circle')
                        ->falseIcon('heroicon-o-x-circle')
                        ->trueColor('success')
                        ->falseColor('danger'),

                ])->space(2),
            ])

            ->filters([
                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos')
                    ->placeholder('Todos'),
            ])

            ->recordActions([
                EditAction::make()->iconButton()->color('primary'),
                ViewAction::make()->iconButton()->color('gray'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}