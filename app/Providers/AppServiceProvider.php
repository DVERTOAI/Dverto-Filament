<?php

namespace App\Providers;

use App\Listeners\ClearLatestLoginSession;
use App\Listeners\StoreLatestLoginSession;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, StoreLatestLoginSession::class);
        Event::listen(Logout::class, ClearLatestLoginSession::class);
        Event::listen(CurrentDeviceLogout::class, ClearLatestLoginSession::class);

        Gate::before(function ($user, string $ability): ?bool {
            if ($user->hasRole('admin')) {
                return true;
            }

            return null;
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_START,
            fn (): View => view('components.filament.sidebar-toggle'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn (): View => view('components.filament.topbar-utilities'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn (): View => view('components.filament.sidebar-footer'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn (): View => view('components.filament.sidebar-accordion'),
        );
    }
}
