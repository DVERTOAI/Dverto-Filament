<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\Pages\EditAdminRecord;
use App\Models\Category;
use Illuminate\Contracts\Support\Htmlable;

class EditCategory extends EditAdminRecord
{
    protected static string $resource = CategoryResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Edit Category';
    }

    public function getSubheading(): ?string
    {
        return 'Category management';
    }

    /**
     * @return array<string>
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['type'] ?? null) !== Category::TYPE_SUBCATEGORY) {
            $data['parent_id'] = null;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
