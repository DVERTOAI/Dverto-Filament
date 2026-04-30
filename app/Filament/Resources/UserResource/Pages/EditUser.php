<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getHeading(): string
    {
        return 'Edit User';
    }

    public function getSubheading(): ?string
    {
        return 'Update account details and access roles.';
    }

    public function getBreadcrumbs(): array
    {
        return [
            UserResource::getUrl('index') => 'Users',
            'Edit User',
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
