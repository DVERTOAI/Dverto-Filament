<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Str;

class DashboardWelcome extends Widget
{
    protected string $view = 'filament.widgets.dashboard-welcome';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $user = auth()->user();
        $role = $user?->hasRole('admin')
            ? 'Super Admin'
            : Str::headline($user?->roles()->pluck('name')->first() ?? 'Team Member');

        return [
            'user' => $user,
            'initials' => Str::of($user?->name ?? 'Admin User')
                ->explode(' ')
                ->filter()
                ->take(2)
                ->map(fn (string $part): string => Str::upper(Str::substr($part, 0, 1)))
                ->implode(''),
            'role' => $role,
        ];
    }
}
