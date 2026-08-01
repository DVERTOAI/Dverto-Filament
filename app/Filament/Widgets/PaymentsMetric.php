<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PaymentsMetric extends Widget
{
    protected string $view = 'filament.widgets.payments-metric';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 6;

    protected static bool $isLazy = true;

    protected function getViewData(): array
    {
        return [
            'amount' => '$2,456',
            'decline' => '-14.82%',
            'label' => 'Payments',
        ];
    }
}
