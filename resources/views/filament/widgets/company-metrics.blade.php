<div class="fi-wi-stats-overview-card relative rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-gray-900">
    <div class="flex flex-col gap-2">
        <div class="text-xs font-semibold text-gray-900 dark:text-white">Company Growth</div>
        <div class="space-y-1">
            @foreach ($metrics as $metric)
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $metric['year'] }}</span>
                    <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $metric['value'] }}</span>
                </div>
            @endforeach
        </div>
        <div class="pt-1 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $companyGrowth }}</p>
        </div>
    </div>
</div>
