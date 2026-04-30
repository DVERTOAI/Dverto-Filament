<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Support\Breadcrumbs;
use Filament\Resources\Pages\CreateRecord;

abstract class CreateAdminRecord extends CreateRecord
{
    /**
     * @return array<string>
     */
    public function getBreadcrumbs(): array
    {
        return Breadcrumbs::resourceCreate(static::getResource());
    }
}
