<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
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

    public function getBreadcrumbs(): array
    {
        return [
            PermissionResource::getUrl('index') => 'Permissions',
            'New Permission',
        ];
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
