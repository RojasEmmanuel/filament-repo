<?php

namespace App\Filament\Resources\Fraccionamientos\RelationManagers;

use App\Filament\Resources\Bancos\BancosResource;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class BancosRelationManager extends RelationManager
{
    protected static string $relationship = 'bancos';

    protected static ?string $relatedResource = BancosResource::class;
    protected static ?string $recordTitleAttribute = 'nombre_banco';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                AttachAction::make()
                ->recordTitleAttribute('nombre_banco')
                ->preloadRecordSelect()
            ])

            ->actions([
                DetachAction::make(), // 👈 opcional (para quitar relación)
            ]);
    }
}
