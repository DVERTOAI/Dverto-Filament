<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\Pages\CreateAdminRecord;
use App\Filament\Resources\RoleResource;
use Illuminate\Contracts\Support\Htmlable;

class CreateRole extends CreateAdminRecord
{
    protected static string $resource = RoleResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'New Role';
    }

    public function getSubheading(): ?string
    {
        return null;
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
