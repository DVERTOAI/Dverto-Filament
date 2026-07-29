<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\EditRecord;

abstract class EditAdminRecord extends EditRecord
{
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
