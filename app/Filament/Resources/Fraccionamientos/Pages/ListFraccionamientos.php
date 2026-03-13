<?php

namespace App\Filament\Resources\Fraccionamientos\Pages;

use App\Filament\Resources\Fraccionamientos\FraccionamientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFraccionamientos extends ListRecords
{
    protected static string $resource = FraccionamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
