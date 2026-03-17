<?php

namespace App\Filament\Resources\Monedas\Pages;

use App\Filament\Resources\Monedas\MonedasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonedas extends ListRecords
{
    protected static string $resource = MonedasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderDescription(): ?string
    {
        return 'Actualmente tienes ' . \App\Models\Monedas::count() . ' monedas registradas.';
    }
}
