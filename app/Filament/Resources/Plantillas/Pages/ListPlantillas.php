<?php

namespace App\Filament\Resources\Plantillas\Pages;

use App\Filament\Resources\Plantillas\PlantillaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlantillas extends ListRecords
{
    protected static string $resource = PlantillaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
