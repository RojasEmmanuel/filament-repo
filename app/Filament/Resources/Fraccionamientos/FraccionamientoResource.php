<?php

namespace App\Filament\Resources\Fraccionamientos;

use App\Filament\Resources\Fraccionamientos\Pages\CreateFraccionamiento;
use App\Filament\Resources\Fraccionamientos\Pages\EditFraccionamiento;
use App\Filament\Resources\Fraccionamientos\Pages\ListFraccionamientos;
use App\Filament\Resources\Fraccionamientos\Schemas\FraccionamientoForm;
use App\Filament\Resources\Fraccionamientos\Tables\FraccionamientosTable;
use App\Models\Fraccionamiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FraccionamientoResource extends Resource
{
    protected static ?string $model = Fraccionamiento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'fraccionamiento';

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
            //
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
