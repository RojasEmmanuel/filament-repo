<?php

namespace App\Filament\Resources\PlanFinanciamientos;

use App\Filament\Resources\PlanFinanciamientos\Pages\CreatePlanFinanciamiento;
use App\Filament\Resources\PlanFinanciamientos\Pages\EditPlanFinanciamiento;
use App\Filament\Resources\PlanFinanciamientos\Pages\ListPlanFinanciamientos;
use App\Filament\Resources\PlanFinanciamientos\Schemas\PlanFinanciamientoForm;
use App\Filament\Resources\PlanFinanciamientos\Tables\PlanFinanciamientosTable;
use App\Models\PlanFinanciamiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PlanFinanciamientoResource extends Resource
{
    protected static ?string $model = PlanFinanciamiento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $label = 'Planes de Financiamiento';
    protected static string|UnitEnum|null $navigationGroup = 'Configuración';
    protected static ?int $navigationSort = 6;
    protected static ?string $recordTitleAttribute = 'PlanFinanciamiento';

    public static function form(Schema $schema): Schema
    {
        return PlanFinanciamientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlanFinanciamientosTable::configure($table);
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
            'index' => ListPlanFinanciamientos::route('/'),
            'create' => CreatePlanFinanciamiento::route('/create'),
            'edit' => EditPlanFinanciamiento::route('/{record}/edit'),
        ];
    }
}
