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
                ->label('Fraccionamiento')
                ->relationship(resolveUsing: 'id')
                ->rules(['required', 'exists:fraccionamientos,id'])
                ->castStateUsing(function ($state) {
                    // Limpiar el valor (eliminar espacios, etc.)
                    $state = trim($state);
                    
                    // Verificar que sea un número válido
                    if (!is_numeric($state)) {
                        throw new \Exception("El valor '{$state}' no es un ID válido de fraccionamiento");
                    }
                    
                    return (int) $state;
                }),
                
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
                ->castStateUsing(function ($state) {
                    // Convertir coma decimal a punto
                    return str_replace(',', '.', $state);
                })
                ->rules(['required', 'numeric', 'min:0'])
                ->requiredMapping(),

            ImportColumn::make('norte')
                ->castStateUsing(fn ($state) => $state ? str_replace(',', '.', $state) : null)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('sur')
                ->castStateUsing(fn ($state) => $state ? str_replace(',', '.', $state) : null)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('este')
                ->castStateUsing(fn ($state) => $state ? str_replace(',', '.', $state) : null)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('oeste')
                ->castStateUsing(fn ($state) => $state ? str_replace(',', '.', $state) : null)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('precio')
                ->label('Precio')
                ->castStateUsing(function ($state) {
                    // Convertir coma decimal a punto
                    return str_replace(',', '.', $state);
                })
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
        // Verificar que existe el fraccionamiento
        $fraccionamientoId = $this->data['fraccionamiento'] ?? null;
        
        if (empty($fraccionamientoId)) {
            throw new \Exception("No se proporcionó un ID de fraccionamiento");
        }
        
        // Verificar que el fraccionamiento existe
        $fraccionamiento = Fraccionamiento::find($fraccionamientoId);
        if (!$fraccionamiento) {
            throw new \Exception("No existe fraccionamiento con ID: {$fraccionamientoId}");
        }
        
        return Lotes::firstOrNew([
            'fraccionamiento_id' => $fraccionamientoId,
            'manzana' => $this->data['manzana'],
            'lote' => $this->data['lote'],
        ]);
    }

    public function mutateBeforeCreate(array $data): array
    {
        // Asegurar que el fraccionamiento_id esté correctamente mapeado
        if (isset($data['fraccionamiento'])) {
            $data['fraccionamiento_id'] = $data['fraccionamiento'];
            unset($data['fraccionamiento']);
        }
        
        return $data;
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