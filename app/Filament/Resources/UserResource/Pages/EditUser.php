<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\Pages\EditAdminRecord;
use App\Filament\Resources\UserResource;
use Illuminate\Contracts\Support\Htmlable;

class EditUser extends EditAdminRecord
{
    protected static string $resource = UserResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Edit User';
    }


    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
