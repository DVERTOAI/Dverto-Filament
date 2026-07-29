<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\Pages\EditAdminRecord;
use App\Filament\Resources\RoleResource;
use Filament\Actions\DeleteAction;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class EditRole extends EditAdminRecord
{
    protected static string $resource = RoleResource::class;

    public function getHeading(): string|Htmlable
    {
        $backUrl = e(RoleResource::getUrl('index'));

        return new HtmlString(<<<HTML
            <div class="ac-create-toolbar">
                <a href="{$backUrl}" class="ac-create-back">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back to roles
                </a>
                <span class="ac-create-title">Edit Role</span>
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
