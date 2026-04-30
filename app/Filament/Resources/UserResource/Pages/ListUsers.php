<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\Pages\ListAdminRecords;
use App\Filament\Resources\UserResource;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;

class ListUsers extends ListAdminRecords
{
    protected static string $resource = UserResource::class;

    public function getHeading(): string
    {
        return 'Users';
    }

    public function getSubheading(): ?string
    {
        return 'Manage and organize admin users across your workspace.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New User')
                ->icon(Heroicon::Plus),
        ];
    }
}
