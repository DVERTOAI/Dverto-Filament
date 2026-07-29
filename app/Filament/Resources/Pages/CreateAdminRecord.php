<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;

abstract class CreateAdminRecord extends CreateRecord
{
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
