<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Tab bar --}}
        <div class="flex items-center gap-2 -mt-2 mb-4 flex-wrap">
            <span class="px-3 py-1.5 rounded-lg font-medium text-xs bg-primary-600 text-white shadow-sm">
                Audit Log
            </span>
            <a href="{{ \MrAdder\FilamentLogger\Resources\ActivityResource::getUrl() }}"
               class="px-3 py-1.5 rounded-lg font-medium text-xs bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/20 transition">
                Full Activity Log
            </a>
        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
            $cards = [
                ['label' => 'Total Events',    'icon' => 'heroicon-o-clipboard-document-list', 'bg' => 'bg-blue-100 dark:bg-blue-900/40',   'color' => 'text-blue-600 dark:text-blue-400',   'value' => \Spatie\Activitylog\Models\Activity::count()],
                ['label' => 'Today',           'icon' => 'heroicon-o-calendar-days',           'bg' => 'bg-green-100 dark:bg-green-900/40',  'color' => 'text-green-600 dark:text-green-400', 'value' => \Spatie\Activitylog\Models\Activity::whereDate('created_at', today())->count()],
                ['label' => 'This Week',       'icon' => 'heroicon-o-chart-bar',               'bg' => 'bg-violet-100 dark:bg-violet-900/40','color' => 'text-violet-600 dark:text-violet-400','value' => \Spatie\Activitylog\Models\Activity::where('created_at', '>=', now()->startOfWeek())->count()],
                ['label' => 'High Risk',       'icon' => 'heroicon-o-shield-exclamation',      'bg' => 'bg-red-100 dark:bg-red-900/40',      'color' => 'text-red-600 dark:text-red-400',     'value' => \Spatie\Activitylog\Models\Activity::where('properties->risk', 'high')->count()],
            ];
            @endphp

            @foreach ($cards as $card)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
                <div class="w-9 h-9 rounded-xl {{ $card['bg'] }} flex items-center justify-center mb-3">
                    @svg($card['icon'], 'w-5 h-5 ' . $card['color'])
                </div>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white leading-none">{{ number_format($card['value']) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $card['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Recent activity --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Recent Activity</h3>
                <a href="{{ \MrAdder\FilamentLogger\Resources\ActivityResource::getUrl() }}"
                   class="text-xs text-primary-600 hover:underline">View all →</a>
            </div>

            @php
            $activities = \Spatie\Activitylog\Models\Activity::latest()->limit(10)->get();
            @endphp

            @if ($activities->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">No activity recorded yet.</p>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($activities as $activity)
                    <div class="flex items-start gap-3 py-3 first:pt-0 last:pb-0">
                        <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                            @svg('heroicon-o-bolt', 'w-3.5 h-3.5 text-gray-500 dark:text-gray-400')
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-800 dark:text-gray-200 truncate">{{ $activity->description }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                {{ $activity->causer?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if ($activity->event)
                        <span class="text-[11px] px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex-shrink-0">
                            {{ $activity->event }}
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
