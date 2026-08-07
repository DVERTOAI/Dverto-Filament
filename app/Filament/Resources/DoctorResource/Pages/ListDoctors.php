<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\DoctorResource;
use App\Filament\Resources\Pages\ListAdminRecords;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;

class ListDoctors extends ListAdminRecords
{
    protected static string $resource = DoctorResource::class;

    public function getHeading(): string
    {
        return 'Doctors';
    }

    public function getSubheading(): ?string
    {
        return 'Manage doctors, specializations, and departments.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Doctor')
                ->icon(Heroicon::Plus)
                ->color('primary'),
        ];
    }
}
