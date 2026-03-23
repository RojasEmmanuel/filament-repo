<?php

namespace App\Filament\Widgets;

use App\Models\Ventas;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VentasPorFraccionamientoChart extends ChartWidget
{
    protected ?string $heading = 'Ventas Por Fraccionamiento Chart';

    protected function getData(): array
    {
        $data = Ventas::select('fraccionamiento_id', DB::raw('count(*) as total'))
            ->groupBy('fraccionamiento_id')
            ->with('fraccionamiento')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data' => $data->pluck('total'),


                    // 👇 AQUÍ VA TU CONFIG
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.6)',
                        'rgba(34, 197, 94, 0.6)',
                        'rgba(245, 158, 11, 0.6)',
                        'rgba(239, 68, 68, 0.6)',
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                    ],
                    'borderWidth' => 3,
                ],
            ],
            'labels' => $data->map(function ($item) {
                return $item->fraccionamiento->nombre ?? 'N/A';
            }),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
