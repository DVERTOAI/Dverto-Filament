<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ProfileReport extends Widget
{
    protected string $view = 'filament.widgets.profile-report';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 4;

    protected static bool $isLazy = true;

    protected function getViewData(): array
    {
        return [
            'year' => 'YEAR 2022',
            'growth' => '+68.2%',
            'revenue' => '$84,686k',
            'status' => 'positive', // or 'negative'
        ];
    }
}
