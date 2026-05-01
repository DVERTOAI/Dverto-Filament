@php
    $canToggleTheme = filament()->hasDarkMode() && (! filament()->hasDarkModeForced());
@endphp

<div class="ac-sidebar-footer-shell">
    @if ($canToggleTheme)
        <div
            x-data="{ theme: localStorage.getItem('theme') ?? 'light' }"
            x-on:ac-theme-changed.window="theme = $event.detail.theme"
            class="ac-sidebar-theme-toggle"
        >
            <div class="ac-sidebar-theme-label">
                <span class="ac-sidebar-theme-icon" aria-hidden="true">
                    {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::OutlinedMoon) }}
                </span>
                <span>Dark mode</span>
            </div>

            <button
                type="button"
                x-on:click="window.acDashboardTheme?.toggle()"
                x-bind:aria-pressed="theme === 'dark' ? 'true' : 'false'"
                x-bind:class="{ 'is-active': theme === 'dark' }"
                class="ac-sidebar-theme-switch"
                aria-label="Toggle dark mode"
            >
                <span class="ac-sidebar-theme-switch-thumb"></span>
            </button>
        </div>
    @endif

    <p class="ac-sidebar-footer-note">© 2024 Your Company</p>
</div>
