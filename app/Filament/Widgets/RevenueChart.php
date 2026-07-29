<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Total Revenue';

    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
        '2xl' => 2,
    ];

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => '2025',
                    'data' => [15, 5, 12, 25, 15, 8, 10],
                    'borderColor' => '#5B3FE5',
                    'backgroundColor' => '#5B3FE5',
                    'borderRadius' => 8,
                    'borderSkipped' => false,
                ],
                [
                    'label' => '2024',
                    'data' => [8, 3, 7, 12, 8, 5, 6],
                    'borderColor' => '#00BCD4',
                    'backgroundColor' => '#00BCD4',
                    'borderRadius' => 8,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'x',
            'scales' => [
                'y' => [
                    'min' => -20,
                    'max' => 30,
                    'ticks' => [
                        'stepSize' => 10,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
        ];
    }
}
