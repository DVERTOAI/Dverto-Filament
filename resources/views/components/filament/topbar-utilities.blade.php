@php
    $canToggleTheme = filament()->hasDarkMode() && (! filament()->hasDarkModeForced());
@endphp

<div class="ac-topbar-utilities">
    <button type="button" class="ac-topbar-utility-btn ac-topbar-utility-btn--notifications" aria-label="Notifications">
        <span aria-hidden="true">
            {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::OutlinedBell) }}
        </span>
        <span class="ac-topbar-utility-badge">3</span>
    </button>

    @if ($canToggleTheme)
        <button
            type="button"
            x-data="{ theme: localStorage.getItem('theme') ?? 'light' }"
            x-on:ac-theme-changed.window="theme = $event.detail.theme"
            x-on:click="window.acDashboardTheme?.toggle()"
            x-bind:aria-pressed="theme === 'dark' ? 'true' : 'false'"
            class="ac-topbar-utility-btn"
            aria-label="Toggle dark mode"
        >
            <span aria-hidden="true">
                {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::OutlinedMoon) }}
            </span>
        </button>
    @endif
</div>
