<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardStatsOverview extends Widget
{
    protected string $view = 'filament.widgets.dashboard-stats-overview';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'items' => [
                [
                    'label' => 'Total Users',
                    'value' => number_format(User::query()->count()),
                    'trend' => '12.5%',
                    'context' => 'from last week',
                    'icon' => Heroicon::OutlinedUserPlus,
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Total Roles',
                    'value' => number_format(Role::query()->count()),
                    'trend' => '8.2%',
                    'context' => 'from last week',
                    'icon' => Heroicon::OutlinedShieldCheck,
                    'tone' => 'green',
                ],
                [
                    'label' => 'Total Permissions',
                    'value' => number_format(Permission::query()->count()),
                    'trend' => '15.3%',
                    'context' => 'from last week',
                    'icon' => Heroicon::OutlinedKey,
                    'tone' => 'orange',
                ],
                [
                    'label' => 'System Activity',
                    'value' => '98.6%',
                    'trend' => 'Healthy',
                    'context' => null,
                    'icon' => Heroicon::OutlinedChartBarSquare,
                    'tone' => 'violet',
                ],
            ],
        ];
    }
}
