<div class="fi-wi-stats-overview-card relative rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-gray-900">
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $label }}</span>
            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                <path d="M16 16a2 2 0 11-4 0 2 2 0 014 0zm-6 0a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $amount }}</div>
        <div class="text-xs text-green-600 font-semibold">{{ $growth }}</div>
    </div>
</div>
