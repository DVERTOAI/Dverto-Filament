<div class="fi-wi-stats-overview-card relative rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-gray-900">
    <div class="flex flex-col gap-2">
        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-0.5 rounded-full w-fit dark:bg-yellow-900 dark:text-yellow-100">
            {{ $year }}
        </span>
        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $revenue }}</div>
        <div class="flex items-center gap-1">
            @if ($status === 'positive')
                <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs font-semibold text-green-600">{{ $growth }}</span>
            @else
                <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs font-semibold text-red-600">{{ $growth }}</span>
            @endif
        </div>
        <button class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition">
            Buy Now
        </button>
    </div>
</div>
