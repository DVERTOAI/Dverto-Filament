<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\Pages\ListAdminRecords;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;

class ListCategories extends ListAdminRecords
{
    protected static string $resource = CategoryResource::class;

    public function getHeading(): string
    {
        return 'Categories';
    }

    public function getSubheading(): ?string
    {
        return 'Manage categories and subcategories.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Category')
                ->icon(Heroicon::Plus)
                ->color('primary'),
        ];
    }
}
