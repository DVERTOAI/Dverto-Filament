<x-filament-widgets::widget class="ac-users-index-stats-widget">
    <section class="ac-users-index-stats" aria-label="User statistics">
        @foreach ($stats as $stat)
        <article class="ac-users-stat ac-users-stat--{{ $stat['tone'] }}">
            <span class="ac-users-stat-icon" aria-hidden="true">
                {{ \Filament\Support\generate_icon_html($stat['icon']) }}
            </span>
            <div class="ac-users-stat-copy">
                <p class="ac-users-stat-label">{{ $stat['label'] }}</p>
                <p class="ac-users-stat-value">{{ $stat['value'] }}</p>
                <p class="ac-users-stat-hint">{{ $stat['hint'] }}</p>
            </div>
        </article>
        @endforeach
    </section>
</x-filament-widgets::widget>
