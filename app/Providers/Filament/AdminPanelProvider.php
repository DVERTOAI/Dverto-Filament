<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Pages\Customers;
use App\Filament\Pages\CustomersList;
use App\Filament\Pages\CustomersSegments;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Reports;
use App\Filament\Pages\ReportsDaily;
use App\Filament\Pages\ReportsMonthly;
use App\Filament\Pages\Settings;
use App\Filament\Pages\SettingsGeneral;
use App\Filament\Pages\SettingsNotifications;
use App\Filament\Resources\PermissionResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use App\Http\Middleware\EnsureSingleSession;
use App\Support\AdminPermissions;
use Filament\Enums\GlobalSearchPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('Laravel')
            ->brandLogo(fn (): HtmlString => new HtmlString(<<<'HTML'
                <span class="ac-brand">
                    <span class="ac-brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 3.5L26.8253 9.75V22.25L16 28.5L5.17468 22.25V9.75L16 3.5Z" stroke="currentColor" stroke-width="2.75" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="ac-brand-text">Laravel</span>
                </span>
            HTML))
            ->brandLogoHeight('2.25rem')
            ->sidebarWidth('20rem')
            ->sidebarCollapsibleOnDesktop()
            ->darkMode()
            ->globalSearch(position: GlobalSearchPosition::Topbar)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldKeyBindingSuffix()
            ->login(Login::class)
            ->maxContentWidth(Width::Full)
            ->colors([
                'primary' => Color::Violet,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $user = Auth::user();

                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon(Heroicon::OutlinedHome)
                                ->isActiveWhen(fn (): bool => request()->routeIs(Dashboard::getRouteName()))
                                ->sort(-2)
                                ->url(Dashboard::getUrl()),
                        ]),
                    NavigationGroup::make('Reports')
                        ->icon(Heroicon::OutlinedChartBarSquare)
                        ->collapsible()
                        ->items([
                            NavigationItem::make('Overview')
                                ->icon(Heroicon::OutlinedChartBarSquare)
                                ->isActiveWhen(fn (): bool => request()->routeIs(Reports::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::VIEW_REPORTS) ?? false)
                                ->url(Reports::getUrl()),
                            NavigationItem::make('Daily')
                                ->icon(Heroicon::OutlinedCalendarDays)
                                ->isActiveWhen(fn (): bool => request()->routeIs(ReportsDaily::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::VIEW_REPORTS) ?? false)
                                ->url(ReportsDaily::getUrl()),
                            NavigationItem::make('Monthly')
                                ->icon(Heroicon::OutlinedPresentationChartLine)
                                ->isActiveWhen(fn (): bool => request()->routeIs(ReportsMonthly::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::VIEW_REPORTS) ?? false)
                                ->url(ReportsMonthly::getUrl()),
                        ]),
                    NavigationGroup::make('Customers')
                        ->icon(Heroicon::OutlinedUsers)
                        ->collapsible()
                        ->items([
                            NavigationItem::make('Overview')
                                ->icon(Heroicon::OutlinedUsers)
                                ->isActiveWhen(fn (): bool => request()->routeIs(Customers::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::VIEW_CUSTOMERS) ?? false)
                                ->url(Customers::getUrl()),
                            NavigationItem::make('Customer List')
                                ->icon(Heroicon::OutlinedListBullet)
                                ->isActiveWhen(fn (): bool => request()->routeIs(CustomersList::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::VIEW_CUSTOMERS) ?? false)
                                ->url(CustomersList::getUrl()),
                            NavigationItem::make('Segments')
                                ->icon(Heroicon::OutlinedSquares2x2)
                                ->isActiveWhen(fn (): bool => request()->routeIs(CustomersSegments::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::MANAGE_CUSTOMERS) ?? false)
                                ->url(CustomersSegments::getUrl()),
                        ]),
                    NavigationGroup::make('Settings')
                        ->icon(Heroicon::OutlinedCog6Tooth)
                        ->collapsible()
                        ->items([
                            NavigationItem::make('Overview')
                                ->icon(Heroicon::OutlinedCog6Tooth)
                                ->isActiveWhen(fn (): bool => request()->routeIs(Settings::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::MANAGE_SETTINGS) ?? false)
                                ->url(Settings::getUrl()),
                            NavigationItem::make('General')
                                ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                                ->isActiveWhen(fn (): bool => request()->routeIs(SettingsGeneral::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::MANAGE_SETTINGS) ?? false)
                                ->url(SettingsGeneral::getUrl()),
                            NavigationItem::make('Notifications')
                                ->icon(Heroicon::OutlinedBell)
                                ->isActiveWhen(fn (): bool => request()->routeIs(SettingsNotifications::getRouteName()))
                                ->visible(fn (): bool => $user?->can(AdminPermissions::MANAGE_SETTINGS) ?? false)
                                ->url(SettingsNotifications::getUrl()),
                        ]),
                    NavigationGroup::make('Administration')
                        ->icon(Heroicon::OutlinedShieldCheck)
                        ->collapsible()
                        ->items([
                            NavigationItem::make(UserResource::getNavigationLabel())
                                ->icon(UserResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(UserResource::getRouteBaseName().'.*'))
                                ->visible(fn (): bool => UserResource::canAccess())
                                ->url(UserResource::getUrl()),
                            NavigationItem::make(RoleResource::getNavigationLabel())
                                ->icon(RoleResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(RoleResource::getRouteBaseName().'.*'))
                                ->visible(fn (): bool => RoleResource::canAccess())
                                ->url(RoleResource::getUrl()),
                            NavigationItem::make(PermissionResource::getNavigationLabel())
                                ->icon(PermissionResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(PermissionResource::getRouteBaseName().'.*'))
                                ->visible(fn (): bool => PermissionResource::canAccess())
                                ->url(PermissionResource::getUrl()),
                        ]),
                ]);
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                EnsureSingleSession::class,
                Authenticate::class,
            ]);
    }
}
