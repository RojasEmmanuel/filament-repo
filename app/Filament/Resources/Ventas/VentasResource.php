<?php

namespace App\Filament\Resources\Ventas;

use App\Filament\Resources\Ventas\Pages\CreateVentas;
use App\Filament\Resources\Ventas\Pages\EditVentas;
use App\Filament\Resources\Ventas\Pages\ListVentas;
use App\Filament\Resources\Ventas\Schemas\VentasForm;
use App\Filament\Resources\Ventas\Tables\VentasTable;
use App\Models\Ventas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VentasResource extends Resource
{
    protected static ?string $model = Ventas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    protected static string|UnitEnum|null $navigationGroup = 'Atención al Cliente';
    protected static ?int $navigationSort =6;
    protected static ?string $recordTitleAttribute = 'Ventas';

    public static function form(Schema $schema): Schema
    {
        return VentasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VentasTable::configure($table);
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
            'index' => ListVentas::route('/'),
            'create' => CreateVentas::route('/create'),
            'edit' => EditVentas::route('/{record}/edit'),
        ];
    }
}
