<?php

namespace App\Filament\Widgets;

use App\Models\Ventas;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VentasStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Ventas totales', Ventas::count()),

            Stat::make('Pendientes', 
                Ventas::where('estatus', 'pendiente')->count()
            ),

            Stat::make('Aprobadas', 
                Ventas::where('estatus', 'aprobada')->count()
            ),
        ];
    }
}
