<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\Pages\CreateAdminRecord;
use App\Filament\Resources\PermissionResource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class CreatePermission extends CreateAdminRecord
{
    protected static string $resource = PermissionResource::class;

    public function getHeading(): string|Htmlable
    {
        $backUrl = e(PermissionResource::getUrl('index'));

        return new HtmlString(<<<HTML
            <div class="ac-create-toolbar">
                <a href="{$backUrl}" class="ac-create-back">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back to permissions
                </a>
                <span class="ac-create-title">New Permission</span>
            </div>
        HTML);
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
