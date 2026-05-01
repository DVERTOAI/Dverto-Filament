<x-filament-widgets::widget class="ac-dashboard-stats-widget">
    <section class="ac-dashboard-stats">
        @foreach ($items as $item)
            <article class="ac-dashboard-stat ac-dashboard-stat--{{ $item['tone'] }}">
                <span class="ac-dashboard-stat-icon" aria-hidden="true">
                    {{ \Filament\Support\generate_icon_html($item['icon']) }}
                </span>

                <div class="ac-dashboard-stat-copy">
                    <p class="ac-dashboard-stat-label">{{ $item['label'] }}</p>

                    <div class="ac-dashboard-stat-value-row">
                        <span class="ac-dashboard-stat-value">{{ $item['value'] }}</span>
                        <span class="ac-dashboard-stat-trend">{{ $item['trend'] }}</span>
                    </div>

                    @if (filled($item['context']))
                        <p class="ac-dashboard-stat-context">{{ $item['context'] }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </section>
</x-filament-widgets::widget>
