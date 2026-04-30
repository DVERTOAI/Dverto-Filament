<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\Pages\CreateAdminRecord;
use App\Filament\Resources\PermissionResource;

class CreatePermission extends CreateAdminRecord
{
    protected static string $resource = PermissionResource::class;

    public function getHeading(): string
    {
        return 'New Permission';
    }

    public function getSubheading(): ?string
    {
        return 'Define permission details for workspace access.';
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
