<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Pages\Page;

class ReportsMonthly extends Page
{
    use HasPagePermission;

    protected static ?string $navigationLabel = 'Monthly';

    protected static ?string $navigationParentItem = 'Reports';

    protected static ?int $navigationSort = 12;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.reports-monthly';

    protected static function requiredPermission(): string
    {
        return AdminPermissions::VIEW_REPORTS;
    }
}
