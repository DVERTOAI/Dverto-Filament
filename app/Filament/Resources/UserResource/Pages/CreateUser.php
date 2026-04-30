<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
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

    public function getBreadcrumbs(): array
    {
        return [
            UserResource::getUrl('index') => 'Users',
            'New User',
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
