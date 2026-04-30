<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Support\Breadcrumbs;
use Filament\Resources\Pages\EditRecord;

abstract class EditAdminRecord extends EditRecord
{
    /**
     * @return array<string>
     */
    public function getBreadcrumbs(): array
    {
        return Breadcrumbs::resourceRecord(
            resource: static::getResource(),
            recordTitle: filled($this->getRecordTitle()) ? strip_tags((string) $this->getRecordTitle()) : null,
        );
    }
}
