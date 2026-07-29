@props(['active' => 'overview'])

@php
use App\Filament\Pages\DashboardAnalytics;
use App\Filament\Pages\DashboardOverview;
use App\Filament\Pages\DashboardReports;

$tabs = [
    'overview' => [
        'label' => 'Overview',
        'url' => DashboardOverview::getUrl(),
    ],
    'analytics' => [
        'label' => 'Analytics',
        'url' => DashboardAnalytics::getUrl(),
    ],
    'reports' => [
        'label' => 'Reports',
        'url' => DashboardReports::getUrl(),
    ],
];
@endphp

<nav class="ac-dash-tabs" aria-label="Dashboard sections">
    @foreach ($tabs as $key => $tab)
        <a
            href="{{ $tab['url'] }}"
            @class(['ac-dash-tab', 'ac-dash-tab--active' => $active === $key])
        >
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
