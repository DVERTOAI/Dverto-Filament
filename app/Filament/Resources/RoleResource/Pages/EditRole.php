<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\Pages\EditAdminRecord;
use App\Filament\Resources\RoleResource;
use Filament\Actions\DeleteAction;

class EditRole extends EditAdminRecord
{
    protected static string $resource = RoleResource::class;

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
