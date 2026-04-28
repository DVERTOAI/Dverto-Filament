<?php

namespace App\Support;

class AdminPermissions
{
    public const MANAGE_ACCESS_CONTROL = 'manage access control';

    public const VIEW_REPORTS = 'view reports';

    public const VIEW_CUSTOMERS = 'view customers';

    public const MANAGE_CUSTOMERS = 'manage customers';

    public const MANAGE_SETTINGS = 'manage settings';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::MANAGE_ACCESS_CONTROL,
            self::VIEW_REPORTS,
            self::VIEW_CUSTOMERS,
            self::MANAGE_CUSTOMERS,
            self::MANAGE_SETTINGS,
        ];
    }
}
