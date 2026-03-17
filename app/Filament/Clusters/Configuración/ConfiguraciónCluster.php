<?php

namespace App\Filament\Clusters\Configuración;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class ConfiguraciónCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;
    protected static ?int $navigationSort = 1;
}
