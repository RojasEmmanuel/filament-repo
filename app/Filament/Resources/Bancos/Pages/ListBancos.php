<?php

namespace App\Filament\Resources\Bancos\Pages;

use App\Filament\Resources\Bancos\BancosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBancos extends ListRecords
{
    protected static string $resource = BancosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
