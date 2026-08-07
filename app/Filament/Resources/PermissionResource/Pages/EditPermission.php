<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\Pages\EditAdminRecord;
use App\Filament\Resources\PermissionResource;
use Illuminate\Contracts\Support\Htmlable;

class EditPermission extends EditAdminRecord
{
    protected static string $resource = PermissionResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Edit Permission';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
