<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Equipos con mas problemas';

    protected static ?string $pollingInterval = null; // Disable auto-refresh
    protected static string  $color           = 'info';

    protected function getData(): array {
        //Mock Data
        $data = [
            ['equipment' => 'Caldera (CM-CAL-01)', 'issues' => 15],
            ['equipment' => 'Mangle 1 (LV-MGL-01)', 'issues' => 12],
            ['equipment' => 'Prensa 1 (LAV-PRE-01)', 'issues' => 8],
            ['equipment' => 'Tombola 11 (LAV-TOM-11)', 'issues' => 6],
            ['equipment' => 'Tombola 1 (LAV-TOM-1)', 'issues' => 5],
        ];

        return [
            'datasets' => [
                [
                    'label'           => 'Numero de alertas',
                    'data'            => collect($data)->pluck('issues')->toArray(),
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.8)', //blue
                        'rgba(255, 99, 132, 0.8)', //red
                        'rgba(255, 205, 86, 0.8)', //yellow
                        'rgba(75, 192, 192, 0.8)', //green
                        'rgba(153, 102, 255, 0.8)', //purple
                    ],
                    'borderWidth'     => 1,
                    'borderColor'     => [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                    ]
                ],
            ],
            'labels'   => collect($data)->pluck('equipment')->toArray(),
        ];
    }

    protected function getFilters(): ?array {
        return [
            'ultima '   => 'ultima semana',
            'ultimo mes'      => 'ultimo mes',
            'ultima quincena' => 'ultima quincena',
            'hoy'             => 'hoy',
            'siempre'         => 'siempre'
        ];
    }

    protected function getType(): string {
        return 'bar';
    }
}
