<?php

namespace App\Filament\Pages\Concerns;

use App\Filament\Support\Breadcrumbs;

trait HasMinimalBreadcrumbs
{
    /**
     * @return class-string<\Filament\Pages\Page>|null
     */
    protected static function getBreadcrumbParentPage(): ?string
    {
        return null;
    }

    /**
     * @return array<string>
     */
    public function getBreadcrumbs(): array
    {
        $parentPage = static::getBreadcrumbParentPage();

        if (filled($parentPage)) {
            return Breadcrumbs::page(
                label: static::getNavigationLabel(),
                parentLabel: $parentPage::getNavigationLabel(),
                parentUrl: $parentPage::getUrl(),
            );
        }

        return Breadcrumbs::page(static::getNavigationLabel());
    }
}
