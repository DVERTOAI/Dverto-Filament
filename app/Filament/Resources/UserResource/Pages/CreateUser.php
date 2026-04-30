<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\Pages\CreateAdminRecord;
use App\Filament\Resources\UserResource;

class CreateUser extends CreateAdminRecord
{
    protected static string $resource = UserResource::class;

    public function getHeading(): string
    {
        return 'New User';
    }

    public function getSubheading(): ?string
    {
        return 'Add profile details and assign workspace roles.';
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
