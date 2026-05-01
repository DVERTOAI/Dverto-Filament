<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PermissionResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Support\AdminPermissions;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Htmlable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessControlOverview extends Widget
{
    protected string $view = 'filament.widgets.access-control-overview';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return auth()->user()?->can(AdminPermissions::MANAGE_ACCESS_CONTROL) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'sectionUrl' => UserResource::getUrl(),
            'items' => [
                $this->makeOverviewItem(
                    label: UserResource::getNavigationLabel(),
                    description: 'Admins and team members',
                    count: User::query()->count(),
                    icon: UserResource::getNavigationIcon(),
                    url: UserResource::getUrl(),
                    tone: 'indigo',
                    sparkline: [18, 22, 20, 28, 24, 19, 27, 26, 34],
                ),
                $this->makeOverviewItem(
                    label: RoleResource::getNavigationLabel(),
                    description: 'Access levels by responsibility',
                    count: Role::query()->count(),
                    icon: RoleResource::getNavigationIcon(),
                    url: RoleResource::getUrl(),
                    tone: 'emerald',
                    sparkline: [10, 16, 15, 22, 17, 16, 22, 21, 29],
                ),
                $this->makeOverviewItem(
                    label: PermissionResource::getNavigationLabel(),
                    description: 'Granular admin capabilities',
                    count: Permission::query()->count(),
                    icon: PermissionResource::getNavigationIcon(),
                    url: PermissionResource::getUrl(),
                    tone: 'orange',
                    sparkline: [24, 28, 25, 20, 19, 24, 22, 18, 27, 26, 35],
                ),
            ],
        ];
    }

    /**
     * @param  array<int, int|float>  $sparkline
     * @return array<string, mixed>
     */
    protected function makeOverviewItem(
        string $label,
        string $description,
        int $count,
        string|\BackedEnum|Htmlable|null $icon,
        string $url,
        string $tone,
        array $sparkline,
    ): array {
        return [
            'label' => $label,
            'description' => $description,
            'count' => number_format($count),
            'icon' => $icon,
            'url' => $url,
            'tone' => $tone,
            ...$this->buildSparkline($sparkline),
        ];
    }

    /**
     * @param  array<int, int|float>  $values
     * @return array{sparkline_path: string, sparkline_area_path: string, sparkline_last_x: float, sparkline_last_y: float}
     */
    protected function buildSparkline(array $values): array
    {
        $width = 280;
        $height = 82;
        $baseline = 70;
        $step = count($values) > 1 ? $width / (count($values) - 1) : $width;
        $min = min($values);
        $max = max($values);
        $range = max($max - $min, 1);

        $points = collect($values)
            ->map(function (int|float $value, int $index) use ($baseline, $height, $min, $range, $step): array {
                $x = round($index * $step, 2);
                $y = round($baseline - ((($value - $min) / $range) * ($height - 24)), 2);

                return compact('x', 'y');
            })
            ->values();

        $path = $this->buildSmoothSparklinePath($points->all());

        $areaPath = $path
            .' L '.$points->last()['x'].' '.$baseline
            .' L '.$points->first()['x'].' '.$baseline
            .' Z';

        return [
            'sparkline_path' => $path,
            'sparkline_area_path' => $areaPath,
            'sparkline_last_x' => $points->last()['x'],
            'sparkline_last_y' => $points->last()['y'],
        ];
    }

    /**
     * @param  array<int, array{x: float, y: float}>  $points
     */
    protected function buildSmoothSparklinePath(array $points): string
    {
        if ($points === []) {
            return '';
        }

        if (count($points) === 1) {
            return 'M'.$points[0]['x'].' '.$points[0]['y'];
        }

        $path = 'M'.$points[0]['x'].' '.$points[0]['y'];

        foreach ($points as $index => $point) {
            if ($index === 0) {
                continue;
            }

            $previous = $points[$index - 1];
            $controlX = round(($previous['x'] + $point['x']) / 2, 2);

            $path .= ' C '.$controlX.' '.$previous['y'].', '.$controlX.' '.$point['y'].', '.$point['x'].' '.$point['y'];
        }

        return $path;
    }
}
