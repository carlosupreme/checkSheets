<?php

namespace App\Filament\Widgets;

use App\Models\CheckSheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string     $heading     = 'Estadisticas';
    protected static ?int $sort        = 1;


    protected function getStats(): array {
        return [
            Stat::make('Hojas de chequeo', CheckSheet::count()),
            Stat::make('Operadores', User::role('operador')->count()),
            Stat::make('Tiempo promedio', '3:12')->description('Para completar una hoja de chequeo')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success')
                ->chart([150, 20, 100, 100, 50, 0]),
        ];
    }
}
