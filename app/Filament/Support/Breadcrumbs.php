<?php

namespace App\Filament\Support;

use BackedEnum;
use UnitEnum;

class Breadcrumbs
{
    /**
     * @return array<string>
     */
    public static function page(string $label, ?string $parentLabel = null, ?string $parentUrl = null): array
    {
        $breadcrumbs = [];

        if (filled($parentLabel)) {
            if (filled($parentUrl)) {
                $breadcrumbs[$parentUrl] = $parentLabel;
            } else {
                $breadcrumbs[] = $parentLabel;
            }
        }

        $breadcrumbs[] = $label;

        return $breadcrumbs;
    }

    /**
     * @param  class-string  $resource
     * @return array<string>
     */
    public static function resourceIndex(string $resource): array
    {
        $breadcrumbs = [];

        if (filled($group = static::normalizeLabel($resource::getNavigationGroup()))) {
            $breadcrumbs[] = $group;
        }

        $breadcrumbs[] = $resource::getBreadcrumb();

        return $breadcrumbs;
    }

    /**
     * @param  class-string  $resource
     * @return array<string>
     */
    public static function resourceCreate(string $resource, string $label = 'New'): array
    {
        return [
            ...static::resourceBase($resource),
            $label,
        ];
    }

    /**
     * @param  class-string  $resource
     * @return array<string>
     */
    public static function resourceRecord(string $resource, ?string $recordTitle): array
    {
        return [
            ...static::resourceBase($resource),
            $recordTitle ?: 'Details',
        ];
    }

    /**
     * @param  class-string  $resource
     * @return array<string>
     */
    protected static function resourceBase(string $resource): array
    {
        $breadcrumbs = [];

        if (filled($group = static::normalizeLabel($resource::getNavigationGroup()))) {
            $breadcrumbs[] = $group;
        }

        $breadcrumbs[$resource::getUrl()] = $resource::getBreadcrumb();

        return $breadcrumbs;
    }

    protected static function normalizeLabel(string | UnitEnum | null $label): ?string
    {
        return match (true) {
            $label instanceof BackedEnum => (string) $label->value,
            $label instanceof UnitEnum => $label->name,
            filled($label) => $label,
            default => null,
        };
    }
}
