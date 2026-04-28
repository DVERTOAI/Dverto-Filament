<?php

namespace App\Filament\Pages\Concerns;

use Illuminate\Support\Facades\Gate;

trait HasPagePermission
{
    abstract protected static function requiredPermission(): string;

    public static function canAccess(): bool
    {
        return Gate::allows(static::requiredPermission());
    }
}
