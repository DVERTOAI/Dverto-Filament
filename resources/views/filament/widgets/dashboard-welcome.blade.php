<x-filament-widgets::widget class="ac-dashboard-welcome-widget">
    <section class="ac-dashboard-hero">
        <div class="ac-dashboard-hero-main">
            <div class="ac-dashboard-avatar">{{ $initials }}</div>

            <div class="ac-dashboard-hero-copy">
                <p class="ac-dashboard-hero-eyebrow">Welcome back,</p>

                <div class="ac-dashboard-hero-heading-row">
                    <h2 class="ac-dashboard-hero-heading">{{ $user?->name ?? 'Admin User' }}</h2>
                    <span class="ac-dashboard-hero-badge">{{ $role }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ filament()->getLogoutUrl() }}" class="ac-dashboard-logout-form">
            @csrf

            <button type="submit" class="ac-dashboard-logout-button">
                <span class="ac-dashboard-logout-button-icon" aria-hidden="true">
                    {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::OutlinedArrowLeftOnRectangle) }}
                </span>
                <span>Sign out</span>
            </button>
        </form>

        <div class="ac-dashboard-hero-art" aria-hidden="true">
            <span class="ac-dashboard-hero-art-panel"></span>
            <span class="ac-dashboard-hero-art-strip ac-dashboard-hero-art-strip--one"></span>
            <span class="ac-dashboard-hero-art-strip ac-dashboard-hero-art-strip--two"></span>
            <span class="ac-dashboard-hero-art-strip ac-dashboard-hero-art-strip--three"></span>
        </div>
    </section>
</x-filament-widgets::widget>
