<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\DoctorResource;
use App\Filament\Resources\Pages\CreateAdminRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateDoctor extends CreateAdminRecord
{
    protected static string $resource = DoctorResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Add Doctor';
    }

    public function getSubheading(): ?string
    {
        return 'Doctor management';
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
