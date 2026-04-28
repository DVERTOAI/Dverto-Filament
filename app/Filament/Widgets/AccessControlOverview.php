<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PermissionResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Support\AdminPermissions;
use Filament\Widgets\Widget;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessControlOverview extends Widget
{
    protected string $view = 'filament.widgets.access-control-overview';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return auth()->user()?->can(AdminPermissions::MANAGE_ACCESS_CONTROL) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'items' => [
                [
                    'label' => UserResource::getNavigationLabel(),
                    'description' => 'Admins and team members',
                    'count' => number_format(User::query()->count()),
                    'icon' => UserResource::getNavigationIcon(),
                    'url' => UserResource::getUrl(),
                    'tone' => 'warning',
                ],
                [
                    'label' => RoleResource::getNavigationLabel(),
                    'description' => 'Access levels by responsibility',
                    'count' => number_format(Role::query()->count()),
                    'icon' => RoleResource::getNavigationIcon(),
                    'url' => RoleResource::getUrl(),
                    'tone' => 'success',
                ],
                [
                    'label' => PermissionResource::getNavigationLabel(),
                    'description' => 'Granular admin capabilities',
                    'count' => number_format(Permission::query()->count()),
                    'icon' => PermissionResource::getNavigationIcon(),
                    'url' => PermissionResource::getUrl(),
                    'tone' => 'gray',
                ],
            ],
        ];
    }
}
