<?php

namespace App\Filament\Exports;

use App\Models\Clientes;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ClientesExporter extends Exporter
{
    protected static ?string $model = Clientes::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('nombre'),
            ExportColumn::make('apellidos'),
            ExportColumn::make('edad'),
            ExportColumn::make('ciudad'),
            ExportColumn::make('created_at')->label('Creado en'),
            ExportColumn::make('updated_at')->label('Actualizado en'),
            ExportColumn::make('tipo'),
            ExportColumn::make('telefono'),
            ExportColumn::make('fecha_nacimiento'),
            ExportColumn::make('curp'),
            ExportColumn::make('rfc'),
            ExportColumn::make('ocupacion'),
            ExportColumn::make('estado_civil'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your clientes export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
