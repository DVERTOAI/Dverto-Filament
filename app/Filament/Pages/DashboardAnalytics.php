<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class DashboardAnalytics extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationLabel = 'Analytics';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.dashboard-analytics';

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
            'totalRevenue' => '$425,000',
            'totalOrders' => '276',
            'totalCustomers' => '1,245',
            'conversionRate' => '3.48%',
        ];
    }
}
