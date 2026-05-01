<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Support\Breadcrumbs;
use Filament\Resources\Pages\ListRecords;

abstract class ListAdminRecords extends ListRecords
{
    /**
     * @return array<string>
     */
    public function getBreadcrumbs(): array
    {
        return Breadcrumbs::resourceIndex(static::getResource());
    }
}
