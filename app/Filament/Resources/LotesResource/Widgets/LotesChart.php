<?php

namespace App\Filament\Resources\LotesResource\Widgets;

use App\Models\Lotes;
use Filament\Widgets\ChartWidget;

class LotesChart extends ChartWidget
{
    // SIN STATIC - para Filament v3+
    protected ?string $heading = 'Distribución de Lotes por Estatus';
    
    public ?array $filters = [];

    protected function getData(): array
    {
        $query = Lotes::query()
            ->when($this->filters['fraccionamiento'] ?? null, function ($query, $fraccionamientoId) {
                return $query->where('fraccionamiento_id', $fraccionamientoId);
            });

        $disponibles = (clone $query)->where('estatus', 'disponible')->count();
        $vendidos = (clone $query)->where('estatus', 'vendido')->count();
        $liquidados = (clone $query)->where('estatus', 'liquidado')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de Lotes',
                    'data' => [$disponibles, $vendidos, $liquidados],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(234, 179, 8, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                    ],
                ],
            ],
            'labels' => ['Disponibles', 'Vendidos', 'Liquidados'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}