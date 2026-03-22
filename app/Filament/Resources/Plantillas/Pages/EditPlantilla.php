<?php

namespace App\Filament\Resources\Plantillas\Pages;

use App\Filament\Resources\Plantillas\PlantillaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlantilla extends EditRecord
{
    protected static string $resource = PlantillaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
