<?php

namespace App\Filament\Resources\Bancos;

use App\Filament\Resources\Bancos\Pages\CreateBancos;
use App\Filament\Resources\Bancos\Pages\EditBancos;
use App\Filament\Resources\Bancos\Pages\ListBancos;
use App\Filament\Resources\Bancos\Schemas\BancosForm;
use App\Filament\Resources\Bancos\Tables\BancosTable;
use App\Models\Bancos;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BancosResource extends Resource
{
    protected static ?string $model = Bancos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'Bancos';
    protected static ?int $navigationSort = 5;
    public static function form(Schema $schema): Schema
    {
        return BancosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BancosTable::configure($table);
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
            'index' => ListBancos::route('/'),
            //'create' => CreateBancos::route('/create'),
            //'edit' => EditBancos::route('/{record}/edit'),
        ];
    }
}
