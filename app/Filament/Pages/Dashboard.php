<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasMinimalBreadcrumbs;
use App\Filament\Widgets\AccessControlOverview;
use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\DashboardWelcome;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard
{
    use HasMinimalBreadcrumbs;

    protected static bool $shouldRegisterNavigation = false;

    protected Width|string|null $maxContentWidth = Width::Full;

    public function getColumns(): int|array
    {
        return 1;
    }

    public function getPageClasses(): array
    {
        return ['ac-dashboard-page'];
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Dashboard <span class="ac-dashboard-wave" aria-hidden="true">👋</span>');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('dateRange')
                ->label('May 12, 2024 - May 18, 2024')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('gray')
                ->disabled()
                ->extraAttributes(['class' => 'ac-dashboard-date-action']),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardWelcome::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            AccessControlOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
        ];
    }
}
