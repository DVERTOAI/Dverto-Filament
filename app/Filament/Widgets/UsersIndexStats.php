<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Spatie\Permission\Models\Role;

class UsersIndexStats extends Widget
{
    protected string $view = 'filament.widgets.users-index-stats';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -1;

    protected static bool $isLazy = true;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $total = User::query()->count();
        $active = User::query()->whereNotNull('email_verified_at')->count();
        $inactive = max(0, $total - $active);

        return [
            'stats' => [
                [
                    'label' => 'Total users',
                    'value' => number_format($total),
                    'hint'  => 'All workspace accounts',
                    'icon'  => Heroicon::OutlinedUsers,
                    'tone'  => 'violet',
                ],
                [
                    'label' => 'Active',
                    'value' => number_format($active),
                    'hint'  => 'Verified accounts',
                    'icon'  => Heroicon::OutlinedCheckCircle,
                    'tone'  => 'green',
                ],
                [
                    'label' => 'Inactive',
                    'value' => number_format($inactive),
                    'hint'  => 'Pending verification',
                    'icon'  => Heroicon::OutlinedClock,
                    'tone'  => 'amber',
                ],
                [
                    'label' => 'Roles',
                    'value' => number_format(Role::query()->count()),
                    'hint'  => 'Access levels defined',
                    'icon'  => Heroicon::OutlinedShieldCheck,
                    'tone'  => 'blue',
                ],
            ],
        ];
    }
}
