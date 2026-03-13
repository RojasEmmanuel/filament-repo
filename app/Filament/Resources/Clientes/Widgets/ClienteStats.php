<?php

namespace App\Filament\Resources\Clientes\Widgets;

use App\Models\Clientes;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClienteStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Clientes', Clientes::where('tipo', 'cliente')->count())
                ->description('Registrados como clientes')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Stat::make('Total Prospectos', Clientes::where('tipo', 'prospecto')->count())
                ->description('Registrados como prospectos')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('warning'),

            Stat::make('Total Registros', Clientes::count())
                ->description('Clientes + Prospectos')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
