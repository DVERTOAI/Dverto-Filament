<x-filament-widgets::widget class="ac-overview-widget">
    <section class="ac-overview-panel">
        <header class="ac-overview-header">
            <div class="ac-overview-header-copy">
                <h2 class="ac-overview-title">Access Control</h2>
                <p class="ac-overview-subtitle">Manage users, roles, and permissions from one place.</p>
            </div>
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
                </a>
            @endforeach
        </div>
    </section>
</x-filament-widgets::widget>
