<?php

namespace App\Filament\Widgets;

use App\Models\Ventas;
use Filament\Widgets\ChartWidget;

class VentasChart extends ChartWidget
{
    protected ?string $heading = 'Ventas Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data' => [
                        Ventas::where('estatus', 'pendiente')->count(),
                        Ventas::where('estatus', 'aprobada')->count(),
                    ],
                ],
            ],
            
            'labels' => ['Pendientes', 'Aprobadas'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
