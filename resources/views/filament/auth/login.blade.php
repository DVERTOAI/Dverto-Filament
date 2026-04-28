<x-filament-panels::page.simple>
    <div class="fi-pro-login">
        <section class="fi-pro-login__panel fi-pro-login__panel--form">
            <div class="fi-pro-login__brand">
                <div class="fi-pro-login__eyebrow">
                    <span class="fi-pro-login__eyebrow-dot"></span>
                    Admin workspace
                </div>
            </div>

            <div class="fi-pro-login__copy">
                <h1 class="fi-pro-login__title">
                    Sign in
                </h1>

                <div class="fi-pro-login__subtitle">
                    Continue with your existing account to access the admin panel.
                </div>
            </div>

            <div class="fi-pro-login__form-wrap">
                {{ $this->content }}
            </div>

            <p class="fi-pro-login__footnote">
                Secure access with your current Filament credentials.
            </p>
        </section>

        <aside class="fi-pro-login__panel fi-pro-login__panel--showcase">
            <div class="fi-pro-login__showcase-copy">
                <div class="fi-pro-login__badge">Clean dashboard access</div>
                <h2>Welcome back.</h2>
                <p>
                    A simpler sign-in experience with the same warm brand accents,
                    calmer spacing, and a more polished first impression.
                </p>
            </div>

            <div class="fi-pro-login__showcase-card">
                <span class="fi-pro-login__metric-label">Design direction</span>
                <strong>Minimal, balanced, and easier on the eye.</strong>
                <p>
                    The layout keeps your amber-led theme while reducing the heavy,
                    over-designed feeling from the previous version.
                </p>
            </div>
        </aside>
    </div>
</x-filament-panels::page.simple>
