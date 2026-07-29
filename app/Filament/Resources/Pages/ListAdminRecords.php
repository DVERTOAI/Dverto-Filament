<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\ListRecords;

abstract class ListAdminRecords extends ListRecords
{
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
