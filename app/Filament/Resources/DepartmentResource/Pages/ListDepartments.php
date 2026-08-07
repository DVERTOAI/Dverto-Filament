<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\Pages\ListAdminRecords;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;

class ListDepartments extends ListAdminRecords
{
    protected static string $resource = DepartmentResource::class;

    public function getHeading(): string
    {
        return 'Departments';
    }

    public function getSubheading(): ?string
    {
        return 'Manage departments, codes, rooms, and access.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Department')
                ->icon(Heroicon::Plus)
                ->color('primary'),
        ];
    }
}
