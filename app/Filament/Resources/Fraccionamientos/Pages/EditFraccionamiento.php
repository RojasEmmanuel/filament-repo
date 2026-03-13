<?php

namespace App\Filament\Resources\Fraccionamientos\Pages;

use App\Filament\Resources\Fraccionamientos\FraccionamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFraccionamiento extends EditRecord
{
    protected static string $resource = FraccionamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
