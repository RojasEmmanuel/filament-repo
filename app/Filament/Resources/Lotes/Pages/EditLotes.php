<?php

namespace App\Filament\Resources\Lotes\Pages;

use App\Filament\Resources\Lotes\LotesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLotes extends EditRecord
{
    protected static string $resource = LotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
