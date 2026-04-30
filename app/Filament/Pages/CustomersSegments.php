<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasMinimalBreadcrumbs;
use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Pages\Page;

class CustomersSegments extends Page
{
    use HasMinimalBreadcrumbs;
    use HasPagePermission;

    protected static ?string $navigationLabel = 'Segments';

    protected static ?string $navigationParentItem = 'Customers';

    protected static ?int $navigationSort = 22;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.customers-segments';

    protected static function getBreadcrumbParentPage(): ?string
    {
        return Customers::class;
    }

    protected static function requiredPermission(): string
    {
        return AdminPermissions::MANAGE_CUSTOMERS;
    }
}
