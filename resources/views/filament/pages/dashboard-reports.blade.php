<x-filament-panels::page class="ac-dashboard-page ac-dash-index-page">
    <div class="ac-dash-index grid grid-cols-1 gap-3">
        @include('filament.pages.partials.dashboard-tabs', ['active' => 'reports'])

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
            <p class="text-sm font-bold text-gray-900 dark:text-white">Reports</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">View and manage all business reports</p>
        </div>

        <!-- Reports List -->
        <div class="space-y-2">
            @foreach($reports as $report)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-500 text-white flex-shrink-0">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-900 dark:text-white">{{ $report['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $report['date'] }}</p>
                    </div>
                </div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                    @if($report['status'] === 'Completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($report['status'] === 'In Progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                    @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                    @endif">
                    {{ $report['status'] }}
                </span>
            </div>
            @endforeach
        </div>

        <!-- Generate Report -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900 rounded-lg p-3 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-900 dark:text-blue-200">Generate New Report</p>
                <p class="text-xs text-blue-700 dark:text-blue-300 mt-0.5">Create custom reports based on your parameters</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-1.5 px-4 rounded-lg transition">Generate</button>
        </div>

        <!-- Templates -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-white mb-2">Report Templates</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach(['Sales', 'Customer', 'Revenue'] as $template)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:border-blue-500 cursor-pointer transition">
                    <p class="text-xs font-semibold text-gray-900 dark:text-white">{{ $template }} Report</p>
                    <p class="text-xs text-gray-500 mt-1 mb-2">Track {{ strtolower($template) }} metrics and performance</p>
                    <button class="text-blue-600 hover:text-blue-700 text-xs font-semibold">Use Template →</button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
