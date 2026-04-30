<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
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

    public function getBreadcrumbs(): array
    {
        return [
            RoleResource::getUrl('index') => 'Roles',
            'New Role',
        ];
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
