<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\Pages\ListAdminRecords;
use App\Filament\Resources\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;

class ListRoles extends ListAdminRecords
{
    protected static string $resource = RoleResource::class;

    public function getHeading(): string
    {
        return 'Roles';
    }

    public function getSubheading(): ?string
    {
        return 'Manage role groups and their access levels across your workspace.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Role')
                ->icon(Heroicon::Plus),
        ];
    }
}
