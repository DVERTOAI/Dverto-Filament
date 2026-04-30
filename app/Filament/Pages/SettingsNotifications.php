<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasMinimalBreadcrumbs;
use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Pages\Page;

class SettingsNotifications extends Page
{
    use HasMinimalBreadcrumbs;
    use HasPagePermission;

    protected static ?string $navigationLabel = 'Notifications';

    protected static ?string $navigationParentItem = 'Settings';

    protected static ?int $navigationSort = 32;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.settings-notifications';

    protected static function getBreadcrumbParentPage(): ?string
    {
        return Settings::class;
    }

    protected static function requiredPermission(): string
    {
        return AdminPermissions::MANAGE_SETTINGS;
    }
}
