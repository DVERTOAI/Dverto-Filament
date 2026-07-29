<x-filament-panels::page.simple>
    <div class="fi-pro-login">

        <section class="fi-pro-login__panel fi-pro-login__panel--form">
            <div class="fi-pro-login__brand">
                <span class="fi-pro-login__brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 3.5L26.8253 9.75V22.25L16 28.5L5.17468 22.25V9.75L16 3.5Z" stroke="currentColor" stroke-width="2.75" stroke-linejoin="round" />
                    </svg>
                </span>
                <span class="fi-pro-login__brand-name">Dverto</span>
            </div>

            <div class="fi-pro-login__copy">
                <span class="fi-pro-login__eyebrow">
                    <span class="fi-pro-login__eyebrow-dot"></span>
                    Secure admin portal
                </span>

                <h1 class="fi-pro-login__title">Sign in to your workspace</h1>

                <p class="fi-pro-login__subtitle">
                    Access hospital operations, user management, and administration tools.
                </p>

                @if (app()->environment('local'))
                    <p class="fi-pro-login__subtitle" style="margin-top: .5rem;">
                        Demo login: <strong>admin@example.com</strong> / <strong>password</strong>
                    </p>
                @endif

                <div class="fi-pro-login__form-wrap">
                    {{ $this->content }}
                </div>
            </div>

            <p class="fi-pro-login__footnote">
                Protected access for authorized staff only.
            </p>
        </section>

        <aside class="fi-pro-login__panel fi-pro-login__panel--showcase" aria-hidden="true">
            <svg class="fi-pro-login__rp-pattern" viewBox="0 0 500 580" preserveAspectRatio="xMidYMid slice">
                <defs>
                    <pattern id="login-grid" width="32" height="32" patternUnits="userSpaceOnUse">
                        <path d="M 32 0 L 0 0 0 32" fill="none" stroke="#fff" stroke-width="0.45" opacity="0.35"/>
                    </pattern>
                </defs>
                <rect width="500" height="580" fill="url(#login-grid)"/>
            </svg>

            <div class="fi-pro-login__showcase-top">
                <span class="fi-pro-login__badge">
                    <span class="fi-pro-login__badge-dot"></span>
                    Dverto Hospital System
                </span>

                <h2>Manage operations with <em>clarity.</em></h2>

                <p>
                    Real-time visibility into admissions, departments, schedules,
                    and hospital performance — all in one secure platform.
                </p>
            </div>

            <div class="fi-pro-login__stat-grid">
                <div class="fi-pro-login__showcase-card">
                    <span class="fi-pro-login__stat-label">Active patients</span>
                    <strong>248</strong>
                    <p class="fi-pro-login__stat-trend">↑ 12 admitted today</p>
                </div>

                <div class="fi-pro-login__showcase-card">
                    <span class="fi-pro-login__stat-label">Departments online</span>
                    <strong>14</strong>
                    <p class="fi-pro-login__stat-trend">All reporting on time</p>
                </div>

                <div class="fi-pro-login__showcase-card">
                    <span class="fi-pro-login__stat-label">ICU occupancy</span>
                    <strong>87%</strong>
                    <p>7 critical cases</p>
                </div>

                <div class="fi-pro-login__showcase-card">
                    <span class="fi-pro-login__stat-label">Staff on duty</span>
                    <strong>156</strong>
                    <p>Across all wards</p>
                </div>
            </div>

            <div class="fi-pro-login__rp-footer">
                <span class="fi-pro-login__rp-avatar">DR</span>
                <div>
                    <p class="fi-pro-login__rp-quote">"A system that keeps up with hospital pace."</p>
                    <p class="fi-pro-login__rp-name">Dr. R. Sharma · Chief Administrator</p>
                </div>
            </div>
        </aside>

    </div>
</x-filament-panels::page.simple>
