<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\Pages\CreateAdminRecord;
use App\Filament\Resources\RoleResource;

class CreateRole extends CreateAdminRecord
{
    protected static string $resource = RoleResource::class;

    public function getHeading(): string
    {
        return 'New Role';
    }

    public function getSubheading(): ?string
    {
        return 'Define role details and attach permission access.';
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
