<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasMinimalBreadcrumbs;
use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AuditLog extends Page
{
    use HasMinimalBreadcrumbs;
    use HasPagePermission;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Audit Log';

    protected static ?int $navigationSort = 4;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.audit-log';

    protected static function requiredPermission(): string
    {
        return AdminPermissions::VIEW_ACTIVITY_LOG;
    }
}
