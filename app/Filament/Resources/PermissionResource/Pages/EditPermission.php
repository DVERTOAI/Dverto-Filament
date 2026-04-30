<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\Pages\EditAdminRecord;
use App\Filament\Resources\PermissionResource;
use Filament\Actions\DeleteAction;

class EditPermission extends EditAdminRecord
{
    protected static string $resource = PermissionResource::class;

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
