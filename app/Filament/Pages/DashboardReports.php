<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class DashboardReports extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.dashboard-reports';

    public function getTitle(): string|Htmlable
    {
        return new HtmlString('Dashboard');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getViewData(): array
    {
        return [
            'reports' => [
                [
                    'title' => 'Sales Report',
                    'date' => 'May 2026',
                    'status' => 'Completed',
                ],
                [
                    'title' => 'Customer Analysis',
                    'date' => 'May 2026',
                    'status' => 'In Progress',
                ],
                [
                    'title' => 'Revenue Forecast',
                    'date' => 'May 2026',
                    'status' => 'Pending',
                ],
            ],
        ];
    }
}
