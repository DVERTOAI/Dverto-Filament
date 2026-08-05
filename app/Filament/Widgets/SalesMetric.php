<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SalesMetric extends Widget
{
    protected string $view = 'filament.widgets.sales-metric';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 5;

    protected static bool $isLazy = true;

    protected function getViewData(): array
    {
        return [
            'amount' => '$4,679',
            'growth' => '+28.42%',
            'label' => 'Sales',
        ];
    }
}
