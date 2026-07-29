<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class DashboardOverview extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Overview';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.dashboard-overview';

    public function getTitle(): string|Htmlable
    {
        return new HtmlString('Dashboard');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getViewData(): array
    {
        return [
            'alerts'      => $this->getAlerts(),
            'kpis'        => $this->getKpis(),
            'departments' => $this->getDepartmentLoad(),
            'schedule'    => $this->getTodaySchedule(),
            'vitals'      => $this->getIcuVitals(),
            'admissions'  => $this->getRecentAdmissions(),
            'caseTypes'   => $this->getCaseTypes(),
            'revenue'     => $this->getRevenueData(),
        ];
    }

    private function getAlerts(): array
    {
        return [
            [
                'title'    => '7 Critical ICU cases',
                'subtitle' => 'Immediate attention required',
                'icon'     => 'heroicon-o-exclamation-triangle',
                'tone'     => 'danger',
                'wrap'     => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
                'iconWrap' => 'bg-red-100 dark:bg-red-900/40',
                'iconColor'=> 'text-red-600 dark:text-red-400',
                'titleColor' => 'text-red-700 dark:text-red-300',
                'subColor' => 'text-red-500 dark:text-red-400',
            ],
            [
                'title'    => '12 appointments today',
                'subtitle' => 'Next: Raj Kumar at 08:30',
                'icon'     => 'heroicon-o-clock',
                'tone'     => 'warn',
                'wrap'     => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
                'iconWrap' => 'bg-amber-100 dark:bg-amber-900/40',
                'iconColor'=> 'text-amber-600 dark:text-amber-400',
                'titleColor' => 'text-amber-700 dark:text-amber-300',
                'subColor' => 'text-amber-500 dark:text-amber-400',
            ],
            [
                'title'    => 'Revenue up 8.4% this month',
                'subtitle' => '$2.6M collected so far',
                'icon'     => 'heroicon-o-arrow-trending-up',
                'tone'     => 'success',
                'wrap'     => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800',
                'iconWrap' => 'bg-green-100 dark:bg-green-900/40',
                'iconColor'=> 'text-green-600 dark:text-green-400',
                'titleColor' => 'text-green-700 dark:text-green-300',
                'subColor' => 'text-green-500 dark:text-green-400',
            ],
        ];
    }

    private function getKpis(): array
    {
        $styles = [
            'blue'   => ['iconBg' => 'bg-blue-100 dark:bg-blue-900/40',   'iconColor' => 'text-blue-600 dark:text-blue-400',   'badgeClass' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'],
            'teal'   => ['iconBg' => 'bg-teal-100 dark:bg-teal-900/40',   'iconColor' => 'text-teal-600 dark:text-teal-400',   'badgeClass' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'],
            'orange' => ['iconBg' => 'bg-orange-100 dark:bg-orange-900/40','iconColor' => 'text-orange-600 dark:text-orange-400','badgeClass' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'],
            'red'    => ['iconBg' => 'bg-red-100 dark:bg-red-900/40',     'iconColor' => 'text-red-600 dark:text-red-400',     'badgeClass' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'],
        ];

        $rows = [
            ['label' => 'Total patients this month', 'value' => '1,284', 'suffix' => '',      'badge' => '+12%',     'color' => 'blue',   'tone' => 'blue',   'icon' => 'heroicon-o-users'],
            ['label' => 'Beds occupied',             'value' => '376',   'suffix' => '/400',  'badge' => '94%',      'color' => 'teal',   'tone' => 'green',  'icon' => 'heroicon-o-home'],
            ['label' => 'Revenue this month',        'value' => '$2.6M', 'suffix' => '',      'badge' => '+8.4%',    'color' => 'orange', 'tone' => 'orange', 'icon' => 'heroicon-o-currency-dollar'],
            ['label' => 'Critical cases (ICU)',      'value' => '7',     'suffix' => '',      'badge' => '7 active', 'color' => 'red',    'tone' => 'violet', 'icon' => 'heroicon-o-heart'],
        ];

        return array_map(function (array $row) use ($styles): array {
            return array_merge($row, $styles[$row['color']]);
        }, $rows);
    }

    private function getDepartmentLoad(): array
    {
        $rows = [
            ['name' => 'Cardiology',  'pct' => 88, 'color' => '#3b82f6'],
            ['name' => 'Orthopedics', 'pct' => 72, 'color' => '#10b981'],
            ['name' => 'Pediatrics',  'pct' => 65, 'color' => '#f59e0b'],
            ['name' => 'Emergency',   'pct' => 97, 'color' => '#ef4444'],
            ['name' => 'Neurology',   'pct' => 54, 'color' => '#8b5cf6'],
            ['name' => 'Oncology',    'pct' => 81, 'color' => '#06b6d4'],
        ];

        return array_map(function (array $row): array {
            $row['pctClass'] = $this->deptPctClass($row['pct']);

            return $row;
        }, $rows);
    }

    private function getTodaySchedule(): array
    {
        $tagColors = [
            'Check-up'  => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
            'Surgery'   => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
            'Consult'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
            'Urgent'    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
            'Follow-up' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
        ];

        $avatarColors = [
            'blue'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
            'red'    => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
            'green'  => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
            'amber'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
            'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
        ];

        $rows = [
            ['time' => '08:30', 'initials' => 'RK', 'name' => 'Raj Kumar',   'type' => 'Check-up',  'color' => 'blue'],
            ['time' => '09:00', 'initials' => 'PM', 'name' => 'Priya Mehta', 'type' => 'Surgery',   'color' => 'red'],
            ['time' => '10:15', 'initials' => 'AS', 'name' => 'Arjun Singh', 'type' => 'Consult',   'color' => 'green'],
            ['time' => '11:30', 'initials' => 'DV', 'name' => 'Divya Verma', 'type' => 'Urgent',    'color' => 'amber'],
        ];

        return array_map(function (array $row) use ($tagColors, $avatarColors): array {
            $row['avatarBg'] = $avatarColors[$row['color']];
            $row['tagBg']    = $tagColors[$row['type']];

            return $row;
        }, $rows);
    }

    private function getIcuVitals(): array
    {
        $statusClasses = [
            'stable' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
            'normal' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
            'good'   => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
        ];

        $rows = [
            ['label' => 'Heart rate',     'value' => '78',     'unit' => 'bpm',        'status' => 'stable', 'icon' => 'heroicon-o-heart'],
            ['label' => 'Blood pressure', 'value' => '118/76', 'unit' => 'mmHg',       'status' => 'normal', 'icon' => 'heroicon-o-chart-bar'],
            ['label' => 'SpO₂',           'value' => '97%',    'unit' => 'oxygen sat', 'status' => 'good',   'icon' => 'heroicon-o-arrow-trending-up'],
            ['label' => 'Temperature',    'value' => '37.1°',  'unit' => 'celsius',    'status' => 'normal', 'icon' => 'heroicon-o-sun'],
        ];

        return array_map(function (array $row) use ($statusClasses): array {
            $row['statusClass'] = $statusClasses[$row['status']];

            return $row;
        }, $rows);
    }

    private function getRecentAdmissions(): array
    {
        $avatarColors = [
            'blue'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
            'green'  => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
            'amber'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
            'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
            'red'    => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
        ];

        $statusClasses = [
            'Critical' => 'text-red-500',
            'Moderate' => 'text-amber-500',
            'Stable'   => 'text-green-600 dark:text-green-400',
        ];

        $rows = [
            ['initials' => 'SK', 'name' => 'Sanjay Kumar', 'ward' => 'Cardiology',  'admitted' => 'Today 06:12', 'condition' => 'Chest pain',     'status' => 'Critical', 'color' => 'blue'],
            ['initials' => 'AM', 'name' => 'Asha Mishra',  'ward' => 'Pediatrics',  'admitted' => 'Yesterday',   'condition' => 'High fever',     'status' => 'Moderate', 'color' => 'green'],
            ['initials' => 'VR', 'name' => 'Vijay Rao',    'ward' => 'Orthopedics', 'admitted' => '14 May',      'condition' => 'Fracture R-leg', 'status' => 'Stable',   'color' => 'amber'],
            ['initials' => 'RT', 'name' => 'Rohan Tiwari', 'ward' => 'ICU',         'admitted' => '13 May',      'condition' => 'Post-op care',   'status' => 'Critical', 'color' => 'red'],
        ];

        return array_map(function (array $row) use ($avatarColors, $statusClasses): array {
            $row['avBg']    = $avatarColors[$row['color']];
            $row['stClass'] = $statusClasses[$row['status']];

            return $row;
        }, $rows);
    }

    private function getCaseTypes(): array
    {
        return [
            ['label' => 'Outpatient', 'pct' => 42, 'color' => '#3b82f6'],
            ['label' => 'Inpatient',  'pct' => 28, 'color' => '#10b981'],
            ['label' => 'Emergency',  'pct' => 18, 'color' => '#ef4444'],
            ['label' => 'Surgery',    'pct' => 12, 'color' => '#f59e0b'],
        ];
    }

    private function getRevenueData(): array
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            'values' => [1.8, 2.0, 2.2, 2.4, 2.6],
            'period' => 'Jan – May 2026',
        ];
    }

    private function deptPctClass(int $pct): string
    {
        if ($pct >= 90) {
            return 'ac-dash-pct--critical';
        }

        if ($pct >= 75) {
            return 'ac-dash-pct--warn';
        }

        return 'ac-dash-pct--normal';
    }
}
