<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\Pages\CreateAdminRecord;
use App\Filament\Resources\PermissionResource;
use Illuminate\Contracts\Support\Htmlable;

class CreatePermission extends CreateAdminRecord
{
    protected static string $resource = PermissionResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'New Permission';
    }

    public function getSubheading(): ?string
    {
        return null;
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
