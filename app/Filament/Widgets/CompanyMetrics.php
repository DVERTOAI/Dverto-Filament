<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CompanyMetrics extends Widget
{
    protected string $view = 'filament.widgets.company-metrics';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected function getViewData(): array
    {
        return [
            'metrics' => [
                [
                    'year' => '2025',
                    'value' => '$32.5k',
                ],
                [
                    'year' => '2024',
                    'value' => '$41.2k',
                ],
            ],
            'companyGrowth' => '62% Company Growth',
        ];
    }
}
