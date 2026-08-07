<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Pages\Customers;
use App\Filament\Pages\CustomersList;
use App\Filament\Pages\CustomersSegments;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\DashboardAnalytics;
use App\Filament\Pages\DashboardOverview;
use App\Filament\Pages\DashboardReports;
use App\Filament\Pages\Reports;
use App\Filament\Pages\ReportsDaily;
use App\Filament\Pages\ReportsMonthly;
use App\Filament\Pages\Settings;
use App\Filament\Pages\SettingsGeneral;
use App\Filament\Pages\SettingsNotifications;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\DoctorResource;
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
use LaraZeus\Sky\Filament\Resources\FaqResource;
use LaraZeus\Sky\Filament\Resources\LibraryResource;
use LaraZeus\Sky\Filament\Resources\NavigationResource;
use LaraZeus\Sky\Filament\Resources\PageResource;
use LaraZeus\Sky\Filament\Resources\PostResource;
use LaraZeus\Sky\Filament\Resources\TagResource;
use LaraZeus\Sky\SkyPlugin;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('Sneat')
            ->brandLogo(fn (): HtmlString => new HtmlString(<<<'HTML'
                <span class="ac-brand">
                    <span class="ac-brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2.5" y="2.5" width="27" height="27" rx="8" fill="currentColor" opacity="0.12"/>
                            <path d="M9.5 11.2h7.6c2.35 0 3.9 1.35 3.9 3.35 0 1.55-.9 2.7-2.35 3.15L22.5 21.5h-2.55l-3.55-3.55H11.9V21.5H9.5V11.2Zm2.4 1.85v3.05h5.05c1.2 0 1.9-.6 1.9-1.55s-.7-1.5-1.9-1.5H11.9Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="ac-brand-text">PSRI Finance</span>
                </span>
            HTML))
            ->brandLogoHeight('2rem')
            ->sidebarWidth('16.25rem')
            ->collapsedSidebarWidth('4.375rem')
            ->sidebarCollapsibleOnDesktop()
            ->darkMode()
            ->spa()
            ->globalSearch(position: GlobalSearchPosition::Topbar)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->login(Login::class)
            ->maxContentWidth(Width::Full)
            ->colors([
                'primary' => Color::hex('#2d7ef7'),
            ])
            ->plugins([
                SpatieTranslatablePlugin::make()->defaultLocales([config('app.locale')]),
                SkyPlugin::make(),
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $user = Auth::user();

                return $builder->groups([
                    NavigationGroup::make()
                        ->collapsible(false)
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon(Heroicon::OutlinedHome)
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    Dashboard::getRouteName(),
                                    DashboardOverview::getRouteName(),
                                    DashboardAnalytics::getRouteName(),
                                    DashboardReports::getRouteName(),
                                ]))
                                ->sort(-2)
                                ->url(DashboardOverview::getUrl()),
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
                    NavigationGroup::make('Sky')
                        ->icon(Heroicon::OutlinedNewspaper)
                        ->collapsible()
                        ->items([
                            NavigationItem::make(PostResource::getNavigationLabel())
                                ->icon(PostResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(PostResource::getRouteBaseName().'.*'))
                                ->url(PostResource::getUrl()),
                            NavigationItem::make(PageResource::getNavigationLabel())
                                ->icon(PageResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(PageResource::getRouteBaseName().'.*'))
                                ->url(PageResource::getUrl()),
                            NavigationItem::make(FaqResource::getNavigationLabel())
                                ->icon(FaqResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(FaqResource::getRouteBaseName().'.*'))
                                ->url(FaqResource::getUrl()),
                            NavigationItem::make(LibraryResource::getNavigationLabel())
                                ->icon(LibraryResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(LibraryResource::getRouteBaseName().'.*'))
                                ->url(LibraryResource::getUrl()),
                            NavigationItem::make(TagResource::getNavigationLabel())
                                ->icon(TagResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(TagResource::getRouteBaseName().'.*'))
                                ->url(TagResource::getUrl()),
                            NavigationItem::make(NavigationResource::getNavigationLabel())
                                ->icon(NavigationResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(NavigationResource::getRouteBaseName().'.*'))
                                ->url(NavigationResource::getUrl()),
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
                            NavigationItem::make(DepartmentResource::getNavigationLabel())
                                ->icon(DepartmentResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(DepartmentResource::getRouteBaseName().'.*'))
                                ->visible(fn (): bool => DepartmentResource::canAccess())
                                ->url(DepartmentResource::getUrl()),
                            NavigationItem::make(DoctorResource::getNavigationLabel())
                                ->icon(DoctorResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(DoctorResource::getRouteBaseName().'.*'))
                                ->visible(fn (): bool => DoctorResource::canAccess())
                                ->url(DoctorResource::getUrl()),
                            NavigationItem::make(CategoryResource::getNavigationLabel())
                                ->icon(CategoryResource::getNavigationIcon())
                                ->isActiveWhen(fn (): bool => request()->routeIs(CategoryResource::getRouteBaseName().'.*'))
                                ->visible(fn (): bool => CategoryResource::canAccess())
                                ->url(CategoryResource::getUrl()),
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
