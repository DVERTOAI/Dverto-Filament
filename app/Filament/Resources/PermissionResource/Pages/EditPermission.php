<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\Pages\EditAdminRecord;
use App\Filament\Resources\PermissionResource;
use Filament\Actions\DeleteAction;

class EditPermission extends EditAdminRecord
{
    protected static string $resource = PermissionResource::class;

    public function getHeading(): string
    {
        return 'Edit Permission';
    }

    public function getSubheading(): ?string
    {
        return 'Update permission details for workspace access.';
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
