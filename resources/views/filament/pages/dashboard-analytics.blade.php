<x-filament-panels::page class="ac-dashboard-page ac-dash-index-page">
    <div class="ac-dash-index grid grid-cols-1 gap-3">
        @include('filament.pages.partials.dashboard-tabs', ['active' => 'analytics'])

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
            <p class="text-sm font-bold text-gray-900 dark:text-white">Analytics Overview</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Track your business metrics and performance indicators</p>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Revenue</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $totalRevenue }}</p>
                <p class="text-xs text-green-600 font-semibold mt-1">↑ +12.5% vs last month</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Orders</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $totalOrders }}</p>
                <p class="text-xs text-green-600 font-semibold mt-1">↑ +8.2% vs last week</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Customers</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $totalCustomers }}</p>
                <p class="text-xs text-green-600 font-semibold mt-1">↑ +3.1% vs last month</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">Conversion Rate</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $conversionRate }}</p>
                <p class="text-xs text-red-600 font-semibold mt-1">↓ -0.12% vs last month</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
                <p class="text-xs font-semibold text-gray-700 dark:text-white mb-2">Sales Trend</p>
                <div class="h-40 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                    <p class="text-xs text-gray-500">Line Chart - Sales Over Time</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
                <p class="text-xs font-semibold text-gray-700 dark:text-white mb-2">Revenue by Category</p>
                <div class="h-40 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                    <p class="text-xs text-gray-500">Pie Chart - Revenue Distribution</p>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-white mb-2">Detailed Analytics</p>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-2 px-3 font-semibold text-gray-700 dark:text-white">Metric</th>
                            <th class="text-left py-2 px-3 font-semibold text-gray-700 dark:text-white">Today</th>
                            <th class="text-left py-2 px-3 font-semibold text-gray-700 dark:text-white">This Week</th>
                            <th class="text-left py-2 px-3 font-semibold text-gray-700 dark:text-white">This Month</th>
                            <th class="text-left py-2 px-3 font-semibold text-gray-700 dark:text-white">Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['Page Views',   '2,458', '14,862', '58,742', '+12.5%', true],
                            ['Users Online', '542',   '3,428',  '12,546', '+8.2%',  true],
                            ['Bounce Rate',  '32.5%', '35.8%',  '38.2%',  '-3.2%',  false],
                            ['Avg. Session', '3m 28s','4m 12s', '5m 45s', '+15.3%', true],
                        ] as [$metric, $today, $week, $month, $change, $up])
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-2 px-3 text-gray-900 dark:text-white">{{ $metric }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $today }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $week }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $month }}</td>
                            <td class="py-2 px-3 font-semibold {{ $up ? 'text-green-600' : 'text-red-600' }}">{{ $change }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
