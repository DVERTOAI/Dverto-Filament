<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\ListRecords;

abstract class ListAdminRecords extends ListRecords
{
    public function getBreadcrumbs(): array
    {
        return [];
    }

    /**
     * Lets render hooks target every admin listing at once, rather than each page class.
     */
    public function getRenderHookScopes(): array
    {
        return [
            ...parent::getRenderHookScopes(),
            self::class,
        ];
    }
}
