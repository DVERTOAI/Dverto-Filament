<?php

namespace App\Providers;

use App\Filament\Resources\Pages\ListAdminRecords;
use App\Listeners\ClearLatestLoginSession;
use App\Listeners\StoreLatestLoginSession;
use App\Models\User;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);

        Gate::before(function (User $user, string $ability): ?bool {
            return $user->isSuperAdmin() ? true : null;
        });

        Filament::serving(function (): void {
            $user = auth()->user();

            if ($user instanceof User) {
                $user->loadMissing('roles');
            }
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): View => view('components.filament.topbar-welcome'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn (): View => view('components.filament.topbar-utilities'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_LOGO_AFTER,
            fn (): View => view('components.filament.sidebar-rail-toggle'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn (): View => view('components.filament.sidebar-footer'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn (): View => view('components.filament.sidebar-accordion'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_START,
            fn (): View => view('components.filament.table-loading'),
            scopes: ListAdminRecords::class,
        );
    }
}
