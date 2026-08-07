<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\Pages\CreateAdminRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateDepartment extends CreateAdminRecord
{
    protected static string $resource = DepartmentResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Add Department';
    }

    public function getSubheading(): ?string
    {
        return 'Department management';
    }

    /**
     * @return array<string>
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
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
