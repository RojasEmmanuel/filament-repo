<?php

namespace App\Filament\Resources\Lotes;

use App\Filament\Resources\Lotes\Pages\CreateLotes;
use App\Filament\Resources\Lotes\Pages\EditLotes;
use App\Filament\Resources\Lotes\Pages\ListLotes;
use App\Filament\Resources\Lotes\Schemas\LotesForm;
use App\Filament\Resources\Lotes\Tables\LotesTable;
use App\Filament\Resources\LotesResource\Widgets\LotesChart;
use App\Filament\Resources\LotesResource\Widgets\StatsOverview;
use App\Models\Lotes;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LotesResource extends Resource
{
    protected static ?string $model = Lotes::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static string|UnitEnum|null $navigationGroup = 'Atención al Cliente';
    
    protected static ?string $recordTitleAttribute = 'Lotes';
    protected static ?int $navigationSort=2;

    public static function form(Schema $schema): Schema
    {
        return LotesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LotesTable::configure($table);
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
            'index' => ListLotes::route('/'),
            'create' => CreateLotes::route('/create'),
            'edit' => EditLotes::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StatsOverview::class,
            LotesChart::class
        ];
    }
}
