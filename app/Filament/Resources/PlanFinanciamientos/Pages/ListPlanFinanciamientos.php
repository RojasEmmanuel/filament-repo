<?php

namespace App\Filament\Resources\PlanFinanciamientos\Pages;

use App\Filament\Resources\PlanFinanciamientos\PlanFinanciamientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlanFinanciamientos extends ListRecords
{
    protected static string $resource = PlanFinanciamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
