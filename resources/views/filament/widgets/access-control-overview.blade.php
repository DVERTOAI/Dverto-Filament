<x-filament-widgets::widget class="ac-overview-widget">
    <section class="ac-overview-panel">
        <header class="ac-overview-header">
            <div class="ac-overview-header-copy">
                <h2 class="ac-overview-title">
                    <span class="ac-overview-title-dot" aria-hidden="true"></span>
                    <span>Access Control</span>
                </h2>
                <p class="ac-overview-subtitle">Manage users, roles, and permissions from one place.</p>
            </div>

            <a href="{{ $sectionUrl }}" class="ac-overview-view-all">
                <span>View all</span>
                <span class="ac-overview-view-all-icon" aria-hidden="true">
                    {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::OutlinedArrowRight) }}
                </span>
            </a>
        </header>

        <div class="ac-overview-grid">
            @foreach ($items as $item)
                <a
                    href="{{ $item['url'] }}"
                    class="ac-overview-card ac-overview-card--{{ $item['tone'] }}"
                >
                    <div class="ac-overview-card-top">
                        <span class="ac-overview-card-icon">
                            {{ \Filament\Support\generate_icon_html($item['icon']) }}
                        </span>

                        <span class="ac-overview-card-arrow">
                            {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::OutlinedArrowTopRightOnSquare) }}
                        </span>
                    </div>

                    <div class="ac-overview-card-count">
                        {{ $item['count'] }}
                    </div>

                    <div class="ac-overview-card-meta">
                        <h3 class="ac-overview-card-title">{{ $item['label'] }}</h3>
                        <p class="ac-overview-card-description">{{ $item['description'] }}</p>
                    </div>

                    <div class="ac-overview-card-chart" aria-hidden="true">
                        <svg viewBox="0 0 280 82" preserveAspectRatio="none" class="ac-overview-card-chart-svg">
                            <path d="{{ $item['sparkline_area_path'] }}" class="ac-overview-card-chart-area"></path>
                            <path d="{{ $item['sparkline_path'] }}" class="ac-overview-card-chart-line"></path>
                            <circle cx="{{ $item['sparkline_last_x'] }}" cy="{{ $item['sparkline_last_y'] }}" r="4.5" class="ac-overview-card-chart-dot"></circle>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</x-filament-widgets::widget>
