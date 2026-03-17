<?php

namespace App\Filament\Resources\Fraccionamientos;

use App\Filament\Clusters\Inmobiliaria\InmobiliariaCluster;
use App\Filament\Resources\Fraccionamientos\Pages\CreateFraccionamiento;
use App\Filament\Resources\Fraccionamientos\Pages\EditFraccionamiento;
use App\Filament\Resources\Fraccionamientos\Pages\ListFraccionamientos;
use App\Filament\Resources\Fraccionamientos\RelationManagers\BancosRelationManager;
use App\Filament\Resources\Fraccionamientos\RelationManagers\LotesRelationManager;
use App\Filament\Resources\Fraccionamientos\Schemas\FraccionamientoForm;
use App\Filament\Resources\Fraccionamientos\Tables\FraccionamientosTable;
use App\Models\Fraccionamiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FraccionamientoResource extends Resource
{
    protected static ?string $model = Fraccionamiento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;
    protected static string|UnitEnum|null $navigationGroup = 'Atención al Cliente';
    
    protected static ?string $recordTitleAttribute = 'fraccionamiento';
    protected static ?int $navigationSort = 1;

    
    public static function form(Schema $schema): Schema
    {
        return FraccionamientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FraccionamientosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            BancosRelationManager::class,
            LotesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFraccionamientos::route('/'),
            'create' => CreateFraccionamiento::route('/create'),
            'edit' => EditFraccionamiento::route('/{record}/edit'),
        ];
    }
}
