<?php

namespace App\Filament\Resources\PlanFinanciamientos\Pages;

use App\Filament\Resources\PlanFinanciamientos\PlanFinanciamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlanFinanciamiento extends EditRecord
{
    protected static string $resource = PlanFinanciamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
