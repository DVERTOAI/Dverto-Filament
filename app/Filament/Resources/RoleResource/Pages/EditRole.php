<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\Pages\EditAdminRecord;
use App\Filament\Resources\RoleResource;
use Filament\Actions\DeleteAction;

class EditRole extends EditAdminRecord
{
    protected static string $resource = RoleResource::class;

    public function getHeading(): string
    {
        return 'Edit Role';
    }

    public function getSubheading(): ?string
    {
        return 'Update role details and permission access.';
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
