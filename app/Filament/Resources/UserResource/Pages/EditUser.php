<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\Pages\EditAdminRecord;
use App\Filament\Resources\UserResource;
use Filament\Actions\DeleteAction;

class EditUser extends EditAdminRecord
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
