<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasMinimalBreadcrumbs;
use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Pages\Page;

class ReportsDaily extends Page
{
    use HasMinimalBreadcrumbs;
    use HasPagePermission;

    protected static ?string $navigationLabel = 'Daily';

    protected static ?string $navigationParentItem = 'Reports';

    protected static ?int $navigationSort = 11;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.reports-daily';

    protected static function getBreadcrumbParentPage(): ?string
    {
        return Reports::class;
    }

    protected static function requiredPermission(): string
    {
        return AdminPermissions::VIEW_REPORTS;
    }
}
