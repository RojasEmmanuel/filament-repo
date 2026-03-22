<?php

namespace App\Filament\Resources\Plantillas;

use App\Filament\Resources\Plantillas\Pages\CreatePlantilla;
use App\Filament\Resources\Plantillas\Pages\EditPlantilla;
use App\Filament\Resources\Plantillas\Pages\ListPlantillas;
use App\Filament\Resources\Plantillas\Schemas\PlantillaForm;
use App\Filament\Resources\Plantillas\Tables\PlantillasTable;
use App\Models\Plantilla;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PlantillaResource extends Resource
{
    protected static ?string $model = Plantilla::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;
    protected static string|UnitEnum|null $navigationGroup = 'Configuración';
    protected static ?int $navigationSort = 6;
    protected static ?string $recordTitleAttribute = 'Plantilla';

    public static function form(Schema $schema): Schema
    {
        return PlantillaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlantillasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlantillas::route('/'),
            'create' => CreatePlantilla::route('/create'),
            'edit' => EditPlantilla::route('/{record}/edit'),
        ];
    }
}
