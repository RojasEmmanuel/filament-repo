<?php

namespace App\Filament\Resources\Monedas\Pages;

use App\Filament\Resources\Monedas\MonedasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMonedas extends EditRecord
{
    protected static string $resource = MonedasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
