<?php

namespace App\Filament\Resources\Fraccionamientos\RelationManagers;

use App\Filament\Imports\LotesImporter;
use App\Filament\Resources\Lotes\LotesResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class LotesRelationManager extends RelationManager
{
    protected static string $relationship = 'lotes';

    protected static ?string $relatedResource = LotesResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                ImportAction::make()->importer(LotesImporter::class)
            ]);
    }
}
