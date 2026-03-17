<?php

namespace App\Filament\Resources\Monedas;

use App\Filament\Clusters\Configuración\ConfiguraciónCluster;
use App\Filament\Resources\Monedas\Pages\CreateMonedas;
use App\Filament\Resources\Monedas\Pages\EditMonedas;
use App\Filament\Resources\Monedas\Pages\ListMonedas;
use App\Filament\Resources\Monedas\Schemas\MonedasForm;
use App\Filament\Resources\Monedas\Tables\MonedasTable;
use App\Models\Monedas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MonedasResource extends Resource
{
    protected static ?string $model = Monedas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    protected static string|UnitEnum|null $navigationGroup = 'Finanzas';
    protected static ?string $recordTitleAttribute = 'Monedas';
    protected static ?string $cluster = ConfiguraciónCluster::class;


    protected static ?int $navigationSort=6;
    public static function form(Schema $schema): Schema
    {
        return MonedasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MonedasTable::configure($table);
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
            'index' => ListMonedas::route('/'),
            //'create' => CreateMonedas::route('/create'),
            //'edit' => EditMonedas::route('/{record}/edit'),
        ];
    }
}
