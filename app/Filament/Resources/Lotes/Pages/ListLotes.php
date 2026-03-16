<?php

namespace App\Filament\Resources\Lotes\Pages;

use App\Filament\Exports\LotesExporter;
use App\Filament\Imports\LotesImporter;
use App\Filament\Resources\Lotes\LotesResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListLotes extends ListRecords
{
    protected static string $resource = LotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()->importer(LotesImporter::class),
            
        ];
    }

}
