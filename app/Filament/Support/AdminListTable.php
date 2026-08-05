<?php

namespace App\Filament\Support;

use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class AdminListTable
{
    /**
     * Shared listing shell — filters always visible horizontally (AboveContent).
     *
     * @param  int|array<string, int>  $filtersFormColumns
     */
    public static function configure(
        Table $table,
        string $searchPlaceholder = 'Search...',
        int|array $filtersFormColumns = 4,
        bool $compact = false,
    ): Table {
        $classes = ['ac-list-table', 'ac-user-table'];

        if ($compact) {
            $classes[] = 'ac-compact-table';
        }

        return $table
            ->extraAttributes([
                'class' => implode(' ', $classes),
            ])
            ->searchPlaceholder($searchPlaceholder)
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10, 25, 50, 100])
            ->recordAction(null)
            ->recordUrl(null)
            ->deferFilters(false)
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns($filtersFormColumns)
            ->columnManager(false)
            ->recordActionsColumnLabel('Actions')
            ->striped(false);
    }
}
