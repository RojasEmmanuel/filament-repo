<?php

namespace App\Filament\Resources\LotesResource\Widgets;

use App\Models\Lotes;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // En StatsOverviewWidget no se usa heading normalmente
    public ?array $filters = [];

    protected function getStats(): array
    {
        $query = Lotes::query()
            ->when($this->filters['fraccionamiento'] ?? null, function ($query, $fraccionamientoId) {
                return $query->where('fraccionamiento_id', $fraccionamientoId);
            });

        $totalLotes = $query->count();
        $disponibles = (clone $query)->where('estatus', 'disponible')->count();
        $vendidos = (clone $query)->where('estatus', 'vendido')->count();
        $liquidados = (clone $query)->where('estatus', 'liquidado')->count();

        return [
            Stat::make('Total de Lotes', $totalLotes)
                ->description('Total de lotes registrados')
                ->descriptionIcon('heroicon-m-home')
                ->color('gray'),

            Stat::make('Disponibles', $disponibles)
                ->description($totalLotes > 0 ? round(($disponibles / $totalLotes) * 100, 1) . '%' : '0%')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Vendidos', $vendidos)
                ->description($totalLotes > 0 ? round(($vendidos / $totalLotes) * 100, 1) . '%' : '0%')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),

            Stat::make('Liquidados', $liquidados)
                ->description($totalLotes > 0 ? round(($liquidados / $totalLotes) * 100, 1) . '%' : '0%')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),
        ];
    }
}