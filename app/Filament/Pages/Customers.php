<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasMinimalBreadcrumbs;
use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Customers extends Page
{
    use HasMinimalBreadcrumbs;
    use HasPagePermission;

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Customers';

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.customers';

    protected static function requiredPermission(): string
    {
        return AdminPermissions::VIEW_CUSTOMERS;
    }
}
