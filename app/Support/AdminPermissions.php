<?php

namespace App\Support;

class AdminPermissions
{
    public const MANAGE_ACCESS_CONTROL = 'manage access control';

    public const VIEW_DASHBOARD = 'view dashboard';

    public const VIEW_REPORTS = 'view reports';

    public const EXPORT_REPORTS = 'export reports';

    public const VIEW_CUSTOMERS = 'view customers';

    public const MANAGE_CUSTOMERS = 'manage customers';

    public const MANAGE_SETTINGS = 'manage settings';

    public const VIEW_ACTIVITY_LOG = 'view activity log';

    public const VIEW_CONTENT = 'view content';

    public const MANAGE_CONTENT = 'manage content';

    public const VIEW_USERS = 'view users';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::MANAGE_ACCESS_CONTROL,
            self::VIEW_DASHBOARD,
            self::VIEW_REPORTS,
            self::EXPORT_REPORTS,
            self::VIEW_CUSTOMERS,
            self::MANAGE_CUSTOMERS,
            self::MANAGE_SETTINGS,
            self::VIEW_ACTIVITY_LOG,
            self::VIEW_CONTENT,
            self::MANAGE_CONTENT,
            self::VIEW_USERS,
        ];
    }
}
