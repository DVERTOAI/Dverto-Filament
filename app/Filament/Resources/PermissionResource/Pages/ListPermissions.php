<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\Pages\ListAdminRecords;
use App\Filament\Resources\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;

class ListPermissions extends ListAdminRecords
{
    protected static string $resource = PermissionResource::class;

    public function getHeading(): string
    {
        return 'Permissions';
    }

    public function getSubheading(): ?string
    {
        return 'Manage permission rules that control access across your workspace.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Permission')
                ->icon(Heroicon::Plus),
        ];
    }
}
