<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListRoles extends ListRecords
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

    public function getBreadcrumbs(): array
    {
        return [];
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
