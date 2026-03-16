<?php

namespace App\Filament\Imports;

use App\Models\Fraccionamiento;
use App\Models\Lotes;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class LotesImporter extends Importer
{
    protected static ?string $model = Lotes::class;

    protected static string $importableName = 'Lotes';

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('fraccionamiento')
                ->label('Fraccionamiento (ID)')
                ->relationship('fraccionamiento', 'id')
                ->rules(['required', 'integer'])
                ->example('8')
                ->numeric()                          // ← fuerza validación numérica
                ->castStateUsing(fn ($state) => (int) $state)  // ← convierte explícitamente a integer
                ->requiredMapping(),
            ImportColumn::make('manzana')
                ->label('Manzana')
                ->rules(['required', 'string', 'max:20'])
                ->requiredMapping(),

            ImportColumn::make('lote')
                ->label('Lote')
                ->rules(['required', 'string', 'max:20'])
                ->requiredMapping(),

            ImportColumn::make('area')
                ->label('Área (m²)')
                ->numeric(10,2)
                ->rules(['required', 'numeric', 'min:0'])
                ->requiredMapping(),

            ImportColumn::make('norte')
                ->numeric(10,2)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('sur')
                ->numeric(10,2)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('este')
                ->numeric(10,2)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('oeste')
                ->numeric(10,2)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('precio')
                ->label('Precio')
                ->numeric(10,2)
                ->rules(['required', 'numeric', 'min:0'])
                ->requiredMapping(),

            ImportColumn::make('estatus')
                ->label('Estatus')
                ->rules([
                    'nullable',
                    Rule::in(['disponible', 'vendido', 'liquidado']),
                ])
                ->example('disponible o liquidado'),

            ImportColumn::make('observaciones')
                ->rules(['nullable', 'string', 'max:1000']),
        ];
    }

    public function resolveRecord(): ?Lotes
    {
        // Agregamos chequeo rápido para depurar
        if (empty($this->data['fraccionamiento_id'])) {
            throw new \Exception("No se encontró fraccionamiento para el valor: " . ($this->data['fraccionamiento'] ?? 'vacío'));
        }

        return Lotes::firstOrNew([
            'fraccionamiento_id' => $this->data['fraccionamiento_id'],
            'manzana'            => $this->data['manzana'],
            'lote'               => $this->data['lote'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Importación de lotes finalizada: ' .
            Number::format($import->successful_rows) . ' ' .
            str('lote')->plural($import->successful_rows) . ' importados.';

        $failed = $import->getFailedRowsCount();

        if ($failed > 0) {
            $body .= ' ' . Number::format($failed) . ' ' .
                str('fila')->plural($failed) . ' fallaron (revisa el archivo de errores).';
        }

        return $body;
    }
}