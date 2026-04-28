<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Pages\Page;

class SettingsGeneral extends Page
{
    use HasPagePermission;

    protected static ?string $navigationLabel = 'General';

    protected static ?string $navigationParentItem = 'Settings';

    protected static ?int $navigationSort = 31;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.settings-general';

    protected static function requiredPermission(): string
    {
        return AdminPermissions::MANAGE_SETTINGS;
    }
}
