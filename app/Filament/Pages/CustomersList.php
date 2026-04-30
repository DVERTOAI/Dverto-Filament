<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasMinimalBreadcrumbs;
use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Pages\Page;

class CustomersList extends Page
{
    use HasMinimalBreadcrumbs;
    use HasPagePermission;

    protected static ?string $navigationLabel = 'Customer List';

    protected static ?string $navigationParentItem = 'Customers';

    protected static ?int $navigationSort = 21;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.customers-list';

    protected static function getBreadcrumbParentPage(): ?string
    {
        return Customers::class;
    }

    protected static function requiredPermission(): string
    {
        return AdminPermissions::VIEW_CUSTOMERS;
    }
}
