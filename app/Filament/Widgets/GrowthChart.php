<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class GrowthChart extends ChartWidget
{
    protected ?string $heading = 'Growth';

    protected static ?int $sort = 2;

    protected static bool $isLazy = true;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [78],
                    'backgroundColor' => [
                        '#5B3FE5',
                    ],
                ],
            ],
            'labels' => ['Growth'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.parsed + "%"; }',
                    ],
                ],
            ],
            'cutout' => '75%',
        ];
    }
}
